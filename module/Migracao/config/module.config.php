<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Migracao;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Migracao\Controller\Index' => 'Migracao\Controller\Factory\IndexControllerFactory',
        ),
    ),
    # definir e gerenciar rotas
    'router' => array(
        'routes' => array(
            'migracao' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/migracao[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Migracao\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    # definir e gerenciar serviços
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    # definir e gerenciar layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/migracao' => __DIR__ . '/../view/layout/layout.phtml',
            'migracao/migracao/index' => __DIR__ . '/../view/migracao/index/index.phtml',
            'migracao/migracao/relatorio' => __DIR__ . '/../view/migracao/index/relatorio.phtml',
            'migracao/migracao/lideres' => __DIR__ . '/../view/migracao/index/lideres.phtml',
            'error/404' => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index' => __DIR__ . '/../../Application/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    # definir driver, classes anotadas para o doctrine e quem faz autenticação
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model\Entity' => 'application_entities'
                )
            )
        ),
    ),
);
