<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Exception;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\LoginORM;
use Login\Form\LoginForm;
use Login\Form\RecuperarAcessoForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\Controller\AbstractActionController;

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

        $classeMessagem = Constantes::$CLASS_HIDDEN;

        $formLogin = new LoginForm(Constantes::$LOGIN_FORM);

        $inputEmailDaRota = $this->params()->fromRoute(Constantes::$INPUT_EMAIL);
        if (!empty($inputEmailDaRota)) {
            $formLogin->get(Constantes::$INPUT_EMAIL)->setValue($inputEmailDaRota);
            $classeMessagem = '';
        }

        return [
            Constantes::$FORM_LOGIN => $formLogin,
            Constantes::$CLASS_HIDDEN => $classeMessagem,
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

            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
                        Constantes::$INPUT_EMAIL => $data[Constantes::$INPUT_EMAIL]
            ));
        }
    }

    /**
     * Função que direciona a tela de acesso
     * GET /acesso
     */
    public function acessoAction() {
        return [];
    }

    /**
     * Função que direciona a tela de esqueceu senha
     * GET /esqueceuSenha
     */
    public function esqueceuSenhaAction() {
        $formRecuperarAcesso = new RecuperarAcessoForm(Constantes::$RECUPERAR_ACESSO_FORM);
        return [
            Constantes::$FORM_RECUPERAR_ACESSO => $formRecuperarAcesso,
        ];
    }

    /**
     * Função que tenta recuperar o acesso
     * GET /acesso
     */
    public function recuperarAcessoAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* Helper Controller */
            $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

            /* Dados da requisição POST */
            $data = $request->getPost();

            $formRecuperarAcesso = new RecuperarAcessoForm(Constantes::$RECUPERAR_ACESSO_FORM);
            $formRecuperarAcesso->setData($data);

            if ($formRecuperarAcesso->isValid()) {
                echo "IS VALID <br />";
            } else {
                echo "IS NOT VALID <br />";
            }

            /* Verificar se existe pessoa por email informado */
            $email = $data[Constantes::$ENTITY_PESSOA_EMAIL];
            $pessoa = $loginORM->getPessoaORM()->encontrarPorEmail($email);

            $mensagem = '';
            /* Pessoa com email informado nao encontrada */
            if (!$pessoa) {
                $mensagem = 'Pessoa nao encontrada';
            } else {
                if (!$pessoa->verificarSeEstaAtivo()) {
                    $mensagem = 'Pessoa inativada';
                } else {
                    $mensagem = 'Pessoa ok truta';
                    try {
                        $message = new Message();
                        $message->setBody('This is the body');
                        $message->setFrom('myemail@mydomain.com');
                        $message->addTo('falecomleonardopereira@gmail.com');
                        $message->setSubject('Test subject');

                        $smtpOptions = new SmtpOptions(array(
                            'name' => 'smtp.circuitodavisao.com.br',
                            'host' => 'smtp.circuitodavisao.com.br',
                            'port' => 587,
                            'connection_class' => 'smtp',
                            'connection_config' => array(
                                'username' => 'leonardo@circuitodavisao.com.br',
                                'password' => 'Leonardo142857',
                                'ssl' => 'tls',
                            ),
                        ));

                        $transport = new Smtp($smtpOptions);
                        $transport->send($message);
                    } catch (Exception $exc) {
                        echo $exc->getTraceAsString();
                    }
                }
            }
        }
        return ['mensagem' => $mensagem];
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }

}
