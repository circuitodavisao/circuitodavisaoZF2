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
                    'user' => 'homologacao',
                    'password' => 'Z#03SOye(hRN',
                    'dbname' => 'homologacao',
                    'encoding' => 'utf8',
                )
            )
        )
    )
);
