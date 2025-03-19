<?php

declare(strict_types=1);

namespace App\Data\TaskList;

use Spatie\LaravelData\Data;

class IndexData extends Data
{
    public function __construct(
        public int $page = 1,
    ) {
    }
}
