<?php

namespace Pages;

$env = (getenv('APP_ENV') == 'development') ? true : false;
return [
    'router' => [
        'routes' => [
            'pages' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/pages/[:alias]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Pages\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'alias' => '[\s\S]*',
                    ],
                ],
            ],
            'admin-pages' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/pages[/[:action[/:id]]][[/:page]-page]',
                    'constraints' => [
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                        'page'       => '[0-9]+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'Pages\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'controllers' => [
        'invokables' => [
            'Pages\Controller\Index' => Controller\IndexController::class,
            'Pages\Controller\Admin' => Controller\AdminController::class,
        ],
    ],

    'admin' => [
        'Pages\Controller\Admin' => [
            'ignore' => 'pagepreview',
        ],
    ],

    'acl' => [
        'resources' => [
            'allow' => [
                'Pages\Controller\Index' => [
                    GUEST_ROLE,
                ],
                'Pages\Controller\Admin' => [
                    MODERATOR_ROLE,
                ],
            ],
        ],
    ],

    'view_manager' => [
        'display_not_found_reason'  => $env,
        'display_exceptions'        => $env,
        'doctype'                   => 'HTML5',
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    'navigation' => [
        'default' => [
            [
                'label'     => 'Страницы',
                'route'     => 'admin-pages',
                'icon'      => 'fa fa-television',
                'resource'	=> 'Pages\Controller\Admin',
                'privilege' => 'index',
                'order'     => 30,
            ],
        ],
    ],

    'doctrine' => [
        'driver' => [
            strtolower(__NAMESPACE__) . '_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity/',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ .'\Entity' => strtolower(__NAMESPACE__) . '_entities',
                ],
            ],
        ],
    ],
];