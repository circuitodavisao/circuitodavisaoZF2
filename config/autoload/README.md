About this directory:
=====================

By default, this application is configured to load all configs in
`./config/autoload/{,*.}{global,local}.php`. Doing this provides a
location for a developer to drop in configuration override files provided by
modules, as well as cleanly provide individual, application-wide config files
for things like database connections, etc.

Acesso Doctrine
Nome: doctrine.local.php

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