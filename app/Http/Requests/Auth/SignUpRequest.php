<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SignUpRequest',
    title: 'Sign In Request',
    required: ['email', 'password', 'name'],
    properties: [
        new OA\Property(
            property: 'email',
            type: 'string',
            example: 'test@example.com',
            nullable: false,
        ),
        new OA\Property(
            property: 'password',
            type: 'string',
            maxLength: 32,
            minLength: 8,
            example: '12345678',
            nullable: false
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
            maxLength: 64,
            minLength: 3,
            example: 'John Doe',
            nullable: false
        ),
    ]
)]
class SignUpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:32',
            'name' => 'required|string|min:3|max:64',
        ];
    }
}
