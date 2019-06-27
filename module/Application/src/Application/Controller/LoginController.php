<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\Acesso666Form;
use Application\Form\LoginForm;
use Application\Form\NovaSenhaForm;
use Application\Form\PerfilForm;
use Application\Form\RecuperarAcessoForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\Entity\RegistroAcao;
use Application\Model\Entity\EntidadeTipo;
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
        /* Destroi a sessao ao acessar a index */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->getManager()->destroy();

        $mensagem = '';
        $tipoMensagem = 0;
        $tipoNaoEncontrouNaBaseDeDados = 1;
        $tipoLinkExpirou = 4;
        $formLogin = new LoginForm(Constantes::$LOGIN_FORM);

        $inputEmailDaRota = $this->params()->fromRoute(Constantes::$INPUT_USUARIO);
        $tipo = $this->params()->fromRoute(Constantes::$TIPO);

        if (!empty($tipo)) {
            $formLogin->get(Constantes::$INPUT_USUARIO)->setValue($inputEmailDaRota);
            if ($tipo == $tipoNaoEncontrouNaBaseDeDados) {
                $mensagem = Constantes::$TRADUCAO_FALHA_LOGIN;
                $tipoMensagem = $tipoNaoEncontrouNaBaseDeDados;
            }
            if ($tipo == $tipoLinkExpirou) {
                $mensagem = $inputEmailDaRota = $this->params()->fromRoute(Constantes::$MENSAGEM);
                $tipoMensagem = $tipoLinkExpirou;
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

    public function acessoTrezeAction() {
        /* Destroi a sessao ao acessar a index */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->getManager()->destroy();
        $formulario = new Acesso666Form('formulario');
        $view = new ViewModel(array(
            'formulario' => $formulario,
        ));
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

        $usuarioTrim = strtolower(trim($data[Constantes::$INPUT_USUARIO]));
        $senhaTrim = trim($data[Constantes::$INPUT_SENHA]);
        $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
        $adapter->setIdentityValue($usuarioTrim);
        $adapter->setCredentialValue(md5($senhaTrim));
        $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
        if ($authenticationResult->isValid()) {
            /* Autenticacao valida */
            $identity = $authenticationResult->getIdentity();
            $this->getDoctrineAuthenticationServicer()->getStorage()->write($identity);

            /* Verificar se existe pessoa por email informado */
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($usuarioTrim);

            /* Tem responsabilidade(s) */
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
                /* Registro de sessão */
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
                if ($pessoa->getEvento_id() !== null) {
                    $evento = $this->getRepositorio()->getEventoORM()->encontrarPorId($pessoa->getEvento_id());
                    $idEntidade = $evento->getGrupoEventoAtivo()->getGrupo()->getEntidadeAtiva()->getId();
                    $sessao->idPessoa = $pessoa->getId();
                    $sessao->idEntidadeAtual = $idEntidade;
                    $sessao->naoMostrarMenu = 1;
                    return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                                Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHAS,
                    ));
                }

                /* Login sem responsabilidade(s) */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_INDEX,
                            Constantes::$INPUT_USUARIO => $usuarioTrim,
                            Constantes::$TIPO => 1,
                ));
            }
        } else {
//            Funcoes::var_dump($authenticationResult->getMessages());
            /* Nao encontrou na base de dados */
            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
                        Constantes::$INPUT_USUARIO => $usuarioTrim,
                        Constantes::$TIPO => 1,
            ));
        }
    }

    public function suporteAction() {
        $data = $this->getRequest()->getPost();
        $usuarioTrim = strtolower(trim($data[Constantes::$INPUT_USUARIO]));
        if ($pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($usuarioTrim)) {
            /* Tem responsabilidade(s) */
            if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
                /* Registro de sessão */
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idPessoa = $pessoa->getId();

                $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
                $adapter->setIdentityValue($usuarioTrim);
                $adapter->setCredentialValue($pessoa->getSenha());
                $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
                $identity = $authenticationResult->getIdentity();
                $this->getDoctrineAuthenticationServicer()->getStorage()->write($identity);

                /* Redirecionamento SELECIONAR PERFIL */
                return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                            Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
                ));
            }
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


                /* Verificar se existe pessoa por email informado */
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($data[Constantes::$INPUT_USUARIO]);

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
			if($data['senha']){
            	$senhaInformada = md5($data['senha']);
			}else{
				$body = $request->getContent();
				$json = Json::decode($body);
            	$senhaInformada = md5($json->senha);
			}
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


            /* Dados da requisição POST */
            $dataPost = $request->getPost();

            /* recupera o id vindo da url */
            $tipoDePesquisa = $this->params()->fromRoute(Constantes::$ID);

            /* Verificar se existe pessoa por email informado */
            if ($tipoDePesquisa == 1) {
                $email = $dataPost[Constantes::$INPUT_USUARIO];
                if ($email) {
                    $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email);
                }
            }
            if ($tipoDePesquisa == 2) {
                /* Verificar se existe pessoa por data de nascimento e digitos do CPF informado */
                $documento = $dataPost[Constantes::$INPUT_CPF];
                $diaNascimento = $dataPost[Constantes::$FORM_INPUT_DIA];
                $mesNascimento = $dataPost[Constantes::$FORM_INPUT_MES];
                $anoNascimento = $dataPost[Constantes::$FORM_INPUT_ANO];

                $pessoa = $this->getRepositorio()->getPessoaORM()->
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
                        $mensagemOriginal = $this->getTranslator()->translate(Constantes::$TRADUCAO_EMAIL_MENSAGEM_RECUPERAR_SENHA_NOVO);
                        $mensagemComEmail = str_replace('#email', $email, $mensagemOriginal);
                        $tokenDeAgora = $pessoa->gerarToken();
                        /* Persistir pessoa */
                        $pessoa->setToken($tokenDeAgora);
                        $this->getRepositorio()->getPessoaORM()->persistir($pessoa, false);

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


        $tokenDaRota = $this->params()->fromRoute(Constantes::$ID);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorToken($tokenDaRota);
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


                /* Dados da requisição POST */
                $dataPost = $request->getPost();
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($dataPost[Constantes::$INPUT_ID_PESSOA]);

                $senhaNova = $dataPost[Constantes::$INPUT_SENHA];
                if (!$senhaNova) {
                    $senhaNova = $dataPost[Constantes::$INPUT_NOVA_SENHA];
                }
                $pessoa->setSenha($senhaNova);
                $pessoa->setToken(null);
                $pessoa->setToken_data(null);
                $pessoa->setToken_hora(null);
                /* Salvando nova senha */
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, false);

                $Subject = 'Dados de Acesso ao CV';
                $ToEmail = $pessoa->getEmail();
                $Content = '<pre>Olá</pre><pre>Seu usuário é: ' . $pessoa->getEmail() . '</pre><pre>Sua Senha é: ' . $senhaNova . '</pre>';
                Funcoes::enviarEmail($ToEmail, $Subject, $Content);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }

        $view = new ViewModel();

        /* Adicionando layout extras */
        $this->colocaTopEBottonModuloLogin($view);

        return $view;
    }

    /**
     * Função que direciona a tela de acesso
     * GET /selecionarPerfil
     */
    public function selecionarPerfilAction() {
        /* Helper Controller */

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idPessoa = $sessao->idPessoa;
        if ($idPessoa) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
            /* Responsabilidades */
            $responsabilidadesAtivas = $pessoa->getResponsabilidadesAtivas($todas = true);
            if ($responsabilidadesAtivas) {
                uksort($responsabilidadesAtivas, function ($a, $b) use ($responsabilidadesAtivas) {
                    return ($responsabilidadesAtivas[$a]->getId() > $responsabilidadesAtivas[$b]->getId()) ? -1 : 1;
                });
                $view = new ViewModel(array(Constantes::$RESPONSABILIDADES => $responsabilidadesAtivas));
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
                $idComposto = $post_data[Constantes::$ID];
                $explodeId = explode('_', $idComposto);
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idEntidadeAtual = $explodeId[0];

				self::registrarLog(RegistroAcao::LOGIN, $extra = '');
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
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idPessoa = (int) $sessao->idPessoa;
        if ($idPessoa > 0) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);

            $view = new ViewModel(array(Constantes::$ENTITY_PESSOA_NOME => $pessoa->getNomePrimeiroUltimo()));

            /* Javascript especifico */
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_PRE_SAIDA);
            $view->addChild($layoutJS, Constantes::$STRING_JS_PRE_SAIDA);

            return $view;
        } else {
            /* Fechando a sessão */
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $sessao->getManager()->destroy();
            /* Redirecionamento */
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
        }
    }

    /**
     * Função que direciona a tela de acesso
     * GET /sair
     */
    public function sairAction() {
        /* Fechando a sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);

		self::registrarLog(RegistroAcao::LOGOUT, $extra = '');
		$sessao->getManager()->destroy();

        /* Redirecionamento */
        return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
    }

    /**
     * Função que direciona a tela de acesso
     * GET /novaSenha
     */
    public function novaSenhaAction() {

        $tokenDaRota = $this->params()->fromRoute(Constantes::$ID);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorToken($tokenDaRota);
        if ($pessoa) {
            $formNovaSenha = new NovaSenhaForm(Constantes::$NOVA_SENHA_FORM, $pessoa->getId());
            $dados[Constantes::$FORM_NOVA_SENHA] = $formNovaSenha;
        } else {
            /* Redirecionamento */
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
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

    public function perfilAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();
        $formulario = new PerfilForm('formulario', $pessoa);
        $tituloDaPagina = 'Meu Perfil';
        $dados = array(
            'pessoa' => $pessoa,
            'hierarquias' => $hierarquias,
            'formulario' => $formulario,
            'tituloDaPagina' => $tituloDaPagina,
        );

        $view = new ViewModel($dados);

        return $view;
    }

    public function perfilSalvarAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data['id']);
                $pessoa->setTelefone($post_data['ddd'] . $post_data['telefone']);
                $pessoa->setSexo($post_data[Constantes::$INPUT_SEXO]);
                $pessoa->setData_nascimento($post_data[Constantes::$FORM_INPUT_ANO] . "-" . $post_data[Constantes::$FORM_INPUT_MES] . "-" .
			    $post_data[Constantes::$FORM_INPUT_DIA]);
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $mudarDataDeCadastro = false);
                $this->getRepositorio()->fecharTransacao();

                //return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(Constantes::$ACTION => 'perfil'));
                return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(Constantes::$ACTION => 'index'));			
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
                $this->direcionaErroDeCadastro($exc->getMessage());
                CircuitoController::direcionandoAoLogin($this);
            }
        }
    }

    public function salvarFotoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();

                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data['id']);
                $valorRandom = LoginController::geraSenha();

                $pessoa->setFoto($pessoa->getId() . '_' . $valorRandom . '.jpg');
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $naoAlterarDataDeCriacao = false);

                $diretorioDocumentos = 'public/img/fotos/';
                $resposta = file_put_contents($diretorioDocumentos . $pessoa->getFoto(), base64_decode(explode(",", $post_data['canvas'])[1]));

                $this->getRepositorio()->fecharTransacao();
                $response->setContent(Json::encode(array(
                            'response' => $resposta,
                )));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
        return $response;
    }

    public function removerFotoAction(){
      $request = $this->getRequest();
      if ($request->isPost()) {
        $this->getRepositorio()->iniciarTransacao();
        $post_data = $request->getPost();
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data['idPessoa']);
        $pessoa->setFoto(null);
        $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $naoAlterarDataDeCriacao = false);
        $this->getRepositorio()->fecharTransacao();
        return $this->redirect()->toRoute('login', array(
  			Constantes::$ACTION => 'Perfil',
  			));
      }
    }

    public function consultarOrdenacaoAction(){
		//self::validarSeSouPresidencial();
		$sessao = new Container(Constantes::$NOME_APLICACAO);
        //$idEntidadeAtual = $sessao->idEntidadeAtual;
        $idEntidadeAtual = 9497; // idEntidade do presidencial setado temporariamente
		$entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoLogado = $entidadeLogada->getGrupo();				
		$request = $this->getRequest();
		$dados = array();	
		$filtrado = false;	
		$situacaoPessoa = 'naoEncontrada';				
		if($request->isPost()){		
			$filtrado = true;		
			$postDados = $request->getPost();
			$repositorio = $this->getRepositorio();
			$cpf = $postDados['cpf'];
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($cpf);				
            if($pessoa){
                $nome = $pessoa->getNomePrimeiroUltimo(); 
                $situacaoPessoa = 'inativada';				
            }
			$nivelDeDificuldade = $postDados['nivelDeDificuldade'];	
			$metas = $grupoLogado->getGrupoMetasOrdenacaoAtivas();
			if($pessoa && $pessoa->verificarSeTemAlgumaResponsabilidadeAtiva()){				                
				$situacaoPessoa = 'ativa';

				if(date('m') == 1){
                    $mes = 12;
                    $ano = date('Y') - 1;
                }else{
                    $mesAtual = date('m');
                    $mes = $mesAtual -1;
                    $ano = date('Y');
                }		
				
				$responsabilidades = $pessoa->getResponsabilidadesAtivas();
				foreach($responsabilidades as $grupoResponsavel){
					$grupo = $grupoResponsavel->getGrupo();
					$entidadeDaPessoa = $grupo->getEntidadeAtiva();					
                    $nomeOndeEstou = null;
					if($situacaoPessoa == 'ativa' && $metas && $entidadeDaPessoa->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
					&& $entidadeDaPessoa->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao){

                        if(!$nomeOndeEstou){
                            if($entidadeDaPessoa->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
                                $nomeOndeEstou = $entidadeDaPessoa->getNome();
                            }
                            if($entidadeDaPessoa->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
                                $nomeOndeEstou = $entidadeDaPessoa->getGrupo()->getGrupoEquipe()->getEntidadeAtiva()->getNome();   
                                $nomeOndeEstou .= ' - ';                         
                                $nomeOndeEstou .= $entidadeDaPessoa->getGrupo()->getGrupoIgreja()->getEntidadeAtiva()->getNome();   
                            }
                        }																					
                        
						$tipoRelatorio = 2; // Somado							

						// Media de Membresia e Média de Pessoas em Célula
						$relatorio = RelatorioController::relatorioCompleto($repositorio, $grupo, RelatorioController::relatorioMembresiaECelula, $mes, $ano, $tudo = true, $tipoRelatorio, 'atual');
						$indiceParaVer = 0;	
						$mediaMembresia = $relatorio[$indiceParaVer]['mediaMembresia'];
						$mediaPessoasFrequentes = $relatorio[$indiceParaVer]['mediaCelula'];				

						// Líderes
						$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
						if($mes == date('m') && $ano == date('Y')){
							$arrayPeriodoDoMes[1] = 0;
						}
						$periodoParaUsar = $arrayPeriodoDoMes[1];				
						$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);								
						$fatoLider = 				
									$repositorio->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoParaUsar, $inativo = false);
						$lideres = $fatoLider[0]['lideres'];
						/* Parceiro de Deus */
						$parceiro = $repositorio->getFatoFinanceiroORM()->fatosValorPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano)['valor'];																	
						$tiposDeMetasOrdenacao = $repositorio->getMetasOrdenacaoTipoORM()->buscarTodosRegistrosEntidade();
						$dados['mediaPessoasFrequentes'] = $mediaPessoasFrequentes;
						$dados['tiposDeMetasOrdenacao'] = $tiposDeMetasOrdenacao;				
						$dados['nivelDeDificuldade'] = $nivelDeDificuldade;	
						if(!$dados['parceiroDeDeus']){
							$dados['parceiroDeDeus'] = $parceiro;				
						}						
						$dados['membresia'] = $mediaMembresia;
						$dados['lideres'] = $lideres;																					
					}
					if($situacaoPessoa == 'ativa' && $metas && ($entidadeDaPessoa->getEntidadeTipo()->getId() === EntidadeTipo::regiao 
					|| $entidadeDaPessoa->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao)){
                        if($entidadeDaPessoa->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
                            $nomeOndeEstou = 'REGIÃO: ' . $entidadeDaPessoa->getNome();
                        }
                        if($entidadeDaPessoa->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){                               
                            $nomeOndeEstou = 'COORDENAÇÃO: ';                         
                            $nomeOndeEstou .= $entidadeDaPessoa->getNumero(); 
                            $entidadeDoPai = $entidadeDaPessoa->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();
                            if($entidadeDoPai->getEntidadeTipo()->getId() === EntidadeTipo::regiao){                               
                                $nomeOndeEstou .= ' - ' . $entidadeDoPai->getNome();
                            }                            
                        }
						$relatorioDadosPrincipais = RelatorioController::buscarDadosPrincipais($repositorio, $grupo, $mes, $ano);
						$parceiroDadosPrincipais = $relatorioDadosPrincipais['parceiro'];
						$igrejasDadosPrincipais = $relatorioDadosPrincipais['igrejas'];
						$dados['parceiroDeDeus'] = $parceiroDadosPrincipais;
						$dados['quantidadeDeIgrejas'] = $igrejasDadosPrincipais;	
					}
				}
			}	
        }	
        if($nome){
            $nome .= ' - ' . $nomeOndeEstou;
        }
		$dados['situacaoPessoa'] = $situacaoPessoa;	
		$dados['ordenacaoMetas'] = $metas;
		$dados['filtrado'] = $filtrado;	
		$dados['nome'] = $nome;	
		$dados['cpf'] = $cpf;
		return new ViewModel($dados);
	}
    
    public function aniversariantesAction(){  
        $filtrado = false;      
        $dados = array();         
        $request = $this->getRequest();                
        if ($request->isPost()) {            
            $aniversariantes = array();
            $filtrado = true;
            $post_data = $request->getPost();
            $hierarquiaId = $post_data['hierarquia'];
            $mes = $post_data['mes'];            
            $dia = $post_data['dia'];   
            $dataAniversariantes = $mes . '-' . $dia;            
            $pessoasAniversariantesDDD_61_87_81_79 = $this->getRepositorio()->getPessoaORM()->encontrarPorDDDRegiaoBispoLucas();             
            foreach($pessoasAniversariantesDDD_61_87_81_79 as $pessoa){
                if(substr($pessoa['data_nascimento'],5,8) == $dataAniversariantes){
                    $pessoa_aniversariante = $this->getRepositorio()->getPessoaORM()->encontrarPorId($pessoa['id']);  
                    if($pessoa_aniversariante->getPessoaHierarquiaAtivo()->getHierarquia_id() == $hierarquiaId){                       
                        $aniversariantes[] = $pessoa_aniversariante;
                    }                        
                }                
            }
            $dados['hierarquiaSelecionadaId'] = $hierarquiaId; 
            $dados['aniversariantes'] = $aniversariantes;             
            $dados['mesSelecionado'] = $mes;             
            $dados['diaSelecionado'] = $dia;             
        }
        $todasHierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();          
        $dados['hierarquias'] = $todasHierarquias;  
        $dados['filtrado'] = $filtrado; 
        return new ViewModel($dados);
      }


    static public function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
// Caracteres de cada tipo
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
// Variáveis internas
        $retorno = '';
        $caracteres = '';
// Agrupamos todos os caracteres que poderão ser utilizados
        $caracteres .= $lmin;
        if ($maiusculas)
            $caracteres .= $lmai;
        if ($numeros)
            $caracteres .= $num;
        if ($simbolos)
            $caracteres .= $simb;
// Calculamos o total de caracteres possíveis
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);
// Concatenamos um dos caracteres na variável $retorno
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
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

	public function testeUmAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$resultado = RelatorioController::buscarTimes($grupo);
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}



}
