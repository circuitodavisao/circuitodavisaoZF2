<?php

/**
 * Nome: Module.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Configuracoes do Modulo Lancamento
 */

namespace Lancamento;

use Lancamento\View\Helper\AbaSelecionada;
use Lancamento\View\Helper\AlertaEnvioRelatorio;
use Lancamento\View\Helper\CabecalhoDeEventos;
use Lancamento\View\Helper\DadosEntidade;
use Lancamento\View\Helper\ListagemDePessoasComEventos;
use Lancamento\View\Helper\ModalAba;
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
