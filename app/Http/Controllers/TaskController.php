<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Task\DestroyAction;
use App\Actions\Task\IndexAction;
use App\Actions\Task\StoreAction;
use App\Actions\Task\UpdateAction;
use App\Data\Task\IndexData;
use App\Data\Task\StoreData;
use App\Data\Task\UpdateData;
use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(IndexRequest $request, IndexData $data, IndexAction $indexAction): AnonymousResourceCollection
    {
        $tasks = $indexAction->run($data);

        return TaskResource::collection($tasks);
    }

    public function store(StoreRequest $request, StoreData $data, StoreAction $storeAction): TaskResource
    {
        $task = $storeAction->run($data);

        return new TaskResource($task);
    }

    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(UpdateRequest $request, UpdateData $data, UpdateAction $updateAction, Task $task): TaskResource
    {
        $task = $updateAction->run($task, $data);

        return new TaskResource($task);
    }

    public function destroy(Task $task, DestroyAction $destroyAction): JsonResponse
    {
        $destroyAction->run($task);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
