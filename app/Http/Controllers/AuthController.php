<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\SignInAction;
use App\Actions\Auth\SignOutAction;
use App\Actions\Auth\SignUpAction;
use App\Data\Auth\SignInData;
use App\Data\Auth\SignUpData;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/sign-in',
        description: 'Sign In',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/SignInRequest'
                )
            ),
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'token',
                            description: "Auth token",
                            type: 'string'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation errors',
                content: new OA\JsonContent(ref: '#/components/schemas/Http422')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/Http401')
            )
        ]
    )]
    public function signIn(SignInRequest $request, SignInData $data, SignInAction $action): JsonResponse
    {
        return response()->json([
            'token' => $action->run($data),
        ]);
    }

    #[OA\Post(
        path: '/api/sign-out',
        description: 'Sign Out',
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Success response'
            ),
        ]
    )]
    public function signOut(Request $request, SignOutAction $action): JsonResponse
    {
        $action->run($request->user());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    #[OA\Post(
        path: '/api/sign-up',
        description: 'Sign Up',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    ref: '#/components/schemas/SignUpRequest'
                )
            ),
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Success response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'token',
                            description: "Auth token",
                            type: 'string'
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
    public function signUp(SignUpRequest $request, SignUpData $data, SignUpAction $action): JsonResponse
    {
        return response()->json([
            'token' => $action->run($data),
        ], Response::HTTP_CREATED);
    }
}
