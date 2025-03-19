<?php

declare(strict_types=1);

namespace App\Actions\TaskList;

use App\Data\TaskList\IndexData;
use App\Models\TaskList;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexAction
{
    public function run(IndexData $data): LengthAwarePaginator
    {
        return TaskList::query()
            ->latest('id')
            ->paginate(null, ['*'], 'page', $data->page);
    }
}
