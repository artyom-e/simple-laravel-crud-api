<?php

declare(strict_types=1);

namespace App\Http\Requests\TaskList;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskListUpdateRequest',
    title: 'Task List Update Request',
    description: 'Task list update request structure',
    required: ['name'],
    properties: [
        new OA\Property(
            property: 'name',
            type: 'string',
            maxLength: 255,
            example: 'Task 1',
            nullable: false,
        ),
        new OA\Property(
            property: 'description',
            type: 'string',
            maxLength: 2048,
            example: 'Description 1',
            nullable: true
        ),
    ]
)]
class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2048',
        ];
    }
}
