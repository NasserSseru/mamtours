<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocController extends Controller
{
    /**
     * Generate OpenAPI specification
     */
    public function spec()
    {
        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'MAM Tours API',
                'description' => 'Car rental management system API',
                'version' => '2.0.0',
                'contact' => [
                    'name' => 'MAM Tours',
                    'email' => 'api@mamtours.com',
                ],
            ],
            'servers' => [
                [
                    'url' => url('/api'),
                    'description' => 'Production server',
                ],
            ],
            'paths' => [
                '/v1/cars' => [
                    'get' => [
                        'summary' => 'List all available cars',
                        'tags' => ['Cars - v1'],
                        'parameters' => [
                            [
                                'name' => 'per_page',
                                'in' => 'query',
                                'schema' => ['type' => 'integer', 'default' => 15],
                            ],
                            [
                                'name' => 'category',
                                'in' => 'query',
                                'schema' => ['type' => 'string'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of cars',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'version' => ['type' => 'string'],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Car'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/v1/cars/{id}' => [
                    'get' => [
                        'summary' => 'Get a specific car',
                        'tags' => ['Cars - v1'],
                        'parameters' => [
                            [
                                'name' => 'id',
                                'in' => 'path',
                                'required' => true,
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Car details',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'version' => ['type' => 'string'],
                                                'data' => ['$ref' => '#/components/schemas/Car'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '404' => ['description' => 'Car not found'],
                        ],
                    ],
                ],
                '/v2/cars' => [
                    'get' => [
                        'summary' => 'List all available cars (Enhanced)',
                        'tags' => ['Cars - v2'],
                        'parameters' => [
                            [
                                'name' => 'per_page',
                                'in' => 'query',
                                'schema' => ['type' => 'integer', 'default' => 15],
                            ],
                            [
                                'name' => 'category',
                                'in' => 'query',
                                'schema' => ['type' => 'string'],
                            ],
                            [
                                'name' => 'sort_by',
                                'in' => 'query',
                                'schema' => ['type' => 'string', 'default' => 'created_at'],
                            ],
                            [
                                'name' => 'sort_order',
                                'in' => 'query',
                                'schema' => ['type' => 'string', 'enum' => ['asc', 'desc'], 'default' => 'desc'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of cars with pagination metadata',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'version' => ['type' => 'string'],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => ['$ref' => '#/components/schemas/Car'],
                                                ],
                                                'meta' => ['$ref' => '#/components/schemas/PaginationMeta'],
                                                'links' => ['$ref' => '#/components/schemas/PaginationLinks'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/webhooks/register' => [
                    'post' => [
                        'summary' => 'Register a webhook endpoint',
                        'tags' => ['Webhooks'],
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['url'],
                                        'properties' => [
                                            'url' => ['type' => 'string', 'format' => 'uri'],
                                            'events' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'string',
                                                    'enum' => ['booking.created', 'booking.confirmed', 'booking.cancelled', 'car.created', 'car.updated'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => ['description' => 'Webhook registered successfully'],
                        ],
                    ],
                ],
            ],
            'components' => [
                'schemas' => [
                    'Car' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'brand' => ['type' => 'string'],
                            'model' => ['type' => 'string'],
                            'numberPlate' => ['type' => 'string'],
                            'dailyRate' => ['type' => 'number', 'format' => 'float'],
                            'seats' => ['type' => 'integer'],
                            'category' => ['type' => 'string', 'nullable' => true],
                            'isAvailable' => ['type' => 'boolean'],
                            'carPicture' => ['type' => 'string', 'nullable' => true],
                        ],
                    ],
                    'PaginationMeta' => [
                        'type' => 'object',
                        'properties' => [
                            'current_page' => ['type' => 'integer'],
                            'per_page' => ['type' => 'integer'],
                            'total' => ['type' => 'integer'],
                            'last_page' => ['type' => 'integer'],
                        ],
                    ],
                    'PaginationLinks' => [
                        'type' => 'object',
                        'properties' => [
                            'first' => ['type' => 'string', 'nullable' => true],
                            'last' => ['type' => 'string', 'nullable' => true],
                            'prev' => ['type' => 'string', 'nullable' => true],
                            'next' => ['type' => 'string', 'nullable' => true],
                        ],
                    ],
                ],
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
            ],
        ];

        return response()->json($spec);
    }

    /**
     * Show Swagger UI
     */
    public function ui()
    {
        return view('api-docs');
    }
}
