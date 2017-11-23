<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Form\AulaForm;
use Application\Form\CursoForm;
use Application\Form\DisciplinaForm;
use Application\Model\Entity\Aula;
use Application\Model\Entity\Curso;
use Application\Model\Entity\Disciplina;
use Application\Model\ORM\RepositorioORM;
use Exception;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

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
              return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                          Constantes::$PAGINA => Constantes::$PAGINA_CURSO_LISTAR,
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

      return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                  Constantes::$PAGINA => Constantes::$PAGINA_CURSO_LISTAR,
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
              return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                          Constantes::$PAGINA => Constantes::$PAGINA_DISCIPLINA_LISTAR,
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
      return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                  Constantes::$PAGINA => Constantes::$PAGINA_DISCIPLINA_LISTAR,
      ));
  }

  /**
   * Função de listagem de aula
   */
  public function aulaListarAction() {
      $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
      $sessao = new Container(Constantes::$NOME_APLICACAO);
      $idDisciplina = $sessao->idSessao;
      $disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);
      $aulas = $repositorioORM->getAulaORM()->buscarTodosRegistrosEntidade('posicao', 'ASC');
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
              return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                          Constantes::$PAGINA => Constantes::$PAGINA_AULA_LISTAR,
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
      return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                  Constantes::$PAGINA => Constantes::$PAGINA_AULA_LISTAR,
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

}
