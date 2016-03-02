<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host' => '158.69.124.139',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => '',
                    'dbname' => 'postgres',
                    'encoding' => 'utf8',
                )
            )
        )
    )
);