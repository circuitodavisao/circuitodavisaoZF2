<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Exception;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\LoginORM;
use Login\Form\LoginForm;
use Login\Form\RecuperarAcessoForm;
use PHPMailer;
use Zend\Authentication\AuthenticationService;
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

                    $mail = new PHPMailer;
//                    $mail->SMTPDebug = 1;                              // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = '200.147.36.31';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'leonardo@circuitodavisao.com.br';                 // SMTP username
                    $mail->Password = 'Leonardo142857';                           // SMTP password
//                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to
                    $mail->setFrom('leonardo@circuitodavisao.com.br', 'Chispirito TLS sem 3');
                    $mail->addAddress('falecomleonardopereira@gmail.com', 'Leo Gatao TLS sem 3');
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Here is the subject TLS';
                    $mail->Body = 'This is the HTML message body <b>in bold!</b> 554';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    if (!$mail->send()) {
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        echo '#### Message has been sent';
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
