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
		$grupo = $entidade->getGrupo();
		$turmas = $grupo->getGrupoIgreja()->getTurma();
		$view = new ViewModel(array(
			'turmas' => $turmas,
		));

		return $view;
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
						$turmaPessoa = new TurmaPessoa();
						$turmaPessoa->setPessoa($pessoa);
						$turmaPessoa->setTurma($turma);
						$this->getRepositorio()->getTurmaPessoaORM()->persistir($turmaPessoa);

						$situacao = $this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ATIVO);
						$turmaPessoaSituacao = new TurmaPessoaSituacao();
						$turmaPessoaSituacao->setSituacao($situacao);
						$turmaPessoaSituacao->setTurma_pessoa($turmaPessoa);
						$this->getRepositorio()->getTurmaPessoaSituacaoORM()->persistir($turmaPessoaSituacao);
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

	public function chamadaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$turmas = $grupo->getGrupoIgreja()->getTurma();
		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);
		$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);

		$request = $this->getRequest();
		$filtrado = false;
		$postado = array();
		$subs = null;

		if($request->isPost()){
			$filtrado = true;
			$post = $request->getPost();
			$postado['idTurma'] = $post['idTurma'];
			$postado['idEquipe'] = $post['idEquipe'];
			$postado['idSituacao'] = $post['idSituacao'];
			$postado['mostrarAulas'] = $post['mostrarAulas'];
			$postado['idSub'] = $post['idSub'];

			if($postado['idEquipe'] != 0){
				$grupoEquipe = $this->getRepositorio()->getGrupoORM()->encontrarPorId($postado['idEquipe']);
				$grupoPaiFilhoFilhosEquipe = $grupoEquipe->getGrupoPaiFilhoFilhosAtivos(0);

				$filhos = array();
				foreach($grupoPaiFilhoFilhosEquipe as $grupoPaiFilho){
					$grupoFilho = $grupoPaiFilho->getGrupoPaiFilhoFilho();
					$dados = array();
					$dados['id'] = $grupoFilho->getId();
					$dados['informacao'] = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();	
					$filhos[] =  $dados;
				}
			}
		}

		if(!$pessoa->getPessoaCursoAcessoAtivo()){
			$postado['mostrarAulas'] = 1;
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

	public function selecionarParaCarterinhaAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$turmas = $entidade->getGrupo()->getGrupoIgreja()->getTurma();
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
		return new ViewModel(array('titulo' => 'Lançar Presença/Reposições'));
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
			if ($this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa)) {
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($idTurmaPessoa);
			}
			if ($this->getRepositorio()->getTurmaPessoaORM()->encontrarPorIdAntigo($idTurmaPessoa)) {
				$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorIdAntigo($idTurmaPessoa);
			}
			if ($turmaPessoa) {
				$resposta = true;
				$nomeCurso = $turmaPessoa->getTurma()->getCurso()->getNome();
				$nomeTurma = Funcoes::mesPorExtenso($turmaPessoa->getTurma()->getMes(), 1) . '/' . $turmaPessoa->getTurma()->getAno();
				$nomeEquipe = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade();
				$nomePessoa = $turmaPessoa->getPessoa()->getNome();

				$temAulaAtiva = false;
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
				'curso' => $nomeCurso,
				'turma' => $nomeTurma,
				'equipe' => $nomeEquipe,
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

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_CURSO, array(
					Constantes::$ACTION => 'Chamada',
				));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getMessage();
			}
		}
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

			if($postado['idTurma'] == 0){
				$turmasFiltradas = $turmas;
			}else{
				foreach($turmas as $turma){
					if($turma->getId() == $postado['idTurma']){
						$turmasFiltradas[] = $turma;
					}
				}
			}
		}else{
			$turmasFiltradas = $turmas;
		}

		$grupoPaiFilhoFilhos = $grupo->getGrupoIgreja()->getGrupoPaiFilhoFilhosAtivos(0);
		$relatorio = CursoController::pegarAlunosComFaltas($grupo->getGrupoIgreja(), $turmasFiltradas, $postado['idEquipe']);
		$alunosComReposições = $relatorio[0];
		$faltas = $relatorio[1];

		$view = new ViewModel(array(
			'turmas' => $turmas,
			'turmasFiltradas' => $turmasFiltradas,
			'filtrado' => $filtrado,
			'postado' => $postado,
			'filhos' => $grupoPaiFilhoFilhos,
			'alunosComReposições' => $alunosComReposições,
			'faltas' => $faltas,
			'formulario' => $formulario,
		));

		return $view;
	}

	public static function pegarAlunosComFaltas($grupo, $turmas = null, $idEquipe) {
		if (!$turmas) {
			$turmas = $grupo->getGrupoIgreja()->getTurma();
		}
		$alunosComReposições = array();
		$faltas = array();
		foreach ($turmas as $turma) {
			$turmaAulaAtiva = $turma->getTurmaAulaAtiva();
			foreach ($turma->getTurmaPessoa() as $turmaPessoa) {
				$verificarAluno = false;
				if ($turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ATIVO ||
					$turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ESPECIAL)	{
						if($idEquipe == 0){
							$verificarAluno = true;
						}else{
							if($turmaPessoa->getPessoa()->getGrupoPessoaAtivo() && $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getGrupoEquipe()->getId() == $idEquipe){
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
									$naoEncontreiPresencaNaAula = true;
									foreach ($turmaPessoaAulas as $turmaPessoaAula) {
										if ($turmaPessoaAula->getAula()->getId() === $aula->getId()) {
											$naoEncontreiPresencaNaAula = false;
										}
									}
									if ($naoEncontreiPresencaNaAula) {
										$mostrar = true;
										$faltas[$turma->getId()][$turmaPessoa->getId()][] = ['Aula ' . $aula->getPosicao(), $aula->getId()];
									}
									if ($turmaAulaAtiva) {
										if ($aula->getId() == $turmaAulaAtiva->getAula()->getId()) {
											$parar = true;
											break;
										}
									} else {
										$parar = true;
										break;
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

	public static function nomeEquipeTurmaPessoa($turmaPessoa) {
		$nomeEquipe = '';
		if ($turmaPessoa->getPessoa()->getGrupoPessoaAtivo()) {
			if ($turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$nomeEquipe = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->getNome();
			} else {
				$nomeEquipe = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade();
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
					/* Alunos ativos ou especiais */
					if($turmaPessoa->getTurmaPessoaSituacaoAtiva()->getSituacao()->getId() === Situacao::ATIVO){

							$nomeEquipeDoTurmaPessoa = CursoController::getNomeDaEquipeDoTurmaPessoa($turmaPessoa);
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

			$situacoes = $this->getRepositorio()->getSituacaoORM()->buscarTodosRegistrosEntidade();

			$view = new ViewModel(array(
				'turmaPessoa' => $turmaPessoa,
				'situacoes' => $situacoes
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
}
