<?php

/**
 * Nome: Module.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Configuracoes do Modulo Lancamento
 */

namespace Lancamento;

use Lancamento\View\Helper\Abas;
use Lancamento\View\Helper\AbaSelecionada;
use Lancamento\View\Helper\AlertaEnvioRelatorio;
use Lancamento\View\Helper\CabecalhoDeCiclos;
use Lancamento\View\Helper\CabecalhoDeEventos;
use Lancamento\View\Helper\DadosEntidade;
use Lancamento\View\Helper\ListagemDePessoasComEventos;
use Lancamento\View\Helper\MensagemRelatorioEnviado;
use Lancamento\View\Helper\ModalAba;
use Lancamento\View\Helper\ModalMuitosCadastros;
use Lancamento\View\Helper\ModalMuitosEventos;
use Lancamento\View\Helper\TabelaLancamento;
use Login\View\Helper\MensagemStatica;
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
