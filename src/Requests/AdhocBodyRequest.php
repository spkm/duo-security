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
 * An ad-hoc POST, PUT or PATCH carrying its parameters as JSON, from DuoSecurity::post() and DuoSecurity::put().
 */
class AdhocBodyRequest extends AdhocRequest implements HasBody
{
    use HasJsonBody;

    /** @var array<string, mixed>|Data|JsonSerializable */
    protected array|Data|JsonSerializable $payload;

    /**
     * @param  array<string, mixed>|Data|JsonSerializable  $body
     * @param  array<string, string|int>  $segments  The values for the path's {placeholders}.
     */
    public function __construct(Method $method, string $path, array|Data|JsonSerializable $body = [], string $shape = self::RAW, array $segments = [])
    {
        parent::__construct($method, $path, [], $shape, $segments);

        $this->payload = $body;
    }

    /**
     * Sends an empty body as {}, as Duo signs and reads a body as an object.
     */
    public function boot(PendingRequest $pendingRequest): void
    {
        $body = $pendingRequest->body();

        if ($body instanceof JsonBodyRepository && $body->isEmpty()) {
            $body->setJsonFlags($body->getJsonFlags() | JSON_FORCE_OBJECT);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = Payload::parameters($this->payload);
        ksort($body, SORT_STRING);

        return $body;
    }
}
