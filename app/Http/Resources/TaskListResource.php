<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TaskListResource",
    title: "Task List",
    description: "Task list response structure",
    required: ["id", "name", 'created_at'],
    properties: [
        new OA\Property(
            property: "id",
            description: "List id",
            type: "numeric",
            example: 1,
            nullable: false
        ),
        new OA\Property(
            property: "name",
            description: "List name",
            type: "string",
            example: "List 1",
            nullable: false
        ),
        new OA\Property(
            property: "description",
            description: "List description",
            type: "string",
            example: "This is list description",
            nullable: true
        ),
        new OA\Property(
            property: "created_at",
            description: "Task creation date",
            type: "string",
            example: "2025-02-20 00:00:00",
            nullable: true
        ),
    ]
)]
class TaskListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'created_at' => $this->resource->created_at?->toDateTimeString(),
        ];
    }
}
