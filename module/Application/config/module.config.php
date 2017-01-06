<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Application;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Application\Controller\Login' => 'Application\Controller\Factory\LoginControllerFactory',
            'Application\Controller\Principal' => 'Application\Controller\Factory\PrincipalControllerFactory',
            'Application\Controller\Lancamento' => 'Application\Controller\Factory\LancamentoControllerFactory',
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
                        'controller' => 'Application\Controller\Login',
                        'action' => 'index',
                    ),
                ),
            ),
            'principal' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/principal',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Principal',
                        'action' => 'index',
                    ),
                ),
            ),
            'lancamento' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/lancamento[:pagina[/:id]]',
                    'constraints' => array(
                        'pagina' => '[a-zA-z]*',
                        'id' => '[1-2]|[1-2]_[1-6]',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Lancamento',
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
//        'locale' => 'us_US',
        'locale' => 'pt_BR',
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
            'layout/layout-js-index' => __DIR__ . '/../view/layout/layout-js-index.phtml',
            'layout/layout-js-recuperar-acesso' => __DIR__ . '/../view/layout/layout-js-recuperar-acesso.phtml',
            'layout/layout-login-top' => __DIR__ . '/../view/layout/layout-login-top.phtml',
            'layout/layout-login-botton' => __DIR__ . '/../view/layout/layout-login-botton.phtml',
            'login/login/index' => __DIR__ . '/../view/login/index/index.phtml',
            'login/login/principal' => __DIR__ . '/../view/login/index/principal.phtml',
            'login/login/esqueceu-senha' => __DIR__ . '/../view/login/index/esqueceu-senha.phtml',
            'login/login/recuperar-acesso' => __DIR__ . '/../view/login/index/recuperar-acesso.phtml',
            'login/login/email-enviado' => __DIR__ . '/../view/login/index/email-enviado.phtml',
            'login/login/recuperar-senha' => __DIR__ . '/../view/login/index/recuperar-senha.phtml',
            'login/login/nova-senha' => __DIR__ . '/../view/login/index/nova-senha.phtml',
            'login/login/alterar-senha' => __DIR__ . '/../view/login/index/alterar-senha.phtml',
            'login/login/selecionar-perfil' => __DIR__ . '/../view/login/index/selecionar-perfil.phtml',
            'login/login/pre-saida' => __DIR__ . '/../view/login/index/pre-saida.phtml',
            'principal/principal/index' => __DIR__ . '/../view/principal/index.phtml',
            'lancamento/lancamento/index' => __DIR__ . '/../view/lancamento/index.phtml',
            'lancamento/lancamento/cadastrar-pessoa' => __DIR__ . '/../view/lancamento/cadastrar-pessoa.phtml',
            'lancamento/lancamento/cadastrar-pessoa-revisao' => __DIR__ . '/../view/lancamento/cadastrar-pessoa-revisao.phtml',
            'lancamento/lancamento/ficha-revisao' => __DIR__ . '/../view/lancamento/ficha-revisao.phtml',
            'lancamento/lancamento/enviar-relatorio' => __DIR__ . '/../view/lancamento/enviar-relatorio.phtml',
            'layout/layout-js-lancamento' => __DIR__ . '/../view/layout/layout-js-lancamento.phtml',
            'layout/layout-js-lancamento-modal-eventos' => __DIR__ . '/../view/layout/layout-js-lancamento-modal-eventos.phtml',
            'layout/layout-js-cadastrar-pessoa' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa.phtml',
            'layout/layout-js-cadastrar-pessoa-validacao' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa-validacao.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
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
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Model\Entity\Pessoa',
                'identity_property' => 'email',
                'credential_property' => 'senha',
            ),
        ),
    ),
);
