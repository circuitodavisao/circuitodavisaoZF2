<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\HierarquiaForm;
use Application\Form\NovoEmailForm;
use Application\Form\NumeracaoForm;
use Application\Form\NomeForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\Curso;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\SolicitacaoTipo;
use Application\Controller\RelatorioController;
use Application\Controller\CursoController;
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

		/* dados pessoa logada */
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$grupo = $entidade->getGrupo();
		$grupoIgreja = $entidade->getGrupo()->getGrupoIgreja();
		$grupoLogado = $grupo;
		$pessoaLogada = $pessoa;

		$mostrarPrincipal = true;

		/* formulario sobre o discipulado do seu lider */
		if(date('m') == 1){
			$mesAnterior = 12;
			$anoAnterior = date('Y') - 1;
		}else{
			$mesAnterior = date('m') - 1;
			$anoAnterior = date('Y');
		}
		if($entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo() && $grupoPai = $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()){
			if($grupoPai->getGrupoEventoAtivosPorTipo(EventoTipo::tipoDiscipulado) && !$this->getRepositorio()->getFatoDiscipuladoORM()->encontrarPorGrupoPessoaMesAno($grupoPai->getId(), $sessao->idPessoa, $mesAnterior, $anoAnterior)){
				return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(Constantes::$ACTION => 'Discipulado'));
			}
		}
		if(!$pessoa->getData_nascimento() || !$pessoa->getSexo() || !$pessoa->getProfissao() || $pessoa->getEmail() == 'atualize' ){			
			return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN, array(Constantes::$ACTION => 'perfil'));			
		}
		if(!$pessoa->getCep()){
			return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN, array(Constantes::$ACTION => 'cep'));			
		}
		/* fim formulario */
		
		$vendoPessoaLogada = true;
		$grupoIdPessoaVerificada = null;

		/* verificando se estou vendo um discipulo abaixo e pegando os dados dele */
		if($sessao->idSessao > 0){
			$explodeIdSessao = explode('_', $sessao->idSessao);
			$vendoDiscipuloAbaixo = $explodeIdSessao[2];
			if($vendoDiscipuloAbaixo == 'vendoDiscipulosAbaixoPaginaPrincipal') {
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($explodeIdSessao[0]);
				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($explodeIdSessao[1]);
				$grupo = $entidade->getGrupo();
				if($entidade->getSecretario_Grupo_id()){
					$grupoIdPessoaVerificada = $entidade->getGrupo_id();
				}				
				unset($sessao->idSessao);

				$vendoPessoaLogada = false;
			}
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post_data = $request->getPost();
			$mes = $post_data['mes'];
			$pessoalOuEquipe = $post_data['pessoalOuEquipe'];
		}
		$selectedAtual = '';
		$selectedAnterior = '';
		if(empty($mes) || $mes == 1){ // atual
			$mes = date('m');
			$ano = date('Y');
			$selectedAtual = 'selected';
		}
		if($mes == 2){
			if(date('m') == 1){
				$mes = 12;
				$ano = date('Y') - 1;
			}else{
				$mes = date('m') - 1;
				$ano = date('Y');
			}
			$selectedAnterior = 'selected';
		}
	
		if (empty($pessoalOuEquipe)) {
			$pessoalOuEquipe = 2; // Trazer os dados da equipe
		}

		//$registros = $this->getRepositorio()->getRegistroORM()->encontrarUltimosRegistroPorIdGrupoELimite($grupo->getId(), 3);

		$dados = array(
			'grupoIdPessoaVerificada' => $grupoIdPessoaVerificada,
			'mostrarPrincipal' => $mostrarPrincipal,
			'grupo' => $grupo,
			'grupoLogado' => $grupoLogado,
			'pessoaLogada' => $pessoaLogada,
			'entidade' => $entidade,
			'pessoa' => $pessoa,
			'vendoPessoaLogada' => $vendoPessoaLogada,
			'pessoalOuEquipe' => $pessoalOuEquipe,
			'mes' => $mes,
			'ano' => $ano,
			'repositorio' => $this->getRepositorio(),
			'selectedAtual' => $selectedAtual,
			'selectedAnterior' => $selectedAnterior,
			'grupoIgreja' => $grupoIgreja->getId();
			//'registros' => $registros,
		);

//		if($relatorioDiscipulado = RelatorioController::relatorioDiscipulado($this->getRepositorio(), $entidade->getGrupo(), $mesAnterior, $anoAnterior)){
//			$dados['discipulado'] = $relatorioDiscipulado;
//		}

		$view = new ViewModel($dados);
		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-principal');
		$view->addChild($layoutJS, 'layoutJSPrincipal');

		return $view;
	}

	public static function montarRelatorioAlunos($repositorio, $grupo){
		$relatorio = array();
		/* alunos da igreja */
		$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
		if($relatorioCursosDesordenados = $repositorio->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador)){
			foreach($relatorioCursosDesordenados as $fatoCurso){
				$relatorio[$fatoCurso->getTurma_id()][$fatoCurso->getSituacao_id()]++;
				$relatorio['total'][$fatoCurso->getSituacao_id()]++;
			}
		}
		return $relatorio;
	}

	public function verAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$profissoes = $this->getRepositorio()->getProfissaoORM()->buscarTodosRegistrosEntidade('nome', 'asc');  		
		$grupo = $entidadeLogada->getGrupo();
		$grupoLogado = $grupo;
		$pessoaLogada = $pessoa;

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

			$listagemDeEventos = Array();			
			$listagemDeEventosCelulasNormais = $entidade->getGrupo()->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);
			foreach ($listagemDeEventosCelulasNormais as $celulasNormais) {
				$listagemDeEventos[] = $celulasNormais;
			}
			$listagemDeEventosCelulasEstrategicas = $entidade->getGrupo()->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelulaEstrategica);
			foreach ($listagemDeEventosCelulasEstrategicas as $celulasEstrategicas) {
				$listagemDeEventos[] = $celulasEstrategicas;
			}

			$dados = array();
			$dados['profissoes'] = $profissoes;
			$dados['idGrupo'] = $idSessao;
			$dados['entidade'] = $entidade;
			$dados['grupoLogado'] = $grupoLogado;
			$dados['grupo'] = $grupoSessao;
			$dados['pessoaLogada'] = $pessoaLogada;
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
		$explodeIdSessao = explode('_', $sessao->idSessao);
		$processar = false;
		$enviandoParaOMesmoEmail = $explodeIdSessao[1];		
		$request = $this->getRequest();
		if($enviandoParaOMesmoEmail || $request->isPost()){
			$processar = true;
		}
		if ($processar) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();
				if($enviandoParaOMesmoEmail){
					$idPessoa = $explodeIdSessao[0];		
				}
				if(!$enviandoParaOMesmoEmail){
					$idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
				}
				
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);

				if(!$enviandoParaOMesmoEmail){
					$pessoa->setEmail($post_data[Constantes::$INPUT_EMAIL]);
				}
				
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

				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL);
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
			if($pessoaLogada->getPessoaHierarquiaAtivo()){
				$hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas($pessoaLogada->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
			} else {				
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'index',
				));				
			}			
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
				if ($pessoa->getPessoaHierarquiaAtivo() && 
					$pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() != $post_data[Constantes::$FORM_HIERARQUIA]) {
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
				if(!$pessoa->getPessoaHierarquiaAtivo()){
					$pessoaHierarquia = new PessoaHierarquia();
					$pessoaHierarquia->setPessoa($pessoa);
					$novaHierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($post_data[Constantes::$FORM_HIERARQUIA]);
					$pessoaHierarquia->setHierarquia($novaHierarquia);
					$this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);

					$sessao->mostrarNotificacao = true;
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

	public function notasDeAtualizacoesAction() {
			$view = new ViewModel();
			return $view;
	}

	public function nomeAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idSessao = $sessao->idSessao;
		if ($idSessao) {
			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
			$formulario = new NomeForm(Constantes::$FORM, $grupo);
			$view = new ViewModel(
				array(
					'formulario' => $formulario,
					'grupo' => $grupo,
				));
			unset($sessao->idSessao);

			/* Javascript especifico */
			$layoutJS = new ViewModel();
			$layoutJS->setTemplate('layout/layout-js-principal-alterar-nome-equipe');
			$view->addChild($layoutJS, 'layoutJSAlterarNome');
			return $view;
		} else {
			return $this->redirect()->toRoute('principal');
		}
	}

	public function nomeSalvarAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();
				$idGrupo = $post_data[Constantes::$FORM_ID];
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
				$entidade = $grupo->getEntidadeAtiva();
				$entidadeNova = new Entidade();
				$entidadeNova->setEntidadeTipo($this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId($entidade->getEntidadeTipo()->getId()));
				$formNome = new NomeForm(Constantes::$FORM, $grupo);
				$formNome->setInputFilter($entidadeNova->getInputFilterAlterarNome());
				$formNome->setData($post_data);

				/* validação */				
				if ($formNome->isValid()) {					
					$validatedData = $formNome->getData();
					$nome = trim($validatedData[Constantes::$INPUT_NOME]);
					$sigla = trim($validatedData[Constantes::$INPUT_SIGLA]);					
					$entidadeNova->setGrupo($grupo);
					$entidadeNova->setNome($nome);
					$entidadeNova->setSigla($sigla);					
					$this->getRepositorio()->getEntidadeORM()->persistir($entidadeNova);
					
					$entidade->setDataEHoraDeInativacao();
					$setarDataEHora = false;
					$this->getRepositorio()->getEntidadeORM()->persistir($entidade, $setarDataEHora);

					$this->getRepositorio()->fecharTransacao();
					$sessao->idSessao = $idGrupo;
					return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
						Constantes::$ACTION => 'ver',
					));
				} else {
					return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN, array(
						Constantes::$ACTION => 'index',
					));
				}
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
			$layoutJS = new ViewModel();
			$layoutJS->setTemplate('layout/layout-js-principal-alterar-numeracao');
			$view->addChild($layoutJS, 'layoutJSAlterarNumeracao');
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
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidadeDaIgreja = null;
		$minhaEntidade = null;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== Entidade::COORDENACAO && $entidade->getEntidadeTipo()->getId() !== Entidade::REGIONAL){			
			$entidadeDaIgreja = $entidade->getGrupo()->getGrupoIgreja()->getEntidadeAtiva();
			$entidadeAcima =  $entidadeDaIgreja->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();
		}
		if($entidade->getEntidadeTipo()->getId() === Entidade::COORDENACAO){
			$minhaEntidade = 'COORDENAÇÃO ' . $entidade->getNumero() . ', ';
			$entidadeAcima =  $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();			
		}		
		if($entidade->getEntidadeTipo()->getId() === Entidade::REGIONAL){
			$minhaEntidade = 'REGIÃO ' . $entidade->getNome() . ', ';
			if($entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()){
				$entidadeAcima =  $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();	
			}			
		}

		if($entidade->getEntidadeTipo()->getId() === Entidade::EQUIPE){
			$minhaEntidade = 'EQUIPE: ' . $entidade->getNome() . ', ';
		}

		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$request = $this->getRequest();
		$dadosPost = array_merge_recursive(
			$request->getPost()->toArray(),
			$request->getFiles()->toArray()
		);
		$tipo = $dadosPost['tipo'];
		$prioridade = $dadosPost['prioridade'];
		$descricao = $dadosPost['descricao'];
		$anexo = $dadosPost['imagem'];
		$remetente['nome'] = $pessoa->getNomePrimeiroUltimo();
		$remetente['email'] = $pessoa->getEmail();
		$assunto = $dadosPost['assunto'] . ' :: ';		
		
		if($minhaEntidade){
			$assunto .= $minhaEntidade;
		}
		
		if($entidadeDaIgreja){
			$assunto .= 'IGREJA: ' .  $entidadeDaIgreja->getNome() . ', ';
		}	
		if($entidadeAcima){
			if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::COORDENACAO){                                  
				$nomeEntidadeAcimaArrumado = ' COORDENAÇÃO: ' . $entidadeAcima->getNumero() . ' RESPONSÁVEIS: ' .$entidadeAcima->getGrupo()->getNomeLideresAtivos();                    
			}  
			if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::REGIONAL){                                  
				$nomeEntidadeAcimaArrumado = ' REGIÃO: ' . $entidadeAcima->getNome();                    
			}
		}			
		 
		if($nomeEntidadeAcimaArrumado){
			$assunto .= ' NÍVEL ACIMA: ' . $nomeEntidadeAcimaArrumado;
		}

		$Subject = $assunto;		
		$ToEmail = 'suporte@circuitodavisaonovo.com.br';
		$Content = '<br />Tipo: '.$tipo.'
			<br />Prioridade: '.$prioridade.'
			<br />Login: '.$remetente['email'].'
			<br />Descricao: '.$descricao.'
			<br />IdPessoa: '.$pessoa->getId().'
			<br />IdGrupo (Responsabilidade [0]): '.$pessoa->getGrupoResponsavel()[0]->getGrupo()->getId();
		try{
			error_log('######## enviar email #########');
			Funcoes::enviarEmail($ToEmail, $Subject, $Content, $remetente, $anexo);
		}catch(Exception $exc){
			error_log($exc->getMessage());
		}
		return $this->redirect()->toRoute('principal', array(
			Constantes::$ACTION => 'suporteFinalizado',
		));
	}

	public function suporteFinalizadoAction() {
		return new ViewModel();
	}

	public function semAcessoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$mensagem = $sessao->mensagemSemAcesso;		
		$corDoTexto = $sessao->corDoTexto;	
		unset($sessao->mensagemSemAcesso);	
		unset($sessao->corDoTexto);
		$dados = array();
		if(!$mensagem){
			$mensagem = 'Você não tem acesso <i class = "fa fa-thumbs-up text-danger"></i>';
		}		
		if(!$corDoTexto){
			$corDoTexto = 'text-danger';
		}		
		$dados['corDoTexto'] = $corDoTexto;
		$dados['mensagem'] = $mensagem;
		return new ViewModel($dados);
	}

	public function arrumarFatoLiderAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idSessao = $sessao->idSessao;
		if ($idSessao) {
			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
			unset($sessao->idSessao);
			$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
			if ($fatoLider = $this->getRepositorio()->getFatoLiderORM()->encontrarFatoLiderPorNumeroIdentificador($numeroIdentificador)) {
				$fatoLider->setLideres(0);
				$this->getRepositorio()->getFatoLiderORM()->persistir($fatoLider, $alterarDataDeCriacao = false);
			}
			return $this->redirect()->toRoute('principal');
		}
	}

	public function arrumarLinhaDeLancamentoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idSessao = $sessao->idSessao;
		$html = '';
		if ($idSessao) {
			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
			unset($sessao->idSessao);
			if($grupoResponsaveis = $pessoa->getGrupoResponsavel()){
				$grupoResponsanvelAtivo = null;
				$grupoResponsanvelInativoParaPegarGrupoPessoa = null;
				foreach($grupoResponsaveis as $grupoResponsavel){
					if($grupoResponsavel->verificarSeEstaAtivo()){
						$grupoResponsanvelAtivo = $grupoResponsavel;
					}
					if(!$grupoResponsavel->verificarSeEstaAtivo()){
						if($grupoResponsanvelInativoParaPegarGrupoPessoa === null){
							$grupoResponsanvelInativoParaPegarGrupoPessoa = $grupoResponsavel;
						}else{
							if($grupoResponsanvel->getId() > $grupoResponsanvelInativoParaPegarGrupoPessoa->getId()){
								$grupoResponsanvelInativoParaPegarGrupoPessoa = $grupoResponsavel;
							}
						}
					}
				}
				if(count($grupoResponsanvelAtivo->getGrupo()->getGrupoPessoasNoPeriodo($periodo = 0, $this->getRepositorio())) < 5){
					if($linhaDeLancamento = $grupoResponsanvelInativoParaPegarGrupoPessoa->getGrupo()->getGrupoPessoasNoPeriodo($periodo = 0, $this->getRepositorio())){
						foreach($linhaDeLancamento as $grupoPessoa){
							$grupoPessoaNovo = new GrupoPessoa();
							$grupoPessoaNovo->setGrupo($grupoResponsanvelAtivo->getGrupo());
							$grupoPessoaNovo->setPessoa($grupoPessoa->getPessoa());
							$grupoPessoaNovo->setGrupoPessoaTipo($grupoPessoa->getGrupoPessoaTipo());
							$grupoPessoaNovo->setDataEHoraDeCriacao($grupoResponsanvelAtivo->getGrupo()->getData_criacaoStringPadraoBanco());
							$this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoaNovo);
						}
					}
				}
			}
			return $this->redirect()->toRoute('principal');
		}
	}

	public function buscarDadosPrincipaisAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial){
					$resultado = RelatorioController::buscarDadosPrincipais($this->getRepositorio(), $grupo, $json->mes, $json->ano, $json->pessoalOuEquipe);
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
	
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDadosPrincipaisDiscipuladoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$resultado = RelatorioController::buscarDadosPrincipaisDiscipulado($this->getRepositorio(), $grupo, $json->mes, $json->ano);
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDadosPrincipaisMembresiaAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$resultado = RelatorioController::buscarDadosPrincipaisMembresia($this->getRepositorio(), $grupo, $json->mes, $json->ano, $json->pessoalOuEquipe);
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDadosPrincipaisCelulaAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$resultado = RelatorioController::buscarDadosPrincipaisCelula($this->getRepositorio(), $grupo, $json->mes, $json->ano, $json->pessoalOuEquipe);
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDadosPrincipaisInstitutoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$resultado = RelatorioController::buscarDadosPrincipaisInstituto($this->getRepositorio(), $grupo, $json->mes, $json->ano, $json->pessoalOuEquipe);
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDadosPrincipaisMeuTimeAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$html = '';
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo = 1);
				if ($grupoPaiFilhoFilhos) {
					$discipulos = array();
					foreach ($grupoPaiFilhoFilhos as $gpFilho) {
						$discipulos[] = $gpFilho;
					}

					foreach($discipulos as $discipulo){
						$grupoFilho = $discipulo->getGrupoPaiFilhoFilho();
						$entidade = $grupoFilho->getEntidadeAtiva();
						foreach($grupoFilho->getPessoasAtivas() as $pessoa){
							$vendoDiscipulosAbaixo = 'vendoDiscipulosAbaixoPaginaPrincipal';
							$idSessao = $pessoa->getId() . '_' . $entidade->getId() . '_' . $vendoDiscipulosAbaixo;
							$funcaoOnClick = 'mostrarSplash(); funcaoCircuito("principal", "'.$idSessao.'")';
							$nomeHierarquia = 'Sem Hierarquia';
							if($pessoa->getPessoaHierarquiaAtivo()){
								$nomeHierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
								if ($pessoa->getSexo() === 'F') {
									if ($nomeFeminino = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome_feminino()) {
										$nomeHierarquia = $nomeFeminino;
									}
								}
							}
							$html .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p10 text-center">';
							$html .= '<img src="/img/fotos/'.$pessoa->getFoto().'" style="width:75px;height:75px;" class="user-avatar" width="128px" height="128px" onClick=\''. $funcaoOnClick .'\' style="cursor: pointer; margin:auto;">';
							$html .= '<div class="caption">';
							$html .= '<h5>'.$pessoa->getNomePrimeiro();

							if($pessoa->getTelefone()){
								$telefone = '<a id="linkWhatsapp_'.$pessoa->getId().'" class="btn btn-success btn-xs" href="https://api.whatsapp.com/send?phone=55'.$pessoa->getTelefone().'"><i class="fa fa-whatsapp"></i></a>';
							}else{
								$telefone = '<span class="label label-warning" data-placement="bottom" data-toggle="popover" data-content="Sem Telefone" style="cursor: pointer;"><i class="fa fa-warning"></i></span>';
							}
							$html .= '&nbsp;' . $telefone;
							$html .= '<br />';
							$html .= '<small>'.$nomeHierarquia. '<small>';
							$html .= '<br /><small>'.$grupoFilho->getEntidadeAtiva()->infoEntidade(). '<small>';
							/* pegando o ultimo registro de acesso */
							$ultimoAcesso = 'Nunca acessou';
							$cor = 'danger';
							if($registro = $this->getRepositorio()->getRegistroORM()->encontrarUltimoRegistroDeLogin($grupoFilho->getId())){
								$ultimoAcesso = $registro->getData_criacaoStringPadraoBrasil();
								$cor = 'success';

								$data1 = date('Y-m-d');
								$data2 = $registro->getData_criacaoStringPadraoBanco();
								$d1 = strtotime($data1); 
								$d2 = strtotime($data2);
								$dataFinal = ($d2 - $d1) /86400;
								if($dataFinal < 0){
									$dataFinal = $dataFinal * -1;
								}
								if($dataFinal > 7){
									$cor = 'warning';
								}
							}
							$html .= '<br /><span class="text text-'.$cor.'">Acesso: ' .$ultimoAcesso. '<span>';
							$html .= '</h5>';
							$html .= '</div>';
							$html .= '</div>';
						}
					}
				}

				$dados['html'] = $html;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarTimesAction(){
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

	public function buscarLideresSolicitacaoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dadosFinal = array();
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$idTipoSolicitacao = $json->idSolicitacaoTipo;
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$entidade = $grupo->getEntidadeAtiva();

				$discipulos = '<option value="0">SELECIONE</option>';
				$nomeLideres = $grupo->getNomeLideresAtivos();
				$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
				$disabled = '';
				if($idTipoSolicitacao === SolicitacaoTipo::TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE || 
					$idTipoSolicitacao === SolicitacaoTipo::TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE ||
					$idTipoSolicitacao === SolicitacaoTipo::REMOVER_LIDER){
						$disabled = 'disabled="disabled"';
					}

				if(
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao || 
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao 
				){
					$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
					foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
						$disabled = '';
						$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();
						if($grupo->verificarSeEstaAtivo()){
							if($entidade12 = $grupo12->getEntidadeAtiva()){
								$nomeLideres = $grupo12->getNomeLideresAtivos();
								$informacao = $entidade12->getEntidadeTipo()->getNome() .' '. $entidade12->infoEntidade() . ' - ' . $nomeLideres;
								$disabled = 'disabled="disabled"';
								$discipulos .= '<option class="" '.$disabled.' value="' . $grupo12->getId() . '">' . $informacao . '</option>';

								if ($grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal()) {
									foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
										$disabled = '';
										$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();
										if ($grupo144->verificarSeEstaAtivo()) {
											$nomeLideres = $grupo144->getNomeLideresAtivos();
											$informacao = '|--> '.$grupo144->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
											if($grupo144->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::equipe){
												$disabled = 'disabled="disabled"';
											}
											$discipulos .= '<option '.$disabled.' class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo144->getId() . '_1">' . $informacao . '</option>';

											if ($grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal()) {
												foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
													$disabled = '';
													$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();
													if ($grupo1728->verificarSeEstaAtivo()) {
														$nomeLideres = $grupo1728->getNomeLideresAtivos();
														$informacao = '|-->|--> '.$grupo1728->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
														if($grupo1728->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::equipe){
															$disabled = 'disabled="disabled"';
														}
														$discipulos .= '<option '.$disabled.' class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . ' grupo' . $grupo1728->getId() . '" value="' . $grupo1728->getId() . '_1">' . $informacao . '</option>';

													}
												}
											}

										}
									}
								}
							}
						}
					}
				}
	
				if(
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja || 
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe || 
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe
				){
					$discipulos .= '<option class="grupoLogado" '.$disabled.' value="' . $grupo->getId() . '">' . $informacao . '</option>';
					$grupoIgreja = $grupo->getGrupoIgreja();
					$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
					$grupoPaiFilhoEquipes = $grupoIgreja->getGrupoPaiFilhoFilhosAtivosReal();
					$equipes = '<option value="0">SELECIONE</option>';
					foreach($grupoPaiFilhoEquipes as $grupoPaiFilhoEquipe){
						$grupoEquipe = $grupoPaiFilhoEquipe->getGrupoPaiFilhoFilho();
						if($grupoEquipe->getId() !== $grupo->getId()){
							$nomeLideres = $grupoEquipe->getNomeLideresAtivos();
							$informacao = $grupoEquipe->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
							$equipes .= '<option value="' . $grupoEquipe->getId() . '">' . $informacao . '</option>';
						}
					}

					$homens = '<option value="0">SELECIONE</option>';
					$mulheres = '<option value="0">SELECIONE</option>';
					$casais = '<option value="0">SELECIONE</option>';
					foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
						$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();
						if ($grupo12->verificarSeEstaAtivo()) {
							$nomeLideres = $grupo12->getNomeLideresAtivos();
							$informacao = $grupo12->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
							$discipulos .= '<option class="lider grupoEquipe grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().'" value="' . $grupo12->getId() . '">' . $informacao . '</option>';

							if (!$grupo12->verificaSeECasal()) {
								if ($grupo12->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'M') {
									$homens .= '<option id="homem'.$grupo12->getId().'" class="'.$grupo->getId().'" value="' . $grupo12->getId() . '">' . $informacao . '</option>';
								}
								if ($grupo12->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'F') {
									$mulheres .= '<option class="mulheres '.$grupo->getId().'" value="' . $grupo12->getId() . '">' . $informacao . '</option>';
								}
							} else {
								$casais .= '<option value="' . $grupo12->getId() . '">' . $informacao . '</option>';
							}
						}
						if ($grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal()) {
							foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
								$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();
								if ($grupo144->verificarSeEstaAtivo()) {
									$nomeLideres = $grupo144->getNomeLideresAtivos();
									$informacao = $grupo144->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
									$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo144->getId() . '">' . $informacao . '</option>';
									if (!$grupo144->verificaSeECasal()) {
										if ($grupo144->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'M') {
											$homens .= '<option id="homem'.$grupo144->getId().'" class="'.$grupo12->getId().'"  value="' . $grupo144->getId() . '">' . $informacao . '</option>';
										}
										if ($grupo144->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'F') {
											$mulheres .= '<option class="mulheres '.$grupo12->getId().'" value="' . $grupo144->getId() . '">' . $informacao . '</option>';
										}
									} else {
										$casais .= '<option value="' . $grupo144->getId() . '">' . $informacao . '</option>';
									}
								}
								if ($grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal()) {
									foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
										$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();
										if ($grupo1728->verificarSeEstaAtivo()) {
											$nomeLideres = $grupo1728->getNomeLideresAtivos();
											$informacao = $grupo1728->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
											$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';
											if (!$grupo1728->verificaSeECasal()) {																																					
												if ($grupo1728->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'M') {
													$homens .= '<option id="homem'.$grupo1728->getId().'" class="'.$grupo144->getId().'" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';
												}
												if ($grupo1728->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'F') {
													$mulheres .= '<option class="mulheres '.$grupo144->getId().'" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';
												}
											} else {
												$casais .= '<option value="' . $grupo1728->getId() . '">' . $informacao . '</option>';
											}
										}

										if ($grupoPaiFilhoFilhos20736 = $grupo1728->getGrupoPaiFilhoFilhosAtivosReal()) {
											foreach ($grupoPaiFilhoFilhos20736 as $grupoPaiFilhoFilho20736) {
												$grupo20736 = $grupoPaiFilhoFilho20736->getGrupoPaiFilhoFilho();
												if ($grupo20736->verificarSeEstaAtivo()) {
													$nomeLideres = $grupo20736->getNomeLideresAtivos();
													$informacao = $grupo20736->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
													$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . ' grupo' . $grupo1728->getId() . '" value="' . $grupo20736->getId() . '">' . $informacao . '</option>';

													if (!$grupo20736->verificaSeECasal()) {	

														if ($grupo20736->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'M') {
															$homens .= '<option id="homem'.$grupo20736->getId().'" class="'.$grupo1728->getId().'" value="' . $grupo20736->getId() . '">' . $informacao . '</option>';
														}
														if ($grupo20736->getGrupoResponsavelAtivo()->getPessoa()->getSexo() == 'F') {
															$mulheres .= '<option class="mulheres '.$grupo1728->getId().'" value="' . $grupo20736->getId() . '">' . $informacao . '</option>';
														}
													} else {
														$casais .= '<option value="' . $grupo20736->getId() . '">' . $informacao . '</option>';
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$resultado['discipulos'] = $discipulos;
				$resultado['equipes'] = $equipes;
				$resultado['homens'] = $homens;
				$resultado['mulheres'] = $mulheres;
				$resultado['casais'] = $casais;

				$dadosFinal['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dadosFinal['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dadosFinal));
		return $response;
	}

	public function buscarLideresSolicitacaoIgrejaAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dadosFinal = array();
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$entidade = $grupo->getEntidadeAtiva();

				$discipulos = '<option value="0">SELECIONE</option>';
				$nomeLideres = $grupo->getNomeLideresAtivos();
				$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
				$disabled = '';

				if(
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao || 
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao 
				){
					$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
					$disabled = '';
					foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
						$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();
						if($grupo->verificarSeEstaAtivo()){
							if($entidade12 = $grupo12->getEntidadeAtiva()){
								$nomeLideres = $grupo12->getNomeLideresAtivos();
								$informacao = $entidade12->getEntidadeTipo()->getNome() .' '. $entidade12->infoEntidade() . ' - ' . $nomeLideres;
								if($grupo12->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
									$disabled = 'disabled="disabled"';
								}
								$discipulos .= '<option class="" '.$disabled.' value="' . $grupo12->getId() . '">' . $informacao . '</option>';

								if($grupo12->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
									if ($grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal()) {
										$disabled = '';
										foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
											$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();
											if ($grupo144->verificarSeEstaAtivo()) {
												$nomeLideres = $grupo144->getNomeLideresAtivos();
												$informacao = '|--> '.$grupo144->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
												if($grupo144->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
													$disabled = 'disabled="disabled"';
												}
												$discipulos .= '<option '.$disabled.' class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo144->getId() . '">' . $informacao . '</option>';

												if ($grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal()) {
													foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
														$disabled = '';
														$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();
														if ($grupo1728->verificarSeEstaAtivo()) {
															$nomeLideres = $grupo1728->getNomeLideresAtivos();
															$informacao = '|-->|--> '.$grupo1728->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
															if($grupo1728->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
																$disabled = 'disabled="disabled"';
															}
															$discipulos .= '<option '.$disabled.' class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . ' grupo' . $grupo1728->getId() . '" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';

														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
	
				$resultado['discipulos'] = $discipulos;

				$dadosFinal['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dadosFinal['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dadosFinal));
		return $response;
	}

	public function buscarAlunosSolicitacaoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
				$relatorioInicial = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador);

				$alunos = '<option value="0">SELECIONE</option>';
				foreach($relatorioInicial as $relatorio){
					$aluno = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($relatorio->getTurma_pessoa_id());
					if($aluno->verificarSeEstaAtivo() && $aluno->getTurma()->verificarSeEstaAtivo()){
						$grupoPessoaAtivo = $aluno->getPessoa()->getGrupoPessoaAtivo();
						$pessoa = $aluno->getPessoa();
						$nomeEquipe = CursoController::nomeEquipeTurmaPessoa($aluno, $grupoPessoaAtivo);
						$alunos .= '<option  value="' . $aluno->getId() . '" >' . $aluno->getId() . ' - ' . $nomeEquipe . ' - '.  $pessoa->getNome() . '</option>';
					}
				}
				$resultado['alunos'] = $alunos;
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function buscarDiscipulosIgrejaSolicitacaoAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		$resultado = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);

				$discipulos = '<option value="0">SELECIONE</option>';
				$grupoIgreja = $grupo->getGrupoIgreja();
				$grupoPaiFilhoFilhos = $grupoIgreja->getGrupoPaiFilhoFilhosAtivosReal();
				foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
					$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();
					if ($grupo12->verificarSeEstaAtivo()) {
						$nomeLideres = $grupo12->getNomeLideresAtivos();
						$informacao = $grupo12->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
						$discipulos .= '<option class="lider grupoEquipe grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().'" value="' . $grupo12->getId() . '">' . $informacao . '</option>';
					}
					if ($grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal()) {
						foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
							$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();
							if ($grupo144->verificarSeEstaAtivo()) {
								$nomeLideres = $grupo144->getNomeLideresAtivos();
								$informacao = $grupo144->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
								$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo144->getId() . '">' . $informacao . '</option>';
							}
							if ($grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal()) {
								foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
									$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();
									if ($grupo1728->verificarSeEstaAtivo()) {
										$nomeLideres = $grupo1728->getNomeLideresAtivos();
										$informacao = $grupo1728->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
										$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . '" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';
									}

									if ($grupoPaiFilhoFilhos20736 = $grupo1728->getGrupoPaiFilhoFilhosAtivosReal()) {
										foreach ($grupoPaiFilhoFilhos20736 as $grupoPaiFilhoFilho20736) {
											$grupo20736 = $grupoPaiFilhoFilho20736->getGrupoPaiFilhoFilho();
											if ($grupo20736->verificarSeEstaAtivo()) {
												$nomeLideres = $grupo20736->getNomeLideresAtivos();
												$informacao = $grupo20736->getEntidadeAtiva()->infoEntidade() . ' - ' . $nomeLideres;
												$discipulos .= '<option class="lider grupo' . $grupo->getId() . ' grupo'.$grupo12->getId().' grupo' . $grupo144->getId() . ' grupo' . $grupo1728->getId() . '" value="' . $grupo1728->getId() . '">' . $informacao . '</option>';

											}
										}
									}
								}
							}
						}
					}
				}

				$resultado['discipulos'] = $discipulos;

				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}
}
