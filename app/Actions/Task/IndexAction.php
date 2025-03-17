<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Data\Task\IndexData;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexAction
{
    public function run(IndexData $data): LengthAwarePaginator
    {
        return Task::query()
            ->latest('id')
            ->when(! $data->filters->includeCompleted, fn ($query) => $query->whereNull('completed_at'))
            ->paginate(null, ['*'], 'page', $data->page);
    }
}
