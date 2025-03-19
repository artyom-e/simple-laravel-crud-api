<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Data\Task\StoreData;
use App\Models\Task;

class StoreAction
{
    public function run(StoreData $data): Task
    {
        return $data->list->tasks()->create($data->all());
    }
}
