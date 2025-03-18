<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskUpdateRequest',
    title: 'Task Update Request',
    description: 'Task update request structure',
    required: ['name', 'is_completed'],
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
        new OA\Property(
            property: 'is_completed',
            type: 'boolean',
            example: true,
            nullable: false
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
            'is_completed' => 'required|bool',
        ];
    }
}
