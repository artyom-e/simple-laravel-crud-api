<?php

declare(strict_types=1);

namespace App\Data\Task;

use App\Models\TaskList;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

class StoreData extends Data
{
    public function __construct(
        #[FromRouteParameter('list')]
        public TaskList $list,
        public string $name,
        public ?string $description
    ) {
    }
}
