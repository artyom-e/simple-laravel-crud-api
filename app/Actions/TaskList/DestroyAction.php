<?php

declare(strict_types=1);

namespace App\Actions\TaskList;

use App\Models\TaskList;

class DestroyAction
{
    public function run(TaskList $list): void
    {
        $list->delete();
    }
}
