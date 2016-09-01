<?php

/**
 * Nome: Module.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Configuracoes do Modulo Cadastro
 */

namespace Cadastro;

use Cadastro\View\Helper\FuncaoOnClick;
use Cadastro\View\Helper\InputDiaDaSemanaHoraMinuto;
use Cadastro\View\Helper\ListagemDeEventos;
use Cadastro\View\Helper\TemplateFormularioRodape;
use Cadastro\View\Helper\TemplateFormularioTopo;
use Cadastro\View\Helper\TituloDaPagina;
use Lancamento\View\Helper\DadosEntidade;
use Lancamento\View\Helper\InputFormulario;
use Login\View\Helper\BotaoLink;
use Login\View\Helper\BotaoSubmit;
use Login\View\Helper\BotaoSubmitDesabilitado;
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
                'inputFormulario' => function($sm) {
                    return new InputFormulario();
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
            )
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
    }

}
