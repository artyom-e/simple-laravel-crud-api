<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TaskResource",
    title: "Task",
    description: "Task item response structure",
    required: ["id", "name", 'is_completed', 'created_at'],
    properties: [
        new OA\Property(
            property: "id",
            description: "Task id",
            type: "numeric",
            example: 1,
            nullable: false
        ),
        new OA\Property(
            property: "name",
            description: "Task name",
            type: "string",
            example: "Task 1",
            nullable: false
        ),
        new OA\Property(
            property: "description",
            description: "Task description",
            type: "string",
            example: "This is task description",
            nullable: true
        ),
        new OA\Property(
            property: "is_completed",
            description: "Task completion marker",
            type: "boolean",
            example: true,
            nullable: false
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
class TaskResource extends JsonResource
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
            'is_completed' => $this->resource->is_completed,
            'created_at' => $this->resource->created_at?->toDateTimeString(),
        ];
    }
}
