<?php

namespace spkm\DuoSecurity;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use spkm\DuoSecurity\Exceptions\InvalidRequestException;
use spkm\DuoSecurity\Exceptions\NotFoundException;
use spkm\DuoSecurity\Exceptions\RateLimitedException;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Exceptions\UnauthorizedException;
use spkm\DuoSecurity\Support\Envelope;

/**
 * An error Duo answered with: {"stat": "FAIL", "code": 40002, "message": "Invalid request parameters",
 * "message_detail": "username"}, where the HTTP status is the first three digits of the code. The exception's own
 * code is that HTTP status; duoCode() gives Duo's. A 2xx that is not an OK envelope (a FAIL with a 200, or
 * something other than JSON) is a ServerErrorException.
 */
class DuoApiException extends RequestException
{
    public function __construct(Response $response)
    {
        parent::__construct($response, $this->describe($response), self::statusOf($response));
    }

    /**
     * The exception for Duo's answer: a subclass for the errors callers handle differently.
     */
    public static function fromResponse(Response $response): self
    {
        $status = self::statusOf($response);

        return match (true) {
            $status === 404 => new NotFoundException($response),
            in_array($status, [401, 403], true) => new UnauthorizedException($response),
            $status === 429 => new RateLimitedException($response),
            in_array($status, [400, 405, 406, 409, 412, 415, 422], true) => new InvalidRequestException($response),
            $status === 408 || $status >= 500 || $response->successful() => new ServerErrorException($response),
            default => new self($response),
        };
    }

    /**
     * Duo's five-digit code (40002), or null when it gave none.
     */
    public function duoCode(): ?int
    {
        return Envelope::code($this->getResponse());
    }

    /**
     * What Duo said went wrong, or null when it said nothing.
     */
    public function duoMessage(): ?string
    {
        return Envelope::message($this->getResponse());
    }

    /**
     * What Duo said the message concerns, such as the parameter at fault, or null when it said nothing more.
     */
    public function messageDetail(): ?string
    {
        return Envelope::messageDetail($this->getResponse());
    }

    /**
     * The HTTP status the answer stands for: the first three digits of Duo's code when it gave one (the status
     * line agrees in practice), else the status line's.
     */
    protected static function statusOf(Response $response): int
    {
        $code = Envelope::code($response);

        return $code !== null && $code >= 10000 && $code <= 99999 ? intdiv($code, 100) : $response->status();
    }

    protected function describe(Response $response): string
    {
        $text = 'Duo answered '.$response->status().' to '.self::requestLine($response);
        $body = trim($response->body());

        if ($body === '') {
            return $text.' with an empty body';
        }

        if (! json_validate($body)) {
            return $text.' with a body that is not JSON: '.self::clean($body);
        }

        $message = Envelope::message($response);
        $detail = Envelope::messageDetail($response);

        if ($message === null && $response->successful()) {
            return $text.' without the OK envelope expected';
        }

        return $text.($message === null ? '' : ': '.self::clean(rtrim($message, '.')).($detail === null ? '' : ' ('.self::clean($detail).')'));
    }

    /**
     * The failed request's method and path, without its query string.
     */
    protected static function requestLine(Response $response): string
    {
        $pendingRequest = $response->getPendingRequest();

        return $pendingRequest->getMethod()->value.' '.(parse_url($pendingRequest->getUrl(), PHP_URL_PATH) ?: '/');
    }

    /**
     * Server text fit for a message or a log line: tags and control characters out, one line, at most 200
     * characters.
     */
    protected static function clean(string $value): string
    {
        $value = trim((string) preg_replace('/\s+/u', ' ', (string) preg_replace('/[\x00-\x1F\x7F]+/u', ' ', strip_tags($value))));

        return mb_strlen($value) > 200 ? mb_substr($value, 0, 197).'...' : $value;
    }
}
