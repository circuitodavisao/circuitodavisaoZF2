<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Principal;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Principal\Controller\Principal' => 'Principal\Controller\Factory\PrincipalControllerFactory',
        ),
    ),
    # definir e gerenciar rotas
    'router' => array(
        'routes' => array(
            'principal' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/principal',
                    'defaults' => array(
                        'controller' => 'Principal\Controller\Principal',
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
            'layout/layout' => __DIR__ . '/../../Login/view/layout/layout.phtml',
            'principal/principal/index' => __DIR__ . '/../view/principal/index.phtml',
            'error/404' => __DIR__ . '/../../Login/view/error/404.phtml',
            'error/index' => __DIR__ . '/../../Login/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
