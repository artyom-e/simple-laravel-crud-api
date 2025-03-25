<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[OA\Info(
    version: "1.0.0",
    description: "TODO List API Docs",
    title: "TODO List API"
)]
#[OA\Server(
    url: "http://localhost:8000/"
)]
#[OA\Schema(
    schema: "Http422",
    title: "HTTP Unprocessable Entity",
    description: "Validation error",
    required: ["message", "errors"],
    properties: [
        new OA\Property(
            property: "message",
            type: "string",
            example: "The given data was invalid."
        ),
        new OA\Property(
            property: "errors",
            properties: [
                new OA\Property(
                    property: "field_name",
                    type: "array",
                    items: new OA\Items(
                        type: "string",
                        example: "validation.exists"
                    )
                )
            ],
            type: "object"
        )
    ]
)]
#[OA\Schema(
    schema: "Http404",
    title: "HTTP Not Found",
    description: "Represents the response for a 404 HTTP error indicating that the requested resource was not found.",
    required: ["message"],
    properties: [
        new OA\Property(
            property: "message",
            description: "A detailed message explaining that no query results were found for the specified model.",
            type: "string",
            example: "No query results for model."
        ),
        new OA\Property(
            property: "exception",
            description: "The exception class that was thrown, typically a NotFoundHttpException in Laravel for 404 errors.",
            type: "string",
            example: NotFoundHttpException::class
        ),
        new OA\Property(
            property: "file",
            description: "The path of the PHP file where the exception was thrown.",
            type: "string",
            example: "/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/Handler.php"
        ),
        new OA\Property(
            property: "line",
            description: "The line number in the file where the exception was thrown.",
            type: "integer",
            example: 385
        )
    ]
)]
#[OA\Schema(
    schema: "Http401",
    title: "HTTP Not Authorized",
    description: "Represents the response for a 401 HTTP error indicating that the requested resource was not authorized.",
    required: ["message"],
    properties: [
        new OA\Property(
            property: "message",
            description: "A detailed message explaining that no query results were found for the specified model.",
            type: "string",
            example: "Unauthorized"
        ),
        new OA\Property(
            property: "exception",
            type: "string",
            example: UnauthorizedHttpException::class
        )
    ]
)]
#[OA\Schema(
    schema: "Meta",
    title: "Meta",
    description: "Pagination meta information",
    required: ["current_page", "per_page"],
    properties: [
        new OA\Property(
            property: "current_page",
            description: "The current page number of the paginated results",
            type: "integer",
            example: 1
        ),
        new OA\Property(
            property: "from",
            description: "The index of the first item on the current page",
            type: "integer",
            example: 1
        ),
        new OA\Property(
            property: "last_page",
            description: "The last page number in the paginated results",
            type: "integer",
            example: 9870
        ),
        new OA\Property(
            property: "links",
            description: "Pagination links object, including URL, label, and active status",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: "url",
                        description: "URL of the paginated page",
                        type: "string",
                        example: "/api/path/to?page=1"
                    ),
                    new OA\Property(
                        property: "label",
                        description: "Label of the page (e.g., page number)",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "active",
                        description: "Whether this page link is active or not",
                        type: "boolean",
                        example: true
                    )
                ]
            )
        ),
        new OA\Property(
            property: "path",
            description: "Base path of the paginated resource (e.g., API endpoint)",
            type: "string",
            example: "/api/path/to"
        ),
        new OA\Property(
            property: "per_page",
            description: "The number of items displayed per page",
            type: "integer",
            example: 15
        ),
        new OA\Property(
            property: "to",
            description: "The index of the last item on the current page",
            type: "integer",
            example: 15
        ),
        new OA\Property(
            property: "total",
            description: "The total number of items across all pages",
            type: "integer",
            example: 148040
        )
    ]
)]
abstract class Controller
{
}
