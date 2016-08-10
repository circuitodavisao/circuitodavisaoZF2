<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Entidade;

return array(
    # definir e gerenciar serviços
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    # definir driver e classes anotadas para o doctrine
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../../Entidade/src/Entidade/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Entidade\Entity' => 'application_entities'
                )
            )
        ),
    ),
);
