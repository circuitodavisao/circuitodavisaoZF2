<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\AulaForm;
use Application\Form\CursoForm;
use Application\Form\CursoUsuarioForm;
use Application\Form\DisciplinaForm;
use Application\Form\ReentradaDeAlunoForm;
use Application\Form\SelecionarAlunosForm;
use Application\Form\SelecionarCarterinhasForm;
use Application\Form\TurmaForm;
use Application\Model\Entity\Aula;
use Application\Model\Entity\Curso;
use Application\Model\Entity\Disciplina;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\PessoaCursoAcesso;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\Turma;
use Application\Model\Entity\TurmaAula;
use Application\Model\Entity\TurmaPessoa;
use Application\Model\Entity\TurmaPessoaAula;
use Application\Model\Entity\TurmaPessoaAvaliacao;
use Application\Model\Entity\TurmaPessoaFinanceiro;
use Application\Model\Entity\TurmaPessoaSituacao;
use Application\Model\Entity\TurmaPessoaVisto;
use Application\Model\Entity\CursoAcesso;
use Application\Model\Entity\FatoCurso;
use Application\Model\Entity\RegistroAcao;
use Application\Model\ORM\RepositorioORM;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: CursoController.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Controle de todas ações do instituto de vencedores
 */
class CursoController extends CircuitoController {

	/**
	 * [cursoListarAction description]
	 * @method cursoListarAction
	 * @return [type]            [description]
	 */
	public function cursoListarAction() {

		$cursos = $this->getRepositorio()->getCursoORM()->buscarTodosRegistrosEntidade();
		$view = new ViewModel(array(
			'cursos' => $cursos,
		));

		return $view;
	}

	/*
	 * Função de retornar formulario de cadastro de cursos
	 */

	public function cursoFormAction() {
		$formCadastroCurso = new CursoForm('formulario');
		$view = new ViewModel(array(
			'formCadastroCurso' => $formCadastroCurso,
		));

		return $view;
	}

	public function cursoSalvarAction() {

		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();

				$dadosPost = $request->getPost();
				$id = $dadosPost['id'];
				$nome = strtoupper($dadosPost['nome']);
				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$idPessoaLogada = $sessao->idPessoa;
				$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoaLogada);
				if ($id) {
					$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($id);
				} else {
					$curso = new Curso();
				}

				$curso->setNome($nome);
				$curso->setPessoa($pessoaLogada);

				if ($id) {
					$this->getRepositorio()->getCursoORM()->persistir($curso, false);
				} else {
					$this->getRepositorio()->getCursoORM()->persistir($curso);
				}

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => Constantes::$PAGINA_CURSO_LISTAR,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
	}

	public function cursoFormEditAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idCurso = $sessao->idSessao;
		$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);
		$formCadastroCurso = new CursoForm('formulario', $curso);

		$view = new ViewModel(array(
			'formCadastroCurso' => $formCadastroCurso,
		));

		return $view;
	}

	/**
	 * Tela com formulário de exclusão de curso
	 * GET /cadastroTurmaExclusao
	 */
	public function cursoExclusaoAction() {
		/* Verificando a se tem algum id na sessão */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$extra = null;
		$idCurso = $sessao->idSessao;

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);

		$view = new ViewModel(array(
			Constantes::$NOME_ENTIDADE_CURSO => $curso,
			Constantes::$ENTIDADE => $entidade,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_CURSO);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_CURSO);

		return $view;
	}

	public function cursoExcluirAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idCurso = $sessao->idSessao;
		$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);
		$curso->setDataEHoraDeInativacao();
		$this->getRepositorio()->getCursoORM()->persistir($curso, false);

		return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
			Constantes::$ACTION => Constantes::$PAGINA_CURSO_LISTAR,
		));
	}

	/**
	 * Função de listagem de disciplina
	 */
	public function disciplinaListarAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idCurso = $sessao->idSessao;
		$disciplinas = $this->getRepositorio()->getDisciplinaORM()->buscarTodosRegistrosEntidade();
		$view = new ViewModel(array(
			'disciplinas' => $disciplinas,
			'idCurso' => $idCurso,
		));

		return $view;
	}

	/*
	 * Função de retornar formulario de cadastro de disciplinas
	 */

	public function disciplinaFormAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idCurso = $sessao->idSessao;

		$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);
		$disciplinas = $curso->getDisciplina();
		//echo count($disciplinas);
		//echo "\n".$disciplinas[0]->getPosicao();
		$formCadastroDisciplina = new DisciplinaForm('formulario', $idCurso, $disciplinas);
		$view = new ViewModel(array(
			'formCadastroDisciplina' => $formCadastroDisciplina,
			'idCurso' => $idCurso,
		));

		return $view;
	}

	public function disciplinaSalvarAction() {

		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();

				$dadosPost = $request->getPost();
				$id = $dadosPost['id'];
				$nome = strtoupper($dadosPost['nome']);
				$posicao = $dadosPost['posicao'];
				$idCurso = $dadosPost['idCurso'];
				if ($id) {
					$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($id);
				} else {
					$disciplina = new Disciplina();
				}
				$curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);
				$disciplina->setNome($nome);
				$disciplina->setPosicao($posicao);
				$disciplina->setCurso($curso);

				if ($id) {
					$this->getRepositorio()->getDisciplinaORM()->persistir($disciplina, false);
				} else {
					$this->getRepositorio()->getDisciplinaORM()->persistir($disciplina);
				}

				$this->getRepositorio()->fecharTransacao();
				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$sessao->idSessao = $idCurso;
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_LISTAR,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
	}

	public function disciplinaFormEditAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idDisciplina = $sessao->idSessao;

		$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);

		$curso = $disciplina->getCurso();
		$disciplinas = $curso->getDisciplina();
		$formCadastroDisciplina = new DisciplinaForm('formulario', $idCurso, $disciplinas, $disciplina);
		$view = new ViewModel(array(
			'formCadastroDisciplina' => $formCadastroDisciplina,
			'idCurso' => $curso->getId(),
		));


		return $view;
	}

	/**
	 * Tela com formulário de exclusão de disciplina
	 * GET /cadastroTurmaExclusao
	 */
	public function disciplinaExclusaoAction() {
		/* Verificando a se tem algum id na sessão */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$extra = null;
		$idDisciplina = $sessao->idSessao;

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);

		$view = new ViewModel(array(
			Constantes::$NOME_ENTIDADE_DISCIPLINA => $disciplina,
			Constantes::$ENTIDADE => $entidade,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_DISCIPLINA);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_DISCIPLINA);

		return $view;
	}

	public function disciplinaExcluirAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idDisciplina = $sessao->idSessao;
		$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);
		$disciplina->setDataEHoraDeInativacao();
		$this->getRepositorio()->getCursoORM()->persistir($disciplina, false);
		$sessao->idSessao = $disciplina->getCurso_id();
		return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
			Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_LISTAR,
		));
	}

	/**
	 * Função de listagem de aula
	 */
	public function aulaListarAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idDisciplina = $sessao->idSessao;
		$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);
		$aulas = $this->getRepositorio()->getAulaORM()->buscarTodosRegistrosEntidade('posicao', 'ASC');
		$view = new ViewModel(array(
			'aulas' => $aulas,
			'idDisciplina' => $idDisciplina,
			'idCurso' => $disciplina->getCurso_id(),
		));

		return $view;
	}

	/*
	 * Função de retornar formulario de cadastro de aulas
	 */

	public function aulaFormAction() {
		$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idDisciplina = $sessao->idSessao;
		$disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);
		$aulas = $disciplina->getAulaOrdenadasPorPosicao();
		$formCadastroAula = new AulaForm('formulario', $idDisciplina, $aulas);
		$view = new ViewModel(array(
			'formCadastroAula' => $formCadastroAula,
			'idDisciplina' => $idDisciplina,
			'idCurso' => $disciplina->getCurso_id(),
		));

		return $view;
	}

	public function aulaSalvarAction() {
		$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$repositorioORM->iniciarTransacao();

				$dadosPost = $request->getPost();
				$id = $dadosPost['id'];
				$nome = strtoupper($dadosPost['nome']);
				$posicao = $dadosPost['posicao'];
				$idDisciplina = $dadosPost['idDisciplina'];
				if ($id) {
					$aula = $repositorioORM->getAulaORM()->encontrarPorId($id);
				} else {
					$aula = new Aula();
				}
				$disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);
				$aula->setNome($nome);
				$aula->setPosicao($posicao);
				$aula->setDisciplina($disciplina);

				if ($id) {
					$repositorioORM->getAulaORM()->persistir($aula, false);
				} else {
					$repositorioORM->getAulaORM()->persistir($aula);
				}

				$repositorioORM->fecharTransacao();
				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$sessao->idSessao = $idDisciplina;
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => Constantes::$PAGINA_AULA_LISTAR,
				));
			} catch (Exception $exc) {
				$repositorioORM->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
	}

	public function aulaFormEditAction() {
		$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idAula = $sessao->idSessao;
		$aula = $repositorioORM->getAulaORM()->encontrarPorId($idAula);
		$aulas = $aula->getDisciplina()->getAulaOrdenadasPorPosicao();
		$formCadastroAula = new AulaForm('formulario', $aula->getDisciplina_id(), $aulas, $aula);
		$view = new ViewModel(array(
			'formCadastroAula' => $formCadastroAula,
			'idDisciplina' => $aula->getDisciplina_id(),
		));


		return $view;
	}

	/**
	 * Tela com formulário de exclusão de aula
	 * GET /cadastroTurmaExclusao
	 */
	public function aulaExclusaoAction() {
		/* Verificando a se tem algum id na sessão */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$extra = null;
		$idAula = $sessao->idSessao;
		$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
		$entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$aula = $repositorioORM->getAulaORM()->encontrarPorId($idAula);

		$view = new ViewModel(array(
			Constantes::$NOME_ENTIDADE_AULA => $aula,
			Constantes::$ENTIDADE => $entidade,
			'idDisciplina' => $aula->getDisciplina_id(),
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_AULA);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_AULA);

		return $view;
	}

	public function aulaExcluirAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
		$idAula = $sessao->idSessao;
		$aula = $repositorioORM->getAulaORM()->encontrarPorId($idAula);
		$aula->setDataEHoraDeInativacao();
		$repositorioORM->getDisciplinaORM()->persistir($aula, false);
		$sessao->idSessao = $aula->getDisciplina_id();
		return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
			Constantes::$ACTION => Constantes::$PAGINA_AULA_LISTAR,
		));
	}

	/**
	 * Controle de funçoes da tela de curso
	 * @return Json
	 */
	public function funcoesAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$funcao = $post_data[Constantes::$FUNCAO];
				$id = $post_data[Constantes::$ID];
				$sessao = new Container(Constantes::$NOME_APLICACAO);
				$sessao->idSessao = $id;
				$response->setContent(Json::encode(
					array(
						'response' => 'true',
						'tipoDeRetorno' => 1,
						'url' => '/curso' . $funcao,
					)));
			} catch (Exception $exc) {
				echo $exc->get();
			}
		}
		return $response;
	}

	public function turmaFormAction() {
		$cursos = $this->getRepositorio()->getCursoORM()->buscarTodosRegistrosEntidade();
		$formCadastroTurma = new TurmaForm('formulario', $cursos);

		$view = new ViewModel(array(
			'formCadastroTurma' => $formCadastroTurma,
		));

		return $view;
	}

	public function salvarTurmaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;

		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();

				$dadosPost = $request->getPost();
				$idCurso = $dadosPost['idCurso'];
				$mes = $dadosPost['Mes'];
				$ano = $dadosPost['Ano'];
				$observacao = strtoupper($dadosPost['observacao']);

				$turma = new Turma();
				$identidadeatual = $sessao->identidadeatual;
				$entidade = $this->getrepositorio()->getEntidadeORM()->encontrarporid($idEntidadeAtual);
				$grupo = $entidade->getGrupo();
				$curso = $this->getRepositorio()->getCursoORM()->encontrarporid($idCurso);

				$turma->setCurso($curso);
				$turma->setGrupo($grupo->getGrupoIgreja());
				$turma->setAno((int) $ano);
				$turma->setMes((int) $mes);
				$turma->setObservacao($observacao);
				$this->getRepositorio()->getTurmaORM()->persistir($turma);

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
			}
		}
	}

	public function listarTurmaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$entidadeDaIgreja = $entidade->getGrupo()->getGrupoIgreja()->getEntidadeAtiva();
		$relatorioCursos = RelatorioController::relatorioAlunosETurmas($this->getRepositorio(), $entidadeDaIgreja);
		$turmas = $entidadeDaIgreja->getGrupo()->getTurma();		
		$view = new ViewModel(array(
			'turmas' => $turmas,
			'relatorio' => $relatorioCursos[0],
		));

		return $view;
	}

	public function reabrirTurmaAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($sessao->idSessao);

		$this->getRepositorio()->iniciarTransacao();
		try{
			$turma->setDataEHoraDeInativacaoIgualANull();
			$this->getRepositorio()->getTurmaORM()->persistir($turma, $mudarDataDeCriacao = false);
			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
				Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
			));
		} catch(Exception $exc){
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getMessage();
		}
	}

	public function fecharTurmaAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($sessao->idSessao);

		$this->getRepositorio()->iniciarTransacao();
		try{
			$turma->setDataEHoraDeInativacao();
			$this->getRepositorio()->getTurmaORM()->persistir($turma, $mudarDataDeCriacao = false);
			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
				Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
			));
		} catch(Exception $exc){
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getMessage();
		}
	}

	public function turmaFormEditAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idTurma = $sessao->idSessao;
		$cursos = $this->getRepositorio()->getCursoORM()->buscarTodosRegistrosEntidade();
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);
		$formCadastroTurma = new TurmaForm('formulario', $cursos, $turma);

		$view = new ViewModel(array(
			'formCadastroTurma' => $formCadastroTurma,
		));

		return $view;
	}

	public function turmaExcluirAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idTurma = $sessao->idSessao;
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);
		$turma->setDataEHoraDeInativacao();
		$this->getRepositorio()->getTurmaORM()->persistir($turma, false);

		return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
			Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
		));
	}

	public function listarTurmaInativaAction() {

		$turmas = $this->getRepositorio()->getTurmaORM()->encontrarTodas();
		$view = new ViewModel(array(
			'turmas' => $turmas,
		));

		return $view;
	}

	public function turmaSelecionarAlunosAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idTurma = $sessao->idTurma;
		$idRevisao = $sessao->idRevisao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);

		$pessoas = array();
		$frequencias = $eventoRevisao->getEventoFrequencia();
		if (count($frequencias) > 0) {
			foreach ($frequencias as $f) {
				$p = null;
				$pAux = null;
				$p = $f->getPessoa();
				$pAux = new Pessoa();
				$grupoPessoa = $p->getGrupoPessoaAtivo();
				if ($grupoPessoa != null) {
					if ($f->getFrequencia() == 'S') {
						$pAux->setNome($p->getNome());
						$pessoas[] = $pAux;
					}
				}
			}
		}
		$formSelecionarAlunos = new SelecionarAlunosForm('selecionar-alunos', $idTurma, $pessoas);

		$view = new ViewModel(array(
			'formSelecionarAlunos' => $formSelecionarAlunos,
		));

		return $view;
	}

	/**
	 * Controle de funçoes da tela de cadastro
	 * @return Json
	 */
	public function funcoesSelecionarAlunosAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$idTurma = $post_data['idTurma'];
				$idRevisao = $post_data['idRevisao'];
				$sessao = new Container(Constantes::$NOME_APLICACAO);

				$sessao->idTurma = $idTurma;
				$sessao->idRevisao = $idRevisao;
				$response->setContent(Json::encode(
					array(
						'response' => 'true',
						'tipoDeRetorno' => 1,
						'url' => '/cadastroTurmaSelecionarAlunos',
					)));
			} catch (Exception $exc) {
				echo $exc->get();
			}
		}
		return $response;
	}

	public function selecionarPessoasRevisaoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idRevisao = $sessao->idSessao;
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$sessao->idRevisao = $idRevisao;
		$eventoRevisao = $this->getRepositorio()->getEventoORM()->encontrarPorId($idRevisao);
		$view = new ViewModel(array(
			Constantes::$ENTIDADE => $entidade,
			'repositorioORM' => $this->getRepositorio(),
			'evento' => $eventoRevisao,
			'entidade' => $entidade,
		));

		return $view;
	}

	public function cadastrarTurmaPessoaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = $request->getPost();
			try {
				$this->getRepositorio()->iniciarTransacao();
				$idEvento = $post['idEvento'];
				$evento = $this->getRepositorio()->getEventoORM()->encontrarPorId($idEvento);
				$frequencias = $evento->getEventoFrequencia();
				$pessoas = array();
				if ($frequencias) {
					foreach ($frequencias as $frequencia) {
						if ($frequencia->getFrequencia() == 'S') {
							$adicionar = false;
							foreach ($post as $key => $value) {
								$comecoDaString = substr($key, 0, 5);
								if($comecoDaString == 'aluno'){
									if ($frequencia->getPessoa()->getId() == $value) {
										$adicionar = true;
									}
								}

							}
							if ($adicionar) {
								$pessoas[] = $frequencia->getPessoa();
							}
						}
					}
					$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($sessao->idTurma);
					foreach ($pessoas as $pessoa) {
						if(!$pessoa->getTurmaPessoaAtivo()){
							$turmaPessoa = new TurmaPessoa();
							$turmaPessoa->setPessoa($pessoa);
							$turmaPessoa->setTurma($turma);
							$this->getRepositorio()->getTurmaPessoaORM()->persistir($turmaPessoa);

							$situacao = $this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ATIVO);
							$turmaPessoaSituacao = new TurmaPessoaSituacao();
							$turmaPessoaSituacao->setSituacao($situacao);
							$turmaPessoaSituacao->setTurma_pessoa($turmaPessoa);
							$this->getRepositorio()->getTurmaPessoaSituacaoORM()->persistir($turmaPessoaSituacao);

							$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $pessoa->getGrupoPessoaAtivo()->getGrupo());
							$fatoCurso = new FatoCurso();
							$fatoCurso->setNumero_identificador($numeroIdentificador);
							$fatoCurso->setTurma_pessoa_id($turmaPessoa->getId());
							$fatoCurso->setTurma_id($turma->getId());
							$fatoCurso->setSituacao_id(Situacao::ATIVO);
							$this->getRepositorio()->getFatoCursoORM()->persistir($fatoCurso);
						}						
					}

					$this->getRepositorio()->fecharTransacao();

					return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
						Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
					));
				}
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
			}
		}
	}

	/**
	 * Tela com formulário de exclusão de turma
	 * GET /cadastroTurmaExclusao
	 */
	public function turmaExclusaoAction() {
		/* Verificando a se tem algum id na sessão */
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$extra = null;
		$idTurma = $sessao->idSessao;

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);

		$view = new ViewModel(array(
			Constantes::$NOME_ENTIDADE_TURMA => $turma,
			Constantes::$ENTIDADE => $entidade,
		));

		/* Javascript */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_TURMA);
		$view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_TURMA);

		return $view;
	}

	public function abrirAulaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idTurma = $sessao->idSessao;
		$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);

		$idAulaAtiva = 0;
		if ($turma->getTurmaAulaAtiva()) {
			$idAulaAtiva = $turma->getTurmaAulaAtiva()->getAula()->getId();
		}
		$opcoes = array();
		$curso = $turma->getCurso();
		foreach ($curso->getDisciplina() as $disciplina) {
			foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
				$opcoes[$aula->getId()][0] = $disciplina->getNome() . ': ' . $aula->getPosicao(). ' - ' . $aula->getNome();
				$opcoes[$aula->getId()][1] = '';
				if ($idAulaAtiva == $aula->getId()) {
					$opcoes[$aula->getId()][1] = 'selected';
				}
			}
		}

		return new ViewModel(array(
			'turma' => $turma,
			'opcoes' => $opcoes,
		));
	}

	public function salvarAulaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$request = $this->getRequest();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$idAula = $_POST['idAula'];

				$idTurma = $sessao->idSessao;
				$idPessoa = $sessao->idPessoa;
				$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);

				if ($turmaAulaAtiva = $turma->getTurmaAulaAtiva()) {
					$naoMudarDataDeCriacao = false;
					$turmaAulaAtiva->setDataEHoraDeInativacao();
					$this->getRepositorio()->getTurmaAulaORM()->persistir($turmaAulaAtiva, $naoMudarDataDeCriacao);
				}

				if (intval($idAula) !== 0) {
					$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
					$turmaAula = new TurmaAula();
					$turmaAula->setTurma($turma);
					$turmaAula->setAula($this->getRepositorio()->getAulaORM()->encontrarPorId($idAula));
					$turmaAula->setPessoa($pessoa);
					$this->getRepositorio()->getTurmaAulaORM()->persistir($turmaAula);
				}

				$this->getRepositorio()->fecharTransacao();

				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
			}
		}
	}
	public function turmasEncerradasAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$entidadeDaIgreja = $entidade->getGrupo()->getGrupoIgreja()->getEntidadeAtiva();
		$turmasAtivas = false;
		$relatorioCursos = RelatorioController::relatorioAlunosETurmas($this->getRepositorio(), $entidadeDaIgreja, $turmasAtivas);	
		$turmas = $entidadeDaIgreja->getGrupo()->getTurmasInativas();			
		$view = new ViewModel(array(
			'turmas' => $turmas,
			'relatorio' => $relatorioCursos[0],
		));

		return $view;
	}

	public function turmaEncerradaSituacaoAction() {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '60');
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();
		$verificarTurmasAtivas = false;

		$resultado = RelatorioController::relatorioAlunosETurmas($this->getRepositorio(), $entidade, $verificarTurmasAtivas);
		$turmas = $resultado[1];

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();

		$entidadeParaUsar = $entidade;
		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
		}

		$view = new ViewModel(array(
			'filtrado' => $filtrado,
			'postado' => $postado,
			'entidade' => $entidade,
			'turmas' => $turmas,
			'situacoes' => $situacoes,
			'repositorio' => $this->getRepositorio(),
			'relatorio' => $resultado[2],
		));
		return $view;
	}

	public function chamadaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);
		$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();
		$filhos = array();

		$entidadeParaUsar = $entidade;
		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
			$postado['idEquipe'] = $post['idEquipe'];
			$postado['idSituacao'] = $post['idSituacao'];
			$postado['mostrarAulas'] = $post['mostrarAulas'];
			$postado['mostrarFinanceiro'] = $post['mostrarFinanceiro'];
			$postado['idSub'] = $post['idSub'];

			if($postado['idEquipe'] == 0){
				$entidadeParaUsar = $grupo->getGrupoIgreja()->getEntidadeAtiva();
			}
			if($postado['idEquipe'] != 0){
				$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe']);
				$grupoPaiFilhoFilhosEquipe = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos(0);

				foreach($grupoPaiFilhoFilhosEquipe as $grupoPaiFilho){
					$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
					$dados = array();
					$dados['id'] = $grupoFilho->getId();
					$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
					$filhos[] =  $dados;
				}
				$entidadeParaUsar = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe'])->getEntidadeAtiva();
			}
			if($postado['idSub'] != 0){
				$entidadeParaUsar = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idSub'])->getEntidadeAtiva();
			}
		}
		$resultado = RelatorioController::relatorioAlunosETurmas($this->getRepositorio(), $entidadeParaUsar);
		$turmas = $resultado[1];

		if(!$pessoa->getPessoaCursoAcessoAtivo() 
			&& $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
			$postado['mostrarAulas'] = 1;
			$postado['mostrarFinanceiro'] = 0;
		}

		$view = new ViewModel(array(
			'filtrado' => $filtrado,
			'postado' => $postado,
			'pessoa' => $pessoa,
			'entidade' => $entidade,
			'turmas' => $turmas,
			'filhos' => $grupoPaiFilhoFilhos,
			'situacoes' => $situacoes,
			'subs' => $filhos,
			'repositorio' => $this->getRepositorio(),
			'relatorio' => $resultado[2],
		));
		self::registrarLog(RegistroAcao::VER_CHAMADA, $extra = '');
		return $view;
	}

	public function listagemAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);
		$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();
		$filhos = array();

		$entidadeParaUsar = $entidade;
		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
			$postado['idEquipe'] = $post['idEquipe'];
			$postado['idSituacao'] = $post['idSituacao'];
			$postado['mostrarAulas'] = $post['mostrarAulas'];
			$postado['mostrarFinanceiro'] = $post['mostrarFinanceiro'];
			$postado['idSub'] = $post['idSub'];

			if($postado['idEquipe'] == 0){
				$entidadeParaUsar = $grupo->getGrupoIgreja()->getEntidadeAtiva();
			}
			if($postado['idEquipe'] != 0){
				$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe']);
				$grupoPaiFilhoFilhosEquipe = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos(0);

				foreach($grupoPaiFilhoFilhosEquipe as $grupoPaiFilho){
					$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
					$dados = array();
					$dados['id'] = $grupoFilho->getId();
					$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
					$filhos[] =  $dados;
				}
				$entidadeParaUsar = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe'])->getEntidadeAtiva();
			}
			if($postado['idSub'] != 0){
				$entidadeParaUsar = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idSub'])->getEntidadeAtiva();
			}
		}
		$resultado = RelatorioController::relatorioAlunosETurmas($this->getRepositorio(), $entidadeParaUsar);
		$turmas = $resultado[1];

		if(!$pessoa->getPessoaCursoAcessoAtivo()){
			$postado['mostrarAulas'] = 1;
			$postado['mostrarFinanceiro'] = 0;
		}

		$view = new ViewModel(array(
			'filtrado' => $filtrado,
			'postado' => $postado,
			'pessoa' => $pessoa,
			'entidade' => $entidade,
			'turmas' => $turmas,
			'filhos' => $grupoPaiFilhoFilhos,
			'situacoes' => $situacoes,
			'subs' => $filhos,
			'repositorio' => $this->getRepositorio(),
			'relatorio' => $resultado[2],
		));
		return $view;
	}

	public function buscarSubsAction(){
		$response = $this->getResponse();
		try {
			$idEquipe = $_POST['id'];
			$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idEquipe);
			$grupoPaiFilhoFilhos = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos(0);

			$filhos = array();
			foreach($grupoPaiFilhoFilhos as $grupoPaiFilho){
				$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
				$dados = array();
				$dados['id'] = $grupoFilho->getId();
				$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
				$filhos[] =  $dados;
			}

			$resposta = true;
			$response->setContent(Json::encode(
				array('response' => $resposta,
				'filhos' => $filhos,
			)));
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $response;
	}
	public function buscarSubsCompletoAction(){
		$response = $this->getResponse();
		try {
			$idEquipe = $_POST['id'];
			$periodo = 0;
			$filhos = array();
			$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idEquipe);
			$grupoPaiFilhoFilhos = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos($periodo);

			if($grupoResponsabilidades = $grupoEquipe->getResponsabilidadesAtivas()){
				foreach($grupoResponsabilidades as $grupoResponsabilidade){
					$dados = array();
					$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
					$dados['informacao'] = $grupoEquipe->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
					$filhos[] =  $dados;
				}
			}

			foreach($grupoPaiFilhoFilhos as $grupoPaiFilho){
				$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
				if($grupoResponsabilidades = $grupoFilho->getResponsabilidadesAtivas()){
					foreach($grupoResponsabilidades as $grupoResponsabilidade){
						$dados = array();
						$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
						$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
						$filhos[] =  $dados;
					}
				}

				if($filhos2 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($periodo)){
					foreach($filhos2 as $filho2){
						$grupoFilho2 = $filho2->getGrupoPaiFilhoFilho();
						if($grupoResponsabilidades = $grupoFilho2->getResponsabilidadesAtivas()){
							foreach($grupoResponsabilidades as $grupoResponsabilidade){
								$dados = array();
								$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
								$dados['informacao'] = $grupoFilho2->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
								$filhos[] =  $dados;
							}
						}


						if($filhos3 = $grupoFilho2->getGrupoPaiFilhoFilhosAtivos($periodo)){
							foreach($filhos3 as $filho3){
								$grupoFilho3 = $filho3->getGrupoPaiFilhoFilho();

								if($grupoResponsabilidades = $grupoFilho3->getResponsabilidadesAtivas()){
									foreach($grupoResponsabilidades as $grupoResponsabilidade){
										$dados = array();
										$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
										$dados['informacao'] = $grupoFilho3->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
										$filhos[] =  $dados;
									}
								}


								if($filhos4 = $grupoFilho3->getGrupoPaiFilhoFilhosAtivos($periodo)){
									foreach($filhos4 as $filho4){
										$grupoFilho4 = $filho4->getGrupoPaiFilhoFilho();

										if($grupoResponsabilidades = $grupoFilho4->getResponsabilidadesAtivas()){
											foreach($grupoResponsabilidades as $grupoResponsabilidade){
												$dados = array();
												$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
												$dados['informacao'] = $grupoFilho4->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
												$filhos[] =  $dados;
											}
										}
										if($filhos5 = $grupoFilho4->getGrupoPaiFilhoFilhosAtivos($periodo)){
											foreach($filhos5 as $filho5){
												$grupoFilho5 = $filho5->getGrupoPaiFilhoFilho();
												if($grupoResponsabilidades = $grupoFilho5->getResponsabilidadesAtivas()){
													foreach($grupoResponsabilidades as $grupoResponsabilidade){
														$dados = array();
														$dados['id'] = $grupoResponsabilidade->getGrupo()->getId() . '_' .$grupoResponsabilidade->getPessoa()->getId();
														$dados['informacao'] = $grupoFilho5->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoResponsabilidade->getPessoa()->getNome();
														$filhos[] =  $dados;
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

			$resposta = true;
			$response->setContent(Json::encode(
				array('response' => $resposta,
				'filhos' => $filhos,
			)));
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $response;
	}


	public function selecionarParaCarterinhaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$formulario = new SelecionarCarterinhasForm('SelecionarCarterinhas');

		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();

		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
			$postado['idEquipe'] = $post['idEquipe'];
			$turmas[] = $this->getRepositorio()->getTurmaORM()->encontrarPorId($postado['idTurma']);
		}else{
			$turmas = $entidade->getGrupo()->getGrupoIgreja()->getTurma();
		}

		$view = new ViewModel(array(
			'turmas' => $turmas,
			'filtrado' => $filtrado,
			'postado' => $postado,
			'filhos' => $grupoPaiFilhoFilhos,
			'formulario' => $formulario,
		));
		return $view;
	}

	public function gerarCarterinhaAction() {
		$request = $this->getRequest();
		if ($request->isPost()) {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 5) == 'aluno') {
					$alunosId[] = $value;
				}
			}
		}

		$idTurmaAluno = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		if ($idTurmaAluno !== 0) {
			$alunosId[] = $idTurmaAluno;
		}
		$viewModel = new ViewModel(
			array(
				'alunosId' => $alunosId,
				'repositorio' => $this->getRepositorio(),
			));
		$viewModel->setTerminal(true);
		return $viewModel;
	}

	public function lancarPresencaAction() {
		return new ViewModel(array('titulo' => 'Lançar Presença'));
	}

	public function lancarReposicaoAction() {
		return new ViewModel(array('titulo' => 'Lançar Reposição'));
	}

	public function matriculaAction() {
		return new ViewModel(array('titulo' => 'Consultar Matrícula'));
	}

	public function consultarMatriculaAction() {
		$response = $this->getResponse();
		try {
			$turmaPessoa = null;
			$idTurmaPessoa = $_POST['id'];
			$idParaRetornar = null;
			$temAulaAtiva = false;

			$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($explodeMatricula[0]);
			if ($this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa)) {
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
			}
			if ($this->getRepositorio()->getTurmaPessoaORM()->encontrarPorIdAntigo($idTurmaPessoa)) {
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorIdAntigo($idTurmaPessoa);
			}
			if ($turmaPessoa) {
				$resposta = true;
				$nomeTurma = Funcoes::mesPorExtenso($turmaPessoa->getTurma()->getMes(), 1) . '/' . $turmaPessoa->getTurma()->getAno();
				$nomePessoa = $turmaPessoa->getPessoa()->getNome();

				if($turmaAulaAtiva = $turmaPessoa->getTurma()->getTurmaAulaAtiva()){
					$temAulaAtiva = true;
					$nomeAula = $turmaAulaAtiva->getAula()->getDisciplina()->getNome().' Aula: '.$turmaAulaAtiva->getAula()->getPosicao();
				}
				$idParaRetornar = $turmaPessoa->getId();
			} else {
				$resposta = false;
			}
			$response->setContent(Json::encode(
				array('response' => $resposta,
				'turma' => $nomeTurma,
				'pessoa' => $nomePessoa,
				'idTurmaPessoa' => $idParaRetornar,
				'temAulaAtiva' => $temAulaAtiva,
				'nomeAula' => $nomeAula,
			)));
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $response;
	}

	public function consultarReposicaoAction() {
		$response = $this->getResponse();
		try {
			$turmaPessoa = null;
			$id = $_POST['id'];
			$idTurmaAluno = substr($id,0,10);
			$idAula = substr($id,10);

			if($turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaAluno)){
				$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId($idAula);
				$resposta = true;
				$nomeTurma = funcoes::mesporextenso($turmaPessoa->getturma()->getmes(), 1) . '/' . $turmaPessoa->getturma()->getano();
				$nomePessoa = $turmaPessoa->getpessoa()->getnome();
				$nomeAula = $aula->getDisciplina()->getNome().' Aula: '.$aula->getPosicao();
			}
			$response->setContent(Json::encode(
				array('response' => $resposta,
				'curso' => $nomeCurso,
				'turma' => $nomeTurma,
				'equipe' => $nomeEquipe,
				'pessoa' => $nomePessoa,
				'nomeAula' => $nomeAula,
			)));
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $response;
	}


	public function lancarPresencaFinalizarAction() {
		$resposta = false;
		$response = $this->getResponse();
		try {
			if($this->getRequest()->isPost()){
				$this->getRepositorio()->iniciarTransacao();
				$idTurmaPessoa = $this->getRequest()->getPost()['id'];
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
				if($turmaPessoa === null){
					$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorIdAntigo($idTurmaPessoa);
				}
				if($turmaPessoa){
					$aula = $turmaPessoa->getTurma()->getTurmaAulaAtiva()->getAula();
					$turmaPessoaAula = $turmaPessoa->getTurmaPessoaAulaPorAula($aula->getId());
					if (!$turmaPessoaAula) {
						$turmaPessoaAula = new TurmaPessoaAula();
						$turmaPessoaAula->setAula($aula);
						$turmaPessoaAula->setTurma_pessoa($turmaPessoa);
						$turmaPessoaAula->setReposicao('N');
					}
					$turmaPessoaAula->setData_inativacao(null);
					$turmaPessoaAula->setHora_inativacao(null);
					$this->getRepositorio()->getTurmaPessoaAulaORM()->persistir($turmaPessoaAula);
					$resposta = true;
					$this->getRepositorio()->fecharTransacao();
				}
			}
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
		}
		$response->setContent(Json::encode(array('response' => $resposta,)));
		return $response;
	}

	public function lancarReposicaoFinalizarAction() {
		$resposta = false;
		$response = $this->getResponse();
		try {
			if($this->getRequest()->isPost()){
				$this->getRepositorio()->iniciarTransacao();
				$id = $this->getRequest()->getPost()['id'];

				$idTurmaAluno = substr($id,0,10);
				$idAula = substr($id,10);

				if($turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaAluno)){
					$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId($idAula);
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
					$resposta = true;
					$this->getRepositorio()->fecharTransacao();
				}
			}
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
		}
		$response->setContent(Json::encode(array('response' => $resposta,)));
		return $response;
	}


	public function reentradaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$turmas = $grupo->getGrupoIgreja()->getTurma();
		$lideres = $this->todosLideresAPartirDoGrupo($grupo->getGrupoIgreja());
		$formulario = new ReentradaDeAlunoForm('formulario', $lideres, $turmas);
		return new ViewModel(array(
			'formulario' => $formulario,
		));
	}

	public function reentradaFinalizarAction() {
		$request = $this->getRequest();
		if ($request->isPost()) {
			$this->getRepositorio()->iniciarTransacao();
			try {
				$post_data = $request->getPost();
				$pessoa = new Pessoa();
				$pessoa->setNome($post_data['primeiro-nome'] . ' ' . $post_data['ultimo-nome']);
				$pessoa->setTelefone($post_data['ddd'] . $post_data['telefone']);
				$pessoa->setData_nascimento($post_data['Ano'] . '-' . $post_data['Mes'] . '-' . $post_data['Dia']);
				$pessoa->setSexo($post_data['nucleoPerfeito']);
				$this->getRepositorio()->getPessoaORM()->persistir($pessoa);

				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($post_data['idGrupo']);
				$grupoPessoaTipo = $this->getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId(GrupoPessoaTipo::MEMBRO);
				$grupoPessoa = new GrupoPessoa();
				$grupoPessoa->setGrupo($grupo);
				$grupoPessoa->setPessoa($pessoa);
				$grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
				$this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa);

				$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($post_data['idTurma']);
				$turmaPessoa = new TurmaPessoa();
				$turmaPessoa->setTurma($turma);
				$turmaPessoa->setPessoa($pessoa);
				$this->getRepositorio()->getTurmaPessoaORM()->persistir($turmaPessoa);

				$situacao = $this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ATIVO);
				$turmaPessoaSituacao = new TurmaPessoaSituacao();
				$turmaPessoaSituacao->setTurma_pessoa($turmaPessoa);
				$turmaPessoaSituacao->setSituacao($situacao);
				$this->getRepositorio()->getTurmaPessoaSituacaoORM()->persistir($turmaPessoaSituacao);

				$numeroIdentificador =
					$this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
				$fatoCurso = new FatoCurso();
				$fatoCurso->setNumero_identificador($numeroIdentificador);
				$fatoCurso->setTurma_pessoa_id($turmaPessoa->getId());
				$fatoCurso->setTurma_id($turma->getId());
				$fatoCurso->setSituacao_id(Situacao::ATIVO);
				$this->getRepositorio()->getFatoCursoORM()->persistir($fatoCurso);

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => 'ReentradaFinalizado',
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
			}
		}
	}

	public function reentradaFinalizadoAction(){
		return new ViewModel();
	}

	public function usuariosAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$usuarios = $grupo->getGrupoIgreja()->getPessoaCursoAcesso();
		return new ViewModel(array(
			'usuarios' => $usuarios,
		));
	}

	public function usuarioAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$lideres = $this->todosLideresAPartirDoGrupo($grupo->getGrupoIgreja(), true);
		$cursoAcessos = $this->getRepositorio()->getCursoAcessoORM()->buscarTodosRegistrosEntidade();
		$formulario = new CursoUsuarioForm('formulario', $cursoAcessos, $lideres);
		return new ViewModel(array(
			'formulario' => $formulario,
		));
	}

	public function usuarioFinalizarAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$request = $this->getRequest();
		if ($request->isPost()) {
			$this->getRepositorio()->iniciarTransacao();
			try {
				$idEntidadeAtual = $sessao->idEntidadeAtual;
				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

				$post_data = $request->getPost();

				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data[Constantes::$INPUT_ID_PESSOA]);
				$cursoAcesso = $this->getRepositorio()->getCursoAcessoORM()->encontrarPorId($post_data[Constantes::$INPUT_ID_ACESSO]);
				$grupo = $entidade->getGrupo();

				$pessoaCursoAcesso = new PessoaCursoAcesso();
				$pessoaCursoAcesso->setPessoa($pessoa);
				$pessoaCursoAcesso->setGrupo($grupo->getGrupoIgreja());
				$pessoaCursoAcesso->setCursoAcesso($cursoAcesso);
				$this->getRepositorio()->getPessoaCursoAcessoORM()->persistir($pessoaCursoAcesso);

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => 'Usuarios',
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
	}

	public function usuarioInativarAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$this->getRepositorio()->iniciarTransacao();
		try {
			$pessoaCursoAcesso = $this->getRepositorio()->getPessoaCursoAcessoORM()->encontrarPorId($sessao->idSessao);
			$pessoaCursoAcesso->setDataEHoraDeInativacao();
			$this->getRepositorio()->getPessoaCursoAcessoORM()->persistir($pessoaCursoAcesso, false);

			$this->getRepositorio()->fecharTransacao();
			return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
				Constantes::$ACTION => 'Usuarios',
			));
		} catch (Exception $exc) {
			$this->getRepositorio()->desfazerTransacao();
			echo $exc->getTraceAsString();
		}
	}

	public function todosLideresAPartirDoGrupo(Grupo $grupo, $separadosPorLider = false) {
		$lideres = array();
		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
		foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho12) {
			$grupo12 = $grupoPaiFilhoFilho12->getGrupoPaiFilhoFilho();
			if (!$separadosPorLider) {
				$lideres [] = $grupo12;
			} else {
				foreach ($grupo12->getPessoasAtivas() as $pessoas) {
					$lideres [] = $pessoas;
				}
			}
			if ($grupoPaiFilhoFilhos144 = $grupo12->getGrupoPaiFilhoFilhosAtivosReal()) {
				foreach ($grupoPaiFilhoFilhos144 as $grupoPaiFilhoFilho144) {
					$grupo144 = $grupoPaiFilhoFilho144->getGrupoPaiFilhoFilho();
					if (!$separadosPorLider) {
						$lideres [] = $grupo144;
					} else {
						foreach ($grupo144->getPessoasAtivas() as $pessoas) {
							$lideres [] = $pessoas;
						}
					}
					if ($grupoPaiFilhoFilhos1728 = $grupo144->getGrupoPaiFilhoFilhosAtivosReal()) {
						foreach ($grupoPaiFilhoFilhos1728 as $grupoPaiFilhoFilho1728) {
							$grupo1728 = $grupoPaiFilhoFilho1728->getGrupoPaiFilhoFilho();
							if (!$separadosPorLider) {
								$lideres [] = $grupo1728;
							} else {
								foreach ($grupo1728->getPessoasAtivas() as $pessoas) {
									$lideres [] = $pessoas;
								}
							}
							if ($grupoPaiFilhoFilhos20736 = $grupo1728->getGrupoPaiFilhoFilhosAtivosReal()) {
								foreach ($grupoPaiFilhoFilhos20736 as $grupoPaiFilhoFilho20736) {
									$grupo20736 = $grupoPaiFilhoFilho20736->getGrupoPaiFilhoFilho();
									if (!$separadosPorLider) {
										$lideres [] = $grupo20736;
									} else {
										foreach ($grupo20736->getPessoasAtivas() as $pessoas) {
											$lideres [] = $pessoas;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $lideres;
	}

	public function selecionarReposicoesAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$turmas = $entidade->getGrupo()->getGrupoIgreja()->getTurma();
		$formulario = new SelecionarCarterinhasForm();

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();

		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
			$postado['idEquipe'] = $post['idEquipe'];
			$postado['idSub'] = $post['idSub'];
			$postado['somenteUltimaAula'] = $post['somenteUltimaAula'];

			if($postado['idTurma'] == 0){
				$turmasFiltradas = $turmas;
			}else{
				foreach($turmas as $turma){
					if($turma->getId() == $postado['idTurma']){
						$turmasFiltradas[] = $turma;
					}
				}
			}
			if($postado['idEquipe'] != 0){
				$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe']);
				$grupoPaiFilhoFilhosEquipe = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos(0);

				foreach($grupoPaiFilhoFilhosEquipe as $grupoPaiFilho){
					$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
					$dados = array();
					$dados['id'] = $grupoFilho->getId();
					$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
					$filhos[] =  $dados;
				}
			}

			$grupoParaVerificar = $postado['idEquipe'];
			if($postado['idSub'] != 0){
				$grupoParaVerificar = $postado['idSub'];
			}
			$relatorio = CursoController::pegarAlunosComFaltas($grupo->getGrupoIgreja(), $turmasFiltradas, $grupoParaVerificar, $postado['somenteUltimaAula']);
			$alunosComReposicoes = $relatorio[0];
			$faltas = $relatorio[1];
		}else{
			$turmasFiltradas = $turmas;
		}

		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);
		$view = new ViewModel(array(
			'turmas' => $turmas,
			'turmasFiltradas' => $turmasFiltradas,
			'filtrado' => $filtrado,
			'postado' => $postado,
			'filhos' => $grupoPaiFilhoFilhos,
			'alunosComReposições' => $alunosComReposicoes,
			'faltas' => $faltas,
			'formulario' => $formulario,
			'subs' => $filhos,
		));

		self::registrarLog(RegistroAcao::VER_REPOSICOES, $extra = '');
		return $view;
	}

	public static function pegarAlunosComFaltas($grupo, $turmas = null, $idEquipe, $somenteUltimaAula = 0) {
		if (!$turmas) {
			$turmas = $grupo->getGrupoIgreja()->getTurma();
		}
		$alunosComReposições = array();
		$faltas = array();
		foreach ($turmas as $turma) {
			$turmaAulaAtiva = $turma->getTurmaAulaAtiva();
			foreach ($turma->getTurmaPessoa() as $turmaPessoa) {
				$verificarAluno = false;
				if ($turmaPessoa->verificarSeEstaAtivo() &&
					($turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ATIVO ||
					$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ESPECIAL)){
						if($idEquipe == 0){
							$verificarAluno = true;
						}else{
							if($turmaPessoa->getPessoa()->getGrupoPessoaAtivo() &&
								($turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getGrupoEquipe()->getId() == $idEquipe
								|| $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getGrupoSubEquipe()->getId() == $idEquipe)){
								$verificarAluno = true;
							}
						}
					}

				if ($verificarAluno) {
					$mostrar = false;
					$turmaPessoaAulas = $turmaPessoa->getTurmaPessoaAula();
					$parar = false;
					foreach ($turma->getCurso()->getDisciplina() as $disciplina) {
						if($turmaAulaAtiva && $turmaAulaAtiva->getAula()->getDisciplina()->getId() === $disciplina->getId()){
							if (!$parar) {
								foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
									if ($turmaAulaAtiva) {
										if ($aula->getPosicao() === $turmaAulaAtiva->getAula()->getPosicao()) {
											$parar = true;
											break;
										}
									} else {
										$parar = true;
										break;
									}

									$naoEncontreiPresencaNaAula = true;
									foreach ($turmaPessoaAulas as $turmaPessoaAula) {
										if ($turmaPessoaAula->getAula()->getId() === $aula->getId()) {
											$naoEncontreiPresencaNaAula = false;
										}
									}
									if ($naoEncontreiPresencaNaAula) {
										$adicionar = false;
										if(
											($somenteUltimaAula == '' || $somenteUltimaAula == 0)
											|| ($somenteUltimaAula == 1 && $aula->getPosicao() == $turmaAulaAtiva->getAula()->getPosicao()-1)
										){
											$adicionar = true;
										}
										if($adicionar){
											$mostrar = true;
											$faltas[$turma->getId()][$turmaPessoa->getId()][] = ['Aula ' . $aula->getPosicao(), $aula->getId()];
										}
									}
							}
							}
						}
					}
					if ($mostrar) {
						$alunosComReposições[$turma->getId()][] = $turmaPessoa;
					}
				}
			}
		}
		$relatorio[0] = $alunosComReposições;
		$relatorio[1] = $faltas;
		return $relatorio;
	}

	public static function nomeEquipeTurmaPessoa($turmaPessoa, $grupoPessoa = null) {
		$nomeEquipe = '';
		$grupoPessoaAtivo = null;
		if($grupoPessoa === null){
			$grupoPessoaAtivo = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo();
		}else{
			$grupoPessoaAtivo = $grupoPessoa;
		}
		if($grupoPessoaAtivo){
			if($grupoPessoaAtivo->getGrupo()->getEntidadeAtiva()){
				$ultimaEntidade = null;
				foreach($grupoPessoaAtivo->getGrupo()->getEntidade() as $entidade){
					if($ultimaEntidade === null){
						$ultimaEntidade = $entidade;
					}else{
						if($entidade->getId > $ultimaEntidade->getId()){
							$ultimaEntidade = $entidade;
						}
					}
				}
				$nomeEquipe = $entidade->getNome();
				if($nomeEquipe == ''){
					$nomeEquipe = $entidade->infoEntidade();
					if($nomeEquipe == ''){
						$nomeEquipe = $entidade->getGrupo()->getGrupoEquipe()->getEntidadeAtiva()->getNome();
					}
				}
			}
		}
		return $nomeEquipe;
	}

	public static function verificarSeParticipaDeUmaSub($turmaPessoa, $idGrupo){
		$participaDaSub = false;

		if ($grupoSelecionado = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()) {
			if ($grupoSelecionado->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::igreja &&
				$grupoSelecionado->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::equipe) {
					$grupoSelecionado = $grupoSelecionado->getGrupo();
					while($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::equipe){

						if($grupoSelecionado->getId() == $idGrupo){
							$participaDaSub = true;
							break;
						}
						$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
					}

				}
		}
		return $participaDaSub;
	}

	public function gerarReposicaoAction() {
		$request = $this->getRequest();
		$reposicoes = array();
		if ($request->isPost()) {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 4) == 'aula') {
					$explodeValor = explode('_', $value);
					$aula = $this->getRepositorio()->getAulaORM()->encontrarPorId($explodeValor[0]);
					$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($explodeValor[1]);
					$reposicao['idAula'] = $aula->getId();
					$reposicao['idTurmaPessoa'] = $turmaPessoa->getId();
					$reposicao['idPessoa'] = $turmaPessoa->getPessoa()->getId();
					$reposicao['idGrupoEquipe'] = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getId();
					$reposicoes[] = $reposicao;
				}
			}
		}
		$viewModel = new ViewModel(
			array(
				'reposicoes' => $reposicoes,
				'repositorio' => $this->getRepositorio(),
			));
		$viewModel->setTerminal(true);

		self::registrarLog(RegistroAcao::GEROU_REPOSICOES, $extra = '');
		return $viewModel;
	}

	public function gerarFaltasAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$turmas = $grupo->getGrupoIgreja()->getTurma();
		$contadorDeFaltas = array();
		$turmasValidas = array();
		foreach ($turmas as $turma) {
				if ($turmaAulaAtiva = $turma->getTurmaAulaAtiva()) {
					foreach ($turma->getTurmaPessoa() as $turmaPessoa) {
						/* Alunos ativos */
						if($turmaPessoa->verificarSeEstaAtivo() && $turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ATIVO){

							$turmaPessoaAulas = $turmaPessoa->getTurmaPessoaAula();
							$parar = false;
							foreach ($turma->getCurso()->getDisciplina() as $disciplina) {
								$mostrar = false;
								if ($turma->getTurmaAulaAtiva() && $turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId() === $disciplina->getId()) {
									$mostrar = true;
								}
								if ($mostrar) {
									if (!$parar) {
										/* Verificar duas aulas antes da atual aula aberta */
										$numeroDaAula = $turmaAulaAtiva->getAula()->getPosicao();
										if($numeroDaAula >= 3){

											$estaNoArray = false;
											if(count($turmasValidas) > 0){
												foreach($turmasValidas as $turmaValida){
													if($turmaValida->getId() === $turma->getId()){
														$estaNoArray = true;
													}
												}
											}
											if(!$estaNoArray){
												$turmasValidas[] = $turma;
											}
											foreach ($disciplina->getAulaOrdenadasPorPosicao() as $aula) {
												if($aula->getPosicao() <= ($numeroDaAula - 2)){
													$naoEncontreiPresencaNaAula = true;
													foreach ($turmaPessoaAulas as $turmaPessoaAula) {
														if ($turmaPessoaAula->verificarSeEstaAtivo() && $turmaPessoaAula->getAula()->getId() === $aula->getId()) {
															$naoEncontreiPresencaNaAula = false;
														}
													}
													if ($naoEncontreiPresencaNaAula) {
														$nomeEquipeDoTurmaPessoa = CursoController::getNomeDaEquipeDoTurmaPessoa($turmaPessoa);
														$contadorDeFaltas[$nomeEquipeDoTurmaPessoa][$turma->getId()] ++;
													}
													if ($aula->getId() == $turmaAulaAtiva->getAula()->getId()) {
														$parar = true;
														break;
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
		$view = new ViewModel(array(
			'contadorDeFaltas' => $contadorDeFaltas,
			'turmasValidas' => $turmasValidas,
		));
		return $view;
	}

	static function getNomeDaEquipeDoTurmaPessoa($turmaPessoa) {
		$resposta = 'IGREJA';
		if ($turmaPessoa->getPessoa()->getGrupoPessoaAtivo()) {
			$grupoSelecionado = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo();
			if($grupoSelecionado->getEntidadeAtiva()){
				if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
					$numeroSub = '';
					while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
						if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
							$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
							if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
								break;
							}
						} else {
							break;
						}
					}
					$resposta = $grupoSelecionado->getEntidadeAtiva()->getNome();
				} else {
					$resposta = $grupoSelecionado->getEntidadeAtiva()->getNome();
				}
			}
		}
		return $resposta;
	}

	public function alunoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);

		$possoAcessarIsso = false;

		if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
			$possoAcessarIsso = true;
		}
		if($pessoa->getPessoaCursoAcessoAtivo() && ($pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR ||
			$pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::SUPERVISOR ||
			$pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::FACILITADOR)){
				$possoAcessarIsso = true;
			}

		if($possoAcessarIsso){
			$idTurmaPessoa = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
			$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
			$fatoCurso = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorTurmaPessoa($idTurmaPessoa)[0];
			$idGrupo = substr($fatoCurso->getNumero_identificador(), (count($fatoCurso->getNumero_identificador())-8));
			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
			$nomeEquipe = $grupo->getEntidadeAtiva()->infoEntidade();
			if($nomeEquipe == ''){
				$nomeEquipe = $grupo->getGrupoEquipe()->getEntidadeAtiva()->getNome();
			}
			$situacao = $this->getRepositorio()->getSituacaoORM()->encontrarPorId($fatoCurso->getSituacao_id());
			$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();
			$view = new ViewModel(array(
				'turmaPessoa' => $turmaPessoa,
				'situacoes' => $situacoes,
				'pessoa' => $pessoa,
				'entidade' => $entidade,
				'fatoCurso' => $fatoCurso,
				'situacao' => $situacao,
				'nomeEquipe' => $nomeEquipe,
			));
			return $view;
		}else{
			return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
				Constantes::$ACTION => 'chamada',
			));
		}
	}

	/**
	 * Alterar situacao do turma pessoa
	 * @return Json
	 */
	public function mudarSituacaoAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$resposta = false;
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();
				$idTurmaPessoa = $post_data['idTurmaPessoa'];
				$idSituacao = (int) $post_data['idSituacao'];
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
				if ($turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() != $idSituacao) {
					$turmaPessoaSituacaoAtiva = $turmaPessoa->getTurmaPessoaSituacaoAtiva();
					$turmaPessoaSituacaoAtiva->setDataEHoraDeInativacao();
					$this->getRepositorio()->getTurmaPessoaSituacaoORM()->persistir($turmaPessoaSituacaoAtiva, $trocaDataDeCriacao = false);

					$turmaPessoaSituacaoNova = new TurmaPessoaSituacao();
					$turmaPessoaSituacaoNova->setTurma_pessoa($turmaPessoa);
					$turmaPessoaSituacaoNova->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId($idSituacao));
					$this->getRepositorio()->getTurmaPessoaSituacaoORM()->persistir($turmaPessoaSituacaoNova);

					if($fatosCurso = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorTurmaPessoa($turmaPessoa->getId())){
						foreach($fatosCurso as $fatoCurso){
							if($fatoCurso->verificarSeEstaAtivo()){
								$fatoCurso->setDataEHoraDeInativacao();
								$this->getRepositorio()->getFatoCursoORM()->persistir($fatoCurso, $trocarDataDeCriacao = false);
							}
						}
					}

					$numeroIdentificador =
						$this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo());
					$fatoCurso = new FatoCurso();
					$fatoCurso->setNumero_identificador($numeroIdentificador);
					$fatoCurso->setTurma_pessoa_id($turmaPessoa->getId());
					$fatoCurso->setTurma_id($turmaPessoa->getTurma()->getId());
					$fatoCurso->setSituacao_id($idSituacao);
					$this->getRepositorio()->getFatoCursoORM()->persistir($fatoCurso);
				}

				$this->getRepositorio()->fecharTransacao();
				$resposta = true;
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
		$response->setContent(Json::encode(array('response' => $resposta)));
		return $response;
	}

	/**
	 * Inativa o turma pessoa
	 * @return Json
	 */
	public function removerDaTurmaAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$resposta = false;
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();
				$post_data = $request->getPost();
				$idTurmaPessoa = $post_data['idTurmaPessoa'];
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
				$turmaPessoa->setDataEHoraDeInativacao();
				$this->getRepositorio()->getTurmaPessoaORM()->persistir($turmaPessoa, $trocaDataDeCriacao = false);

				if($fatosCurso = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorTurmaPessoa($turmaPessoa->getId())){
					foreach($fatosCurso as $fatoCurso){
						if($fatoCurso->verificarSeEstaAtivo()){
							$fatoCurso->setDataEHoraDeInativacao();
							$this->getRepositorio()->getFatoCursoORM()->persistir($fatoCurso, $trocarDataDeCriacao = false);
						}
					}
				}

				$this->getRepositorio()->fecharTransacao();
				$resposta = true;
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
		$response->setContent(Json::encode(array('response' => $resposta)));
		return $response;
	}

	/**
	 * Muda a presença,visto ou financeiro de uma turma pessoa
	 * @return Json
	 */
	public function mudarPresencaOuVistoOuFinanceiroAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$this->getRepositorio()->iniciarTransacao();

				$post_data = $request->getPost();
				$valor = $post_data['valor'];
				$idTurmaPessoa = (int) $post_data['idTurmaPessoa'];
				$idAulaOuDisciplina = (int) $post_data['idAulaOuDisciplina'];
				$tipoDeLancamento = (int) $post_data['tipoDeLancamento'];

				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);

				$tipoDeLancamentoPresenca = 1;
				$tipoDeLancamentoVisto = 2;
				$tipoDeLancamentoFinanceiro = 3;
				$tipoDeLancamentoAvaliacao = 4;
				if ($tipoDeLancamento === $tipoDeLancamentoPresenca) {
					$aulaOuDisciplina = $this->getRepositorio()->getAulaORM()->encontrarPorId($idAulaOuDisciplina);
					$turmaPessoaElemento = $turmaPessoa->getTurmaPessoaAulaPorAula($aulaOuDisciplina->getId());
					if (!$turmaPessoaElemento) {
						$turmaPessoaElemento = new TurmaPessoaAula();
						$turmaPessoaElemento->setReposicao('N');
					}
					$turmaPessoaElemento->setAula($aulaOuDisciplina);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoVisto) {
					$aulaOuDisciplina = $this->getRepositorio()->getAulaORM()->encontrarPorId($idAulaOuDisciplina);
					$turmaPessoaElemento = $turmaPessoa->getTurmaPessoaVistoPorAula($aulaOuDisciplina->getId());
					if (!$turmaPessoaElemento) {
						$turmaPessoaElemento = new TurmaPessoaVisto();
					}
					$turmaPessoaElemento->setAula($aulaOuDisciplina);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoFinanceiro) {
					$aulaOuDisciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idAulaOuDisciplina);
					$turmaPessoaElemento = $turmaPessoa->getTurmaPessoaFinanceiroPorDisciplina($aulaOuDisciplina->getId());
					if (!$turmaPessoaElemento) {
						$turmaPessoaElemento = new TurmaPessoaFinanceiro();
					}
					$turmaPessoaElemento->setDisciplina($aulaOuDisciplina);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoAvaliacao) {
					$aulaOuDisciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idAulaOuDisciplina);
					$turmaPessoaElemento = $turmaPessoa->getTurmaPessoaAvaliacaoPorDisciplina($aulaOuDisciplina->getId());
					if (!$turmaPessoaElemento) {
						$turmaPessoaElemento = new TurmaPessoaAvaliacao();
					}
					$turmaPessoaElemento->setDisciplina($aulaOuDisciplina);
					$qualAvaliacao = (int) $post_data['qualAvaliacao'];

					switch ($qualAvaliacao) {
					case 1:
						$turmaPessoaElemento->setAvaliacao1($valor);
						break;
					case 2:
						$turmaPessoaElemento->setAvaliacao2($valor);
						break;
					case 3:
						$turmaPessoaElemento->setExtra($valor);
						break;
					}
				}
				$turmaPessoaElemento->setTurma_pessoa($turmaPessoa);

				if ($tipoDeLancamento !== $tipoDeLancamentoAvaliacao) {
					if ($valor === 'S') {
						$turmaPessoaElemento->setData_inativacao(null);
						$turmaPessoaElemento->setHora_inativacao(null);
					} else {
						$turmaPessoaElemento->setDataEHoraDeInativacao();
					}
				}

				if ($tipoDeLancamento === $tipoDeLancamentoPresenca) {
					$this->getRepositorio()->getTurmaPessoaAulaORM()->persistir($turmaPessoaElemento);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoVisto) {
					$this->getRepositorio()->getTurmaPessoaVistoORM()->persistir($turmaPessoaElemento);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoFinanceiro) {
					$this->getRepositorio()->getTurmaPessoaFinanceiroORM()->persistir($turmaPessoaElemento);
				}
				if ($tipoDeLancamento === $tipoDeLancamentoAvaliacao) {
					$this->getRepositorio()->getTurmaPessoaAvaliacaoORM()->persistir($turmaPessoaElemento);
				}

				$this->getRepositorio()->fecharTransacao();
				$response->setContent(Json::encode(array('response' => 'true')));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	public function aproveitamentoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos(0);
		$turmas = $grupo->getGrupoIgreja()->getTurma();
		$cursoInstituoDeVencedores = $this->getRepositorio()->getCursoORM()->encontrarPorId(Curso::INSTITUTO_DE_VENCEDORES);
		$disciplinas = $cursoInstituoDeVencedores->getDisciplina();

		/* Montar relatorio */
		$relatorio = array();
		$relatorioDiscipulos = array();
		$somaGeral = array();
		$periodoAtual = 0;
		$grupoIgreja = $grupo->getGrupoIgreja();
		$grupoPessoasIgreja = $grupoIgreja->getGrupoPessoasNoPeriodo($periodoAtual);

		$relatorio[0]['lideres'] = $grupoIgreja->getNomeLideresAtivos();
		$relatorio[0]['entidade'] = 'IGREJA';
		foreach ($turmas as $turma) {
			if ($turma->getTurmaPessoa()) {
				foreach ($turma->getTurmaPessoa() as $turmaPessoa) {
					foreach ($grupoPessoasIgreja as $grupoPessoaIgreja) {
						if ($grupoPessoaIgreja->getPessoa()->getId() === $turmaPessoa->getPessoa()->getId()) {
							$relatorio[0][$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][0] ++;
							$relatorio[0][$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId()] ++;

							$somaGeral[$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][0] ++;
							$somaGeral[$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId()] ++;
						}
					}
					foreach ($grupoPaiFilhoFilhos as $filho) {
						$grupoFilho = $filho->getGrupoPaiFilhoFilho();
						if ($turmaPessoa->getPessoa()->getGrupoPessoa()[0]->getGrupo()->getGrupoEquipe()->getEntidadeAtiva()->getId() === $grupoFilho->getEntidadeAtiva()->getId()) {
							$relatorioDiscipulos[$grupoFilho->getId()][$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][0] ++;
							$relatorioDiscipulos[$grupoFilho->getId()][$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getId()][$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId()] ++;
						}
					}
				}
			}
		}

		$relatorioDiscipulosDesordenados = array();
		foreach ($grupoPaiFilhoFilhos as $filho) {
			$grupoFilho = $filho->getGrupoPaiFilhoFilho();

			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][0] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_UM][0];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::DESISTENTE] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::DESISTENTE];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA];
			$comparador = 0.3;
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM]['performance'] = ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL]) / ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][0] * $comparador) * 100;

			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::DESISTENTE] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::DESISTENTE];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA];
			$comparador = 0.5;
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS]['performance'] = ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL]) / ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0] * $comparador) * 100;

			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][0] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_TRES][0];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::DESISTENTE] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::DESISTENTE];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulos[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA];
			$comparador = 0.7;
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES]['performance'] = ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL]) / ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][0] * $comparador) * 100;

			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][0] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][0] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][0];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ATIVO] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ESPECIAL] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::DESISTENTE] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::DESISTENTE] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::DESISTENTE] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::DESISTENTE];
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA];

			$mediaDasPerformance = ($relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM]['performance'] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS]['performance'] + $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES]['performance']) / 3;
			$relatorioDiscipulosDesordenados[$grupoFilho->getId()]['performance'] = $mediaDasPerformance;
		}

		$discipulosOrdenados = RelatorioController::ordenacaoDiscipulos($grupoPaiFilhoFilhos, $relatorioDiscipulosDesordenados, RelatorioController::ORDENACAO_TIPO_PERFORMANCE);


		$contadorDeEquipes = 1;
		foreach ($discipulosOrdenados as $discipulo) {
			$grupoFilho = $discipulo->getGrupoPaiFilhoFilho();
			$relatorio[$contadorDeEquipes]['lideres'] = $grupoFilho->getNomeLideresAtivos();
			$relatorio[$contadorDeEquipes]['entidade'] = $grupoFilho->GetEntidadeAtiva()->infoEntidade();

			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][0] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][0];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ATIVO] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ESPECIAL] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::DESISTENTE] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::DESISTENTE];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM]['performance'] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM]['performance'];

			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][0] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ATIVO] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ESPECIAL] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::DESISTENTE] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::DESISTENTE];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS]['performance'] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS]['performance'];

			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][0] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][0];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ATIVO] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ESPECIAL] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::DESISTENTE] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::DESISTENTE];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA];
			$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES]['performance'] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES]['performance'];

			$relatorio[$contadorDeEquipes]['total'][0] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][0];
			$relatorio[$contadorDeEquipes]['total'][Situacao::ATIVO] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ATIVO];
			$relatorio[$contadorDeEquipes]['total'][Situacao::ESPECIAL] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ESPECIAL];
			$relatorio[$contadorDeEquipes]['total'][Situacao::DESISTENTE] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::DESISTENTE];
			$relatorio[$contadorDeEquipes]['total'][Situacao::REPROVADO_POR_FALTA] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::REPROVADO_POR_FALTA];
			$relatorio[$contadorDeEquipes]['performance'] = $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['performance'];

			$contadorDeEquipes++;

			$somaGeral[Disciplina::MODULO_UM][0] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][0];
			$somaGeral[Disciplina::MODULO_UM][Situacao::ATIVO] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ATIVO];
			$somaGeral[Disciplina::MODULO_UM][Situacao::ESPECIAL] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::ESPECIAL];
			$somaGeral[Disciplina::MODULO_UM][Situacao::DESISTENTE] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::DESISTENTE];
			$somaGeral[Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA];

			$somaGeral[Disciplina::MODULO_DOIS][0] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][0];
			$somaGeral[Disciplina::MODULO_DOIS][Situacao::ATIVO] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ATIVO];
			$somaGeral[Disciplina::MODULO_DOIS][Situacao::ESPECIAL] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::ESPECIAL];
			$somaGeral[Disciplina::MODULO_DOIS][Situacao::DESISTENTE] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::DESISTENTE];
			$somaGeral[Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA];

			$somaGeral[Disciplina::MODULO_TRES][0] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][0];
			$somaGeral[Disciplina::MODULO_TRES][Situacao::ATIVO] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ATIVO];
			$somaGeral[Disciplina::MODULO_TRES][Situacao::ESPECIAL] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::ESPECIAL];
			$somaGeral[Disciplina::MODULO_TRES][Situacao::DESISTENTE] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::DESISTENTE];
			$somaGeral[Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA];

			$somaGeral['total'][0] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][0];
			$somaGeral['total'][Situacao::ATIVO] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ATIVO];
			$somaGeral['total'][Situacao::ESPECIAL] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::ESPECIAL];
			$somaGeral['total'][Situacao::DESISTENTE] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::DESISTENTE];
			$somaGeral['total'][Situacao::REPROVADO_POR_FALTA] += $relatorioDiscipulosDesordenados[$grupoFilho->getId()]['total'][Situacao::REPROVADO_POR_FALTA];
		}

		$relatorio[$contadorDeEquipes]['entidade'] = 'TOTAL';
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][0] = $somaGeral[Disciplina::MODULO_UM][0];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ATIVO] = $somaGeral[Disciplina::MODULO_UM][Situacao::ATIVO];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ESPECIAL] = $somaGeral[Disciplina::MODULO_UM][Situacao::ESPECIAL];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::DESISTENTE] = $somaGeral[Disciplina::MODULO_UM][Situacao::DESISTENTE];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA] = $somaGeral[Disciplina::MODULO_UM][Situacao::REPROVADO_POR_FALTA];
		$comparador = 0.3;
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_UM]['performance'] = ($relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ATIVO] + $relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][Situacao::ESPECIAL]) / ($relatorio[$contadorDeEquipes][Disciplina::MODULO_UM][0] * $comparador) * 100;

		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][0] = $somaGeral[Disciplina::MODULO_DOIS][0];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ATIVO] = $somaGeral[Disciplina::MODULO_DOIS][Situacao::ATIVO];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ESPECIAL] = $somaGeral[Disciplina::MODULO_DOIS][Situacao::ESPECIAL];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::DESISTENTE] = $somaGeral[Disciplina::MODULO_DOIS][Situacao::DESISTENTE];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA] = $somaGeral[Disciplina::MODULO_DOIS][Situacao::REPROVADO_POR_FALTA];
		$comparador = 0.5;
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS]['performance'] = ($relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ATIVO] + $relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][Situacao::ESPECIAL]) / ($relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS][0] * $comparador) * 100;

		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][0] = $somaGeral[Disciplina::MODULO_TRES][0];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ATIVO] = $somaGeral[Disciplina::MODULO_TRES][Situacao::ATIVO];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ESPECIAL] = $somaGeral[Disciplina::MODULO_TRES][Situacao::ESPECIAL];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::DESISTENTE] = $somaGeral[Disciplina::MODULO_TRES][Situacao::DESISTENTE];
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA] = $somaGeral[Disciplina::MODULO_TRES][Situacao::REPROVADO_POR_FALTA];
		$comparador = 0.7;
		$relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES]['performance'] = ($relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ATIVO] + $relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][Situacao::ESPECIAL]) / ($relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES][0] * $comparador) * 100;

		$relatorio[$contadorDeEquipes]['total'][0] = $somaGeral['total'][0];
		$relatorio[$contadorDeEquipes]['total'][Situacao::ATIVO] = $somaGeral['total'][Situacao::ATIVO];
		$relatorio[$contadorDeEquipes]['total'][Situacao::ESPECIAL] = $somaGeral['total'][Situacao::ESPECIAL];
		$relatorio[$contadorDeEquipes]['total'][Situacao::DESISTENTE] = $somaGeral['total'][Situacao::DESISTENTE];
		$relatorio[$contadorDeEquipes]['total'][Situacao::REPROVADO_POR_FALTA] = $somaGeral['total'][Situacao::REPROVADO_POR_FALTA];
		$relatorio[$contadorDeEquipes]['performance'] = ($relatorio[$contadorDeEquipes][Disciplina::MODULO_UM]['performance'] + $relatorio[$contadorDeEquipes][Disciplina::MODULO_DOIS]['performance'] + $relatorio[$contadorDeEquipes][Disciplina::MODULO_TRES]['performance']) / 3;

		$dados = array(
			'grupoPaiFilhoFilhos' => $grupoPaiFilhoFilhos,
			'disciplinas' => $disciplinas,
			'relatorio' => $relatorio,
		);

		return new ViewModel($dados);
	}

	public function reciboFinanceiroAction(){
		$idPassado = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		$explodeId = explode('9999999999', $idPassado);

		$dados = array();
		$dados['turmaPessoa'] = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($explodeId[0]);
		$dados['disciplina'] = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($explodeId[1]);

		$view = new ViewModel($dados);
		$view->setTerminal(true);
		return $view;
	}

	public function financeiroPorDataAction(){
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){
			$sessao = new Container(Constantes::$NOME_APLICACAO);

			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

			$postDados = $request->getPost();

			$dia1 = $postDados['dia1'];
			$mes1 = $postDados['mes1'];
			$ano1 = $postDados['ano1'];

			$dia2 = $postDados['dia2'];
			$mes2 = $postDados['mes2'];
			$ano2 = $postDados['ano2'];

			$dados['filtrado'] = true;

			$dataInicial = $ano1 . '-' . $mes1 . '-' . $dia1;
			$dataFinal = $ano2 . '-' . $mes2 . '-' . $dia2;
			$turmaPessoaFinanceiros = $this->getRepositorio()->getTurmaPessoaFinanceiroORM()->encontrarPorDatas($dataInicial, $dataFinal);
			$dados['turmaPessoaFinanceiros'] = $turmaPessoaFinanceiros;
		}else{
			$dia1 = date('d');
			$mes1 = date('m');
			$ano1 = date('Y');

			$dia2 = date('d');
			$mes2 = date('m');
			$ano2 = date('Y');
		}

		$dados['dia'][1] = $dia1;
		$dados['mes'][1] = $mes1;
		$dados['ano'][1] = $ano1;
		$dados['dia'][2] = $dia2;
		$dados['mes'][2] = $mes2;
		$dados['ano'][2] = $ano2;

		$view = new ViewModel($dados);
		return $view;
	}
}
