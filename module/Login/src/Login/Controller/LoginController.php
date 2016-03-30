<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\Funcoes;
use Login\Controller\Helper\LoginORM;
use Login\Form\LoginForm;
use Login\Form\RecuperarAcessoForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */
class LoginController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $_doctrineAuthenticationService;
    private $_translator;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, AuthenticationService $doctrineAuthenticationService = null, Translator $translator = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }

        if (!is_null($doctrineAuthenticationService)) {
            $this->_doctrineAuthenticationService = $doctrineAuthenticationService;
        }

        if (!is_null($translator)) {
            $this->_translator = $translator;
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



        /* Post sem email */
        if (is_null($data[Constantes::$INPUT_EMAIL])) {
            /* Redirecionamento */
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }

        $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
        $adapter->setIdentityValue($data[Constantes::$INPUT_EMAIL]);
        $adapter->setCredentialValue(md5($data[Constantes::$INPUT_SENHA]));
        $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();

        if ($authenticationResult->isValid()) {
            /* Autenticacao valida */

            /* Helper Controller */
            $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

            /* Verificar se existe pessoa por email informado */
            $pessoa = $loginORM->getPessoaORM()->encontrarPorEmail($data[Constantes::$INPUT_EMAIL]);

            if (!$pessoa->verificarSeEstaAtivo()) {
                /* Inativada */
                /* Autenticacao falhou */

                /* Redirecionamento */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_INDEX,
                            Constantes::$INPUT_EMAIL => $data[Constantes::$INPUT_EMAIL],
                            Constantes::$MENSAGEM => $this->getTranslator()->translate(Constantes::$TRADUCAO_PESSOA_INATIVADA)
                ));
            } else {
                /* Ativada */
            }

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
     * GET /acessoAction
     */
    public function acessoAction() {
        return [];
    }

    /**
     * Função que direciona a tela de email enviado
     * GET /emailEnviado
     */
    public function emailEnviadoAction() {
        return [];
    }

    /**
     * Função que direciona a tela de esqueceu senha
     * GET /esqueceuSenha
     */
    public function esqueceuSenhaAction() {
        $formRecuperarAcesso = new RecuperarAcessoForm(Constantes::$RECUPERAR_ACESSO_FORM);

        /* Mensagem */
        $tipo = $this->params()->fromRoute(Constantes::$TIPO);
        $messagem = $this->params()->fromRoute(Constantes::$MENSAGEM);
        return [
            Constantes::$FORM_RECUPERAR_ACESSO => $formRecuperarAcesso,
            Constantes::$TIPO => $tipo,
            Constantes::$MENSAGEM => $messagem,
        ];
    }

    /**
     * Função que tenta recuperar o acesso
     * GET /acesso
     */
    public function recuperarAcessoAction() {
        $resposta = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* Helper Controller */
            $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

            /* Dados da requisição POST */
            $dataPost = $request->getPost();

            /* recupera o id vindo da url */
            $idTipo = $this->params()->fromRoute('id', 0);
            if ($idTipo == 1) {
                /* Verificar se existe pessoa por email informado */
                $email = $dataPost[Constantes::$ENTITY_PESSOA_EMAIL];
                $pessoa = $loginORM->getPessoaORM()->encontrarPorEmail($email);
            }
            if ($idTipo == 2) {
                /* Verificar se existe pessoa por data de nascimento e digitos do CPF informado */
                $documento = $dataPost[Constantes::$INPUT_CPF];
                $dataNascimento = Funcoes::mudarPadraoData($dataPost[Constantes::$INPUT_DATA_NASCIMENTO], 0);
                $pessoa = $loginORM->getPessoaORM()->encontrarPorCPFEDataNascimento($documento, $dataNascimento);
            }
            /* Pessoa não encontrada */
            if (!$pessoa) {
                /* Redirecionamento */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                            Constantes::$TIPO => 1,
                            Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_NAO_ENCONTRADA,
                ));
            } else {
                if (!$pessoa->verificarSeEstaAtivo()) {
                    /* Redirecionamento */
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                                Constantes::$TIPO => 1,
                                Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_INATIVADA,
                    ));
                } else {
                    /* Email */
                    if ($idTipo == 1) {
                        $resposta = $email;
                        $this->enviarEmail($email, '$mensagem');

                        /* Redirecionamento */
                        return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                    Constantes::$ACTION => Constantes::$ACTION_EMAIL_ENVIADO
                        ));
                    }
                    /* CPF e Data de Nascimento */
                    if ($idTipo == 2) {
                        $resposta = $this->getTranslator()->translate(Constantes::$TRADUCAO_SEU_LOGIN_E) . ' ' . $pessoa->getEmail();
                    }
                }
            }
        }

        return [
            'resposta' => $resposta,
        ];
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }

    public function getTranslator() {
        return $this->_translator;
    }

    private function enviarEmail($email, $mensagem) {
//        $mail = new PHPMailer;
////                    $mail->SMTPDebug = 1;                              // Enable verbose debug output
//                    $mail->isSMTP();                                      // Set mailer to use SMTP
//                    $mail->Host = '200.147.36.31';  // Specify main and backup SMTP servers
//                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
//                    $mail->Username = 'leonardo@circuitodavisao.com.br';                 // SMTP username
//                    $mail->Password = 'Leonardo142857';                           // SMTP password
////                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//                    $mail->Port = 587;                                    // TCP port to connect to
//                    $mail->setFrom('leonardo@circuitodavisao.com.br', 'Chispirito TLS sem 3');
//                    $mail->addAddress('falecomleonardopereira@gmail.com', 'Leo Gatao TLS sem 3');
//                    $mail->isHTML(true);                                  // Set email format to HTML
//                    $mail->Subject = 'Here is the subject TLS';
//                    $mail->Body = 'This is the HTML message body <b>in bold!</b> 554';
//                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//
//                    if (!$mail->send()) {
//                        echo 'Message could not be sent.';
//                        echo 'Mailer Error: ' . $mail->ErrorInfo;
//                    } else {
//                        echo '#### Message has been sent';
//                    }
    }

}
