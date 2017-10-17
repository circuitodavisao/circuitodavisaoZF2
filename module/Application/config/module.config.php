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
            'Application\Controller\Cadastro' => 'Application\Controller\Factory\CadastroControllerFactory',
            'Application\Controller\Relatorio' => 'Application\Controller\Factory\RelatorioControllerFactory',
            'Application\Controller\Instituto' => 'Application\Controller\Factory\InstitutoControllerFactory',
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
                        'id' => '[a-zA-Z0-9_]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Login',
                        'action' => 'index',
                    ),
                ),
            ),
            'principal' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/principal[:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Principal',
                        'action' => 'index',
                    ),
                ),
            ),
            'lancamento' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/lancamento[:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-z]*',
                        'id' => '[-0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Lancamento',
                        'action' => 'index',
                    ),
                ),
            ),
            'cadastro' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cadastro[:pagina]',
                    'constraints' => array(
                        'pagina' => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cadastro',
                        'action' => 'index',
                    ),
                ),
            ),
            'relatorio' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/relatorio[:action][/:tipoRelatorio][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                        'tipoRelatorio' => '[1-3]',
                        'id' => '[-0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Relatorio',
                        'action' => 'index',
                    ),
                ),
            ),
            'instituto' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/instituto[:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                        'id' => '[1-2]',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Instituto',
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
            'lancamento/lancamento/atendimento' => __DIR__ . '/../view/lancamento/atendimento.phtml',
            'lancamento/lancamento/arregimentacao' => __DIR__ . '/../view/lancamento/arregimentacao.phtml',
            'cadastro/cadastro/index' => __DIR__ . '/../view/cadastro/index.phtml',
            'cadastro/cadastro/evento' => __DIR__ . '/../view/cadastro/evento.phtml',
            'cadastro/cadastro/evento-exclusao' => __DIR__ . '/../view/cadastro/evento-exclusao.phtml',
            'cadastro/cadastro/grupo' => __DIR__ . '/../view/cadastro/grupo.phtml',
            'cadastro/cadastro/grupo-finalizar' => __DIR__ . '/../view/cadastro/grupo-finalizar.phtml',
            'cadastro/cadastro/grupo-atualizacao' => __DIR__ . '/../view/cadastro/grupo-atualizacao.phtml',
            'cadastro/cadastro/celula' => __DIR__ . '/../view/cadastro/celula.phtml',
            'cadastro/cadastro/celula-confirmacao' => __DIR__ . '/../view/cadastro/celula-confirmacao.phtml',
            'cadastro/cadastro/celula-exclusao' => __DIR__ . '/../view/cadastro/celula-exclusao.phtml',
            'cadastro/cadastro/celulas' => __DIR__ . '/../view/cadastro/celulas.phtml',
            'cadastro/cadastro/revisao' => __DIR__ . '/../view/cadastro/revisao.phtml',
            'cadastro/cadastro/transferencia' => __DIR__ . '/../view/cadastro/transferencia.phtml',
            'cadastro/cadastro/selecionar_revisionista' => __DIR__ . '/../view/cadastro/selecionar_revisionista.phtml',
            'cadastro/cadastro/turma' => __DIR__ . '/../view/cadastro/selecionar_revisionista.phtml',
            'relatorio/relatorio/index' => __DIR__ . '/../view/relatorio/index.phtml',
            'relatorio/relatorio/atendimento' => __DIR__ . '/../view/relatorio/atendimento.phtml',
            'instituto/instituto/index' => __DIR__ . '/../view/instituto/index.phtml',
            'layout/layout-js-relatorio-atendimento' => __DIR__ . '/../view/layout/layout-js-relatorio-atendimento.phtml',
            'layout/layout-js-celulas' => __DIR__ . '/../view/layout/layout-js-celulas.phtml',
            'layout/layout-js-celula' => __DIR__ . '/../view/layout/layout-js-celula.phtml',
            'layout/layout-js-celula-validacao' => __DIR__ . '/../view/layout/layout-js-celula-validacao.phtml',
            'layout/layout-js-celulas-validacao' => __DIR__ . '/../view/layout/layout-js-celulas-validacao.phtml',
            'layout/layout-js-lancamento' => __DIR__ . '/../view/layout/layout-js-lancamento.phtml',
            'layout/layout-js-lancamento-atendimento' => __DIR__ . '/../view/layout/layout-js-lancamento-atendimento.phtml',
            'layout/layout-js-lancamento-modal-eventos' => __DIR__ . '/../view/layout/layout-js-lancamento-modal-eventos.phtml',
            'layout/layout-js-cadastrar-pessoa' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa.phtml',
            'layout/layout-js-cadastrar-pessoa-validacao' => __DIR__ . '/../view/layout/layout-js-cadastrar-pessoa-validacao.phtml',
            'layout/layout-js-grupo' => __DIR__ . '/../view/layout/layout-js-grupo.phtml',
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layout-js-index' => __DIR__ . '/../view/layout/layout-js-index.phtml',
            'layout/layout-js-recuperar-acesso' => __DIR__ . '/../view/layout/layout-js-recuperar-acesso.phtml',
            'layout/layout-login-top' => __DIR__ . '/../view/layout/layout-login-top.phtml',
            'layout/layout-login-botton' => __DIR__ . '/../view/layout/layout-login-botton.phtml',
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
