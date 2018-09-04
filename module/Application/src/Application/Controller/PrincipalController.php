<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\HierarquiaForm;
use Application\Form\NovoEmailForm;
use Application\Form\NumeracaoForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\Situacao;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: PrincipalController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class PrincipalController extends CircuitoController {

    /**
     * Função padrão, traz a tela principal
     * GET /principal
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $grupo = $entidade->getGrupo();

		if($grupo->getId() !== 1 && $grupo->getId() !== 1225){
			$eCasal = $grupo->verificaSeECasal();
			$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno(date('m'), date('Y'));
			$relatorio = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, RelatorioController::relatorioMembresiaECelula, date('m'), date('Y'));

			$mesAnterior = date('m') - 1;
			$anoAnterior = date('Y');
			if (date('m') == 1) {
				$mesAnterior = 12;
				$anoAnterior = date('Y') - 1;
			}

			$mostrarPrincipal = true;
			if (!$entidade->verificarSeEstaAtivo()) {
				$mostrarPrincipal = false;
			}

			if ($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$turmas = $grupo->getTurma();
			} else {
				$turmas = $grupo->getGrupoIgreja()->getTurma();
			}

			$hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();
		}

        $dados = array(
            'relatorio' => $relatorio,
            'periodoInicial' => $arrayPeriodoDoMes[0],
            'periodoFinal' => $arrayPeriodoDoMes[1],
            'mostrarPrincipal' => $mostrarPrincipal,
            'eCasal' => $eCasal,
            'grupo' => $grupo,
            'hierarquias' => $hierarquias,
            'repositorio' => $this->getRepositorio(),
            'pessoa' => $pessoa,
            'turmas' => $turmas,
        );

        $tipoRelatorioPessoal = 1;
        $periodo = -1;

        $relatorioCelulas = array();
        $relatorioCelulas[$grupo->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupo, $periodo);
        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $discipulos[] = $gpFilho;
            }
            $dados['discipulos'] = $discipulos;
        }

        $view = new ViewModel($dados);
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate('layout/layout-js-principal');
        $view->addChild($layoutJS, 'layoutJSPrincipal');

        if ($sessao->jaMostreiANotificacao) {
            unset($sessao->mostrarNotificacao);
            unset($sessao->nomePessoa);
            unset($sessao->exclusao);
            unset($sessao->jaMostreiANotificacao);
        }

        return $view;
    }

    public function verAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        unset($sessao->idSessao);
        if ($idSessao) {

            $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

            $mostrarParaReenviarEmails = false;
            foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                $pessoaSelecionada = $grupoResponsavel->getPessoa();
                if ($pessoaSelecionada->getToken()) {
                    $mostrarParaReenviarEmails = true;
                }
            }

            $entidade = $grupoSessao->getEntidadeAtiva();
            $entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
            $listagemDeEventos = $entidade->getGrupo()->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);

            $dados = array();
            $dados['idGrupo'] = $idSessao;
            $dados['entidade'] = $entidade;
            $dados['idEntidadeTipo'] = $entidadeLogada->getTipo_id();
            $dados['mostrarParaReenviarEmails'] = $mostrarParaReenviarEmails;
            $dados['responsabilidades'] = $grupoSessao->getResponsabilidadesAtivas();
            $dados[Constantes::$LISTAGEM_EVENTOS] = $listagemDeEventos;
            $dados[Constantes::$TIPO_EVENTO] = EventoTipo::tipoCelula;

            return new ViewModel($dados);
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function grupoExclusaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        try {
            $this->getRepositorio()->iniciarTransacao();
            $idSessao = $sessao->idSessao;
            unset($sessao->idSessao);
            if ($idSessao) {

                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

                $dados = array();
                $dados['idGrupo'] = $idSessao;
                $dados['entidade'] = $grupoSessao->getEntidadeAtiva();
                $dados[Constantes::$EXTRA] = null;

                $view = new ViewModel($dados);
                /* Javascript */
                $layoutJS = new ViewModel();
                $layoutJS->setTemplate('layout/layout-js-exclusao');
                $view->addChild($layoutJS, 'layoutJSExclusao');

                return $view;
            } else {
                return $this->redirect()->toRoute('principal');
            }
            $this->getRepositorio()->fecharTransacao();
        } catch (Exception $exc) {
            $this->getRepositorio()->desfazerTransacao();
            echo $exc->getTraceAsString();
            $this->direcionaErroDeCadastro($exc->getMessage());
            CircuitoController::direcionandoAoLogin($this);
        }
    }

    public function novoEmailParaEnviarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $form = new NovoEmailForm(Constantes::$FORM, $idSessao);

            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);

            $view = new ViewModel(
                    array(
                Constantes::$FORM => $form,
                'nome' => $pessoa->getNome(),
            ));
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate('layout/layout-js-enviar-email');
            $view->addChild($layoutJS, 'layoutJSEnviarEmail');
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function enviarEmailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setEmail($post_data[Constantes::$INPUT_EMAIL]);
                $setarDataEHora = false;
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);
                if ($pessoa->getToken()) {
                    CadastroController::enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $pessoa->getToken(), $pessoa);
                }
                $sessao->mostrarNotificacao = true;
                $sessao->emailEnviado = true;
                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function emailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $form = new NovoEmailForm(Constantes::$FORM, $idSessao);
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $view = new ViewModel(
                    array(
                Constantes::$FORM => $form,
                'pessoa' => $pessoa,
            ));
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate('layout/layout-js-enviar-email');
            $view->addChild($layoutJS, 'layoutJSEnviarEmail');
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function emailSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $email = $post_data[Constantes::$INPUT_EMAIL];
                $setarDataEHora = false;

                /* caso algum inativo esteja usando o email remover dele */
                if ($pessoaPesquisada = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email)) {
                    $pessoaPesquisada->setEmail('');
                    $this->getRepositorio()->getPessoaORM()->persistir($pessoaPesquisada, $setarDataEHora);
                }

                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setEmail($email);
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);
                $sessao->mostrarNotificacao = true;
                $sessao->emailAlterado = true;
                $this->getRepositorio()->fecharTransacao();

                $sessao->idSessao = $pessoa->getResponsabilidadesAtivas()[0]->getGrupo()->getId();
                return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                            Constantes::$ACTION => 'ver',
                ));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function hierarquiaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
            $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas($pessoaLogada->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
            $formulario = new HierarquiaForm(Constantes::$FORM, $pessoa, $hierarquias);
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'pessoa' => $pessoa,
            ));
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function hierarquiaSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() != $post_data[Constantes::$FORM_HIERARQUIA]) {
                    $setarDataEHora = false;
                    $pessoaHierarquiaAtivo = $pessoa->getPessoaHierarquiaAtivo();
                    $pessoaHierarquiaAtivo->setDataEHoraDeInativacao();
                    $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquiaAtivo, $setarDataEHora);

                    $pessoaHierarquia = new PessoaHierarquia();
                    $pessoaHierarquia->setPessoa($pessoa);
                    $novaHierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($post_data[Constantes::$FORM_HIERARQUIA]);
                    $pessoaHierarquia->setHierarquia($novaHierarquia);
                    $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);

                    $sessao->mostrarNotificacao = true;
                    $sessao->hierarquiaAlterada = true;
                }
                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function senhaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $formulario = new RecuperarSenhaForm(Constantes::$FORM, $pessoa->getId());
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'pessoa' => $pessoa,
            ));
            unset($sessao->idSessao);

            /* Javascript especifico */
            $layoutJSIndex = new ViewModel();
            $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_SENHA);
            $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_SENHA);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function senhaSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setSenha($post_data[Constantes::$INPUT_SENHA]);
                $setarDataEHora = false;
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);

                $Subject = 'Dados de Acesso ao CV';
                $ToEmail = $pessoa->getEmail();
                $Content = '<pre>Olá</pre><pre>Seu usuário é: ' . $pessoa->getEmail() . '</pre><pre>Sua Senha é: ' . $post_data[Constantes::$INPUT_SENHA] . '</pre>';
                Funcoes::enviarEmail($ToEmail, $Subject, $Content);

                $sessao->mostrarNotificacao = true;
                $sessao->senhaAlterada = true;

                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function numeracaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
            $grupoPai = $grupo->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
            $formulario = new NumeracaoForm(Constantes::$FORM, $grupoPai, $grupo);
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'grupo' => $grupo,
            ));
            unset($sessao->idSessao);

            /* Javascript especifico */
            $layoutJSIndex = new ViewModel();
            $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_SENHA);
            $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_SENHA);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function numeracaoSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idGrupo = $post_data[Constantes::$FORM_ID];
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
                $numeracao = $post_data[Constantes::$FORM_NUMERACAO];

                $entidade = $grupo->getEntidadeAtiva();
                if ($numeracao != $entidade->getNumero()) {
                    $entidade->setDataEHoraDeInativacao();
                    $setarDataEHora = false;
                    $this->getRepositorio()->getEntidadeORM()->persistir($entidade, $setarDataEHora);
                    $entidadeNova = new Entidade();
                    $entidadeNova->setGrupo($grupo);
                    $entidadeNova->setNumero($numeracao);
                    $entidadeNova->setEntidadeTipo($this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
                    $this->getRepositorio()->getEntidadeORM()->persistir($entidadeNova);
                }
                $this->getRepositorio()->fecharTransacao();
                $sessao->idSessao = $idGrupo;
                return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                            Constantes::$ACTION => 'ver',
                ));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    /**
     * Controle de funçoes da tela de cadastro
     * @return Json
     */
    public function funcoesAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $funcao = $post_data[Constantes::$FUNCAO];
                $id = $post_data[Constantes::$ID];
                $sessao->idSessao = $id;
                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'tipoDeRetorno' => 1,
                                    'url' => '/' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        return $response;
    }

    public function suporteAction() {
      return new ViewModel();
    }

    public function suporteFinalizarAction() {
      $sessao = new Container(Constantes::$NOME_APLICACAO);
      $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
      $request = $this->getRequest();
      $dadosPost = array_merge_recursive(
           $request->getPost()->toArray(),
           $request->getFiles()->toArray()
       );
      $tipo = $dadosPost['tipo'];
      $prioridade = $dadosPost['prioridade'];
      $descricao = $dadosPost['descricao'];

      error_log(print_r($dadosPost, true));
      $anexo = $dadosPost['imagem'];
      $remetente['nome'] = $pessoa->getNomePrimeiroUltimo();
      $remetente['email'] = $pessoa->getEmail();
      $Subject = $dadosPost['assunto'].' :: '.$remetente['nome'].' ID('.$sessao->idPessoa.')';
  		$ToEmail = 'support@circuitodavisao.zendesk.com';
      $Content = 'Tipo: '.$tipo.'
                  Prioridade: '.$prioridade.'
                  Login: '.$remetente['email'].'
                  Descricao: '.$descricao;
  		Funcoes::enviarEmail($ToEmail, $Subject, $Content, $remetente, $anexo);
      return $this->redirect()->toRoute('principal', array(
			Constantes::$ACTION => 'suporteFinalizado',
			));
  	}

    public function suporteFinalizadoAction() {
      return new ViewModel();
    }

}
