<?php
return [
    'navigation' => [
        'default' => [
            [
                'label'     => 'Главная',
                'route'     => 'admin',
                'resource'	=> 'Admin\Controller\Index',
                'privilege'	=> 'index',
                'icon'      => 'fa fa-th-large',
                'order'     => -1,
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'adminPanel'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'mainNavigation'    => 'Navigator\Navigation\Service\MainNavigationFactory',
            'dbNavigation'      => 'Navigator\Navigation\Service\DbNavigationFactory',
        ],
    ],
];