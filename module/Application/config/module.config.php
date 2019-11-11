<?php

/**
 * Nome: module.config.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> e Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Arquivo com as configurações globais da aplicação, como modulos ativos, caminhos para eles e arquivos gerais
 */

namespace Application;

use Memcached;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'factories' => array(
            'Application\Controller\Login' => 'Application\Controller\Factory\LoginControllerFactory',
            'Application\Controller\Principal' => 'Application\Controller\Factory\PrincipalControllerFactory',
            'Application\Controller\Lancamento' => 'Application\Controller\Factory\LancamentoControllerFactory',
            'Application\Controller\Cadastro' => 'Application\Controller\Factory\CadastroControllerFactory',
            'Application\Controller\Relatorio' => 'Application\Controller\Factory\RelatorioControllerFactory',
            'Application\Controller\Curso' => 'Application\Controller\Factory\CursoControllerFactory',
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
                    'route' => '/principal[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-2]',
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
                    'route' => '/relatorio[:action][/:tipoRelatorio][/:mes/:ano]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                        'tipoRelatorio' => '[1-9]',
                        'mes' => '[0-9]+',
                        'ano' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Relatorio',
                        'action' => 'index',
                    ),
                ),
            ),
            'curso' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/curso[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[-0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Curso',
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
			'doctrine.cache.minha_memcache' => function(){
				$memcached = new Memcached();
				$memcached->addServer('localhost', 11211);
	
				$cache = new \Doctrine\Common\Cache\MemcachedCache();
				$cache->setMemcached($memcached);
				return $cache;
			}
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
        'template_map' => include __DIR__ . '/../template_map.php',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    # definir driver, classes anotadas para o doctrine e quem faz autenticação
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'memcache',
                'paths' => array(__DIR__ . '/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model\Entity' => 'application_entities'
                )
            )
        ),
		'cache' => array(
			'memcache' => array(
				'instance' => 'minha_memcache',
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
