<?php

namespace News;

$env = (getenv('APP_ENV') == 'development') ? true : false;
return [
    'router' => [
        'routes' => [
            'news' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/news/[:alias]',
                    'defaults' => [
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'alias' => '[\s\S]*',
                    ],
                ],
            ],
            'admin-news' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/news[/[:action[/:id]]][[/:page]-page]',
                    'constraints' => [
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                        'page'       => '[0-9]+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'News\Controller',
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
            'News\Controller\Index' => Controller\IndexController::class,
            'News\Controller\Admin' => Controller\AdminController::class,
        ],
    ],

    'admin' => [
        'News\Controller\Admin' => [
            'ignore' => 'newspreview',
        ],
    ],

    'acl' => [
        'resources' => [
            'allow' => [
                'News\Controller\Index' => [
                    GUEST_ROLE,
                ],
                'News\Controller\Admin' => [
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
                'label'     => 'Новости',
                'route'     => 'admin-news',
                'icon'      => 'fa fa-newspaper-o',
                'resource'	=> 'News\Controller\Admin',
                'privilege' => 'index',
                'order'     => 40,
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