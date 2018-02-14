<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\AulaForm;
use Application\Form\CursoForm;
use Application\Form\DisciplinaForm;
use Application\Form\SelecionarAlunosForm;
use Application\Form\TurmaForm;
use Application\Model\Entity\Aula;
use Application\Model\Entity\Curso;
use Application\Model\Entity\Disciplina;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\Turma;
use Application\Model\Entity\TurmaAula;
use Application\Model\Entity\TurmaPessoa;
use Application\Model\Entity\TurmaPessoaFrequencia;
use Application\Model\Entity\TurmaPessoaSituacao;
use Application\Model\ORM\RepositorioORM;
use DateTime;
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
                $nome = $dadosPost['nome'];
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
                $nome = $dadosPost['nome'];
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
        $aulas = $disciplina->getAula();
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
                $nome = $dadosPost['nome'];
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
        $aulas = $aula->getDisciplina()->getAula();
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
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();

                $dadosPost = $request->getPost();
                $id = $dadosPost['id'];
                $idCurso = $dadosPost['idCurso'];
                $mes = $dadosPost['Mes'];
                $ano = $dadosPost['Ano'];
                $observacao = $dadosPost['observacao'];

                if ($id) {
                    $turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($id);
                } else {
                    $turma = new Turma();
                }

                $idEntidadeAtual = $sessao->idEntidadeAtual;
                $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
                $grupo = $entidade->getGrupo();
                $curso = $this->getRepositorio()->getCursoORM()->encontrarPorId($idCurso);

                $turma->setCurso($curso);
                $turma->setGrupo($grupo);
                $turma->setAno((int) $ano);
                $turma->setMes((int) $mes);
                $turma->setObservacao($observacao);

                if ($id) {
                    $this->getRepositorio()->getTurmaORM()->persistir($turma, false);
                } else {
                    $this->getRepositorio()->getTurmaORM()->persistir($turma);
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

    public function listarTurmaAction() {

        $turmas = $this->getRepositorio()->getTurmaORM()->encontrarTodas();
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
            try {
                $this->getRepositorio()->iniciarTransacao();
                $idEvento = $_POST['idEvento'];
                $evento = $this->getRepositorio()->getEventoORM()->encontrarPorId($idEvento);
                $frequencias = $evento->getEventoFrequencia();
                $pessoas = array();
                if ($frequencias) {
                    foreach ($frequencias as $frequencia) {
                        if ($frequencia->getFrequencia() == 'S') {
                            $adicionar = true;
                            foreach ($_POST as $key => $value) {
                                if ($key == 'alunos' && $frequencia->getPessoa()->getId() != $value) {
                                    $adicionar = false;
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
            foreach ($disciplina->getAula() as $aula) {
                $opcoes[$aula->getId()][0] = $disciplina->getNome() . ' - ' . $aula->getNome();
                $opcoes[$aula->getId()][1] = '';
                if ($idAulaAtiva == $aula->getId()) {
                    $opcoes[$aula->getId()][1] = 'selected';
                }
            }
        }

        return new ViewModel(array(
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
                $turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($idTurma);

                if ($turmaAulaAtiva = $turma->getTurmaAulaAtiva()) {
                    $naoMudarDataDeCriacao = false;
                    $turmaAulaAtiva->setDataEHoraDeInativacao();
                    $this->getRepositorio()->getTurmaAulaORM()->persistir($turmaAulaAtiva, $naoMudarDataDeCriacao);
                }

                if (intval($idAula) !== 0) {
                    $turmaAula = new TurmaAula();
                    $turmaAula->setTurma($turma);
                    $turmaAula->setAula($this->getRepositorio()->getAulaORM()->encontrarPorId($idAula));
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

}
