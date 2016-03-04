<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Login\Controller\Helper\Constantes;
use Login\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */
class LoginController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $_doctrineAuthenticationservice;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, AuthenticationService $doctrineAuthenticationservice = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }

        if (!is_null($doctrineAuthenticationservice)) {
            $this->_doctrineAuthenticationservice = $doctrineAuthenticationservice;
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /
     */
    public function indexAction() {
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

        $adapter = $this->getDoctrineAuthenticationservicer()->getAdapter();
        $adapter->setIdentityValue($data[Constantes::$INPUT_EMAIL]);
        $adapter->setCredentialValue(md5($data[Constantes::$INPUT_SENHA]));
        $authenticationResult = $this->getDoctrineAuthenticationservicer()->authenticate();

        if ($authenticationResult->isValid()) {
            /* Autenticacao valida */
            $identity = $authenticationResult->getIdentity();
            /* Por Entity na Sessão */
            $this->getDoctrineAuthenticationservicer()->getStorage()->write($identity);
            /* redirecionar */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_ACESSO,
            ));
        } else {
            /* Autenticacao falhou */
            $formLogin = new LoginForm(Constantes::$LOGIN_FORM);
            $formLogin->setData($this->getRequest()->getPost());
            /* Mensagem para teste */
            $this->flashMessenger()->
                    addErrorMessage("Logar falhou truta !");
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }
    }

    /**
     * Função que direciona a tela de acesso
     * GET /
     */
    public function acessoAction() {
        return [];
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function getDoctrineAuthenticationservicer() {
        return $this->_doctrineAuthenticationservice;
    }

}

//        // TESTE DO ORM
//        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
//        $idPessoa = 1;
//        $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);
//        $this->flashMessenger()->addInfoMessage("Pessoa: " . $pessoa->getNome());
//        $formLoginDaRota = $this->params()->fromRoute('formLogin', 0);
//        if (!empty($formLoginDaRota)) {
//            $formLogin = $formLoginDaRota;
//        } else {
//            $formLogin = new LoginForm("LoginForm");
//        }
