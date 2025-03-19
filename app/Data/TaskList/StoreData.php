<?php

declare(strict_types=1);

namespace App\Data\TaskList;

use Spatie\LaravelData\Data;

class StoreData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description
    ) {
    }
}
