<?php

declare(strict_types=1);

namespace App\Data\Task;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class UpdateData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description,
        #[MapInputName('is_completed')]
        #[MapOutputName('is_completed')]
        public bool $isCompleted = false,
    ) {}
}
