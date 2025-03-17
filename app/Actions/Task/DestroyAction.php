<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;

class DestroyAction
{
    public function run(Task $task): void
    {
        $task->delete();
    }
}
