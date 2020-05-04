<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\Acesso666Form;
use Application\Form\LoginForm;
use Application\Form\NovaSenhaForm;
use Application\Form\PerfilForm;
use Application\Form\RecuperarAcessoForm;
use Application\Form\AtualizarCadastroForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\Entity\RegistroAcao;
use Application\Model\Entity\Disciplina;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\CursoAcesso;
use Application\Model\Entity\Pergunta;
use Application\Model\Entity\TurmaPessoaAula;
use Application\Model\Entity\TurmaPessoaVisto;
use Application\Model\Entity\TurmaPessoaFinanceiro;
use Application\View\Helper\BotaoSimples;
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

		$pessoa = null;
        $usuarioTrim = strtolower(trim($data[Constantes::$INPUT_USUARIO]));
		if(is_numeric($usuarioTrim)){
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($usuarioTrim);
		}else{
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($usuarioTrim);
			if($pessoa === null){
				return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
					Constantes::$ACTION => Constantes::$ACTION_INDEX,
					Constantes::$INPUT_USUARIO => $usuarioTrim,
					Constantes::$TIPO => 10,
				));
			}
		}

		if($pessoa){
			$senhaTrim = trim($data[Constantes::$INPUT_SENHA]);
			$adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
			$adapter->setIdentityValue($pessoa->getEmail());
			$adapter->setCredentialValue(md5($senhaTrim));
			$authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
			if ($authenticationResult->isValid()) {
				/* Autenticacao valida */
				$identity = $authenticationResult->getIdentity();
				$this->getDoctrineAuthenticationServicer()->getStorage()->write($identity);

				/* Verificar se existe pessoa por email informado */
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
		}else{
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
		try{
			$pessoa = null;
			if(is_numeric($usuarioTrim)){
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorCPF($usuarioTrim);
			}else{
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($usuarioTrim);
			}
			if($pessoa){
				if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
					$sessao = new Container(Constantes::$NOME_APLICACAO);
					$sessao->idPessoa = $pessoa->getId();

					$adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
					$adapter->setIdentityValue($pessoa->getEmail());
					$adapter->setCredentialValue($pessoa->getSenha());
					$authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
					echo '<br />pessoa: '.$pessoa->getId();
					echo '<br />nome: '.$pessoa->getNome();
					echo '<br />documento: '.$pessoa->getDocumento();
					echo '<br />email: '.$pessoa->getEmail();
					echo '<br />senha: '.$pessoa->getSenha();
					echo '<br />autenticacao: '.$authenticationResult->isValid();
					$identity = $authenticationResult->getIdentity();
					$this->getDoctrineAuthenticationServicer()->getStorage()->write($identity);

					/* Redirecionamento SELECIONAR PERFIL */
					return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
						Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
					));
				}
			}else{
				return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
					Constantes::$ACTION => 'acessoTreze',
				));
			}
		}catch(Execption $e){
			echo 'error: ' . $e->getMessage();
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
		if($sessao->getManager()){
			$sessao->getManager()->destroy();
		}

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
        if(!$pessoa->verificarSeTemAlgumaResponsabilidadeAtiva()){          
            return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                Constantes::$ACTION => 'semAcesso',
            ));
        }
        $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();        
        $profissoes = $this->getRepositorio()->getProfissaoORM()->buscarTodosRegistrosEntidade('nome', 'asc');                  
        $formulario = new PerfilForm('formulario', $pessoa, $profissoes);
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
                $profissao = $this->getRepositorio()->getProfissaoORM()->encontrarPorId($post_data['profissao']);
                $pessoa->setProfissao($profissao);
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
						$tamanhoDoArray = count($relatorio);
						$mediaMembresia = $relatorio[$tamanhoDoArray-1]['mediaMembresia'];
						$mediaPessoasFrequentes = $relatorio[$tamanhoDoArray-1]['mediaCelula'];				

						// Líderes
						$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
						if($mes == date('m') && $ano == date('Y')){
							$arrayPeriodoDoMes[1] = 0;
						}
						$periodoParaUsar = $arrayPeriodoDoMes[1];				
						$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);								
						$fatoLider = $repositorio->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoParaUsar, $inativo = false);
						$lideres = $fatoLider[0]['lideres'];
						/* Parceiro de Deus */
						$parceiro = $repositorio->getFatoFinanceiroORM()->fatosValorPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano)['valor'];																	
						$tiposDeMetasOrdenacao = $repositorio->getMetasOrdenacaoTipoORM()->buscarTodosRegistrosEntidade();
						$dados['mediaPessoasFrequentes'] = number_format($mediaPessoasFrequentes);
						$dados['tiposDeMetasOrdenacao'] = $tiposDeMetasOrdenacao;				
						$dados['nivelDeDificuldade'] = $nivelDeDificuldade;	
						if(!$dados['parceiroDeDeus']){
							$dados['parceiroDeDeus'] = $parceiro;				
						}						
						$dados['membresia'] = number_format($mediaMembresia);
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
		$dados['ok'] = false;
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);

				/* Verificar se existe pessoa por email informado */
				if($pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($json->usuario)){
					/* Tem responsabilidade(s) */
					if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
						$idEquipe = null;
						$idIgreja = null;
						$grupoSelecionado = null;
						foreach($pessoa->getResponsabilidadesAtivas() as $grupoResponsavel){
							if($grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
								$grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
								$grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
									$grupoSelecionado = $grupoResponsavel->getGrupo();
								}
						}
						if($grupoSelecionado){
							$idEquipe = $grupoSelecionado->getGrupoEquipe()->getId();
							$idIgreja = $grupoSelecionado->getGrupoIgreja()->getId();
						}
						$dados['ok'] = 'true';
						$dados['email'] = $json->usuario;
						$dados['senha'] = $json->senha;
						$dados['equipe_id'] = $idEquipe;
						$dados['igreja_id'] = $idIgreja;
					}
				}

			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function cepAction(){
		$form = new AtualizarCadastroForm(Constantes::$FORM, $sessao->idPessoa);

		$view = new ViewModel(array(
			'tituloDaPagina' => 'Atualizar CEP',
			Constantes::$FORM => $form,
			Constantes::$FORM_ENDERECO_HIDDEN => Constantes::$FORM_HIDDEN
		));

		return $view;
	}

	public function cepSalvarAction() {
		$data = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()){
		try{
			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
			$pessoaLogada->setCep($data['cep_logradouro']);
			$pessoaLogada->setLocalidade_uf($data['hiddencidade'].'/'.$data['hiddenuf']);
			$pessoaLogada->setBairro_distrito($data['hiddenbairro']);
			$pessoaLogada->setLogradouro($data['hiddenlogradouro']);
			$pessoaLogada->setComplemento($data['complemento']);
			$this->getRepositorio()->getPessoaORM()->persistir($pessoaLogada, $mudarDataDeCadastro = false);
            return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL);
		}catch(Execption $e){
			echo 'error: ' . $e->getMessage();
		}
		}else{
            return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN);
		}
	}

	public function espacoAlunoAction(){
		return new ViewModel();
	}

	public function consultarMatriculaAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		$dados['ok'] = false;
		$dados['message'] = 'Matrícula não encontrada!';
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				if($turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($json->matricula)){
					if(
						$turmaPessoa->getTurma()->getGrupo()->getGrupoRegiao()->getId() === 3110 || 
						$turmaPessoa->getTurma()->getGrupo()->getGrupoRegiao()->getId() === 7694
					){
						$html = '';
						if($turmaPessoa->verificarSeEstaAtivo()){
							$html .= '<div class="panel">';

							$html .= '<div id="divDados">';
							$html .= '<input type="hidden" id="matricula" value="'.$json->matricula.'" />';
							$html .= '<table class="table table-condensed text-left">';

							$html .= '<tr>';
							$html .= '<td class="text-right">Matrícula</td>';
							$html .= '<td>'.$turmaPessoa->getId().'</td>';
							$html .= '</tr>';

							$html .= '<tr>';
							$html .= '<td class="text-right">Aluno</td>';
							$html .= '<td>'.$turmaPessoa->getPessoa()->getNome().'</td>';
							$html .= '</tr>';

							$html .= '<tr>';
							$html .= '<td class="text-right">Time</td>';
							$html .= '<td>'.$turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade().'</td>';
							$html .= '</tr>';

							$html .= '<tr>';
							$html .= '<td class="text-right">Turma</td>';
							$html .= '<td>';
							$turma = $turmaPessoa->getTurma();
							$nomeDisciplina = 'PÓS REVISÃO';
							if($turma->getTurmaAulaAtiva()){
								$nomeDisciplina = $turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getNome();
							}
							$html .= $turma->getCurso()->getNomeSigla() . ' - ' . Funcoes::mesPorExtenso($turma->getMes(), 1) . '/' . $turma->getAno() . ' - ' . $nomeDisciplina;
							$html .= '</td>';
							$html .= '</tr>';

							$html .= '<tr>';
							$html .= '<td class="text-right">Situação</td>';
							$corSituacao = 'success';
							if ($turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ESPECIAL) {
								$corSituacao = 'primary';
							}
							$html .= '<td><span class="label label-'.$corSituacao.'">'.$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getNome().'</span></td>';
							$html .= '</tr>';

							/* financeiro */
							$html .= '<tr>';
							$html .= '<td class="text-right">Financeiro</td>';
							$html .= '<td><button type="button" class="btn btn-primary btn-xs" onClick="mostrarSituacaoFinanceira()">Ver Situação  Financeira</button></td>';
							$html .= '</tr>';

							$html .= '</table>';

							/* Aula aberta */
													$html .= '<div class="panel panel-primary m5">';
													$html .= '<div class="panel-heading" style="padding: 0px 8px;">Aula Aberta</div>';
													$html .= '<div class="panel-body">';
													if($turma->getTurmaAulaAtiva()){
														$turmaAula = $turma->getTurmaAulaAtiva();
														$html .= 'Aula '.$turmaAula->getAula()->getPosicao();
														if($turmaPessoa->getTurma()->getGrupo()->getGrupoRegiao()->getId() !== 3110){
															$html.= '&nbsp;<button type="button" class="btn btn-primary btn-xs" onClick="verReposicao('.$turmaAula->getId().')">Ver Reposição</button></td>';
														}
														$htmlU = '';
														$htmlU .= '<table class="table table-condesed text-left">';

														if($turmaAula->getUrl1()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Segunda-Feira';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl1().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl2()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Terça-Feira';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl2().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl3()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Quarta-Feira';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl3().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl4()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Quinta-Feira';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl4().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl5()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Sexta-Feira';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl5().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl6()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Sábado';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl6().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
														if($turmaAula->getUrl7()){
															$htmlU .= '<tr>';
															$htmlU .= '<td class="text-right">';
															$htmlU .= 'Domingo';
															$htmlU .= '</td>';
															$htmlU .= '<td><a target="_blanck" class="btn btn-primary btn-xs" href="'.$turmaAula->getUrl7().'">Acessar Link do ZOOM</a></td>';
															$htmlU .= '</tr>';
														}
		
														$htmlU .= '</table>';
														$html .= $htmlU;
													}else{
														$html .= '<div class="alert alert-danger">Sem Aula aberta entre em contato com seu líder</div>';
													}
													$html .= '</div>';
													$html .= '</div>';

							/* faltas */
							$html .= '<div class="panel panel-primary m5">';
							$html .= '<div class="panel-heading" style="padding: 0px 8px;">Reposições</div>';
							$html .= '<div class="panel-body">';
							$listaDeFaltas = array();
							if($turma->getTurmaAulaAtiva()){
								$idPrimeiroModulo = 6;
								$disciplina = $turma->getTurmaAulaAtiva()->getAula()->getDisciplina();
								if($disciplina->getId() === $idPrimeiroModulo){
									$disciplinaPosRevisao = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId(5);
									foreach ($disciplinaPosRevisao->getAulaOrdenadasPorPosicao() as $aula) {
									if($turma->getTurmaAulaAtiva()->getAula()->getId() === $aula->getId()){
										break;
									}
									$falta = true;
									if (count($turmaPessoa->getTurmaPessoaAula()) > 0) {
										foreach ($turmaPessoa->getTurmaPessoaAula() as $turmaPessoaAula) {
											if ($turmaPessoaAula->getAula()->getId() === $aula->getId() && $turmaPessoaAula->verificarSeEstaAtivo()) {
												$falta = false;
											}
										}
									}
									if($falta){
										$listaDeFaltas[] = $aula;
									}
								}
								}

								foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
									if($turma->getTurmaAulaAtiva()->getAula()->getId() === $aula->getId()){
										break;
									}
									$falta = true;
									if (count($turmaPessoa->getTurmaPessoaAula()) > 0) {
										foreach ($turmaPessoa->getTurmaPessoaAula() as $turmaPessoaAula) {
											if ($turmaPessoaAula->getAula()->getId() === $aula->getId() && $turmaPessoaAula->verificarSeEstaAtivo()) {
												$falta = false;
											}
										}
									}
									if($falta){
										$listaDeFaltas[] = $aula;
									}
								}
							}

							$htmlFaltas = '<div class="alert alert-danger">Sem aulas para serem repostas</div>';
							if(count($listaDeFaltas) > 0){
								$htmlFaltas = '';
								$htmlFaltas .= '<table class="table table-condesed text-left">';
								foreach($listaDeFaltas as $falta){
									$htmlFaltas .= '<tr>';
									$htmlFaltas .= '<td class="text-right">';
									$htmlFaltas .= 'Aula '.$falta->getPosicao();
									$htmlFaltas .= '</td>';
									$htmlFaltas .= '<td><button type="button" class="btn btn-primary btn-xs" onClick="verReposicao('.$falta->getId().')">Ver Reposição</button></td>';
									$htmlFaltas .= '</tr>';
								}
								$htmlFaltas .= '</table>';
							}
							$html .= $htmlFaltas;
							$html .= '</div>';
							$html .= '</div>';

							$html .= '<div id="divSair" class="mt5">';
							$html .= '<button type="button" class="btn btn-primary mb10" onClick="sair()">Sair</button>';
							$html .= '</div>';

							/* fim panel dados */
							$html .= '</div>';
							/* fim div dados */
							$html .= '</div>';

							/* div aula aberta */
							if($turma->getTurmaAulaAtiva()){
								$aula = $turma->getTurmaAulaAtiva()->getAula();
								$html .= '<div id="divAulaAberta" class="p5 hidden">';
								$html .= '<div class="panel panel-primary">';
								$html .= '<div class="panel-heading" style="padding: 0 8px;">Aula '.$aula->getPosicao().' - '.$aula->getNome().'</div>';
								$html .= '<div class="panel-body">';
								if($aula->getUrl() !== null && $aula->getUrl() !== ''){
									$idVideo = $aula->getUrl();
									$url = 'https://player.vimeo.com/video/'.$idVideo.'?byline=0&portrait=0';
									$html .= '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="'.$url.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
								}else{
									$html .= '<div class="alert alert-danger">URL da Aula não cadastrada entre em contato com seu líder para resolver</div>';
								}
								$html .= '<div class="alert alert-primary mt10">Questionário</div>';
								$temPerguntas = false;
								foreach($aula->getPergunta() as $pergunta){
									if($pergunta->verificarSeEstaAtivo()){
										$temPerguntas = true;
									}
								}
								if($temPerguntas){
									foreach($aula->getPergunta() as $pergunta){
										if($pergunta->verificarSeEstaAtivo()){
											$html .= '<div class="panel panel-default mt5">';
											$html .= '<div class="panel-body">';
											$html .= '<p class="text-left">'.$pergunta->getPergunta().'</p><br /><br />';
											$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 1});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="1"> '.$pergunta->getR1().'</p>';
											$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 2});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="2"> '.$pergunta->getR2().'</p>';
											$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 3});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="3"> '.$pergunta->getR3().'</p>';
											$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 4});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="4"> '.$pergunta->getR4().'</p>';
											$html .= '</div>';
											$html .= '</div>';
										}
									}
									$html .= '<button type="button" class="btn btn-primary mt10" onClick="enviarRespostas('.$aula->getId().', 1)">Enviar Respostas</button>';
								}else{
									$html .= '<div class="alert alert-danger">Sem perguntas cadastradas entre em contato com seu líder para resolver</div>';
								}
								$html .= '</div>';

								$html .= '<button type="button" class="mt5 btn btn-xs btn-default" onClick="voltarAosDados()">Voltar</button>';
								$html .= '</div>';

								$html .= '</div>';
							}

							/* div faltas */
							if(count($listaDeFaltas) > 0){
								$html .= '<div id="divFaltas" class="p5 hidden">';
								foreach($listaDeFaltas as $falta){
									$html .= '<div id="divFalta'.$falta->getId().'" class="panel panel-primary hidden falta">';
									$html .= '<div class="panel-heading" style="padding: 0 8px;">Aula '.$falta->getPosicao().' - '.$falta->getNome().'</div>';
									$html .= '<div class="panel-body">';
									if($falta->getUrl() !== null && $falta->getUrl() !== ''){
										$idVideo = $falta->getUrl();
										$url = 'https://player.vimeo.com/video/'.$idVideo.'?byline=0&portrait=0';
										$html .= '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="'.$url.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
									}else{
										$html .= '<div class="alert alert-danger">URL da Aula não cadastrada entre em contato com seu líder para resolver</div>';
									}
									$html .= '<div class="alert alert-primary mt10">Questionário</div>';
									$temPerguntas = false;
									foreach($falta->getPergunta() as $pergunta){
										if($pergunta->verificarSeEstaAtivo()){
											$temPerguntas = true;
										}
									}
									if($temPerguntas){
										foreach($falta->getPergunta() as $pergunta){
											if($pergunta->verificarSeEstaAtivo()){
												$html .= '<div class="panel panel-default mt5">';
												$html .= '<div class="panel-body">';
												$html .= '<p class="text-left">'.$pergunta->getPergunta().'</p><br /><br />';
												$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 1});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="1"> '.$pergunta->getR1().'</p>';
												$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 2});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="2"> '.$pergunta->getR2().'</p>';
												$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 3});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="3"> '.$pergunta->getR3().'</p>';
												$html .= '<p class="text-left"><input onClick="estado.respostas.push({pergunta_id: '.$pergunta->getId().', resposta: 4});" type="radio" id="'.$pergunta->getId().'" name="'.$pergunta->getId().'" value="4"> '.$pergunta->getR4().'</p>';
												$html .= '</div>';
												$html .= '</div>';
											}
										}
										$html .= '<button type="button" class="btn btn-primary mt10" onClick="enviarRespostas('.$falta->getId().')">Enviar Respostas</button>';
									}else{
										$html .= '<div class="alert alert-danger">Sem perguntas cadastradas entre em contato com seu líder para resolver</div>';
									}
									$html .= '</div>';
									$html .= '</div>';
								}
								$html .= '<button type="button" class="mt5 btn btn-sm btn-default" onClick="voltarAosDados()">Voltar</button>';
								$html .= '</div>';
							}

							/* situacao financeira */
							$html .= '<div id="divSituacaoFinanceira" class="panel panel-primary m5 hidden">';
							$html .= '<div class="panel-heading" style="padding: 0px 8px;">Financeiro</div>';
							$html .= '<div class="panel-body">';

							$html .= '<div class="table-responsive">';
							$html .= '<table class="table table-condensed">';
							$html .= '<thead>';

							$html .= '<tr>';
							$html .= '<th colspan="3">Legenda'
								. ' - <span class="label label-xs label-danger"><i class="fa fa-times"></i> Sem Pagamento</span>'
								. ' - <span class="label label-xs label-success"><i class="fa fa-check"></i> Pago</span></th>';
							$html .= '</tr>';

							$html .= '<tr>';
							$html .= '<th>Diciplinas</th>';
							$html .= '<th colspan="3" class="text-center">Mensalidade</th>';
							$html .= '</tr>';
							$html .= '</thead>';
							$html .= '<tbody>';
							$pontosFinanceiro = Array();		
							$validacaoFinanceiro = 1;		
							foreach ($turmaPessoa->getTurma()->getCurso()->getDisciplina() as $disciplina) {
								if ($disciplina->getId() !== Disciplina::POS_REVISAO) {				
									$html .= '<tr>';	
									$html .= '<td>' . $disciplina->getNome() . '</td>';			
									if (count($turmaPessoa->getTurmaPessoaFinanceiro()) > 0) {
										$mensalidadeFinanceiro = Array();
										foreach ($turmaPessoa->getTurmaPessoaFinanceiro() as $turmaPessoaFinanceiro) {
											if ($turmaPessoaFinanceiro->getDisciplina()->getId() === $disciplina->getId() && $turmaPessoaFinanceiro->verificarSeEstaAtivo()) {							
												$mensalidadeFinanceiro['valor1'] = $turmaPessoaFinanceiro->getValor1();
												$mensalidadeFinanceiro['valor2'] = $turmaPessoaFinanceiro->getValor2();
												$mensalidadeFinanceiro['valor3'] = $turmaPessoaFinanceiro->getValor3();
											}
										}
									}


									for ($indiceMensalidade=1; $indiceMensalidade <= 3 ; $indiceMensalidade++) { 
										$extra = '';
										$icone = 'fa-times';
										$corDoBotao = BotaoSimples::botaoMuitoPequenoPerigoso;				

										if($mensalidadeFinanceiro['valor'.$indiceMensalidade] == 'S'){
											$pontosFinanceiro[$disciplina->getNome()] += 1;
											$icone = 'fa-check';
											$corDoBotao = BotaoSimples::botaoMuitoPequenoSucesso;
										}
										$iconeBotao = '<i class="fa ' . $icone . '"></i>';
										$idBotao = 'botao_' . $turmaPessoa->getId() . '_' . $disciplina->getId() . '_3' . '_' . $indiceMensalidade;

										$html .= '<td>';
										$mostrarRecibo = 'hidden';
										if($corDoBotao == BotaoSimples::botaoMuitoPequenoSucesso){
											$corDoSpan = 'success';
										}
										if($corDoBotao == BotaoSimples::botaoMuitoPequenoPerigoso){
											$corDoSpan = 'danger';
										}						
										$html .= '<span class="btn-xs btn-'.$corDoSpan.'">';
										$html .= $iconeBotao;
										$html .= '</span>';				

										$html .= '</td>';
									}

									$html .= '</tr>';
								}
							}
							$html .= '</tbody>';
							$html .= '</table>';
							$html .= '</div>';
							$html .= '<button type="button" class="btn btn-xs btn-primary" onClick="mostrarPagamentos()">Pagar Mensalidade</button>';
							$html .= '&nbsp;&nbsp;<button type="button" class="mt5 btn btn-xs btn-default" onClick="voltarAosDados()">Voltar</button>';
							$html .= '</div>';
							$html .= '</div>';
							/* fim div situacao financeira */

							/* div pagamentos */
							$html .= '<div id="divPagamentos" class="p5 hidden">';
							$email = $turmaPessoa->getPessoa()->getEmail();
							if($email === 'atualize' || $email === null || $email === ''){
								$html .= '<div id="divEmail" class="panel panel-primary">';
								$html .= '<div class="panel-heading" style="padding: 0 8px;">Pagamentos</div>';
								$html .= '<div class="panel-body">';

								$html .= '<p>Para realizar um pagamento você precisa ter o email cadastrado</p>';
								$html .= '<p>Sem Email cadastrado</p>';
								$html .= '<p><button type="button" class="btn btn-xs btn-primary" onClick="mostrarCadastrarEmail()">Cadastrar email</button></p>';
								$html .= '<div id="divCadastrarEmail" class="p5 hidden">';
								$html .= '<p>Informe o email</p>';
								$html .= '<input type="email" id="email" class="form-control" />';
								$html .= '<br />';
								$html .= '<button type="button" onClick="salvarEmail()" class="btn btn-sm btn-primary">Salvar</button>';
								$html .= '</div>';

								$html .= '</div>';

								$html .= '<button type="button" class="mt5 btn btn-sm btn-default" onClick="mostrarSituacaoFinanceira()">Voltar</button>';
								$html .= '</div>';
							}else{
								$html .= self::pagamentos($turmaPessoa);
							}
							/* fim div pagamentos */
							$html .= '</div>';

						}
						$dados['html'] = $html;
						$dados['ok'] = true;
					}else{
						$dados['false'] = true;
						$dados['message'] = 'Sua igreja não tem acesso!';
					}
				}
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	function pagamentos($turmaPessoa){
		$html = '';
		$url = 'https://cieloecommerce.cielo.com.br/transactionalvnext/order/buynow/';
		$email = $turmaPessoa->getPessoa()->getEmail();
		$html .= '<div class="panel panel-primary">';
		$html .= '<div class="panel-heading" style="padding: 0 8px;">Pagamentos</div>';
		$html .= '<div class="panel-body">';

		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-condensed text-left">';
		$html .= '<tr>';
		$html .= '<td>Tudo à vista - R$100,00</td>';
		$id = self::$PRODUTO_TUDO;
		$html .= '<td><a target="_blanck" onClick="location.href=\''.$url.$id.'\';" class="btn btn-xs btn-primary">Pagar</a></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>Tudo parcelado em 3 vezes - R$120,00</td>';
		$id = self::$PRODUTO_TUDO_PARCELADO;
		$html .= '<td><a target="_blanck" onClick="location.href=\''.$url.$id.'\';" class="btn btn-xs btn-primary">Pagar</a></td>';
		$html .= '</tr>';
	
		for($indiceModulo = 1; $indiceModulo <= 3; $indiceModulo++){
			$valorModulor = 45;
			if($indiceModulo === 1){
				$valorModulor = 30;
			}
			$html .= '<tr>';
			$html .= '<td>'.$indiceModulo.'º Módulo Completo - R$'.$valorModulor.',00</td>';
			if($indiceModulo === 1){
				$id = self::$PRODUTO_MODULO_1;
			}
			if($indiceModulo === 2){
				$id = self::$PRODUTO_MODULO_2;
			}
			if($indiceModulo === 3){
				$id = self::$PRODUTO_MODULO_3;
			}
			$html .= '<td><a target="_blanck" onClick="location.href=\''.$url.$id.'\';" class="btn btn-xs btn-primary">Pagar</a></td>';
			$html .= '</tr>';
			for($indiceParcela = 1; $indiceParcela <= 3; $indiceParcela++){
				$valorParcela = 15;
				if($indiceModulo === 1){
					$valorParcela = 10;
				}
				$html .= '<tr>';
				$html .= '<td>'.$indiceParcela.'º Parcela do '.$indiceModulo.'º Módulo - R$'.$valorParcela.',00</td>';
				if($indiceModulo === 1){
					if($indiceParcela === 1){
						$id = self::$PRODUTO_PARCELA_1_MODULO_1;
					}
					if($indiceParcela === 2){
						$id = self::$PRODUTO_PARCELA_2_MODULO_1;
					}
					if($indiceParcela === 3){
						$id = self::$PRODUTO_PARCELA_3_MODULO_1;
					}
				}
				if($indiceModulo === 2){
					if($indiceParcela === 1){
						$id = self::$PRODUTO_PARCELA_1_MODULO_2;
					}
					if($indiceParcela === 2){
						$id = self::$PRODUTO_PARCELA_2_MODULO_2;
					}
					if($indiceParcela === 3){
						$id = self::$PRODUTO_PARCELA_3_MODULO_2;
					}
				}
				if($indiceModulo === 3){
					if($indiceParcela === 1){
						$id = self::$PRODUTO_PARCELA_1_MODULO_3;
					}
					if($indiceParcela === 2){
						$id = self::$PRODUTO_PARCELA_2_MODULO_3;
					}
					if($indiceParcela === 3){
						$id = self::$PRODUTO_PARCELA_3_MODULO_3;
					}
				}
	
				$html .= '<td><a target="_blanck" onClick="location.href=\''.$url.$id.'\';" class="btn btn-xs btn-primary">Pagar</a></td>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		$html .= '</div>';


		$html .= '</div>';

		$html .= '<button type="button" class="mt5 btn btn-xs btn-default" onClick="mostrarSituacaoFinanceira()">Voltar</button>';
		$html .= '</div>';
		return $html;
	}

	public function salvarEmailAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		$dados['ok'] = false;
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				if($turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($json->matricula)){
					$pessoa = $turmaPessoa->getPessoa();
					$emailDisponivel = true;
					if ($pessoaPesquisada = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($json->email)) {
						$emailDisponivel = false;
					}
					if($emailDisponivel){
						$pessoa->setEmail($json->email);
						$this->getRepositorio()->getPessoaORM()->persistir($pessoa, $alterarDataDeCriacao = false);
						$dados['ok'] = true;
						$dados['html'] = self::pagamentos($turmaPessoa);
					}
				}
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function enviarRespostasAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		$dados['ok'] = false;
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$aulaReposta = false;

				$contagemRespostasCertas = 0;
				foreach($json->respostas as $resposta){
					$pergunta = $this->getRepositorio()->getPerguntaORM()->encontrarPorId(intVal($resposta->pergunta_id));
					if($pergunta->getAula()->getId() === intVal($json->aula_id)){
						if(intVal($resposta->resposta) === $pergunta->getCerta()){
							$contagemRespostasCertas++;
						}
					}
				}

				$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId(intVal($json->aula_id));
				$contagemDeAulas = 0;
				foreach($aula->getPergunta() as $perguntasParaValidar){
					if($perguntasParaValidar->verificarSeEstaAtivo()){
						$contagemDeAulas++;
					}
				}

				$percentual = $contagemRespostasCertas / $contagemDeAulas * 100;
				if($percentual >= 70){
					$aulaReposta = true;
				}

				if($aulaReposta){
					$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId(intVal($json->matricula));
					$turmaPessoaAula = $turmaPessoa->getTurmaPessoaAulaPorAula($aula->getId());
					if (!$turmaPessoaAula) {
						$turmaPessoaAula = new TurmaPessoaAula();
						$turmaPessoaAula->setAula($aula);
						$turmaPessoaAula->setTurma_pessoa($turmaPessoa);
					}
					$turmaPessoaAula->setData_inativacao(null);
					$turmaPessoaAula->setHora_inativacao(null);
					$turmaPessoaAula->setReposicao('S');
					/* presença */
					if(intVal($json->tipo) === 1){
						$turmaPessoaAula->setReposicao('N');
					}
					$this->getRepositorio()->getTurmaPessoaAulaORM()->persistir($turmaPessoaAula);

					/* reposição */
					if(intVal($json->tipo) === 0){
						$turmaPessoaVisto = $turmaPessoa->getTurmaPessoaVistoPorAula($aula->getId());
						if (!$turmaPessoaVisto) {
							$turmaPessoaVisto = new TurmaPessoaVisto();
							$turmaPessoaVisto->setAula($aula);
							$turmaPessoaVisto->setTurma_pessoa($turmaPessoa);
						}
						$turmaPessoaVisto->setData_inativacao(null);
						$turmaPessoaVisto->setHora_inativacao(null);
						$this->getRepositorio()->getTurmaPessoaVistoORM()->persistir($turmaPessoaVisto);
					}
	
					$dados['ok'] = true;
				}
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function espacoCoordenadorAction(){
		return new ViewModel();
	}

	public function validarAcessoAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);

				$adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
				$adapter->setIdentityValue($json->email);
				$adapter->setCredentialValue(md5($json->senha));
				$authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
				if ($authenticationResult->isValid()) {
					$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($json->email);
					/* Tem responsabilidade(s) */
					if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
						if (
							($pessoa->getPessoaCursoAcessoAtivo() && $pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR)
							|| $pessoa->getEmail() === 'diegokort@gmail.com'
							|| $pessoa->getEmail() === 'julianafmo@gmail.com'
							|| $pessoa->getEmail() === 'tvcouto@hotmail.com'
						) {
							$sessao = new Container(Constantes::$NOME_APLICACAO);
							$sessao->idPessoa = $pessoa->getId();

							$html = self::buscarAulasComPerguntas();

							$dados['html'] = $html;
							$dados['ok'] = true;
						}
					}
				}
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function voltarAAulasEPerguntasAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$html = self::buscarAulasComPerguntas();
				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarAulasComPerguntas(){
		$html = '';
		$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso = 2);
		$html .= '<div class="panel panel-default">';
		$html .= '<div class="panel-heading" style="padding: 0px 8px;">Aulas</div>';
		$html .= '<div class="panel-body">';
		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-condensed">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th colspan="13">Legenda'
			. ' - <span class="label label-xs label-danger"><i class="fa fa-times"></i> Sem Perguntas e URL</span>'
			. ' - <span class="label label-xs label-warning"><i class="fa fa-retweet"></i> Tem Perguntas sem URL</span>'
			. ' - <span class="label label-xs label-warning"><i class="fa fa-retweet"></i> Não tem Pergunta mas tem URL</span>'
			. ' - <span class="label label-xs label-success"><i class="fa fa-check"></i> Tem Perguntas e URL</span>'
	   		. '	- Clique na aula para criar ou alterar as perguntas e a URL do Vimeo</th>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<th>Diciplinas</th>';
		$html .= '<th colspan="12">Aulas</th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$pontosPresenca = Array();
		foreach ($curso->getDisciplina() as $disciplina) {
			$html .= '<tr>';
			$html .= '<td>' . $disciplina->getNome() . '</td>';		
			foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
				$icone = 'fa-times';
				$corDoBotao = 'danger';
				$iconeBotao = '<i class="fa ' . $icone . '"></i>';
				$temUrl = false;
				$temPerguntas = false;

				foreach($aula->getPergunta() as $pegunta){
					if($pegunta->verificarSeEstaAtivo()){
						$temPerguntas = true;
						$corDoBotao = 'warning';
						$icone = 'fa-retweet';
					}
				}

				if($aula->getUrl() !== '' && $aula->getUrl() !== null){
					$temUrl = true;
					$corDoBotao = 'warning';
					$icone = 'fa-retweet';
				}

				if($temUrl && $temPerguntas){
					$icone = 'fa-check';
					$corDoBotao = 'success';
				}
				$html .= '<td class="text-center">';
				$html .= $aula->getPosicao();
				$html .= ' <button onClick="alterarPerguntas('.$aula->getId().');" class="btn btn-xs btn-'.$corDoBotao.'"><i class="fa '.$icone.'"></i></button>';
				$html .= '</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= '<br /><button type="button" class="btn btn-sm btn-default" onClick="sair()">Sair</button>';
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	public function buscarPerguntasAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				
				$html = self::buscarDadosDeUmaAula($json->aula_id);

				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	function buscarDadosDeUmaAula($aula_id){
		$html = '';
		$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId($aula_id);
		$html .= '<div class="panel panel-default m5">';
		$html .= '<div class="panel-heading" style="padding: 0px 8px;">Aula '.$aula->getPosicao().' - '.$aula->getNome().'</div>';
		$html .= '<div class="panel-body">';

		$html .= '<div class="panel panel-primary">';
		$html .= '<div class="panel-heading" style="padding: 0 8px;">Número de identificação do vídeo do Vimeo</div>';
		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-condensed">';
		$html .= '<tbody>';
		$html .= '<tr>';
		$html .= '<td>Número</td>';
		$html .= '<td><input class="form-control" type="number" id="urlVimeo" value="'.$aula->getUrl().'" placeholder="Número" /></td>';
		$html .= '</tr>';
		if($aula->getPessoa()){
			$html .= '<tr>';
			$html .= '<td>Quem Alterou/Salvou</td>';
			$html .= '<td>'.$aula->getPessoa()->getNome().'</td>';
			$html .= '</tr>';
		}
		$html .= '<tr>';
		$html .= '<td colspan="2"><button type="button" onClick="salvarUrl('.$aula_id.');" class="btn btn-sm btn-primary">Salvar Número</button></td>';
		$html .= '</tr>';
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= '</div>';

		$html .= '<div class="panel panel-primary">';
		$html .= '<div class="panel-heading" style="padding: 0 8px;">Perguntas - <button type="button" class="btn btn-sm btn-success" onClick="abrirSalvarPergunta(0, '.$aula->getId().');"><i class="fa fa-plus"></i> Adicionar Pergunta</div>';
		$perguntasAtivas = false;
		foreach($aula->getPergunta() as $pegunta){
			if($pegunta->verificarSeEstaAtivo()){
				$perguntasAtivas = true;
			}
		}
		if($perguntasAtivas){
			$perguntas = $aula->getPergunta();
			uksort($perguntas, function ($a, $b) use ($perguntas) {
				return ($perguntas[$a]->getId() > $perguntas[$b]->getId()) ? -1 : 1;
			});

			foreach($perguntas as $pegunta){
				if($pegunta->verificarSeEstaAtivo()){
					$html .= '<hr />';
					$html .= '<p class="text-left">Pergunta: '.$pegunta->getPergunta().'</p>';
					$html .= '<p class="text-left">Reposta 1: '.$pegunta->getR1().'</p>';
					$html .= '<p class="text-left">Reposta 2: '.$pegunta->getR2().'</p>';
					$html .= '<p class="text-left">Reposta 3: '.$pegunta->getR3().'</p>';
					$html .= '<p class="text-left">Reposta 4: '.$pegunta->getR4().'</p>';
					$html .= '<p class="text-left">Certa: '.$pegunta->getCerta().'</p>';
					$html .= '<p class="text-left">Quem Criou/Alterou: '.$pegunta->getPessoa()->getNome().'</p>';
					$html .= '<p class="text-left">Opções: ';
					$html .= '<button type="button" class="btn btn-sm btn-primary" onClick="abrirSalvarPergunta('.$pegunta->getId().','.$aula->getId().');"><i class="fa fa-pencil"></i> Alterar</button>';
					$html .= '&nbsp;<button type="button" class="btn btn-sm btn-danger" onClick="removerPergunta('.$pegunta->getId().','.$aula->getId().');"><i class="fa fa-times"></i> Remover</button>';
					$html .= '</p>';
				}
			}
		}else{
			$html .= '<div class="alert alert-danger">Sem perguntas Cadastradas</div>';
		}
		$html .= '</div>';

		$html .= '<button type="button" onClick="voltarAAulasEPerguntas();" class="btn btn-sm btn-default">Voltar</button>';

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	public function abrirSalvarPerguntaAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$perguntaSelecionada = null;
				if(intVal($json->pergunta_id) !== 0){
					$perguntaSelecionada = $this->getRepositorio()->getPerguntaORM()->encontrarPorId(intVal($json->pergunta_id));
				}

				$html .= '<div class="panel panel-default m5">';
				$html .= '<div class="panel-heading" style="padding: 0px 8px;">Salvar Pergunta</div>';
				$html .= '<div class="panel-body">';
				$idPergunta = $perguntaSelecionada ? $perguntaSelecionada->getId() : 0;
				$html .= '<input type="hidden" id="pergunta_id" value="'.$idPergunta.'"/>';
				$html .= '<input type="hidden" id="aula_id" value="'.$json->aula_id.'"/>';

				$html .= '<table class="table table-condensed">';
				$html .= '<tbody>';
				$html .= '<tr>';
				$html .= '<td>Pergunta</td>';
				$pergunta = $perguntaSelecionada ? $perguntaSelecionada->getPergunta() : '';
				$html .= '<td><textarea class="form-control" id="pergunta" placeholder="Pergunta" />'.$pergunta.'</textarea></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td>Resposta 1</td>';
				$r1 = $perguntaSelecionada ? $perguntaSelecionada->getR1() : '';
				$html .= '<td><input class="form-control" type="text" id="r1" value="'.$r1.'" placeholder="Resposta 1" /></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td>Resposta 2</td>';
				$r2 = $perguntaSelecionada ? $perguntaSelecionada->getR2() : '';
				$html .= '<td><input class="form-control" type="text" id="r2" value="'.$r2.'" placeholder="Resposta 2" /></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td>Resposta 3</td>';
				$r3 = $perguntaSelecionada ? $perguntaSelecionada->getR3() : '';
				$html .= '<td><input class="form-control" type="text" id="r3" value="'.$r3.'" placeholder="Resposta 3" /></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td>Resposta 4</td>';
				$r4 = $perguntaSelecionada ? $perguntaSelecionada->getR4() : '';
				$html .= '<td><input class="form-control" type="text" id="r4" value="'.$r4.'" placeholder="Resposta 4" /></td>';
				$html .= '</tr>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td>Resposta Certa</td>';
				$html .= '<td>';
				$certa = $perguntaSelecionada ? $perguntaSelecionada->getCerta() : '';
				$selected1 = '';
				if($certa === 1){
					$selected1 = 'selected';
				}
				$selected2 = '';
				if($certa === 2){
					$selected2 = 'selected';
				}
				$selected3 = '';
				if($certa === 3){
					$selected3 = 'selected';
				}
				$selected4 = '';
				if($certa === 4){
					$selected4 = 'selected';
				}
				$html .= '<select id="certa" class="form-control">';
				$html .= '<option value="0">Selecione</option>';
				$html .= '<option '.$selected1.' value="1">Resposta 1</option>';
				$html .= '<option '.$selected2.' value="2">Resposta 2</option>';
				$html .= '<option '.$selected3.' value="3">Resposta 3</option>';
				$html .= '<option '.$selected4.' value="4">Resposta 4</option>';
				$html .= '</select>';
				$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td colspan="2">';
				$html .= '<button class="btn btn-sm btn-primary" onClick="salvarPergunta();">Salvar Pergunta</button>';
				$html .= '&nbsp;<button class="btn btn-sm btn-default" onClick="voltarAAula('.$json->aula_id.');">Voltar</button>';
				$html .= '</td>';
				$html .= '</tr>';
				$html .= '</tbody>';
				$html .= '</table>';

				$html .= '</div>';
				$html .= '</div>';

				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function salvarPerguntaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$perguntaSelecionada = null;
				if(intVal($json->pergunta_id) !== 0){
					$perguntaSelecionada = $this->getRepositorio()->getPerguntaORM()->encontrarPorId(intVal($json->pergunta_id));
				}else{
					$perguntaSelecionada = new Pergunta();
					$perguntaSelecionada->setDataEHoraDeCriacao();
				}
				$perguntaSelecionada->setPergunta($json->pergunta);
				$perguntaSelecionada->setR1($json->r1);
				$perguntaSelecionada->setR2($json->r2);
				$perguntaSelecionada->setR3($json->r3);
				$perguntaSelecionada->setR4($json->r4);
				$perguntaSelecionada->setCerta($json->certa);
				$perguntaSelecionada->setAula($this->getRepositorio()->getAulaORM()->encontrarPorId(intVal($json->aula_id)));
				$perguntaSelecionada->setPessoa($this->getRepositorio()->getPessoaORM()->encontrarPorId(intVal($sessao->idPessoa)));
				$this->getRepositorio()->getPerguntaORM()->persistir($perguntaSelecionada, false);

				$html = self::buscarDadosDeUmaAula($json->aula_id);

				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function removerPerguntaAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$perguntaSelecionada = $this->getRepositorio()->getPerguntaORM()->encontrarPorId(intVal($json->pergunta_id));
				$perguntaSelecionada->setDataEHoraDeInativacao();
				$this->getRepositorio()->getPerguntaORM()->persistir($perguntaSelecionada, $mudarDataDeCriacao = false);

				$html = self::buscarDadosDeUmaAula($json->aula_id);

				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function salvarUrlAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['ok'] = false;
		$html = '';
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId(intVal($json->aula_id));
				$aula->setUrl($json->urlVimeo);
				$aula->setPessoa($this->getRepositorio()->getPessoaORM()->encontrarPorId(intVal($sessao->idPessoa)));
				$this->getRepositorio()->getAulaORM()->persistir($aula, $mudarDataDeCriacao = false);

				$html = self::buscarDadosDeUmaAula($json->aula_id);

				$dados['html'] = $html;
				$dados['ok'] = true;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function sairEspacoCoordenadorAction(){
        $sessao = new Container(Constantes::$NOME_APLICACAO);
		$response = $this->getResponse();

		if($sessao->getManager()){
			$sessao->getManager()->destroy();
		}

		$dados = array();	
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function pagamentoRealizadoAction(){
		return new ViewModel();
	}

	static $ESTADO_PAGO = 2;
	static $ESTADO_AUTORIZADO_CARTAO_CREDITO = 7;
	static $PRODUTO_TESTE = '68373664-2be7-40ba-84f3-34451f7a13d3';
	static $PRODUTO_TUDO = 'b11a589e-93fd-4532-bc21-e01379ec82a1';
	static $PRODUTO_TUDO_PARCELADO = '89246bd1-9be1-4d44-a1b6-5c6d83543a75';
	static $PRODUTO_MODULO_1 = '281faaf1-f4e7-4800-9619-e6c33ff09474';
	static $PRODUTO_PARCELA_1_MODULO_1 = '59ded433-689f-4fe9-b587-eff429f9db3d';
	static $PRODUTO_PARCELA_2_MODULO_1 = 'b974d23b-148d-42cc-bc7f-21bc20600ebd';
	static $PRODUTO_PARCELA_3_MODULO_1 = 'cfa0a967-3257-4039-89dc-2a59c3bd9070';
	static $PRODUTO_MODULO_2 = 'ee4cce00-e8e2-4889-87ed-10668c8924aa';
	static $PRODUTO_PARCELA_1_MODULO_2 = '908a825b-5fbd-4178-a518-3af92965abc0';
	static $PRODUTO_PARCELA_2_MODULO_2 = 'b275c76a-4f89-4877-93b7-bec027574c4f';
	static $PRODUTO_PARCELA_3_MODULO_2 = '3364ed80-dd6f-4fa4-8e83-5d7992c5d87d';
	static $PRODUTO_MODULO_3 = '95166276-6f1a-496b-814b-b13467b415b3';
	static $PRODUTO_PARCELA_1_MODULO_3 = '7581785f-d6fb-4f21-b4a3-ae71fe0dd8b2';
	static $PRODUTO_PARCELA_2_MODULO_3 = 'ce981f85-12ca-4132-9880-29b2de6322dd';
	static $PRODUTO_PARCELA_3_MODULO_3 = '9b3e2cc9-ea13-47dd-9351-3ef360625e52';

	public function checkoutAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();

		if ($request->isPost()) {
			$dataPost = $request->getPost();
			$email = $dataPost['customer_email'];
			$produto_id = $dataPost['product_id'];
			$estado_pagamento = $dataPost['payment_status'];
			if(
				intVal($estado_pagamento) === self::$ESTADO_PAGO ||
				intVal($estado_pagamento) === self::$ESTADO_AUTORIZADO_CARTAO_CREDITO
			){
				if($pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email)){
					if($turmaPessoa = $pessoa->getTurmaPessoaAtivo()){
						/* validar qual produto e alterar o financeiro */
						$alterarDataDeCriacaoFinanceiro = false;
						$indiceInicialModulos = 0;
						$indiceFinalModulos = 0;
						$todasParcelas = false;
						$parcela1 = false;
						$parcela2 = false;
						$parcela3 = false;
						if(
							$produto_id === self::$PRODUTO_TUDO ||
							$produto_id === self::$PRODUTO_TUDO_PARCELADO
						){
							$indiceInicialModulos = 1;
							$indiceFinalModulos = 3;
							$todasParcelas = true;
						}
						if($produto_id === self::$PRODUTO_MODULO_1){
							$indiceInicialModulos = 1;
							$indiceFinalModulos = 1;
							$todasParcelas = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_1_MODULO_1){
							$indiceInicialModulos = 1;
							$indiceFinalModulos = 1;
							$parcela1 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_2_MODULO_1){
							$indiceInicialModulos = 1;
							$indiceFinalModulos = 1;
							$parcela2 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_3_MODULO_1){
							$indiceInicialModulos = 1;
							$indiceFinalModulos = 1;
							$parcela3 = true;
						}
						if($produto_id === self::$PRODUTO_MODULO_2){
							$indiceInicialModulos = 2;
							$indiceFinalModulos = 2;
							$todasParcelas = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_1_MODULO_2){
							$indiceInicialModulos = 2;
							$indiceFinalModulos = 2;
							$parcela1 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_2_MODULO_2){
							$indiceInicialModulos = 2;
							$indiceFinalModulos = 2;
							$parcela2 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_3_MODULO_2){
							$indiceInicialModulos = 2;
							$indiceFinalModulos = 2;
							$parcela3 = true;
						}
						if($produto_id === self::$PRODUTO_MODULO_3){
							$indiceInicialModulos = 3;
							$indiceFinalModulos = 3;
							$todasParcelas = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_1_MODULO_3){
							$indiceInicialModulos = 3;
							$indiceFinalModulos = 3;
							$parcela1 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_2_MODULO_3){
							$indiceInicialModulos = 3;
							$indiceFinalModulos = 3;
							$parcela2 = true;
						}
						if($produto_id === self::$PRODUTO_PARCELA_3_MODULO_3){
							$indiceInicialModulos = 3;
							$indiceFinalModulos = 3;
							$parcela3 = true;
						}
						for($indiceModulo = $indiceInicialModulos; $indiceModulo <= $indiceFinalModulos; $indiceModulo++){
							$cadastroNovo = false;				
							$idDisciplina = null;
							if($indiceModulo === 1){
								$idDisciplina = Disciplina::MODULO_UM;
							}
							if($indiceModulo === 2){
								$idDisciplina = Disciplina::MODULO_DOIS;
							}
							if($indiceModulo === 3){
								$idDisciplina = Disciplina::MODULO_TRES;
							}
							$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);
							$turmaPessoaFinanceiro = $turmaPessoa->getTurmaPessoaFinanceiroPorDisciplina($disciplina->getId());
							if (!$turmaPessoaFinanceiro) {
								$turmaPessoaFinanceiro = new TurmaPessoaFinanceiro();	
								$cadastroNovo = true;						
							}
							$turmaPessoaFinanceiro->setDisciplina($disciplina);
							$turmaPessoaFinanceiro->setTurma_pessoa($turmaPessoa);
							$qualAvaliacao = null;
							$mes = date('m');
							$ano = date('Y');
							$valor1 = 'N';
							$valor2 = 'N';
							$valor3 = 'N';
							if($todasParcelas){
								$valor1 = 'S';
								$valor2 = 'S';
								$valor3 = 'S';
							}
							if($parcela1){
								$valor1 = 'S';
							}
							if($parcela2){
								$valor2 = 'S';
							}
							if($parcela3){
								$valor3 = 'S';
							}
							if($valor1 === 'S'){
								$turmaPessoaFinanceiro->setValor1($valor1);
								$turmaPessoaFinanceiro->setMes1($mes);
								$turmaPessoaFinanceiro->setAno1($ano);
							}
							if($valor2 === 'S'){
								$turmaPessoaFinanceiro->setValor2($valor2);
								$turmaPessoaFinanceiro->setMes2($mes);
								$turmaPessoaFinanceiro->setAno2($ano);
							}
							if($valor3 === 'S'){
								$turmaPessoaFinanceiro->setValor3($valor3);
								$turmaPessoaFinanceiro->setMes3($mes);
								$turmaPessoaFinanceiro->setAno3($ano);
							}
							if($cadastroNovo){
								$alterarDataDeCriacaoFinanceiro = true;
								if(!$turmaPessoaFinanceiro->getValor1()){
									$turmaPessoaFinanceiro->setValor1('N');
								}
								if(!$turmaPessoaFinanceiro->getValor2()){
									$turmaPessoaFinanceiro->setValor2('N');
								}
								if(!$turmaPessoaFinanceiro->getValor3()){
									$turmaPessoaFinanceiro->setValor3('N');
								}
							}					
							$this->getRepositorio()->getTurmaPessoaFinanceiroORM()->persistir($turmaPessoaFinanceiro, $alterarDataDeCriacaoFinanceiro);
						}
					}
				}
			}
		}

		$dados = array();	
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function sincronizarAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$ok = false;
		
		if ($request->isPost()) {
			$body = $request->getContent();
			$json = Json::decode($body);
			$adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
			$adapter->setIdentityValue($json->email);
			$adapter->setCredentialValue(md5($json->senha));
			$authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
			if ($authenticationResult->isValid()) {
				$mes = date('m');	
				$ano = date('Y');	
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($json->email);
				if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
					$ok = true;
					$dados['email'] = $json->email;

				$grupoResponsaveis = $pessoa->getResponsabilidadesAtivas();

				$dados['perfils'] = array();
				foreach($grupoResponsaveis as $grupoResponsavel){
					$grupo = $grupoResponsavel->getGrupo();
					if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial){
						$resultado = RelatorioController::buscarDadosPrincipais($this->getRepositorio(), $grupo, $mes, $ano, $equipe = 2);
					}
					if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::presidencial){
						$fatoPresidencial = $this->getRepositorio()->getFatoPresidencialORM()->buscarTodosRegistrosEntidade($campoOrderBy = 'id', $sentidoOrderBy = 'DESC')[0];
						$resultado['lideres'] = $fatoPresidencial->getLideres();
						$resultado['celulas'] = $fatoPresidencial->getCelulas();
						$resultado['discipulados'] = $fatoPresidencial->getDiscipulados();
						$resultado['regioes'] = $fatoPresidencial->getRegioes();
						$resultado['coordenacoes'] = $fatoPresidencial->getCoordenacoes();
						$resultado['igrejas'] = $fatoPresidencial->getIgrejas();
						$resultado['parceiro'] = $fatoPresidencial->getParceiro();
						$resultado['mostrarRegioes'] = true;
						$resultado['mostrarCoordenacoes'] = true;
						$resultado['mostrarIgrejas'] = true;
					}
					$resultado['entidade'] = $grupo->getEntidadeAtiva()->infoEntidade();
					$resultado['entidadeTipo'] = $grupo->getEntidadeAtiva()->getEntidadeTipo()->getNome();
					$dados['perfils'][] = $resultado;
				}
			  }
		   }
		}
		$dados['ok'] = $ok;
		$response->getHeaders()
			->addHeaderLine('Access-Control-Allow-Origin', '*')
			->addHeaderLine('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')
			->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
			->addHeaderLine('Content-Type', 'application/json; charset=utf-8')
			->addHeaderLine('x-content-type-options', 'nosniff')
			->addHeaderLine('x-dns-prefetch-control', 'off')
			->addHeaderLine('x-download-options', 'noopen')
			->addHeaderLine('x-frame-options', 'SAMEORIGIN')
			->addHeaderLine('x-xss-protection', '1; mode=block');
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function sincronizarAlunoAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$dados['message'] = 'Matrícula incorreta ou inativa';
		if ($request->isPost()) {
			$body = $request->getContent();
			$json = Json::decode($body);
try{
			$dados = self::dadosAluno($json->matricula);
}catch(Exception $e){
	$dados['error'] = $e->getMessage();
	$dados['ok'] = false;
}
		
				}
		$response->getHeaders()
			->addHeaderLine('Access-Control-Allow-Origin', '*')
			->addHeaderLine('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')
			->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
			->addHeaderLine('Content-Type', 'application/json; charset=utf-8')
			->addHeaderLine('x-content-type-options', 'nosniff')
			->addHeaderLine('x-dns-prefetch-control', 'off')
			->addHeaderLine('x-download-options', 'noopen')
			->addHeaderLine('x-frame-options', 'SAMEORIGIN')
			->addHeaderLine('x-xss-protection', '1; mode=block');
		$response->setContent(Json::encode($dados));
		return $response;
	}

function dadosAluno($matricula){
$dados = array();
		if($turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($matricula)){
					if(
$turmaPessoa->getTurma()->getGrupo()->getGrupoRegiao()->getId() === 3110 ||
						$turmaPessoa->getTurma()->getGrupo()->getGrupoRegiao()->getId() === 7694
){
						if($turmaPessoa->verificarSeEstaAtivo()){
							$dados['message'] = '';
							$usuario = array();
							$usuario['matricula'] = $turmaPessoa->getId();
							$usuario['nome'] = $turmaPessoa->getPessoa()->getNome();
							$usuario['time'] = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade();

							$turma = $turmaPessoa->getTurma();
							$nomeDisciplina = 'PÓS REVISÃO';
							if($turma->getTurmaAulaAtiva()){
								$nomeDisciplina = $turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getNome();
							}
							$nomeTurma = $turma->getCurso()->getNomeSigla() . ' - ' . Funcoes::mesPorExtenso($turma->getMes(), 1) . '/' . $turma->getAno() . ' - ' . $nomeDisciplina;
	
							$usuario['turma'] = $nomeTurma;
							$usuario['situacao'] = $turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getNome();
							$listaDeFaltas = array();
							if($turma->getTurmaAulaAtiva()){
							/* links Aula aberta */
								$turmaAula = $turma->getTurmaAulaAtiva();
							$listaDeLinks = array();
							if($turmaAula->getUrl1()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 1;
								$item['dia'] = 'Segunda-Feira';
								$item['link'] = $turmaAula->getUrl1();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl2()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 2;
								$item['dia'] = 'Terça-Feira';
								$item['link'] = $turmaAula->getUrl2();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl3()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 3;
								$item['dia'] = 'Quarta-Feira';
								$item['link'] = $turmaAula->getUrl3();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl4()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 4;
								$item['dia'] = 'Quinta-Feira';
								$item['link'] = $turmaAula->getUrl4();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl5()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 5;
								$item['dia'] = 'Sexta-Feira';
								$item['link'] = $turmaAula->getUrl5();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl6()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 6;
								$item['dia'] = 'Sábado';
								$item['link'] = $turmaAula->getUrl6();
								$listaDeLinks[] = $item;
							}
							if($turmaAula->getUrl7()){
								$item = array();
								$item['id'] = $turmaAula->getId() + 7;
								$item['dia'] = 'Domingo';
								$item['link'] = $turmaAula->getUrl7();
								$listaDeLinks[] = $item;
							}

							/* faltas */
								$disciplina = $turma->getTurmaAulaAtiva()->getAula()->getDisciplina();
								foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
									if($turma->getTurmaAulaAtiva()->getAula()->getId() === $aula->getId()){
										break;
									}
									$falta = true;
									if (count($turmaPessoa->getTurmaPessoaAula()) > 0) {
										foreach ($turmaPessoa->getTurmaPessoaAula() as $turmaPessoaAula) {
											if ($turmaPessoaAula->getAula()->getId() === $aula->getId() && $turmaPessoaAula->verificarSeEstaAtivo()) {
												$falta = false;
											}
										}
									}
									if($falta){
										$listaDeFaltas[] = $aula;
									}
								}
							}

							$faltas = array();
							if(count($listaDeFaltas) > 0){
								foreach($listaDeFaltas as $falta){
									$item = array();
									$item['id'] = $falta->getId();
									$item['posicao'] = $falta->getPosicao();
									$item['idVimeo'] = $falta->getUrl();
									foreach($falta->getPergunta() as $pergunta){
										if($pergunta->verificarSeEstaAtivo()){
											$temPerguntas = true;
										}
									}
									$item['perguntas'] = array();
									if($temPerguntas){
										foreach($falta->getPergunta() as $pergunta){
											if($pergunta->verificarSeEstaAtivo()){
												$perguntaItem = array();
												$perguntaItem['id'] = $pergunta->getId();
												$perguntaItem['pergunta'] = $pergunta->getPergunta();
												$perguntaItem['r1'] = $pergunta->getR1();
												$perguntaItem['r2'] = $pergunta->getR2();
												$perguntaItem['r3'] = $pergunta->getR3();
												$perguntaItem['r4'] = $pergunta->getR4();
												$perguntaItem['certa'] = $pergunta->getCerta();
												$item['perguntas'][] = $perguntaItem;
											}
										}
									}else{
										$item['pergunta'] = false;
									}
									$faltas[] = $item;
								}
							}
							$usuario['faltas'] = $faltas;
							$usuario['links'] = $listaDeLinks;
							$dados['usuario'] = $usuario;
							$ok = true;
						}
					}else{
						$dados['message'] = 'Sua igreja não tem acesso!';
					}
					}
$dados['ok'] = $ok;
return $dados;
}

	public function vimeoAction() {
        $idVimeo = $this->params()->fromRoute('id');
		return new ViewModel(array('idVimeo' => $idVimeo));	
	}

	public function efetivarReposicaoAlunoAction() {
		$response = $this->getResponse();
		$request = $this->getRequest();
		$dados = array();
		$ok = false;
		if ($request->isPost()) {
			$body = $request->getContent();
			$json = Json::decode($body);

			$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId(intVal($json->aula_id));

			$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId(intVal($json->matricula));
			$turmaPessoaAula = $turmaPessoa->getTurmaPessoaAulaPorAula($aula->getId());
			if (!$turmaPessoaAula) {
				$turmaPessoaAula = new TurmaPessoaAula();
				$turmaPessoaAula->setAula($aula);
				$turmaPessoaAula->setTurma_pessoa($turmaPessoa);
			}
			$turmaPessoaAula->setData_inativacao(null);
			$turmaPessoaAula->setHora_inativacao(null);
			$turmaPessoaAula->setReposicao('S');
			$this->getRepositorio()->getTurmaPessoaAulaORM()->persistir($turmaPessoaAula);

			/* reposição */
			$turmaPessoaVisto = $turmaPessoa->getTurmaPessoaVistoPorAula($aula->getId());
			if (!$turmaPessoaVisto) {
				$turmaPessoaVisto = new TurmaPessoaVisto();
				$turmaPessoaVisto->setAula($aula);
				$turmaPessoaVisto->setTurma_pessoa($turmaPessoa);
			}
			$turmaPessoaVisto->setData_inativacao(null);
			$turmaPessoaVisto->setHora_inativacao(null);
			$this->getRepositorio()->getTurmaPessoaVistoORM()->persistir($turmaPessoaVisto);
			$ok = true;
		}
		$dados['ok'] = $ok;
		$response->getHeaders()
			->addHeaderLine('Access-Control-Allow-Origin', '*')
			->addHeaderLine('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')
			->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
			->addHeaderLine('Content-Type', 'application/json; charset=utf-8')
			->addHeaderLine('x-content-type-options', 'nosniff')
			->addHeaderLine('x-dns-prefetch-control', 'off')
			->addHeaderLine('x-download-options', 'noopen')
			->addHeaderLine('x-frame-options', 'SAMEORIGIN')
			->addHeaderLine('x-xss-protection', '1; mode=block');
		$response->setContent(Json::encode($dados));
		return $response;
	}
}
