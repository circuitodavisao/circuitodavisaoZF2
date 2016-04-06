<?php

/**
 * Nome: doctrine.local.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações de acesso ao banco de dados postgre com doctrine
 */
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host' => '158.69.124.139',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'qwaszx159753',
                    'dbname' => 'homologacao',
                    'encoding' => 'utf8',
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'hydration_cache' => 'array',
                'generate_proxies' => true,
                'proxy_dir' => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace' => 'DoctrineORMModule\Proxy',
            ),
        ),
    )
);
