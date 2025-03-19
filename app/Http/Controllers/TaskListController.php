<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\TaskList\DestroyAction;
use App\Actions\TaskList\IndexAction;
use App\Actions\TaskList\StoreAction;
use App\Actions\TaskList\UpdateAction;
use App\Data\TaskList\IndexData;
use App\Data\TaskList\StoreData;
use App\Data\TaskList\UpdateData;
use App\Http\Requests\TaskList\IndexRequest;
use App\Http\Requests\TaskList\StoreRequest;
use App\Http\Requests\TaskList\UpdateRequest;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class TaskListController extends Controller
{
    #[OA\Get(
        path: '/api/lists',
        description: 'Get lists',
        tags: ['Lists'],
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
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            description: "List resource",
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TaskListResource')
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
        $lists = $indexAction->run($data);

        return TaskListResource::collection($lists);
    }

    #[OA\Post(
        path: '/api/lists',
        description: 'Create List',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/TaskListStoreRequest'
                )
            ),
        ),
        tags: ['Lists'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TaskListResource',
                            description: "List resource",
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
    public function store(StoreRequest $request, StoreData $data, StoreAction $storeAction): TaskListResource
    {
        $list = $storeAction->run($data);

        return new TaskListResource($list);
    }

    #[OA\Get(
        path: '/api/lists/{list}',
        description: 'Get list by id',
        tags: ['Lists'],
        parameters: [
            new OA\Parameter(
                name: 'list',
                description: 'List id',
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
                            ref: '#/components/schemas/TaskListResource',
                            description: "List resource",
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'List not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Http404')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            )
        ]
    )]
    public function show(TaskList $list): TaskListResource
    {
        return new TaskListResource($list);
    }

    #[OA\Put(
        path: '/api/lists/{list}',
        description: 'Update List by id',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/TaskListUpdateRequest'
                )
            ),
        ),
        tags: ['Lists'],
        parameters: [
            new OA\Parameter(
                name: 'list',
                description: 'List id',
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
                            ref: '#/components/schemas/TaskListResource',
                            description: "List resource",
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
    public function update(UpdateRequest $request, UpdateData $data, UpdateAction $updateAction, TaskList $list): TaskListResource
    {
        $list = $updateAction->run($list, $data);

        return new TaskListResource($list);
    }

    #[OA\Delete(
        path: '/api/lists/{list}',
        description: 'Delete list by id',
        tags: ['Lists'],
        parameters: [
            new OA\Parameter(
                name: 'list',
                description: 'List id',
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
                description: 'List not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Http404')
            )
        ]
    )]
    public function destroy(TaskList $list, DestroyAction $destroyAction): JsonResponse
    {
        $destroyAction->run($list);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
