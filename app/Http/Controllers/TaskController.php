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
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Get(
        path: '/api/tasks',
        description: 'Get task list',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'number',
                    example: 1
                )
            ),
            new OA\Parameter(
                name: 'filters[include_completed]',
                description: 'Filter by task completion status',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'boolean',
                    example: true
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            description: "Task resource",
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TaskResource')
                        ),
                        new OA\Property(
                            property: 'meta',
                            ref: '#/components/schemas/Meta',
                            description: 'Pagination meta information',
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            )
        ]
    )]
    public function index(IndexRequest $request, IndexData $data, IndexAction $indexAction): AnonymousResourceCollection
    {
        $tasks = $indexAction->run($data);

        return TaskResource::collection($tasks);
    }

    #[OA\Post(
        path: '/api/tasks',
        description: 'Create Task',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/TaskStoreRequest'
                )
            ),
        ),
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TaskResource',
                            description: "Task resource",
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            )
        ]
    )]
    public function store(StoreRequest $request, StoreData $data, StoreAction $storeAction): TaskResource
    {
        $task = $storeAction->run($data);

        return new TaskResource($task);
    }

    #[OA\Get(
        path: '/api/tasks/{task}',
        description: 'Get task by id',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'number',
                    example: 1
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TaskResource',
                            description: "Task resource",
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Http404')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            )
        ]
    )]
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    #[OA\Put(
        path: '/api/tasks/{task}',
        description: 'Update Task by id',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/TaskUpdateRequest'
                )
            ),
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'number',
                    example: 1
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TaskResource',
                            description: "Task resource",
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            )
        ]
    )]
    public function update(UpdateRequest $request, UpdateData $data, UpdateAction $updateAction, Task $task): TaskResource
    {
        $task = $updateAction->run($task, $data);

        return new TaskResource($task);
    }

    #[OA\Delete(
        path: '/api/tasks/{task}',
        description: 'Delete task by id',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'number',
                    example: 1
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Success response'
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Http404')
            )
        ]
    )]
    public function destroy(Task $task, DestroyAction $destroyAction): JsonResponse
    {
        $destroyAction->run($task);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
