<?php

/**
 * Nome: global.php
 * Descrição: configurações de acesso global
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 */
return array(
    /*
     * Banco de dados PostgreSQl
     */
    'db' => array(
        'adapters' => array(
            'adapterPostgre' => array(
                'driver' => 'Pdo',
                'dsn' => 'pgsql:dbname=postgres;host=158.69.124.139',
                'username' => 'postgres',
                'password' => '',
            ),
        )
    )
);
