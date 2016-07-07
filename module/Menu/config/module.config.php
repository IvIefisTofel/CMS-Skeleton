<?php

namespace Menu;

$env = (getenv('APP_ENV') == 'development') ? true : false;
return [
    'router' => [
        'routes' => [
            'admin-menu' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/menu[/[:action[/:id]]][[/:page]-page]',
                    'constraints' => [
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                        'page'       => '[0-9]+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'Menu\Controller',
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
            'Menu\Controller\Admin' => Controller\AdminController::class,
        ],
    ],

    'admin' => [
        'Menu\Controller\Admin' => [],
    ],

    'acl' => [
        'resources' => [
            'allow' => [
                'Menu\Controller\Admin' => [
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
                'label'     => 'Меню',
                'route'     => 'admin-menu',
                'icon'      => 'fa fa-list',
                'resource'	=> 'Menu\Controller\Admin',
                'privilege'	=> 'index',
                'order'     => 20,
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