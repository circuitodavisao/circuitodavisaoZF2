<?php

/**
 * Nome: Module.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Configuracoes do Modulo Login
 */

namespace Login;

use Login\View\Helper\LinkLogo;
use Login\View\Helper\MensagemStatica;

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

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
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
            )
        );
    }

}
