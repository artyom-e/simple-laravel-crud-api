<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Data\Task\IndexData;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexAction
{
    public function run(IndexData $data): LengthAwarePaginator
    {
        return $data->list
            ->tasks()
            ->latest('id')
            ->when(!$data->filters->includeCompleted, fn($query) => $query->whereNull('completed_at'))
            ->paginate(null, ['*'], 'page', $data->page);
    }
}
