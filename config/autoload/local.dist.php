<?php
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host'     => 'localhost',  // Default 'localhost'
                    'port'     => '3306',       // Default '3306'
                    'user'     => 'root',       // Default 'root'
                    'password' => '',           // Default ''
                    'dbname'   => 'lit_db',     // Default 'lit_db'
                    'charset'  => 'utf8',
                    'driverOptions' => [
                        1002 => 'SET NAMES utf8 COLLATE utf8_general_ci'
                    ],
                ],
            ],
        ],
    ],
    //'mysql' => '',                            // Path to MySQL
    'view_manager' => [
        'base_path' => '/'                      // Default '/'
    ],
];