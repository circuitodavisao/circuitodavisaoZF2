<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\RepositorioORM;
use Application\View\Helper\Abas;
use Application\View\Helper\AbaSelecionada;
use Application\View\Helper\AlertaEnvioRelatorio;
use Application\View\Helper\BotaoLink;
use Application\View\Helper\BotaoSubmit;
use Application\View\Helper\BotaoSubmitDesabilitado;
use Application\View\Helper\CabecalhoDeCiclos;
use Application\View\Helper\CabecalhoDeEventos;
use Application\View\Helper\DadosEntidade;
use Application\View\Helper\DivCapslock;
use Application\View\Helper\DivJavaScript;
use Application\View\Helper\InputFormulario;
use Application\View\Helper\LinkLogo;
use Application\View\Helper\ListagemDePessoasComEventos;
use Application\View\Helper\MensagemRelatorioEnviado;
use Application\View\Helper\MensagemStatica;
use Application\View\Helper\Menu;
use Application\View\Helper\MenuHierarquia;
use Application\View\Helper\ModalAba;
use Application\View\Helper\ModalMuitosCadastros;
use Application\View\Helper\ModalMuitosEventos;
use Application\View\Helper\PerfilDropDown;
use Application\View\Helper\PerfilIcone;
use Application\View\Helper\TabelaLancamento;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module {

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'linkLogo' => function($sm) {
                    return new LinkLogo();
                },
                'mensagemStatica' => function($sm) {
                    return new MensagemStatica();
                },
                'divCapslock' => function($sm) {
                    return new DivCapslock();
                },
                'divJavaScript' => function($sm) {
                    return new DivJavaScript();
                },
                'perfilIcone' => function($sm) {
                    return new PerfilIcone();
                },
                'perfilDropDown' => function($sm) {
                    return new PerfilDropDown();
                },
                'botaoSubmit' => function($sm) {
                    return new BotaoSubmit();
                },
                'botaoSubmitDesabilitado' => function($sm) {
                    return new BotaoSubmitDesabilitado();
                },
                'botaoLink' => function($sm) {
                    return new BotaoLink();
                },
                'menu' => function($sm) {
                    return new Menu();
                },
                'menuHierarquia' => function($sm) {
                    return new MenuHierarquia();
                },
                'dadosEntidade' => function($sm) {
                    return new DadosEntidade();
                },
                'alertaEnvioRelatorio' => function($sm) {
                    return new AlertaEnvioRelatorio();
                },
                'abaSelecionada' => function($sm) {
                    return new AbaSelecionada();
                },
                'ModalAba' => function($sm) {
                    return new ModalAba();
                },
                'cabecalhoDeEventos' => function($sm) {
                    return new CabecalhoDeEventos();
                },
                'listagemDePessoasComEventos' => function($sm) {
                    return new ListagemDePessoasComEventos();
                },
                'tabelaLancamento' => function($sm) {
                    return new TabelaLancamento();
                },
                'cabecalhoDeCiclos' => function($sm) {
                    return new CabecalhoDeCiclos();
                },
                'modalMuitosEventos' => function($sm) {
                    return new ModalMuitosEventos();
                },
                'abas' => function($sm) {
                    return new Abas();
                },
                'mensagemStatica' => function($sm) {
                    return new MensagemStatica();
                },
                'mensagemRelatorioEnviado' => function($sm) {
                    return new MensagemRelatorioEnviado();
                },
                'modalMuitosCadastros' => function($sm) {
                    return new ModalMuitosCadastros();
                },
                'botaoSubmit' => function($sm) {
                    return new BotaoSubmit();
                },
                'botaoSubmitDesabilitado' => function($sm) {
                    return new BotaoSubmitDesabilitado();
                },
                'botaoLink' => function($sm) {
                    return new BotaoLink();
                },
                'inputFormulario' => function($sm) {
                    return new InputFormulario();
                },
            )
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
            ),
        );
    }

    public function initSession($config) {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

    public function onBootstrap(MvcEvent $e) {
        $this->initSession(array(
            'remember_me_seconds' => 180,
            'use_cookies' => true,
            'cookie_httponly' => true,
        ));
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        //attach event here
        $eventManager->attach('route', array($this, 'checkUserAuth'), 2);

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        if ($sessao->idPessoa) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
            $repositorioORM = new RepositorioORM($serviceManager->get('Doctrine\ORM\EntityManager'));
            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($sessao->idPessoa);
            $viewModel->pessoa = $pessoa;
            $viewModel->responsabilidades = $pessoa->getResponsabilidadesAtivas();
            if ($sessao->idEntidadeAtual) {
                $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
                $grupo = $entidade->getGrupo();
                $viewModel->discipulos = $grupo->getGrupoPaiFilhoFilhos();
//                $viewModel->discipulos = 0;
            }
            if ($pessoa->getAtualizar_dados() === 'S') {
                $viewModel->mostrarMenu = 0;
            }
        }
    }

    public function checkUserAuth(MvcEvent $e) {
        $router = $e->getRouter();
        $matchedRoute = $router->match($e->getRequest());

        //this is a whitelist for routes that are allowed without authentication
        //!!! Your authentication route must be whitelisted
        $allowedRoutesConfig = array(
            Constantes::$ROUTE_LOGIN
        );
        if (!isset($matchedRoute) || in_array($matchedRoute->getMatchedRouteName(), $allowedRoutesConfig)) {
            // no auth check required
            return;
        }
        $seviceManager = $e->getApplication()->getServiceManager();
        $authenticationService = $seviceManager->get('Zend\Authentication\AuthenticationService');
        $identity = $authenticationService->getIdentity();
        if (!$identity) {
            //redirect to login route...
            $response = $e->getResponse();
            $response->setStatusCode(302);
            //this is the login screen redirection url
            $url = $e->getRequest()->getBaseUrl() . '/';
            $response->getHeaders()->addHeaderLine('Location', $url);
            $app = $e->getTarget();
            //dont do anything other - just finish here
            $app->getEventManager()->trigger(MvcEvent::EVENT_FINISH, $e);
            $e->stopPropagation();
        }
    }

}
