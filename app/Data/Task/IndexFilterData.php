<?php

declare(strict_types=1);

namespace App\Data\Task;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class IndexFilterData extends Data
{
    public function __construct(
        #[MapInputName('include_completed')]
        public bool $includeCompleted = false,
    ) {
    }
}
