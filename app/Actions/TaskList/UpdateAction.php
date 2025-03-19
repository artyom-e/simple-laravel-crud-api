<?php

declare(strict_types=1);

namespace App\Actions\TaskList;

use App\Data\TaskList\UpdateData;
use App\Models\TaskList;

class UpdateAction
{
    public function run(TaskList $list, UpdateData $data): TaskList
    {
        $list->update($data->all());

        return $list;
    }
}
