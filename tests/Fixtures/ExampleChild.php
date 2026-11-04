<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Data\Data;

readonly class ExampleChild extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $phone_id = null,
        public ?string $number = null,
        public array $extra = [],
    ) {}
}
