<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Lancamento;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Lancamento\Controller\Lancamento' => 'Lancamento\Controller\Factory\LancamentoControllerFactory',
        ),
    ),
    # definir e gerenciar rotas
    'router' => array(
        'routes' => array(
            'lancamento' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/lancamento[:pagina[/:id]]',
                    'constraints' => array(
                        'pagina' => '[a-zA-z]*',
                        'id' => '[1-2]|[1-2]_[1-6]',
                    ),
                    'defaults' => array(
                        'controller' => 'Lancamento\Controller\Lancamento',
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
            'lancamento/lancamento/index' => __DIR__ . '/../view/lancamento/index.phtml',
            'lancamento/lancamento/cadastrar-pessoa' => __DIR__ . '/../view/lancamento/cadastrar-pessoa.phtml',
            'lancamento/lancamento/cadastrar-pessoa-revisao' => __DIR__ . '/../view/lancamento/cadastrar-pessoa-revisao.phtml',
            'lancamento/lancamento/ficha-revisao' => __DIR__ . '/../view/lancamento/ficha-revisao.phtml',
            'lancamento/lancamento/enviar-relatorio' => __DIR__ . '/../view/lancamento/enviar-relatorio.phtml',
            'lancamento/lancamento/atendimento' => __DIR__ . '/../view/lancamento/atendimento.phtml',
            'layout/layout-js-lancamento' => __DIR__ . '/../view/layout/layout-js-lancamento.phtml',
            'layout/layout-js-lancamento-modal-eventos' => __DIR__ . '/../view/layout/layout-js-lancamento-modal-eventos.phtml',
            'layout/layout-js-cadastrar-pessoa' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa.phtml',
            'layout/layout-js-cadastrar-pessoa-validacao' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa-validacao.phtml',
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
