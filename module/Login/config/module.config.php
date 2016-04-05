<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Login;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Login\Controller\Login' => 'Login\Controller\Factory\LoginControllerFactory',
        ),
    ),
    # definir e gerenciar rotas
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Login\Controller\Login',
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
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    # definir e gerenciar traduções
    'translator' => array(
        'locale' => 'us_US',
//        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
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
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'login/login/index' => __DIR__ . '/../view/login/index/index.phtml',
            'login/login/principal' => __DIR__ . '/../view/login/index/principal.phtml',
            'login/login/esqueceu-senha' => __DIR__ . '/../view/login/index/esqueceu-senha.phtml',
            'login/login/recuperar-acesso' => __DIR__ . '/../view/login/index/recuperar-acesso.phtml',
            'login/login/email-enviado' => __DIR__ . '/../view/login/index/email-enviado.phtml',
            'login/login/recuperar-senha' => __DIR__ . '/../view/login/index/recuperar-senha.phtml',
            'login/login/alterar-senha' => __DIR__ . '/../view/login/index/alterar-senha.phtml',
            'login/login/selecionar-perfil' => __DIR__ . '/../view/login/index/selecionar-perfil.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
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
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => 'application_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Login\Entity\Pessoa',
                'identity_property' => 'email',
                'credential_property' => 'senha',
            ),
        ),
    ),
);
