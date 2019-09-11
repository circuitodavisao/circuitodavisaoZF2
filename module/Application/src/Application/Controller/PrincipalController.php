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
use Application\Controller\RelatorioController;
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
		if(!$pessoa->getData_nascimento() || !$pessoa->getSexo() || !$pessoa->getProfissao()){			
			return $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN, array(Constantes::$ACTION => 'perfil'));			
		}
		/* fim formulario */
		
		$vendoPessoaLogada = true;
		$mesFinal = date('m');
		$anoFinal = date('Y');
		if($mesFinal == 1){
			$mesInicial = 12;
			$anoInicial = $anoFinal - 1;
		}else{
			$mesInicial = $mesFinal - 1;
			$anoInicial = $anoFinal;
		}

		if (!$entidade->verificarSeEstaAtivo()) {
			$mostrarPrincipal = false;
		}

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
			$ano = $post_data['ano'];
			$pessoalOuEquipe = $post_data['pessoalOuEquipe'];
		}
		if (empty($mes)) {
			$mes = date('m');
		}
		if (empty($ano)) {
			$ano = date('Y');
		}
		if (empty($pessoalOuEquipe)) {
			$pessoalOuEquipe = 2; // Trazer os dados da equipe
		}

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
			'mesInicial' => $mesInicial,
			'anoInicial' => $anoInicial,
			'mesFinal' => $mesFinal,
			'anoFinal' => $anoFinal,
		);

		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo = 1);
		if ($grupoPaiFilhoFilhos) {
			$discipulos = array();
			foreach ($grupoPaiFilhoFilhos as $gpFilho) {
				$discipulos[] = $gpFilho;
			}
			$dados['discipulos'] = $discipulos;
		}

		if($relatorioDiscipulado = RelatorioController::relatorioDiscipulado($this->getRepositorio(), $entidade->getGrupo(), $mesAnterior, $anoAnterior)){
			$dados['discipulado'] = $relatorioDiscipulado;
		}

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
		$ToEmail = 'support@circuitodavisao.zendesk.com';
		$Content = 'Tipo: '.$tipo.'
			Prioridade: '.$prioridade.'
			Login: '.$remetente['email'].'
			Descricao: '.$descricao.'
			IdPessoa: '.$pessoa->getId().'
			IdGrupo (Responsabilidade [0]): '.$pessoa->getGrupoResponsavel()[0]->getGrupo()->getId();
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
				$resultado = RelatorioController::buscarDadosPrincipais($this->getRepositorio(), $grupo, $json->mes, $json->ano, $json->pessoalOuEquipe);
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

}
