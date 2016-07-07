<?php

namespace Settings;

$env = (getenv('APP_ENV') == 'development') ? true : false;
return [
    'router' => [
        'routes' => [
            'admin-settings' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/settings[/[:action[/:id]]][[/:page]-page]',
                    'constraints' => [
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                        'page'       => '[0-9]+',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'Settings\Controller',
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
            'Settings\Controller\Admin' => Controller\AdminController::class,
        ],
    ],

    'admin' => [
        'Settings\Controller\Admin' => [],
    ],

    'acl' => [
        'resources' => [
            'allow' => [
                'Settings\Controller\Admin' => [
                    'index'  => MODERATOR_ROLE,
                    'groups' => MODERATOR_ROLE,
                    ADMIN_ROLE
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
                'label' => 'Настройки',
                'uri'   => '#',
                'icon'  => 'fa fa-cogs',
                'pages' => [
                    [
                        'label'     => 'Параметры',
                        'route'     => 'admin-settings',
                        'action'    => 'index',
                        'icon'      => 'fa fa-th-list',
                        'resource'	=> 'Settings\Controller\Admin',
                        'privilege' => 'index',
                    ],
                    [
                        'label'     => 'Группы параметров',
                        'route'     => 'admin-settings',
                        'action'    => 'groups',
                        'icon'      => 'fa fa-group',
                        'resource'	=> 'Settings\Controller\Admin',
                        'privilege'	=> 'index',
                    ],
                ],
                'order' => 90,
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