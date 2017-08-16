<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\LoginForm;
use Application\Form\NovaSenhaForm;
use Application\Form\RecuperarAcessoForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Json\Json;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */
class LoginController extends CircuitoController {

    private $_doctrineAuthenticationService;
    private $_translator;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, AuthenticationService $doctrineAuthenticationService = null, Translator $translator = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
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
        $mensagem = '';
        $tipoMensagem = 0;
        $tipoNaoEncontrouNaBaseDeDados = 1;
        $formLogin = new LoginForm(Constantes::$LOGIN_FORM);

        $inputEmailDaRota = $this->params()->fromRoute(Constantes::$INPUT_USUARIO);
        $tipo = $this->params()->fromRoute(Constantes::$TIPO);

        if (!empty($tipo)) {
            $formLogin->get(Constantes::$INPUT_USUARIO)->setValue($inputEmailDaRota);
            if ($tipo == $tipoNaoEncontrouNaBaseDeDados) {
                $mensagem = Constantes::$TRADUCAO_FALHA_LOGIN;
                ;
                $tipoMensagem = $tipoNaoEncontrouNaBaseDeDados;
            }
        }

        $view = new ViewModel(array(
            Constantes::$FORM_LOGIN => $formLogin,
            Constantes::$MENSAGEM => $mensagem,
            Constantes::$TIPO => $tipoMensagem,)
        );

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);
        /* Javascript especifico */
        $layoutJSIndex = new ViewModel();
        $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_INDEX);
        $view->addChild($layoutJSIndex, Constantes::$STRING_JS_INDEX);

        return $view;
    }

    /**
     * Adiciona os layout do top e do botton nas paginas de login
     * @param ViewModel $view
     */
    public static function colocaTopEBottonModuloLogin($view) {
        $layoutLoginTop = new ViewModel();
        $layoutLoginTop->setTemplate(Constantes::$TEMPLATE_LOGIN_TOP);

        $layoutLoginBotton = new ViewModel();
        $layoutLoginBotton->setTemplate(Constantes::$TEMPLATE_LOGIN_BOTTON);

        $view
                ->addChild($layoutLoginTop, Constantes::$STRING_LOGIN_TOP)
                ->addChild($layoutLoginBotton, Constantes::$STRING_LOGIN_BOTTON);
    }

    /**
     * Função que tenta logar
     * POST /logar
     */
    public function logarAction() {
        $data = $this->getRequest()->getPost();

        /* Post sem email */
        if (is_null($data[Constantes::$INPUT_USUARIO])) {
            /* Redirecionamento */
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }

        $usuarioTrim = trim($data[Constantes::$INPUT_USUARIO]);
        $senhaTrim = trim($data[Constantes::$INPUT_SENHA]);
        $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
        $adapter->setIdentityValue($usuarioTrim);
        $adapter->setCredentialValue(md5($senhaTrim));
        $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
        if ($authenticationResult->isValid()) {
            /* Autenticacao valida */
            $identity = $authenticationResult->getIdentity();
            $this->getDoctrineAuthenticationServicer()->getStorage()->write($identity);

            /* Helper Controller */
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

            /* Verificar se existe pessoa por email informado */
            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail($usuarioTrim);

            /* Tem responsabilidade(s) */
            if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
                /* Registro de sessão */
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idPessoa = $pessoa->getId();
                /* Não precisa atualizar dados */
                if ($pessoa->getAtualizar_dados() === 'N') {
                    /* Redirecionamento SELECIONAR PERFIL */
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
                    ));
                } else {/* Precisa atualizar dados */
                    /* Redirecionamento CadastroGrupoAtualizar */
                    return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                                Constantes::$PAGINA => Constantes::$PAGINA_GRUPO_ATUALIZACAO,
                    ));
                }
            } else {
                /* Login sem responsabilidade(s) */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_INDEX,
                            Constantes::$INPUT_USUARIO => $usuarioTrim,
                            Constantes::$TIPO => 1,
                ));
            }
        } else {
            /* Nao encontrou na base de dados */
            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
                        Constantes::$INPUT_USUARIO => $usuarioTrim,
                        Constantes::$TIPO => 1,
            ));
        }
    }

    /**
     * Função que tenta logar
     * POST /logarJason
     */
    public function logarJasonAction() {
        $data = $this->getRequest()->getPost();
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* Post sem email */
            if (is_null($data[Constantes::$INPUT_USUARIO])) {
                /* Redirecionamento */
                return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
            }

            $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
            $adapter->setIdentityValue($data[Constantes::$INPUT_USUARIO]);
            $adapter->setCredentialValue(md5($data[Constantes::$INPUT_SENHA]));
            $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
            if ($authenticationResult->isValid()) {
                /* Autenticacao valida */

                /* Helper Controller */
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                /* Verificar se existe pessoa por email informado */
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail($data[Constantes::$INPUT_USUARIO]);

                /* Tem responsabilidade(s) */
                if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
                    /* Registro de sessão */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->idPessoa = $pessoa->getId();
                    /* Não precisa atualizar dados */
                    if ($pessoa->getAtualizar_dados() === 'N') {
                        $response->setContent(Json::encode(
                                        array('response' => 'true')));
                    } else {/* Precisa atualizar dados */
                        $response->setContent(Json::encode(
                                        array('response' => 'true')));
                    }
                } else {
                    $response->setContent(Json::encode(
                                    array('response' => 'false')));
                }
            } else {
                $response->setContent(Json::encode(
                                array('response' => 'false')));
            }
        }
        return $response;
    }

    /**
     * Função que tenta logar
     * POST /logarJason
     */
    public function validarSenhaAction() {
        $data = $this->getRequest()->getPost();
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $senhaInformada = md5($data['senha']);
            $senhaNaIdentidade = $this->identity()->getSenha();

            if ($senhaNaIdentidade === $senhaInformada) {
                $response->setContent(Json::encode(
                                array('response' => true)));
            } else {
                $response->setContent(Json::encode(
                                array('response' => false)));
            }
        }
        return $response;
    }

    /**
     * Função que direciona a tela de email enviado
     * GET /emailEnviado
     */
    public function emailEnviadoAction() {
        $view = new ViewModel();

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

        return $view;
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
        $classDiv1 = Constantes::$CLASS_HIDDEN;
        $classDiv2 = Constantes::$CLASS_HIDDEN;
        if ($div == 1) {
            $classDiv0 = Constantes::$CLASS_HIDDEN;
            $classDiv1 = '';
            $classDiv2 = Constantes::$CLASS_HIDDEN;
        }
        if ($div == 2) {
            $classDiv0 = Constantes::$CLASS_HIDDEN;
            $classDiv1 = Constantes::$CLASS_HIDDEN;
            $classDiv2 = '';
        }

        $view = new ViewModel(array(
            Constantes::$FORM_RECUPERAR_ACESSO => $formRecuperarAcesso,
            Constantes::$TIPO => $tipo,
            Constantes::$MENSAGEM => $messagem,
            'classDiv0' => $classDiv0,
            'classDiv1' => $classDiv1,
            'classDiv2' => $classDiv2,)
        );

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);
        /* Javascript especifico */
        $layoutJSIndex = new ViewModel();
        $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_ACESSO);
        $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_ACESSO);

        return $view;
    }

    /**
     * Função que tenta recuperar o acesso
     * GET /recuperarAcesso
     */
    public function recuperarAcessoAction() {
        $resposta = '';
        $pessoa = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* Helper Controller */
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

            /* Dados da requisição POST */
            $dataPost = $request->getPost();

            /* recupera o id vindo da url */
            $tipoDePesquisa = $this->params()->fromRoute(Constantes::$ID);

            /* Verificar se existe pessoa por email informado */
            if ($tipoDePesquisa == 1) {
                $email = $dataPost[Constantes::$INPUT_USUARIO];
                if ($email) {
                    $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail($email);
                }
            }
            if ($tipoDePesquisa == 2) {
                /* Verificar se existe pessoa por data de nascimento e digitos do CPF informado */
                $documento = $dataPost[Constantes::$INPUT_CPF];
                $diaNascimento = $dataPost[Constantes::$FORM_INPUT_DIA];
                $mesNascimento = $dataPost[Constantes::$FORM_INPUT_MES];
                $anoNascimento = $dataPost[Constantes::$FORM_INPUT_ANO];

                $pessoa = $repositorioORM->getPessoaORM()->
                        encontrarPorCPFEDataNascimento($documento, $anoNascimento . '-' . $mesNascimento . '-' . $diaNascimento);
            }



            /* Pessoa não encontrada */
            if (!$pessoa) {
                /* Redirecionamento */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                            Constantes::$TIPO => 1, //danger
                            Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_NAO_ENCONTRADA,
                ));
            } else {
                $contagemDeResponsabilidadesAtivas = count($pessoa->getResponsabilidadesAtivas());
                if ($contagemDeResponsabilidadesAtivas === 0) {
                    /* Redirecionamento */
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                                Constantes::$TIPO => 1,
                                Constantes::$MENSAGEM => Constantes::$TRADUCAO_PESSOA_INATIVADA,
                    ));
                } else {
                    /* Email */
                    if ($tipoDePesquisa == 1) {
                        $mensagemOriginal = $this->getTranslator()->translate(Constantes::$TRADUCAO_EMAIL_MENSAGEM_RECUPERAR_SENHA);
                        $mensagemComEmail = str_replace('#email', $email, $mensagemOriginal);
                        $tokenDeAgora = $pessoa->gerarToken();
                        /* Persistir pessoa */
                        $pessoa->setToken($tokenDeAgora);
                        $repositorioORM->getPessoaORM()->persistir($pessoa, false);

                        $mensagemAjustada = str_replace('#id', $tokenDeAgora, $mensagemComEmail);
                        Funcoes::enviarEmail($email, $this->getTranslator()->translate(Constantes::$TRADUCAO_EMAIL_TITULO_RECUPERAR_SENHA), $mensagemAjustada);

                        /* Redirecionamento */
                        return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                    Constantes::$ACTION => Constantes::$ACTION_EMAIL_ENVIADO
                        ));
                    }
                    /* CPF e Data de Nascimento */
                    if ($tipoDePesquisa == 2) {
                        $resposta = $this->getTranslator()->translate(Constantes::$TRADUCAO_SEU_LOGIN_E) . ' <b>' . $pessoa->getEmail() . '</b>';
                    }
                }
            }
        }

        $view = new ViewModel(array('resposta' => $resposta,));

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

        return $view;
    }

    /**
     * Função qpara recuperar a senha
     * GET /recuperarSenha
     */
    public function recuperarSenhaAction() {
        unset($dados);
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $tokenDaRota = $this->params()->fromRoute(Constantes::$ID);
        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorToken($tokenDaRota);
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

        $view = new ViewModel($dados);

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

        /* Javascript especifico */
        $layoutJSIndex = new ViewModel();
        $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_SENHA);
        $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_SENHA);

        return $view;
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
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                /* Dados da requisição POST */
                $dataPost = $request->getPost();
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($dataPost[Constantes::$INPUT_ID_PESSOA]);

                $senhaNova = $dataPost[Constantes::$INPUT_SENHA];
                if (!$senhaNova) {
                    $senhaNova = $dataPost[Constantes::$INPUT_NOVA_SENHA];
                }
                $pessoa->setSenha($senhaNova);
                $pessoa->setToken(null);
                $pessoa->setToken_data(null);
                $pessoa->setToken_hora(null);
                /* Salvando nova senha */
                $repositorioORM->getPessoaORM()->persistir($pessoa, false);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }

        $view = new ViewModel();

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

//        return $view;
    }

    /**
     * Função que direciona a tela de acesso
     * GET /selecionarPerfil
     */
    public function selecionarPerfilAction() {
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idPessoa = $sessao->idPessoa;
        if ($idPessoa) {
            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
            /* Responsabilidades */
            $responsabilidadesAtivas = $pessoa->getResponsabilidadesAtivas(true);
            if ($responsabilidadesAtivas) {
                $view = new ViewModel(array(Constantes::$RESPONSABILIDADES => $responsabilidadesAtivas));

                /* Javascript especifico */
                $layoutJS = new ViewModel();
                $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_MODAL_SELECIONAR_PERFIL);
                $view->addChild($layoutJS, Constantes::$STRING_JS_MODAL_SELECIONAR_PERFIL);

                return $view;
            }
        }
    }

    /**
     * Função que direciona a tela de acesso e enviando as responsabilidades da pessoa
     * POST /perfilSelecionado
     */
    public function perfilSelecionadoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idEntidade = $post_data[Constantes::$ID];
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idEntidadeAtual = $idEntidade;

                $response->setContent(Json::encode(
                                array('response' => 'true')));
                /* Redirecionamento */
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Função que direciona a tela de acesso
     * GET /preSaida
     */
    public function preSaidaAction() {

        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idPessoa = $sessao->idPessoa;
        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);

        $view = new ViewModel(array(Constantes::$ENTITY_PESSOA_NOME => $pessoa->getNomePrimeiroUltimo()));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_PRE_SAIDA);
        $view->addChild($layoutJS, Constantes::$STRING_JS_PRE_SAIDA);

        return $view;
    }

    /**
     * Função que direciona a tela de acesso
     * GET /sair
     */
    public function sairAction() {
        /* Fechando a sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->getManager()->destroy();

        /* Redirecionamento */
        return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
    }

    /**
     * Função que direciona a tela de acesso
     * GET /novaSenha
     */
    public function novaSenhaAction() {
        unset($dados);
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $tokenDaRota = $this->params()->fromRoute(Constantes::$ID);
        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorToken($tokenDaRota);
        if ($pessoa) {
            $formNovaSenha = new NovaSenhaForm(Constantes::$NOVA_SENHA_FORM, $pessoa->getId());
            $dados[Constantes::$FORM_NOVA_SENHA] = $formNovaSenha;
        } else {
            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_ESQUECEU_SENHA,
                        Constantes::$TIPO => 4,
                        Constantes::$MENSAGEM => 'Seu link de recuperacao expirou',
            ));
        }
        $view = new ViewModel($dados);

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

        /* Javascript especifico */
        $layoutJSIndex = new ViewModel();
        $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_NOVA_SENHA_VALIDACAO);
        $view->addChild($layoutJSIndex, Constantes::$STRING_JS_NOVA_SENHA_VALIDACAO);

        return $view;
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
