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
use Application\Controller\RelatorioController;
use Application\Model\ORM\RepositorioORM;
use Application\Model\Entity\EntidadeTipo;
use Application\View\Helper\AbaSelecionada;
use Application\View\Helper\Sitemap;
use Application\View\Helper\FiltroCursos;
use Application\View\Helper\AbasPanel;
use Application\View\Helper\AlertaEnvioRelatorio;
use Application\View\Helper\AtendimentoGruposAbaixo;
use Application\View\Helper\AtendimentosDoGrupo;
use Application\View\Helper\BarraDeProgresso;
use Application\View\Helper\BarraDeProgressoBonita;
use Application\View\Helper\BlocoDiv;
use Application\View\Helper\BlocoResponsavel;
use Application\View\Helper\BotaoLink;
use Application\View\Helper\BotaoPopover;
use Application\View\Helper\BotaoSimples;
use Application\View\Helper\BotaoSubmit;
use Application\View\Helper\BotaoSubmitDesabilitado;
use Application\View\Helper\CabecalhoDeAtendimentos;
use Application\View\Helper\CabecalhoDeCiclos;
use Application\View\Helper\CabecalhoDeEventos;
use Application\View\Helper\CabecalhoDeMeses;
use Application\View\Helper\CabecalhoDePeriodos;
use Application\View\Helper\CircuitoMeAjuda;
use Application\View\Helper\DadosEntidade;
use Application\View\Helper\DadosPrincipal;
use Application\View\Helper\DadosProximoNivel;
use Application\View\Helper\DivCapslock;
use Application\View\Helper\DivJavaScript;
use Application\View\Helper\DivMensagens;
use Application\View\Helper\DivTempoRestante;
use Application\View\Helper\FuncaoOnClick;
use Application\View\Helper\GrupoDadosComplementares;
use Application\View\Helper\GrupoEstadoCivil;
use Application\View\Helper\InformacoesGrupoAtendido;
use Application\View\Helper\InputAddon;
use Application\View\Helper\InputCampoEndereco;
use Application\View\Helper\InputDiaDaSemanaHoraMinuto;
use Application\View\Helper\InputExtras;
use Application\View\Helper\InputFormulario;
use Application\View\Helper\InputFormularioSimples;
use Application\View\Helper\LinkLogo;
use Application\View\Helper\ListagemAulas;
use Application\View\Helper\ListagemConsolidacaoParaRevisao;
use Application\View\Helper\ListagemCursos;
use Application\View\Helper\ListagemDeEventos;
use Application\View\Helper\ListagemDePessoasComEventos;
use Application\View\Helper\ListagemDisciplinas;
use Application\View\Helper\ListagemFichasAtivasRevisao;
use Application\View\Helper\ListagemFichasParaRevisao;
use Application\View\Helper\ListagemLideresTransferencia;
use Application\View\Helper\ListagemPessoasRevisao;
use Application\View\Helper\ListagemTurmas;
use Application\View\Helper\ListagemTurmasInativas;
use Application\View\Helper\MensagemRelatorioEnviado;
use Application\View\Helper\Menu;
use Application\View\Helper\MenuHierarquia;
use Application\View\Helper\ModalLoader;
use Application\View\Helper\ModalMuitosCadastros;
use Application\View\Helper\ModalMuitosEventos;
use Application\View\Helper\MontaGraficoDeBarra;
use Application\View\Helper\MontarEndereco;
use Application\View\Helper\PassoAPasso;
use Application\View\Helper\Perfil;
use Application\View\Helper\PerfilDropDown;
use Application\View\Helper\PerfilIcone;
use Application\View\Helper\SpanDadosValidados;
use Application\View\Helper\TabelaDeAlunos;
use Application\View\Helper\TabelaLancamento;
use Application\View\Helper\TemplateFormularioRodape;
use Application\View\Helper\TemplateFormularioTopo;
use Application\View\Helper\TituloDaPagina;
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
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
       );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'linkLogo' => function($sm) {
                    return new LinkLogo();
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
                'dadosPrincipal' => function($sm) {
                    return new DadosPrincipal();
                },
                'dadosProximoNivel' => function($sm) {
                    return new DadosProximoNivel();
                },
                'barraDeProgressoBonita' => function($sm) {
                    return new BarraDeProgressoBonita();
                },
                'alertaEnvioRelatorio' => function($sm) {
                    return new AlertaEnvioRelatorio();
                },
                'abaSelecionada' => function($sm) {
                    return new AbaSelecionada();
                },
                'cabecalhoDeEventos' => function($sm) {
                    return new CabecalhoDeEventos();
                },
                'listagemDePessoasComEventos' => function($sm) {
                    return new ListagemDePessoasComEventos();
                },
                'listagemConsolidacaoParaRevisao' => function($sm) {
                    return new ListagemConsolidacaoParaRevisao();
                },
                'listagemFichasAtivasRevisao' => function($sm) {
                    return new ListagemFichasAtivasRevisao();
                },
                'listagemPessoasRevisao' => function($sm) {
                    return new ListagemPessoasRevisao();
                },
                'filtroCursos' => function($sm) {
                    return new FiltroCursos();
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
                'abasPanel' => function($sm) {
                    return new AbasPanel();
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
                'inputFormularioSimples' => function($sm) {
                    return new InputFormularioSimples();
                },
                'divMensagens' => function($sm) {
                    return new DivMensagens();
                },
                'divTempoRestante' => function($sm) {
                    return new DivTempoRestante();
                },
                'botaoSimples' => function($sm) {
                    return new BotaoSimples();
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
                'funcaoOnClick' => function($sm) {
                    return new FuncaoOnClick();
                },
                'dadosEntidade' => function($sm) {
                    return new DadosEntidade();
                },
                'tituloDaPagina' => function($sm) {
                    return new TituloDaPagina();
                },
                'templateFormularioTopo' => function($sm) {
                    return new TemplateFormularioTopo();
                },
                'templateFormularioRodape' => function($sm) {
                    return new TemplateFormularioRodape();
                },
                'inputDiaDaSemanaHoraMinuto' => function($sm) {
                    return new InputDiaDaSemanaHoraMinuto();
                },
                'listagemDeEventos' => function($sm) {
                    return new ListagemDeEventos();
                },
                'listagemConsolidacaoParaRevisao' => function($sm) {
                    return new ListagemConsolidacaoParaRevisao();
                },
                'listagemFichasParaRevisao' => function($sm) {
                    return new ListagemFichasParaRevisao();
                },
                'listagemFichasAtivasParaRevisao' => function($sm) {
                    return new ListagemFichasAtivasParaRevisao();
                },
                'listagemTurmas' => function($sm) {
                    return new ListagemTurmas();
                },
                'listagemCursos' => function($sm) {
                    return new ListagemCursos();
                },
                'listagemDisciplinas' => function($sm) {
                    return new ListagemDisciplinas();
                },
                'listagemAulas' => function($sm) {
                    return new ListagemAulas();
                },
                'listagemTurmasInativas' => function($sm) {
                    return new ListagemTurmasInativas();
                },
                'inputExtras' => function($sm) {
                    return new InputExtras();
                },
                'botaoPopover' => function($sm) {
                    return new BotaoPopover();
                },
                'inputAddon' => function($sm) {
                    return new InputAddon();
                },
                'grupoEstadoCivil' => function($sm) {
                    return new GrupoEstadoCivil();
                },
                'blocoResponsavel' => function($sm) {
                    return new BlocoResponsavel();
                },
                'blocoDiv' => function($sm) {
                    return new BlocoDiv();
                },
                'barraDeProgresso' => function($sm) {
                    return new BarraDeProgresso();
                },
                'passoAPasso' => function($sm) {
                    return new PassoAPasso();
                },
                'spanDadosValidados' => function($sm) {
                    return new SpanDadosValidados();
                },
                'tabelaDeAlunos' => function($sm) {
                    return new TabelaDeAlunos();
                },
                'montarEndereco' => function($sm) {
                    return new MontarEndereco();
                },
                'inputCampoEndereco' => function($sm) {
                    return new InputCampoEndereco();
                },
                'modalLoader' => function($sm) {
                    return new ModalLoader();
                },
                'grupoDadosComplementares' => function($sm) {
                    return new GrupoDadosComplementares();
                },
                'atendimentoGruposAbaixo' => function($sm) {
                    return new AtendimentoGruposAbaixo();
                },
                'cabecalhoDeAtendimentos' => function($sm) {
                    return new CabecalhoDeAtendimentos();
                },
                'informacoesGrupoAtendido' => function ($sm) {
                    return new InformacoesGrupoAtendido();
                },
                'atendimentosDoGrupo' => function ($sm) {
                    return new AtendimentosDoGrupo();
                },
                'listagemLideresTransferencia' => function ($sm) {
                    return new ListagemLideresTransferencia();
                },
                'cabecalhoDePeriodos' => function ($sm) {
                    return new CabecalhoDePeriodos();
                },
                'cabecalhoDeMeses' => function ($sm) {
                    return new CabecalhoDeMeses();
                },
                'montaGraficoDeBarra' => function ($sm) {
                    return new MontaGraficoDeBarra();
                },
                'circuitoMeAjuda' => function ($sm) {
                    return new CircuitoMeAjuda();
                },
                'perfil' => function ($sm) {
					return new Perfil();
				},
				'sitemap' => function ($sm) {
					return new Sitemap();
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
			'remember_me_seconds' => 1,
			'use_cookies' => true,
			'cookie_httponly' => true,
			'save_path' => '/tmp',
		));

		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
		//attach event here
		$eventManager->attach('route', array($this, 'checkUserAuth'), 2);

		$viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		if (!empty($sessao->idPessoa) || !empty($sessao->idEntidadeAtual)) {

			$serviceManager = $e->getApplication()->getServiceManager();
			$repositorioORM = new RepositorioORM($serviceManager->get('Doctrine\ORM\EntityManager'));
			if (!empty($sessao->idPessoa)) {
				$pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($sessao->idPessoa);
				$viewModel->pessoa = $pessoa;
			}

			if (!empty($sessao->idEntidadeAtual)) {
				$entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
				$viewModel->entidade = $entidade;
			}
		}
		$stringHttps = 'https://';
		$stringUrl = 'circuitodavisaonovo.com.br';
		if (empty($sessao->idEntidadeAtual) ||
			empty($sessao->idPessoa) ||
			(
				$pessoa->getAtualizar_dados() === 'S' && 
				(
					$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao &&
					$entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao
				)
			) 
			||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/logar' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/selecionarPerfil' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/preSaida' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/cep' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/cadastroFichaRevisao' ||
			$e->getRequest()->getUriString() == $stringHttps . 'www.' . $stringUrl . '/lancamentoDiscipulado' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/logar' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/selecionarPerfil' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/preSaida' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/cep' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/cadastroFichaRevisao' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/lancamentoDiscipulado' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/principalEmail' ||
			$e->getRequest()->getUriString() == $stringHttps . $stringUrl . '/vimeo' ||
			!$pessoa->getData_nascimento() || 
			!$pessoa->getSexo() || 
			!$pessoa->getProfissao() || 
			$pessoa->getEmail() == 'atualize' ||
			$sessao->naoMostrarMenu === 1
		) {
			$viewModel->mostrarMenu = 0;
		}
	}

    public function checkUserAuth(MvcEvent $e) {
        $router = $e->getRouter();
        $matchedRoute = $router->match($e->getRequest());

//this is a whitelist for routes that are allowed without authentication
//!!! Your authentication route must be whitelisted
        $allowedRoutesConfig = array(
            Constantes::$ROUTE_LOGIN,
            'migracao',
            'deploy',
            'api',
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
