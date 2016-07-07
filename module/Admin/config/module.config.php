<?php

namespace Admin;

$env = (getenv('APP_ENV') == 'development') ? true : false;
return [
    'router' => [
        'routes' => [
            'admin' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'admin-login' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/login[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'AuthDoctrine\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'login',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'controllers' => [
        'invokables' => [
            'Admin\Controller\Index' => Controller\IndexController::class,
        ],
    ],

    'admin' => [
        'Admin\Controller\Index' => []
    ],

    'view_manager' => [
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'doctype'                   => 'HTML5',
        'template_map' => [
            'layout/admin'          => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'collections' => $env ? [] : [
                'css/core.css' => [
                    'css/bootstrap.min.css',
                    'css/font-awesome.min.css',
                    'admin/css/plugins/toastr/toastr.css',
                    'admin/css/plugins/gritter/jquery.gritter.css',
                    'admin/css/plugins/iCheck/custom.css',
                    'admin/css/animate.css',
                    'admin/css/inspinia.css',
                    'admin/css/style.css',
                ],
                'js/core.js' => [
                    'js/jquery.min.js',
                    'js/plugins/jQueryUI/jquery-ui.min.js',
                    'js/fixConflictUI.js',
                    'js/bootstrap.min.js',
                    'admin/js/plugins/metisMenu/jquery.metisMenu.js',
                    'admin/js/plugins/slimscroll/jquery.slimscroll.min.js',
                    'admin/js/plugins/flot/jquery.flot.js',
                    'admin/js/plugins/flot/jquery.flot.tooltip.min.js',
                    'admin/js/plugins/flot/jquery.flot.spline.js',
                    'admin/js/plugins/flot/jquery.flot.resize.js',
                    'admin/js/plugins/flot/jquery.flot.pie.js',
                    'admin/js/plugins/peity/jquery.peity.min.js',
                    'admin/js/plugins/gritter/jquery.gritter.min.js',
                    'admin/js/plugins/sparkline/jquery.sparkline.min.js',
                    'admin/js/plugins/toastr/toastr.min.js',
                    'admin/js/plugins/iCheck/icheck.min.js',
                    'admin/js/plugins/pace/pace.min.js',
                    'admin/js/inspinia.js',
                ],
            ],
            'paths' => [
                __DIR__ . '/../public',
            ],
        ],
        'filters' => $env ? [] : [
            'admin/css/plugins/toastr/toastr.css'           => [['filter' => 'CssMinFilter']],
            'admin/css/plugins/gritter/jquery.gritter.css'  => [['filter' => 'CssMinFilter']],
            'admin/css/plugins/iCheck/custom.css'           => [['filter' => 'CssMinFilter']],
            'admin/css/animate.css'                         => [['filter' => 'CssMinFilter']],
            'admin/css/inspinia.css'                        => [['filter' => 'CssMinFilter']],
            'admin/css/style.css'                           => [['filter' => 'CssMinFilter']],
        ],
        'caching' => $env ? [] : [
            'css/core.css' => [
                'cache'     => 'FilesystemCache',
                'options' => [
                    'dir' => './public/css/core',
                ],
            ],
            'js/core.js' => [
                'cache'     => 'FilesystemCache',
                'options' => [
                    'dir' => './public/js/core',
                ],
            ],
        ],
    ],
];