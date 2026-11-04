<?php

namespace spkm\DuoSecurity\Requests;

use JsonSerializable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Repositories\Body\JsonBodyRepository;
use Saloon\Traits\Body\HasJsonBody;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Support\Payload;

/**
 * A POST, PUT or PATCH: its parameters go in a JSON body, as Duo's v5 signing reads them, and {} when there are
 * none. Duo answers with the record as stored, a list, a string, or "" for an operation.
 */
abstract class WriteRequest extends DuoRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    protected bool $safe = false;

    /** @var array<string, mixed>|Data|JsonSerializable */
    protected array|Data|JsonSerializable $params = [];

    /**
     * Sends an empty body as {}: Duo signs and reads a body as an object, and refuses [].
     */
    public function boot(PendingRequest $pendingRequest): void
    {
        $body = $pendingRequest->body();

        if ($body instanceof JsonBodyRepository && $body->isEmpty()) {
            $body->setJsonFlags($body->getJsonFlags() | JSON_FORCE_OBJECT);
        }
    }

    /**
     * The parameters, sorted by key as Duo's own clients send them, so a body is written the same way every time.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = Payload::parameters($this->params);
        ksort($body, SORT_STRING);

        return $body;
    }
}
