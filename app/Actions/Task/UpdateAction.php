<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Data\Task\UpdateData;
use App\Models\Task;

class UpdateAction
{
    public function run(Task $task, UpdateData $data): Task
    {
        $task->update($data->all());

        return $task;
    }
}
