<?php

namespace Login\Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\Funcoes;
use Login\Controller\Helper\LoginORM;
use Login\Form\LoginForm;
use Login\Form\RecuperarAcessoForm;
use Login\Form\RecuperarSenhaForm;
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
        $tipoMensagem = 0;
        $formLogin = new LoginForm(Constantes::$LOGIN_FORM);

        $inputEmailDaRota = $this->params()->fromRoute(Constantes::$INPUT_EMAIL);
        $mensagem = $this->params()->fromRoute(Constantes::$MENSAGEM);
        $tipo = $this->params()->fromRoute(Constantes::$TIPO);

        if (!empty($inputEmailDaRota)) {
            $formLogin->get(Constantes::$INPUT_EMAIL)->setValue($inputEmailDaRota);
            if ($tipo == 1) {//warning
                $mensagem = Constantes::$TRADUCAO_PESSOA_INATIVADA;
                $tipoMensagem = 1; // warning
            } else {// danger
                $mensagem = Constantes::$TRADUCAO_FALHA_LOGIN;
                $tipoMensagem = 4; // danger
            }
        }


        return [
            Constantes::$FORM_LOGIN => $formLogin,
            Constantes::$MENSAGEM => $mensagem,
            Constantes::$TIPO => $tipoMensagem,
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
                            Constantes::$MENSAGEM => $this->getTranslator()->translate(Constantes::$TRADUCAO_PESSOA_INATIVADA),
                            Constantes::$TIPO => 1, // warning
                ));
            } else {
                /* Ativada */
            }

            /* Redirecionamento SELECIONAR PERFIL */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
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
        $div = $this->params()->fromRoute(Constantes::$DIV);

        $classDiv0 = '';
        $classDiv1 = 'hidden';
        $classDiv2 = 'hidden';
        if ($div == 1) {
            $classDiv0 = 'hidden';
            $classDiv1 = '';
            $classDiv2 = 'hidden';
        }
        if ($div == 2) {
            $classDiv0 = 'hidden';
            $classDiv1 = 'hidden';
            $classDiv2 = '';
        }

        return [
            Constantes::$FORM_RECUPERAR_ACESSO => $formRecuperarAcesso,
            Constantes::$TIPO => $tipo,
            Constantes::$MENSAGEM => $messagem,
            'classDiv0' => $classDiv0,
            'classDiv1' => $classDiv1,
            'classDiv2' => $classDiv2,
        ];
    }

    /**
     * Função que tenta recuperar o acesso
     * GET /recuperarAcesso
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
            $idTipo = $this->params()->fromRoute(Constantes::$ID, 0);
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

            /* Div que precisa esta aberta na volta */
            $div = 0;
            if ($idTipo == 1) {
                $div = 1;
            } else {
                $div = 2;
            }

            /* Pessoa não encontrada */
            if (!$pessoa) {
                /* Redirecionamento */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                            Constantes::$TIPO => 4, //danger
                            Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_NAO_ENCONTRADA,
                            Constantes::$DIV => $div,
                ));
            } else {
                if (!$pessoa->verificarSeEstaAtivo()) {
                    /* Redirecionamento */
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                                Constantes::$TIPO => 1,
                                Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_INATIVADA,
                                Constantes::$DIV => $div,
                    ));
                } else {
                    /* Email */
                    if ($idTipo == 1) {
                        $mensagemOriginal = $this->getTranslator()->translate(Constantes::$TRADUCAO_EMAIL_MENSAGEM_RECUPERAR_SENHA);
                        $mensagemComEmail = str_replace('#email', $email, $mensagemOriginal);
                        $timeNow = new DateTime();

                        $dataEnvio = $timeNow->format('Ymd');
                        $hora = $timeNow->format('His');
                        $token = md5($dataEnvio . $hora);

                        /* Persistir pessoa */
                        $pessoa->setToken($token);
                        $loginORM->getPessoaORM()->persistirPessoa($pessoa);

                        $mensagemAjustada = str_replace('#id', $token, $mensagemComEmail);
                        Funcoes::enviarEmail($email, $this->getTranslator()->translate(Constantes::$TRADUCAO_EMAIL_TITULO_RECUPERAR_SENHA), $mensagemAjustada);

                        /* Redirecionamento */
                        return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                    Constantes::$ACTION => Constantes::$ACTION_EMAIL_ENVIADO
                        ));
                    }
                    /* CPF e Data de Nascimento */
                    if ($idTipo == 2) {
                        $resposta = $this->getTranslator()->translate(Constantes::$TRADUCAO_SEU_LOGIN_E) . ' <b>' . $pessoa->getEmail() . '</b>';
                    }
                }
            }
        }

        return [
            'resposta' => $resposta,
        ];
    }

    /**
     * Função qpara recuperar a senha
     * GET /recuperarSenha
     */
    public function recuperarSenhaAction() {
        unset($dados);
        /* Helper Controller */
        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

        $tokenDaRota = $this->params()->fromRoute(Constantes::$ID);
        $pessoa = $loginORM->getPessoaORM()->encontrarPorToken($tokenDaRota);
        if ($pessoa) {
            /* Verificando se se passaram 24 horas desde a solicitacao */
            /* Data e Hora atual */
            $timeNow = new DateTime();

            /* Data do token */
            $tokenData = new DateTime();
            $tokenData->setDate($pessoa->getToken_data_ano(), $pessoa->getToken_data_mes(), $pessoa->getToken_data_dia());
            $tokenData->setTime($pessoa->getToken_hora_hora(), $pessoa->getToken_hora_minutos(), $pessoa->getToken_hora_segundos());

            $diferenca = $tokenData->diff($timeNow);
            $diferencaDias = $diferenca->format('%d');
            $diferencaHoras = $diferenca->format('%H');

            /* Mesmo dia ou 1 dia */
            if ($diferencaDias == 0 && $diferencaHoras < 24) {
                $formRecuperarSenha = new RecuperarSenhaForm(Constantes::$RECUPERAR_SENHA_FORM, $pessoa->getId());
                $dados[Constantes::$FORM_RECUPERAR_SENHA] = $formRecuperarSenha;
            }
            /* Mais de um dia */ else {
                /* Redirecionamento */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                            Constantes::$TIPO => 4,
                            Constantes::$MENSAGEM => 'Seu link de recuperacao expirou',
                ));
            }
        } else {
            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                        Constantes::$TIPO => 4,
                        Constantes::$MENSAGEM => 'Seu link de recuperacao expirou',
            ));
        }

        return $dados;
    }

    /**
     * Função que direciona a tela de email enviado
     * GET /alterarSenha
     */
    public function alterarSenhaAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {

            try {
                /* Helper Controller */
                $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

                /* Dados da requisição POST */
                $dataPost = $request->getPost();
                $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($dataPost[Constantes::$INPUT_ID_PESSOA]);
                $pessoa->setSenha($dataPost[Constantes::$INPUT_SENHA]);
                $pessoa->setToken(null);
                $pessoa->setToken_data(null);
                $pessoa->setToken_hora(null);
                /* Salvando nova senha */
                $loginORM->getPessoaORM()->persistirPessoa($pessoa);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return [];
    }

    /**
     * Função que direciona a tela de acesso
     * GET /principal
     */
    public function principalAction() {
        return [];
    }

    /**
     * Função que direciona a tela de acesso
     * GET /selecionarPerfil
     */
    public function selecionarPerfilAction() {
        /* Helper Controller */
        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

        $idPessoa = 1; // leonardo pereira
        $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);

        if ($pessoa->getPerfisDeAcesso()) {
            foreach ($pessoa->getPerfisDeAcesso() as $perfilAcesso) {
                echo "<br />" . $perfilAcesso->getId_perfil_acesso();
                $perfil = $perfilAcesso->getPerfilAcesso();
                echo "<br />" . $perfil->getNome();
            }
        }

        return [];
    }

    /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Recupera autenticação doctrine
     * @return AuthenticationService
     */
    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }

    /**
     * Recupera translator
     * @return translator
     */
    public function getTranslator() {
        return $this->_translator;
    }

}
