<?php

namespace Login\Controller;

use CU_Controller_Plugin_CsrfProtect;
use Doctrine\ORM\EntityManager;
use Login\Controller\Helper\Constantes;
use Login\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */
class LoginController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $_doctrineAuthenticationService;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, AuthenticationService $doctrineAuthenticationService = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }

        if (!is_null($doctrineAuthenticationService)) {
            $this->_doctrineAuthenticationService = $doctrineAuthenticationService;
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /
     */
    public function indexAction() {
        /* Limpar mensagens */
        $this->flashMessenger()->clearCurrentMessages();

        $formLogin = new LoginForm(Constantes::$LOGIN_FORM);

        return [
            Constantes::$FORM_LOGIN => $formLogin,
        ];
    }

    /**
     * Função que tenta logar
     * POST /logar
     */
    public function logarAction() {
        $data = $this->getRequest()->getPost();

        /*
         * Testando ataques
         */
        $this->flashMessenger()->clearCurrentMessages();

        /* Post sem email */
        if (is_null($data[Constantes::$INPUT_EMAIL])) {
            $this->flashMessenger()->
                    addErrorMessage(Constantes::$MENSAGEM_ERRO_CSRF);
            /* Redirecionamento */
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }

        $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
        $adapter->setIdentityValue($data[Constantes::$INPUT_EMAIL]);
        $adapter->setCredentialValue(md5($data[Constantes::$INPUT_SENHA]));
        $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();

        if ($authenticationResult->isValid()) {
            /* Autenticacao valida */

            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_ACESSO,
            ));
        } else {
            /* Autenticacao falhou */
            $formLogin = new LoginForm(Constantes::$LOGIN_FORM);
            $formLogin->setData($this->getRequest()->getPost());
            /* Mensagem de erro */
            $mensagens = "";
            foreach ($authenticationResult->getMessages() as $message) {
                $mensagens .= "$message\n";
            }
            $this->flashMessenger()->
                    addErrorMessage($mensagens);
            /* Redirecionamento */
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }
    }

    /**
     * Função que direciona a tela de acesso
     * GET /acesso
     */
    public function acessoAction() {
        return [];
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }

}
