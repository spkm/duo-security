<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Data\Data;

readonly class ExampleData extends Data
{
    protected const LISTS = ['children' => ExampleChild::class];

    protected const HIDDEN = ['secret_key'];

    /**
     * @param  Collection<int, ExampleChild>|null  $children
     * @param  list<string>|null  $tags
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $user_id = null,
        public ?string $username = null,
        public ?int $count = null,
        public ?float $ratio = null,
        public ?bool $is_enrolled = null,
        public ?CarbonImmutable $created = null,
        public ?CarbonImmutable $isotimestamp = null,
        public ?ExampleChild $phone = null,
        public ?Collection $children = null,
        public ?array $tags = null,
        public ?string $secret_key = null,
        public array $extra = [],
    ) {}
}
