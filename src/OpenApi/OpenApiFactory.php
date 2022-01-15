<?php
namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if($path->getGet() && $path->getGet()->getSummary()==='hidden'){
                $openApi->getPaths()->addPath($key,$path->withGet(null));
            }
        }
        $schemes = $openApi->getComponents()->getSecuritySchemes(); 
        $schemes['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);
        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'user@app.com'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'password'
                ]
            ]
        ]);
        $loginPathitem = new PathItem(
            ref: 'JWT Token',
            post: new Operation(
                operationId: 'postCredentialsItem',
                tags: ['Auth'],
                security:[],
                summary: 'Login to Get JWT token.',
                requestBody: new RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' =>[
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' =>[
                        'description' => 'Get the loged in user JWT token',
                        'content' => [
                            'application/json' =>[
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token'
                                ]
                            ]
                        ]
                    ]
                ]
            )

        );
        $openApi->getPaths()->addPath('/api/login', $loginPathitem) ;
        $profilePathItem = new PathItem(
            ref: 'User Profile',
            parameters:[],
            get: new Operation(
                operationId: 'getUserProfileDetails',
                tags: ['User'],
                security:[
                    ['bearerAuth'=>['is_granted("ROLE_USER")']]
                ],
                summary: 'Get User Profile Details.',
                requestBody: null,
                responses: [
                    '200' =>[
                        'description' => 'Loged in, Get User Profile Details',
                        'content' => [
                            'application/json' =>[
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-user.read'
                                ]
                            ]
                        ]
                    ]
                ]
            )

        );
        $openApi->getPaths()->addPath('/api/profile', $profilePathItem) ;
        
        // $placeOrderOperation = $openApi->getPaths()->getPath('/api/orders/{id}/place')->getGet()->withParameters([]);
        // $placeOrderPathItem = $openApi->getPaths()->getPath('/api/orders/{id}/place')->withGet($placeOrderOperation);
        // $openApi->getPaths()->addPath('/api/orders/{id}/place', $placeOrderPathItem);
        return $openApi;
    }
}