<?php

declare(strict_types=1);

namespace App\Actions\TaskList;

use App\Data\TaskList\StoreData;
use App\Models\TaskList;

class StoreAction
{
    public function run(StoreData $data): TaskList
    {
        return $data->user->taskLists()
            ->create([
                'name' => $data->name,
                'description' => $data->description,
            ]);
    }
}
