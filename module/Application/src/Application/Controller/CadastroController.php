<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Correios;
use Application\Controller\Helper\Funcoes;
use Application\Form\AtivarFichaForm;
use Application\Form\AtualizarCadastroForm;
use Application\Form\CadastrarPessoaRevisaoForm;
use Application\Form\CelulaForm;
use Application\Form\CursoForm;
use Application\Form\DisciplinaForm;
use Application\Form\EventoForm;
use Application\Form\GrupoForm;
use Application\Form\SelecionarAlunosForm;
use Application\Form\TransferenciaForm;
use Application\Form\TurmaForm;
use Application\Model\Entity\Curso;
use Application\Model\Entity\Disciplina;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Evento;
use Application\Model\Entity\EventoCelula;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\Turma;
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Exception;
use Migracao\Controller\IndexController;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com> 
 * Descricao: Controle de todas ações de lancamento
 */
class CadastroController extends CircuitoController {

    /**
     * Função padrão, traz a tela para lancamento
     * GET /cadastro[:pagina]
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->pagina = '';
        $extra = '';
        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(Constantes::$PAGINA, 1);
        if ($pagina == Constantes::$PAGINA_EVENTO_CULTO || $pagina == Constantes::$PAGINA_EVENTO_CELULA) {
            if ($pagina == Constantes::$PAGINA_EVENTO_CULTO) {
                $sessao->pagina = Constantes::$PAGINA_EVENTO_CULTO;
            }
            if ($pagina == Constantes::$PAGINA_EVENTO_CELULA) {
                $sessao->pagina = Constantes::$PAGINA_EVENTO_CELULA;
            }
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EVENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_GRUPO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_GRUPO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_GRUPO_FINALIZAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_GRUPO_FINALIZAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_GRUPO_ATUALIZACAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_GRUPO_ATUALIZACAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_GRUPO_ATUALIZAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_GRUPO_ATUALIZAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EVENTO_CULTO_PERSISTIR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EVENTO_CULTO_PERSISTIR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EVENTO_CELULA_PERSISTIR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EVENTO_CELULA_PERSISTIR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EVENTO_EXCLUSAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EVENTO_EXCLUSAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO,
            ));
        }
        /* Busca de endereço por CEP JSON */
        if ($pagina == Constantes::$PAGINA_BUSCAR_ENDERECO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_ENDERECO,
            ));
        }
        /* Busca de CPF JSON */
        if ($pagina == Constantes::$PAGINA_BUSCAR_CPF) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_CPF,
            ));
        }
        /* Busca de Email JSON */
        if ($pagina == Constantes::$PAGINA_BUSCAR_EMAIL) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_BUSCAR_EMAIL,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SOLICITACOES) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SOLICITACOES,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SOLICITACAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SOLICITACAO,
            ));
        }
        /* Páginas Revisão */
        if ($pagina == Constantes::$PAGINA_SELECIONAR_REVISIONISTA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_REVISIONISTA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SALVAR_PESSOA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SALVAR_PESSOA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_FICHA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_FICHA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SELECIONAR_FICHA_REVISIONISTA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_FICHA_REVISIONISTA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CONSULTAR_FICHA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CONSULTAR_FICHA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ATIVAR_FICHA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ATIVAR_RESERVA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_RESERVA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SELECIONAR_FICHA_ATIVAS) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_FICHA_ATIVAS,
            ));
        }
        if ($pagina == Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SELECIONAR_LIDER_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_LIDER_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ATIVAR_LIDERES_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ATIVAR_LIDERES_REVISAO,
            ));
        }
        /* Fim Páginas Revisão */
        /* Início das páginas relaiconadas ao Iv */
        if ($pagina == Constantes::$PAGINA_CADASTRAR_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRAR_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SALVAR_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SALVAR_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EDITAR_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EDITAR_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_LISTAR_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_LISTAR_TURMA_INATIVA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_LISTAR_TURMA_INATIVA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EXCLUIR_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EXCLUIR_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EXCLUSAO_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EXCLUSAO_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SELECIONAR_ALUNOS_TURMA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SELECIONAR_ALUNOS_TURMA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_LISTAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_LISTAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_CADASTRAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_CADASTRAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_SALVAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_SALVAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_EDITAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_EDITAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_EXCLUIR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_EXCLUIR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CURSO_EXCLUSAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CURSO_EXCLUSAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_LISTAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_LISTAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_CADASTRAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_CADASTRAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_SALVAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_SALVAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EDITAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EDITAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EXCLUIR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EXCLUIR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EXCLUSAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EXCLUSAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_LISTAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_LISTAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_CADASTRAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_CADASTRAR,
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_SALVAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_SALVAR,
            ));
        } 
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EDITAR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EDITAR, 
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EXCLUIR) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EXCLUIR, 
            ));
        }
        if ($pagina == Constantes::$PAGINA_DISCIPLINA_EXCLUSAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_DISCIPLINA_EXCLUSAO, 
            ));

        }
        /* Funcoes */
        if ($pagina == Constantes::$PAGINA_FUNCOES) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_FUNCOES,
            )); 
        }

        /* Por titulo e eventos na sessão para passar a tela */
        $listagemDeEventos = null;
        $tituloDaPagina = '';
        /* Listagem de celulas */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        $extra = '';
        $tipoEvento = 0;
        $tipoCelula = 1;
        $tipoCulto = 2;
        $tipoRevisao = 3;
        if ($pagina == Constantes::$PAGINA_CELULAS) {
            $listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoCelula);
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CELULAS . ' <b class="text-danger">' . Constantes::$TRADUCAO_MULTIPLICACAO . '</b>';
            $tipoEvento = 2;
        }
        if ($pagina == Constantes::$PAGINA_CULTOS) {
            $listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoCulto);
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CULTOS;
            $tipoEvento = 1;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_REVISAO) {
            $listagemDeEventos = $grupo->getGrupoEventoAtivosPorTipo($tipoRevisao);
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISAO;
            $tipoEvento = 3;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_REVISIONISTAS) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
            $tipoEvento = 4;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_FICHA_REVISIONISTAS) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
            $tipoEvento = 5;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_ATIVOS_REVISIONISTAS) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
            $tipoEvento = 6;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_LIDERES_REVISAO) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
            $tipoEvento = 7;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_ATIVAR_FICHAS) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISIONISTAS;
            $tipoEvento = 8;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_REVISAO;
            $tipoEvento = 9;
            /* Id da Turma em que os alunos serão selecionados */
            $extra = $sessao->idSessao;
        }


        $view = new ViewModel(array(
            Constantes::$LISTAGEM_EVENTOS => $listagemDeEventos,
            Constantes::$TITULO_DA_PAGINA => $tituloDaPagina,
            Constantes::$TIPO_EVENTO => $tipoEvento,
            Constantes::$EXTRA => $extra,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
        $view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

        return $view;
    }

    /**
     * Função para o formulário de eventos
     * GET /cadastroEvento
     */
    public function eventoAction() {
        $form = null;
        $enderecoHidden = '';
        $extra = null;
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        if ($sessao->pagina == Constantes::$PAGINA_EVENTO_CULTO) {
            /* Verificando a se tem algum id na sessão */
            $eventoNaSessao = new Evento();
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            if (!empty($sessao->idSessao)) {
                $eventoNaSessao = $repositorioORM->getEventoORM()->encontrarPorId($sessao->idSessao);
            }
            $form = new EventoForm(Constantes::$FORM, $eventoNaSessao);
            $idEntidadeAtual = $sessao->idEntidadeAtual;
            $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
            $grupo = $entidade->getGrupo();
            $extra = $grupo->getGrupoPaiFilhoFilhos();
        }
        if ($sessao->pagina == Constantes::$PAGINA_EVENTO_CELULA) {
            /* Verificando a se tem algum id na sessão */
            $eventoCelulaNaSessao = new EventoCelula();
            if (!empty($sessao->idSessao)) {
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $eventoCelulaNaSessao = $repositorioORM->getEventoCelulaORM()->encontrarPorId($sessao->idSessao);
            } else {
                $enderecoHidden = Constantes::$FORM_HIDDEN;
            }
            $form = new CelulaForm(Constantes::$FORM_CELULA, $eventoCelulaNaSessao);
        }

        $view = new ViewModel(array(
            Constantes::$FORM => $form,
            Constantes::$FORM_ENDERECO_HIDDEN => $enderecoHidden,
            Constantes::$EXTRA => $extra,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTO);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTO);

        return $view;
    }

    /**
     * Função para persistir o evento culto
     * POST /eventoCultoPersistir
     */
    public function eventoCultoPersistirAction() {
        CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

        $stringCheckEquipe = 'checkEquipe';
        $request = $this->getRequest();
        if ($request->isPost()) {
            /* Repositorios */
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            try {
                $repositorioORM->iniciarTransacao();
                $post_data = $request->getPost();

                /* Entidades */
                $evento = new Evento();
                $eventoForm = new EventoForm(Constantes::$FORM, $evento);
                $eventoForm->setInputFilter($evento->getInputFilterEventoCulto());
                $eventoForm->setData($post_data);

                /* validação */
                if ($eventoForm->isValid()) {
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $criarNovoEvento = true;
                    $mudarDataDeCadastroParaProximoDomingo = false;
                    $validatedData = $eventoForm->getData();

                    /* Entidades */
                    $evento = new Evento();
                    $grupoEvento = new GrupoEvento();

                    /* ALTERANDO */
                    if (!empty($post_data[Constantes::$FORM_ID])) {
                        $criarNovoEvento = false;
                        $eventoAtual = $repositorioORM->getEventoORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);
                        echo "EventoAtual: " . $eventoAtual->getId();
                        $grupoEventoAtivos = $eventoAtual->getGrupoEventoAtivos();
                        /* Dia foi alterado */
                        if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoAtual->getDia()) {
                            /* Persistindo */
                            /* Inativando o Evento */
                            $eventoParaInativar = $eventoAtual;
                            $eventoParaInativar->setDataEHoraDeInativacao();
                            $repositorioORM->getEventoORM()->persistir($eventoParaInativar, false);
                            /* Inativando todos Grupo Evento */
                            foreach ($grupoEventoAtivos as $gea) {
                                $gea->setDataEHoraDeInativacao();
                                $repositorioORM->getGrupoEventoORM()->persistir($gea, false);
                            }
                            $criarNovoEvento = true;
                            $mudarDataDeCadastroParaProximoDomingo = true;
                        } else {
                            /* Dia não foi alterado */

                            /* Dados exclusivo do Culto */
                            if ($post_data[(Constantes::$FORM_NOME)] != $eventoAtual->getNome()) {
                                $eventoAtual->setNome(strtoupper($post_data[(Constantes::$FORM_NOME)]));
                            }
                            $eventoAtual->setHora($post_data[(Constantes::$FORM_HORA)] . ':' . $post_data[(Constantes::$FORM_MINUTOS)] . ':00');
                            $repositorioORM->getEventoORM()->persistir($eventoAtual, false);
                            /* Sessão */
                            $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_ALTERAR_CULTO;
                            $sessao->textoMensagem = $eventoAtual->getNome() . ' ' . $eventoAtual->getHoraFormatoHoraMinutoParaListagem();
                        }
                        /* Verificando Grupos abaixo ou equipes */
                        /* Marcação */
                        foreach ($post_data as $key => $value) {
                            $stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
                            if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
                                $stringValor = substr($key, strlen($stringParaVerificar));
                                /* Verificando marcações */
                                $validacaoMarcado = false;
                                foreach ($grupoEventoAtivos as $gea) {
                                    if ($gea->getGrupo()->getId() == $stringValor) {
                                        $validacaoMarcado = true;
                                    }
                                }
                                /* Equipe esta marcada mas não foi gerada ainda */
                                if (!$validacaoMarcado) {
                                    $grupoEquipe = $repositorioORM->getGrupoORM()->encontrarPorId($stringValor);
                                    $grupoEventoEquipe = new GrupoEvento();
                                    $grupoEventoEquipe->setDataEHoraDeCriacao();
                                    $grupoEventoEquipe->setGrupo($grupoEquipe);
                                    $grupoEventoEquipe->setEvento($eventoAtual);
                                    $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoEquipe);
                                }
                            }
                        }
                        /* Desmarcação */
                        foreach ($grupoEventoAtivos as $grupoEventAtivo) {
                            $idEntidadeAtual = $sessao->idEntidadeAtual;
                            $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
                            $grupo = $entidade->getGrupo();
                            if ($grupoEventAtivo->getGrupo()->getId() != $grupo->getId()) {
                                $validacaoMarcado = false;
                                foreach ($post_data as $key => $value) {
                                    $stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
                                    if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
                                        $stringValor = substr($key, strlen($stringParaVerificar));
                                        if ($grupoEventAtivo->getGrupo()->getId() == $stringValor) {
                                            $validacaoMarcado = true;
                                        }
                                    }
                                }
                                /* Equipe esta marcada mas não foi gerada ainda */
                                if (!$validacaoMarcado) {
                                    $grupoEventAtivo->setDataEHoraDeInativacao();
                                    $repositorioORM->getGrupoEventoORM()->persistir($grupoEventAtivo, false);
                                }
                            }
                        }
                    }
                    if ($criarNovoEvento) {
                        /* Entidade selecionada */
                        $idEntidadeAtual = $sessao->idEntidadeAtual;
                        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

                        $evento->exchangeArray($eventoForm->getData());
                        $dataParaCadastro = Funcoes::dataAtual();
                        if ($mudarDataDeCadastroParaProximoDomingo) {
                            $dataParaCadastro = Funcoes::proximoDomingo();
                        }
                        $evento->setData_criacao($dataParaCadastro);
                        $evento->setHora_criacao(Funcoes::horaAtual());
                        $evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
                        $evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
                        $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorId(1));

                        $grupoEvento->setData_criacao(Funcoes::dataAtual());
                        $grupoEvento->setHora_criacao(Funcoes::horaAtual());
                        $grupoEvento->setGrupo($entidade->getGrupo());
                        $grupoEvento->setEvento($evento);

                        /* Persistindo */
                        $repositorioORM->getEventoORM()->persistir($evento);
                        $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
                        /* Sessão */
                        $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_CULTO;
                        $sessao->textoMensagem = $evento->getNome();
                        $sessao->idSessao = $evento->getId();

                        /* Grupos Abaixos ou Equipes */
                        foreach ($post_data as $key => $value) {
                            $stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
                            if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
                                $stringValor = substr($key, strlen($stringParaVerificar));
                                $grupoEquipe = $repositorioORM->getGrupoORM()->encontrarPorId($stringValor);
                                $grupoEventoEquipe = new GrupoEvento();
                                $grupoEventoEquipe->setData_criacao(Funcoes::dataAtual());
                                $grupoEventoEquipe->setHora_criacao(Funcoes::horaAtual());
                                $grupoEventoEquipe->setGrupo($grupoEquipe);
                                $grupoEventoEquipe->setEvento($evento);
                                $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoEquipe);
                            }
                        }
                    }
                } else {
                    $this->direcionaErroDeCadastro($eventoForm->getMessages());
                    CircuitoController::direcionandoAoLogin($this);
                }

                $repositorioORM->fecharTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CULTOS,
                ));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getMessage();
                CircuitoController::direcionandoAoLogin($this);
            }
        }
    }

    /**
     * Função para persistir o evento célula
     * POST /eventoCelulaPersistir
     */
    public function eventoCelulaPersistirAction() {
        CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $eventoCelula = new EventoCelula();
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $celulaForm = new CelulaForm(Constantes::$FORM_CELULA, $eventoCelula);
            $repositorioORM->iniciarTransacao();
            try {
                $post_data = $request->getPost();

                /* Entidades */
                $celulaForm->setInputFilter($eventoCelula->getInputFilter());
                $celulaForm->setData($post_data);

                /* validação */
                if ($celulaForm->isValid()) {
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $criarNovaCelula = true;
                    $mudarDataDeCadastroParaProximoDomingo = false;
                    $validatedData = $celulaForm->getData();

                    /* Entidades */
                    $evento = new Evento();
                    $grupoEvento = new GrupoEvento();

                    /* ALTERANDO */
                    if (!empty($post_data[Constantes::$FORM_ID])) {
                        $criarNovaCelula = false;
                        $eventoCelulaAtual = $repositorioORM->getEventoCelulaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

                        /* Dia foi alterado */
                        if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoCelulaAtual->getEvento()->getDia()) {
                            /* Persistindo */
                            $dataParaInativacao = Funcoes::proximoDomingo();
                            $dataParaInativacaoFormatada = DateTime::createFromFormat('Y-m-d', $dataParaInativacao);

                            $eventoParaInativar = $eventoCelulaAtual->getEvento();
                            $grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();

                            $eventoParaInativar->setData_inativacao($dataParaInativacaoFormatada);
                            $eventoParaInativar->setHora_inativacao('00:00:00');
                            $grupoEventoAtivos[0]->setData_inativacao($dataParaInativacaoFormatada);
                            $grupoEventoAtivos[0]->setHora_inativacao('00:00:00');

                            $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoAtivos[0], false);
                            $repositorioORM->getEventoORM()->persistir($eventoParaInativar, false);
                            $criarNovaCelula = true;
                            $mudarDataDeCadastroParaProximoDomingo = true;
                        } else {
                            /* Dia não foi alterado */

                            /* Dados exclusivo da célula */
                            if ($validatedData[Constantes::$FORM_NOME_HOSPEDEIRO] != $eventoCelulaAtual->getNome_hospedeiro()) {
                                $eventoCelulaAtual->setNome_hospedeiro($validatedData[Constantes::$FORM_NOME_HOSPEDEIRO]);
                            }
                            if ($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroDDDSemTelefone()) {
                                $eventoCelulaAtual->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
                            }
                            if ($validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroTelefoneSemDDD()) {
                                $eventoCelulaAtual->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
                            }
                            if ($post_data[Constantes::$FORM_CEP_LOGRADOURO] != $eventoCelulaAtual->getCep()) {
                                $eventoCelulaAtual->setCep($validatedData[Constantes::$FORM_CEP]);
                            }
                            if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)] != $eventoCelulaAtual->getUf()) {
                                $eventoCelulaAtual->setUf($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)]);
                            }
                            if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)] != $eventoCelulaAtual->getCidade()) {
                                $eventoCelulaAtual->setCidade($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)]);
                            }
                            if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)] != $eventoCelulaAtual->getBairro()) {
                                $eventoCelulaAtual->setBairro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)]);
                            }
                            if ($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)] != $eventoCelulaAtual->getLogradouro()) {
                                $eventoCelulaAtual->setLogradouro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)]);
                            }
                            if ($post_data[(Constantes::$FORM_COMPLEMENTO)] != $eventoCelulaAtual->getComplemento()) {
                                $eventoCelulaAtual->setComplemento(strtoupper($post_data[(Constantes::$FORM_COMPLEMENTO)]));
                            }
                            $repositorioORM->getEventoCelulaORM()->persistir($eventoCelulaAtual, false);
                            /* Dados do Evento - Hora */
                            $eventoAtual = $eventoCelulaAtual->getEvento();
                            if ($validatedData[Constantes::$FORM_HORA] != $eventoAtual->getHoraSemMinutosESegundos()) {
                                $eventoAtual->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
                            }
                            if ($validatedData[Constantes::$FORM_MINUTOS] != $eventoAtual->getMinutosSemHorasESegundos()) {
                                $eventoAtual->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
                            }
                            $repositorioORM->getEventoORM()->persistir($eventoAtual);
                            /* Sessão */
                            $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_ALTERAR_CELULA;
                            $sessao->textoMensagem = $eventoCelulaAtual->getNome_hospedeiro();
                        }
                    }
                    if ($criarNovaCelula) {
                        /* Entidade selecionada */
                        $idEntidadeAtual = $sessao->idEntidadeAtual;
                        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

                        $eventoCelula->exchangeArray($celulaForm->getData());
                        $eventoCelula->setTelefone_hospedeiro($validatedData[Constantes::$FORM_DDD_HOSPEDEIRO] . $validatedData[Constantes::$FORM_TELEFONE_HOSPEDEIRO]);
                        $eventoCelula->setUf($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_UF)]);
                        $eventoCelula->setCidade($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_CIDADE)]);
                        $eventoCelula->setLogradouro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_LOGRADOURO)]);
                        $eventoCelula->setBairro($post_data[(Constantes::$FORM_HIDDEN . Constantes::$FORM_BAIRRO)]);
                        $eventoCelula->setComplemento(strtoupper($post_data[Constantes::$FORM_COMPLEMENTO]));
                        $eventoCelula->setCep($post_data[Constantes::$FORM_CEP_LOGRADOURO]);

                        $eventoCelula->setEvento($evento);
                        $alterarDataDeCriacao = true;
                        if ($mudarDataDeCadastroParaProximoDomingo) {
                            $alterarDataDeCriacao = false;
                            $dataParaCriacao = Funcoes::proximaSegunda();
                            $dataParaCriacaoFormatada = DateTime::createFromFormat('Y-m-d', $dataParaCriacao);
                            $evento->setData_criacao($dataParaCriacaoFormatada);
                            $evento->setHora_criacao('00:00:00');
                            $grupoEvento->setData_criacao($dataParaCriacaoFormatada);
                            $grupoEvento->setHora_criacao('00:00:00');
                        }
                        $evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
                        $evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
                        $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorId(2));

                        $grupoEvento->setGrupo($entidade->getGrupo());
                        $grupoEvento->setEvento($evento);

                        /* Persistindo */
                        $repositorioORM->getEventoORM()->persistir($evento, $alterarDataDeCriacao);
                        $repositorioORM->getEventoCelulaORM()->persistir($eventoCelula, false);
                        $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento, $alterarDataDeCriacao);
                        /* Sessão */
                        $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_CELULA;
                        $sessao->textoMensagem = $eventoCelula->getNome_hospedeiro();
                        $sessao->idSessao = $eventoCelula->getId();

                        /* Cadastro do fato celula */
                        /* cadastro fato apenas se for nova celula */
                        if (empty($post_data[Constantes::$FORM_ID])) {
                            $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM);
                            $periodo = 0;
                            $arrayPeriodo = Funcoes::montaPeriodo($periodo);
                            $stringData = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                            $dateFormatada = DateTime::createFromFormat('Y-m-d', $stringData);
                            $fatoPeriodo = $repositorioORM->getFatoCicloORM()->
                                    encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dateFormatada, $repositorioORM);
                            $repositorioORM->getFatoCelulaORM()->criarFatoCelula($fatoPeriodo, $eventoCelula->getId());
                        }
                    }
                    $repositorioORM->fecharTransacao();

                    return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                                Constantes::$PAGINA => Constantes::$PAGINA_CELULAS,
                    ));
                } else {
                    $this->direcionaErroDeCadastro($celulaForm->getMessages());
                    CircuitoController::direcionandoAoLogin($this);
                }
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                $this->direcionaErroDeCadastro($celulaForm->getMessages());
                CircuitoController::direcionandoAoLogin($this);
            }
        }
    }

    /**
     * Tela com formulário de exclusão de evento
     * GET /cadastroEventoExclusao
     */
    public function eventoExclusaoAction() {
        /* Verificando a se tem algum id na sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $extra = null;
        $eventoNaSessao = new Evento();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
        if (!empty($sessao->idSessao)) {
            $eventoNaSessao = $repositorioORM->getEventoORM()->encontrarPorId($sessao->idSessao);
            if ($eventoNaSessao->getGrupoEventoAtivos() > 1) {
                $grupo = $entidade->getGrupo();
                foreach ($eventoNaSessao->getGrupoEventoAtivos() as $eg) {
                    if ($eg->getGrupo()->getId() != $grupo->getId()) {
                        $grupo = $eg->getGrupo();
                        $entidadeMarcada = $grupo->getEntidadeAtiva();
                        $extra .= $entidadeMarcada->infoEntidade() . "<br />";
                    }
                }
            }
        }

        $view = new ViewModel(array(
            Constantes::$EVENTO => $eventoNaSessao,
            Constantes::$ENTIDADE => $entidade,
            Constantes::$EXTRA => $extra,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_EVENTO);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_EVENTO);

        return $view;
    }

    /**
     * Tela com formulário de exclusão de celula
     * GET /cadastroEventoConfirmacao
     */
    public function eventoExclusaoConfirmacaoAction() {
        CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $repositorioORM->iniciarTransacao();
        try {

            /* Verificando a se tem algum id na sessão */
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $eventoNaSessao = new Evento();
            if (!empty($sessao->idSessao)) {
                $eventoNaSessao = $repositorioORM->getEventoORM()->encontrarPorId($sessao->idSessao);

                /* Persistindo */

                /* Relatório de célula */
                if ($eventoNaSessao->getEventoCelula()) {
                    /* Somente inativar caso o dia do evento seja posterior ao dia da exclusao */
                    $timeNow = new DateTime();
                    $format = 'N';
                    $diaDaSemana = $timeNow->format($format);
                    $eventoNaSessao->getDia();
                    if ($diaDaSemana == 7) {
                        $diaDaSemana = 1;
                    } else {
                        $diaDaSemana++;
                    }
                    if ($diaDaSemana < $eventoNaSessao->getDia()) {
                        $fatoCelula = $repositorioORM->getFatoCelulaORM()->encontrarPorEventoCelulaId($eventoNaSessao->getEventoCelula()->getId());
                        $fatoCelula->setDataEHoraDeInativacao();
                        $repositorioORM->getFatoCelulaORM()->persistir($fatoCelula, false);
                    }
                }

                /* Inativando o Evento */
                $eventoNaSessao->setDataEHoraDeInativacao();
                $repositorioORM->getEventoORM()->persistir($eventoNaSessao, false);

                /* Inativando o Grupo Evento */
                $grupoEventoAtivos = $eventoNaSessao->getGrupoEventoAtivos();

                foreach ($grupoEventoAtivos as $grupoEventoAtivo) {
                    $grupoEventoAtivo->setDataEHoraDeInativacao();
                    $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoAtivo, false);
                }

                $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CULTO;
                $sessao->textoMensagem = $eventoNaSessao->getNome();
                if ($eventoNaSessao->verificaSeECelula()) {
                    $celula = $eventoNaSessao->getEventoCelula();
                    $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CELULA;
                    $sessao->textoMensagem = $celula->getNome_hospedeiro();
                }
                $sessao->nomeEventoExcluido = $eventoNaSessao->getNome();
                unset($sessao->idSessao);

                $tipoCelula = !empty($eventoNaSessao->verificaSeECelula());
                $pagina = Constantes::$PAGINA_CULTOS;
                if ($tipoCelula) {
                    $pagina = Constantes::$PAGINA_CELULAS;
                }

                $repositorioORM->fecharTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CELULAS,
                ));
            }
        } catch (Exception $exc) {
            $repositorioORM->desfazerTransacao();
            echo $exc->getTraceAsString();
            CircuitoController::direcionandoAoLogin($this);
        }
    }

    /**
     * Tela com formulário de cadastro de grupo
     * GET /cadastroGrupo
     */
    public function grupoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

        $grupo = $entidade->getGrupo();
        $arrayGrupoAlunos = $grupo->getGrupoAlunoAtivos();
        $mostrarCadastro = false;
//        if (!empty($arrayGrupoAlunos)) {
        $mostrarCadastro = true;
//        }

        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $arrayHierarquia = $repositorioORM->getHierarquiaORM()->encontrarTodas($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());

        $arrayDeNumerosUsados = array();
        if ($grupo->getGrupoPaiFilhoFilhos()) {
            $filhos = $grupo->getGrupoPaiFilhoFilhos();
            foreach ($filhos as $filho) {
                if ($filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero()) {
                    $numero = $filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero();
                    $arrayDeNumerosUsados[] = $numero;
                }
            }
        }
        $form = new GrupoForm(Constantes::$FORM, $arrayGrupoAlunos, $arrayHierarquia, $arrayDeNumerosUsados);

        $view = new ViewModel(array(
            Constantes::$FORM => $form,
            'tipoEntidade' => $entidade->getTipo_id(),
            'mostrarCadastro' => $mostrarCadastro,
            'tituloDaPagina' => 'Cadastro de Time',
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_GRUPO_VALIDACAO);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_GRUPO_VALIDACAO);

        return $view;
    }

    /**
     * Tela com confrmação de cadastro de grupo
     * POST /cadastroGrupoFinalizar
     */
    public function grupoFinalizarAction() {
        CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            try {
                $repositorioORM->iniciarTransacao();
                $post_data = $request->getPost();

                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $idEntidadeAtual = $sessao->idEntidadeAtual;
                $entidadeLogada = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

                /* Criar Grupo */
                $grupoNovo = new Grupo();
                $repositorioORM->getGrupoORM()->persistir($grupoNovo);

                /* Entidade abaixo do perfil selecionado/logado */
                $tipoEntidadeAbaixo = Entidade::SUBEQUIPE; // sub equipe por padrao
                if ($entidadeLogada->getTipo_id() != Entidade::SUBEQUIPE) {
                    $tipoEntidadeAbaixo = $entidadeLogada->getTipo_id() + 1;
                }
                $entidadeNova = new Entidade();
                $entidadeNova->setEntidadeTipo(
                        $repositorioORM->getEntidadeTipoORM()->encontrarPorId($tipoEntidadeAbaixo)
                );
                $entidadeNova->setGrupo($grupoNovo);
                if ($post_data[Constantes::$FORM_NUMERACAO]) {
                    $entidadeNova->setNumero($post_data[Constantes::$FORM_NUMERACAO]);
                }
                if ($post_data['nomeEntidade']) {
                    $entidadeNova->setNome($post_data['nomeEntidade']);
                }
                $repositorioORM->getEntidadeORM()->persistir($entidadeNova);

                $inputEstadoCivil = intval($post_data[Constantes::$INPUT_ESTADO_CIVIL]);
                /* Alterar dados do aluno */
                /* Solteiro */
                $indicePessoasInicio = 0;
                $indicePessoasFim = 0;
                /* Casado */
                if ($inputEstadoCivil === 2) {
                    $indicePessoasInicio = 1;
                    $indicePessoasFim = 2;
                }
                for ($indicePessoas = $indicePessoasInicio; $indicePessoas <= $indicePessoasFim; $indicePessoas++) {
                    $mudarDataDeCriacao = true;
                    if ($repositorioORM->getPessoaORM()->verificarSeTemCPFCadastrado($cpf)) {
                        $pessoaSelecionada = $repositorioORM->getPessoaORM()->encontrarPorCPF($cpf);
                        $mudarDataDeCriacao = false;
                    } else {
                        $pessoaSelecionada = new Pessoa();
                    }
                    $pessoaSelecionada->setNome($post_data[Constantes::$FORM_NOME . $indicePessoas]);
                    $pessoaSelecionada->setEmail($post_data[Constantes::$FORM_EMAIL . $indicePessoas]);
                    $pessoaSelecionada->setDocumento($post_data[Constantes::$FORM_CPF . $indicePessoas]);
                    $pessoaSelecionada->setData_nascimento(Funcoes::mudarPadraoData($post_data[Constantes::$FORM_DATA_NASCIMENTO . $indicePessoas], 0));
                    $tokenDeAgora = $pessoaSelecionada->gerarToken($indicePessoas);
                    $pessoaSelecionada->setToken($tokenDeAgora);
                    $repositorioORM->getPessoaORM()->persistir($pessoaSelecionada, $mudarDataDeCriacao);

                    /* Apenas para uma nova pessoa, quem ja tem nao muda apenas pelo juridico */
                    if ($mudarDataDeCriacao) {
                        /* Criar hierarquia */
                        $idHierarquia = $post_data[Constantes::$FORM_HIERARQUIA . $indicePessoas];
                        $hierarquia = $repositorioORM->getHierarquiaORM()->encontrarPorId($idHierarquia);
                        $pessoaHierarquia = new PessoaHierarquia();
                        $pessoaHierarquia->setPessoa($pessoaSelecionada);
                        $pessoaHierarquia->setHierarquia($hierarquia);
                        $repositorioORM->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);
                    }

                    /* Criar Grupo_Responsavel */
                    $grupoResponsavelNovo = new GrupoResponsavel();
                    $grupoResponsavelNovo->setPessoa($pessoaSelecionada);
                    $grupoResponsavelNovo->setGrupo($grupoNovo);
                    $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);
                }

                /* Criar Grupo_Pai_Filho */
                $grupoAtualSelecionado = $entidadeLogada->getGrupo();
                $grupoPaiFilhoNovo = new GrupoPaiFilho();
                $grupoPaiFilhoNovo->setGrupoPaiFilhoPai($grupoAtualSelecionado);
                $grupoPaiFilhoNovo->setGrupoPaiFilhoFilho($grupoNovo);
                $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoNovo);

                $repositorioORM->fecharTransacao();

                for ($indicePessoas = $indicePessoasInicio; $indicePessoas <= $indicePessoasFim; $indicePessoas++) {
                    /* Enviar Email */
                    $this->enviarEmailParaCompletarOsDados($tokenDeAgora, $pessoaSelecionada);
                }
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getTraceAsString();
                $this->direcionaErroDeCadastro($exc->getMessage());
                CircuitoController::direcionandoAoLogin($this);
            }
        }
    }

    /**
     * Tela com atualização de cadastro de grupo
     * GET /cadastroGrupoAtualizar
     */
    public function grupoAtualizacaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $form = new AtualizarCadastroForm(Constantes::$FORM, $sessao->idPessoa);

        $view = new ViewModel(array(
            Constantes::$FORM => $form,
            Constantes::$FORM_ENDERECO_HIDDEN => Constantes::$FORM_HIDDEN
        ));

        /* Javascript */
//        $layoutJS = new ViewModel();
//        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_GRUPO_VALIDACAO);
//        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_GRUPO_VALIDACAO);

        return $view;
    }

    /**
     * Atualização dos dados depois de cadastrar o grupo
     * POST /cadastroGrupoAtualizar
     */
    public function grupoAtualizarAction() {
        CircuitoController::verificandoSessao(new Container(Constantes::$NOME_APLICACAO), $this);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $pessoa = $loginORM->getPessoaORM()->encontrarPorId($post_data[Constantes::$ID]);
                $pessoa->setTelefone($post_data[Constantes::$FORM_INPUT_DDD] + $post_data[Constantes::$FORM_INPUT_CELULAR]);
                $pessoa->dadosAtualizados();
                $loginORM->getPessoaORM()->persistir($pessoa);
            } catch (Exception $exc) {
                $this->direcionaErroDeCadastro($exc->getMessage());
                CircuitoController::direcionandoAoLogin($this);
            }
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_SELECIONAR_PERFIL,
            ));
        }
    }

    /**
     * Busca de endereço por cep ou logradouro
     * @return Json
     */
    public function buscarEnderecoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $cep_logradouro = $post_data[Constantes::$FORM_CEP_LOGRADOURO];

                $pesquisa = Correios::cep($cep_logradouro);
                $quantidadeDeResultados = count($pesquisa);

                $dadosDeResposta = array(
                    'quantidadeDeResultados' => $quantidadeDeResultados,
                    'pesquisa' => $pesquisa
                );
                $response->setContent(Json::encode($dadosDeResposta));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Busca de email
     * Resposta: 0 - Não utilizado; 1 - Utilizado; 
     * @return Json
     */
    public function buscarEmailAction() {
        $resposta = 0;
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $email = $post_data[Constantes::$FORM_EMAIL];

                $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $pessoaPesquisada = $loginORM->getPessoaORM()->encontrarPorEmail($email);

                if ($pessoaPesquisada) {
                    $resposta = 1;
                }

                $dadosDeResposta = array(
                    'resposta' => $resposta,
                );

                $response->setContent(Json::encode($dadosDeResposta));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Busca de cpf
     * 1 - Sucesso
     * 2 - Não encontrou ou dados errados
     * @return Json
     */
    public function buscarCPFAction() {
        $resposta = 0;
        $respostaSucesso = 1;
        $respostaNaoEncotrado = 2;
        $respostaTemCadastroAtivo = 3;
        $respostaTemCadastroInativo = 4;
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $idPessoa = 0;
                $post_data = $request->getPost();
                $cpf = $post_data[Constantes::$FORM_CPF];

                $nomeDaPesquisa = '';
                $dataDeNascimentoDaPesquisa = '';

                $explodeDataNascimento = explode('/', $post_data[Constantes::$FORM_DATA_NASCIMENTO]);
                $dia = str_pad($explodeDataNascimento[0], 2, 0, STR_PAD_LEFT);
                $mes = str_pad($explodeDataNascimento[1], 2, 0, STR_PAD_LEFT);
                $ano = $explodeDataNascimento[2];
                $dataNascimento = $dia . $mes . $ano;
                $urlUsada = Constantes::$PROCOB_URL . Constantes::$PROCOB_URL_RECEITA_FEDERAL . $cpf . '?dataNascimento=' . $dataNascimento;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, Constantes::$PROCOB_USUARIO . ':' . Constantes::$PROCOB_SENHA);
                curl_setopt($curl, CURLOPT_URL, $urlUsada);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($curl);
                $json = Json::decode($result, TRUE);
//                var_dump($json);
                $stringCode = 'code';
                $stringContent = 'content';
                $stringNome = 'nome';
                $stringDataNascimento = 'data_nascimento';

                curl_close($curl);

                $dados = array();
                /* Sucesso */
                if ($json[$stringCode] === '000') {
                    $nomeDaPesquisa = $json[$stringContent][$stringNome];
                    $dataDeNascimentoDaPesquisa = $json[$stringContent][$stringDataNascimento];
                    $resposta = $respostaSucesso;

                    /* CPF encontrado na receita verificando se tem cadastro no sistema */
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                    if ($pessoaEncotrada = $repositorioORM->getPessoaORM()->encontrarPorCPF($cpf)) {
                        $responsabilidadesAtivas = count($pessoaEncotrada->getResponsabilidadesAtivas());
                        if ($responsabilidadesAtivas === 0) {
                            $resposta = $respostaTemCadastroInativo;
                            $idPessoa = $pessoaEncotrada->getId();
                            $dados['idHierarquia'] = $pessoaEncotrada->getPessoaHierarquiaAtivo()->getHierarquia()->getId();
                        } else {
                            $resposta = $respostaTemCadastroAtivo;
                        }
                    }
                }
                if ($json[$stringCode] === '001' || $json[$stringCode] === '999') {
                    $resposta = $respostaNaoEncotrado;
                }

                $dados['resposta'] = $resposta;
                $dados['cpf'] = $cpf;
                $dados['nome'] = $nomeDaPesquisa;
                $dados['dataNascimento'] = $dataDeNascimentoDaPesquisa;
                $dados['idPessoa'] = $idPessoa;
                $response->setContent(Json::encode($dados));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Controle de funçoes da tela de cadastro
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
                                    'url' => '/cadastro' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->get();
            }
        }
        return $response;
    }

    /**
     * Envia email de convte para o novo grupo
     * @param string $tokenDeAgora
     * @param Pessoa $pessoa
     */
    public function enviarEmailParaCompletarOsDados($tokenDeAgora, $pessoa) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $pessoaLogada = $loginORM->getPessoaORM()->encontrarPorId($sessao->idPessoa);

        $Subject = 'Convite';
        $ToEmail = $pessoa->getEmail();
        $avatar = 'placeholder.png';
        if ($pessoaLogada->getFoto()) {
            $avatar = $pessoaLogada->getFoto();
        }
        $nomeLider = str_replace(' ', '', $pessoaLogada->getNomePrimeiro());
        $nomePessoaEmail = str_replace(' ', '', $pessoa->getNomePrimeiro());
        $url = "https://" . Constantes::$HOST . "/convite.php?nomeLider=$nomeLider&avatar=$avatar&token=$tokenDeAgora&nomePessoaEmail=$nomePessoaEmail";
        $Content = file_get_contents($url);
        Funcoes::enviarEmail($ToEmail, $Subject, $Content);
    }

    public function transferenciaAction() {
        $form = new TransferenciaForm('transferencia');

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $discipulos = $grupo->getGrupoPaiFilhoFilhos();

        return new ViewModel(
                array(
            Constantes::$FORM => $form,
            'discipulos' => $discipulos,
        ));
    }

    public function selecionarRevisionistaAction() {

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisao = $sessao->idSessao;
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $sessao->idRevisao = $idRevisao;
        $view = new ViewModel(array(
            Constantes::$ENTIDADE => $entidade,
            'repositorioORM' => $repositorioORM,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
        $view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

        return $view;
    }

    public function cadastrarPessoaRevisaoAction() {
        /* Helper Controller */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        if ($sessao->idSessao == null || $sessao->idRevisao == null) {
            return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                        Constantes::$PAGINA => Constantes::$PAGINA_REVISIONISTAS,
            ));
        }
        $idPessoa = $sessao->idSessao;
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $tipos = $repositorioORM->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
        $grupoPessoa = $pessoa->getGrupoPessoaAtivo();


        /* Formulario */
        $formCadastrarPessoaRevisao = new CadastrarPessoaRevisaoForm(Constantes::$FORM_CADASTRAR_PESSOA_REVISAO, $pessoa);

        $view = new ViewModel(array(
            Constantes::$FORM_CADASTRAR_PESSOA_REVISAO => $formCadastrarPessoaRevisao,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA_REVISAO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA_REVISAO);



        return $view;
    }

    /**
     * Recupera o grupo do perfil selecionado
     * @param RepositorioORM $repositorioORM
     * @return Grupo
     */
    private function getGrupoSelecionado($repositorioORM) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;

        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        return $entidade->getGrupo();
    }

    public function salvarPessoaRevisaoAction() {
        $request = $this->getRequest();

//            try {
        $post_data = $request->getPost();

        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

        /* validação */
        $pessoa->setNome($post_data[Constantes::$INPUT_PRIMEIRO_NOME] . " " . $post_data[Constantes::$INPUT_ULTIMO_NOME]);
        $pessoa->setTelefone($post_data[Constantes::$INPUT_DDD] . $post_data[Constantes::$INPUT_TELEFONE]);
        $pessoa->setData_nascimento($post_data[Constantes::$FORM_INPUT_ANO] . "-" . $post_data[Constantes::$FORM_INPUT_MES] . "-" .
                $post_data[Constantes::$FORM_INPUT_DIA]);
        $pessoa->setSexo($post_data[Constantes::$INPUT_NUCLEO_PERFEITO]);

        /* Salvar a pessoa e o grupo pessoa correspondente */
        $repositorioORM->getPessoaORM()->persistir($pessoa, false);
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->idRevisionista = $pessoa->getId();

        /* Bloco para inclusao da pessoa no evento frequencia */
        $idRevisao = $sessao->idRevisao;
        if ($sessao->idRevisao == null) {
            return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                        Constantes::$PAGINA => Constantes::$PAGINA_REVISIONISTAS,
            ));
        }
        unset($sessao->idRevisao);
        $eventoFrequencia = new EventoFrequencia();
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
        $eventoFrequencia->setEvento($eventoRevisao);
        $eventoFrequencia->setPessoa($pessoa);
        $eventoFrequencia->setFrequencia('N');
        $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia);
        $sessao->idSessao = $eventoFrequencia->getId();

        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => Constantes::$PAGINA_FICHA_REVISAO,
        ));
//            } catch (Exception $exc) {
//                echo $exc->getMessage();
//            }
    }

    public function fichaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEventoFrequencia = $sessao->idSessao;
        $eventoFrequencia = $repositorioORM->getEventoFrequenciaORM()->encontrarPorId($idEventoFrequencia);
        $pessoaRevisionista = $eventoFrequencia->getPessoa();
        $idRevisao = $eventoFrequencia->getEvento()->getId();
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
        $grupoPessoaRevisionista = $pessoaRevisionista->getGrupoPessoaAtivo();
        $grupoLider = $grupoPessoaRevisionista->getGrupo();
        $nomeEntidadeLider = $grupoLider->getEntidadeAtiva()->infoEntidade();
        $grupoIgreja = $grupoLider->getGrupoIgreja();
        $nomeIgreja = $grupoIgreja->getEntidadeAtiva()->infoEntidade();
        $grupoResponsavel = $grupoLider->getResponsabilidadesAtivas();
        $pessoas = array();
        foreach ($grupoResponsavel as $gr) {
            $p = $gr->getPessoa();
            $pessoas[] = $p;
        }
        $view = new ViewModel(array(
            Constantes::$PESSOA_REVISAO => $pessoaRevisionista,
            Constantes::$REVISAO_VIEW => $eventoRevisao,
            Constantes::$PESSOA_LIDER_REVISAO => $pessoas[0],
            Constantes::$ENTIDADE_REVISAO => $nomeEntidadeLider,
            Constantes::$NOME_IGREJA_FICHA_REVISAO => $nomeIgreja,
            Constantes::$STRING_ID_EVENTO_FREQUENCIA => $idEventoFrequencia,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_FICHA_REVISAO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_FICHA_REVISAO);

        return $view;
    }

    public function selecionarFichasRevisionistaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisao = $sessao->idSessao;
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $sessao->idRevisao = $idRevisao;
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
        $view = new ViewModel(array(
            Constantes::$ENTIDADE => $entidade,
            'repositorioORM' => $repositorioORM,
            'evento' => $eventoRevisao,
            'entidade' => $entidade,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
        $view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

        return $view;
    }

    public function ativarFichaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisao = $sessao->idSessao;
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
        $formAtivarFicha = new AtivarFichaForm(Constantes::$FORM_ATIVAR_FICHA, null);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $view = new ViewModel(array(
            Constantes::$FORM_ATIVAR_FICHA => $formAtivarFicha,
            'repositorioORM' => $repositorioORM,
            'evento' => $eventoRevisao,
            'entidade' => $entidade,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_ATIVAR_FICHA);
        $view->addChild($layoutJS, Constantes::$STRING_JS_ATIVAR_FICHA_REVISAO);

        return $view;
    }

    public function selecionarFichasAtivasAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisao = $sessao->idSessao;
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $sessao->idRevisao = $idRevisao;
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
        $view = new ViewModel(array(
            Constantes::$ENTIDADE => $entidade,
            'repositorioORM' => $repositorioORM,
            'evento' => $eventoRevisao,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EVENTOS);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EVENTOS);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(Constantes::$LAYOUT_JS_EVENTOS_VALIDACAO);
        $view->addChild($layoutJSValidacao, Constantes::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

        return $view;
    }

    public function consultarFichaAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        if ($request->isPost()) {

            $post_data = $request->getPost();
            $idEventoFrequencia = $post_data['idEventoFrequencia'];
            if ($idEventoFrequencia != null || $idEventoFrequencia == 0) {

                $eventoFrequencia = $repositorioORM->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);

                if (!$eventoFrequencia) {
                    $response->setContent(Json::encode(
                                    array('response' => 'true',
                                        'status' => 0,
                    )));
                } else {

                    $pessoaRevisionista = $eventoFrequencia->getPessoa();


                    $grupoPessoaRevisionista = $pessoaRevisionista->getGrupoPessoaAtivo();
                    $grupoLider = $grupoPessoaRevisionista->getGrupo();
                    $nomeEntidadeLider = $grupoLider->getEntidadeAtiva()->infoEntidade();
                    $response->setContent(Json::encode(
                                    array('response' => 'true',
                                        'status' => 1,
                                        'nomeRevisionista' => $pessoaRevisionista->getNome(),
                                        'nomeEntidadeLider' => $nomeEntidadeLider,
                                        'idEventoFrequencia' => $eventoFrequencia->getId(),
                    )));
                }
            } else {
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    'status' => 0,
                )));
            }
            return $response;
        }
    }

    /*
     * Função para trocar GrupoPessoaTipo
     *  idTipo 1 = Visitante
     *  idTipo 2 = Consolidação
     *  idTipo 3 = Membro
     */

    private function alterarGrupoPessoaTipo($idTipo, RepositorioORM $repositorioORM, Pessoa $pessoaRevisionista) {

        /* Inativando o grupo pessoa antigo */
        $grupoPessoaRevisionistaAntigo = $pessoaRevisionista->getGrupoPessoaAtivo();
        $grupoPessoaRevisionistaAntigo->setDataEHoraDeInativacao();
        $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoaRevisionistaAntigo, false);

        /* Busca GrupoPessoaTipo */
        $grupoPessoaTipo = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorId($idTipo);

        /* Bloco para inclusao da pessoa no grupo Pessoa como membro. */
        $grupoPessoa = new GrupoPessoa();
        $grupoPessoa->setPessoa($pessoaRevisionista);
        $grupoPessoa->setGrupo($grupoPessoaRevisionistaAntigo->getGrupo());
        $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
        $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);

        return $grupoPessoa;
    }

    public function ativarReservaRevisaoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        if ($request->isPost()) {
            try {
                $repositorioORM->iniciarTransacao();
                $post_data = $request->getPost();
                $idEventoFrequencia = $post_data['codigo'];

                /* Resgatando Dados do EventoFrequencia e do Revisionista */
                $eventoFrequencia = $repositorioORM->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
                if ($eventoFrequencia->getFrequencia() == 'N') {
                    $pessoaRevisionista = $eventoFrequencia->getPessoa();
                    /* Membro = idTipo 3 */
                    $grupoPessoaRevisionista = $this->alterarGrupoPessoaTipo(3, $repositorioORM, $pessoaRevisionista);

                    /* Ativando a presença do Revisionista  */
                    $eventoFrequencia->setFrequencia('S');
                    $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia, false);

                    /* Mensagens de retorno */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->mostrarNotificacao = true;
                    $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_REVISIONISTA;
                    $sessao->textoMensagem = $pessoaRevisionista->getNome();
//                    $sessao->idSessao = $eventoFrequencia->getId();

                    /* Migração Sitema Antigo */

                    $grupoLider = $grupoPessoaRevisionista->getGrupo();
                    $grupoResponsavel = $grupoLider->getResponsabilidadesAtivas();
                    $numeroLideres = count($grupoResponsavel);
                    $grupoCv = $grupoLider->getGrupoCv();
                    if ($numeroLideres > 1) {
                        $idAluno = IndexController::cadastrarPessoaRevisionista($pessoaRevisionista->getNome(), substr('' . $pessoaRevisionista->getTelefone() . '', 0, 2), substr('' . $pessoaRevisionista->getTelefone() . '', 2, strlen('' . $pessoaRevisionista->getTelefone() . '')), $pessoaRevisionista->getSexo(), $pessoaRevisionista->getData_nascimento(), $grupoCv->getLider1(), $grupoCv->getLider2());
                    } else {
                        $idAluno = IndexController::cadastrarPessoaRevisionista($pessoaRevisionista->getNome(), substr('' . $pessoaRevisionista->getTelefone() . '', 0, 2), substr('' . $pessoaRevisionista->getTelefone() . '', 2, strlen('' . $pessoaRevisionista->getTelefone() . '')), $pessoaRevisionista->getSexo(), $pessoaRevisionista->getData_nascimento(), $grupoCv->getLider1());
                    }
                    IndexController::cadastrarPessoaAluno($idAluno, 6503, 'A', 1);

                    /* Fim da migração do Sistema Antigo */

                    $repositorioORM->fecharTransacao();
                    return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                                Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
                    ));
                } else {
                    $repositorioORM->desfazerTransacao();
                    return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                                Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
                    ));
                }
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
    }

    public function ativarReservaRevisaoQrCodeAction() {
        /* Busca numero do IdEventoMatricula */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        $idEventoFrequencia = $parametro;

        /* Resgatando Dados do EventoFrequencia e do Revisionista */
        $eventoFrequencia = $repositorioORM->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
        $pessoaRevisionista = $eventoFrequencia->getPessoa();
        /* Membro = idTipo 3 */
        $this->alterarGrupoPessoaTipo(3, $repositorioORM, $pessoaRevisionista);

        /* Ativando a presença do Revisionista  */
        $eventoFrequencia->setFrequencia('S');

        /* Mensagens de retorno */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->mostrarNotificacao = true;
        $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_REVISIONISTA;
        $sessao->textoMensagem = $pessoaRevisionista->getNome();
        $sessao->idSessao = $eventoFrequencia->getId();

        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
        ));
    }

    public function selecionarLiderRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisao = $sessao->idSessao;
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

        $view = new ViewModel(array(
            Constantes::$ENTIDADE => $entidade,
            'idRevisao' => $idRevisao,
        ));

        /* Javascript */
        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_SELECIONAR_LIDER_REVISAO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_SELECIONAR_LIDER_REVISAO);

        return $view;
    }

    public function ativarLideresRevisaoAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        try {
            $repositorioORM->iniciarTransacao();
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $idRevisao = $sessao->idSessao;
            $idPessoa = $sessao->idPessoa;
            $pessoaLogada = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
            $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
            $eventoFrequencia = new EventoFrequencia();
            $eventoFrequencia->setEvento($eventoRevisao);
            $eventoFrequencia->setPessoa($pessoaLogada);
            $eventoFrequencia->setFrequencia('N');
            $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia);
            $sessao->idSessao = $eventoFrequencia->getId();
            $repositorioORM->fecharTransacao();
            return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                        Constantes::$PAGINA => Constantes::$PAGINA_FICHA_REVISAO,
            ));
        } catch (Exception $exc) {
            $repositorioORM->desfazerTransacao();
            echo $exc->getTraceAsString();
        }
    }

    public function removerRevisionistaAtivoAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        try {
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $repositorioORM->iniciarTransacao();
            $idEventoFrequencia = $sessao->idSessao;

            /* Resgatando Dados do EventoFrequencia e do Revisionista */
            $eventoFrequencia = $repositorioORM->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
            if ($eventoFrequencia->getFrequencia() == 'S') {
                $pessoaRevisionista = $eventoFrequencia->getPessoa();
                /* Membro = idTipo 3 */
                $grupoPessoaRevisionista = $this->alterarGrupoPessoaTipo(3, $repositorioORM, $pessoaRevisionista);

                /* Ativando a presença do Revisionista  */
                $eventoFrequencia->setFrequencia('N');
                $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia, false);

                /* Mensagens de retorno */
                $sessao->mostrarNotificacao = true;
                $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_REVISIONISTA;
                $sessao->textoMensagem = $pessoaRevisionista->getNome();
                $sessao->idSessao = $eventoFrequencia->getEvento()->getId();

                /* Migração Sitema Antigo */

//                    $grupoLider = $grupoPessoaRevisionista->getGrupo();
//                    $grupoResponsavel = $grupoLider->getResponsabilidadesAtivas();
//                    $numeroLideres = count($grupoResponsavel);
//                    $grupoCv = $grupoLider->getGrupoCv();
//                    if($numeroLideres > 1){
//                        $idAluno = IndexController::cadastrarPessoaRevisionista($pessoaRevisionista->getNome(), substr(''.$pessoaRevisionista->getTelefone().'',0,2),
//                        substr(''.$pessoaRevisionista->getTelefone().'', 2, strlen(''.$pessoaRevisionista->getTelefone().'')), $pessoaRevisionista->getSexo(),
//                                $pessoaRevisionista->getData_nascimento(),$grupoCv->getLider1(), $grupoCv->getLider2());
//                    }else{
//                        $idAluno = IndexController::cadastrarPessoaRevisionista($pessoaRevisionista->getNome(), substr(''.$pessoaRevisionista->getTelefone().'',0,2),
//                        substr(''.$pessoaRevisionista->getTelefone().'', 2, strlen(''.$pessoaRevisionista->getTelefone().'')), $pessoaRevisionista->getSexo(),
//                                $pessoaRevisionista->getData_nascimento(),$grupoCv->getLider1());
//
//                    }
//                    IndexController::cadastrarPessoaAluno($idAluno, 5930, 'A', 1);

                /* Fim da migração do Sistema Antigo */

                $repositorioORM->fecharTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
                ));
            } else {
                $repositorioORM->desfazerTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_ATIVAR_FICHA_REVISAO,
                ));
            }
        } catch (Exception $exc) {
            $repositorioORM->desfazerTransacao();
            echo $exc->getTraceAsString();
        }
    }

    public function turmaFormAction() {

        $formCadastroTurma = new TurmaForm('formulario');

        $view = new ViewModel(array(
            'formCadastroTurma' => $formCadastroTurma,
        ));

        return $view;
    }

    public function salvarTurmaAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $repositorioORM->iniciarTransacao();

                $dadosPost = $request->getPost();
                $id = $dadosPost['id'];
                $idTipo = $dadosPost['Tipo'];
                $mes = $dadosPost['Mes'];
                $ano = $dadosPost['Ano'];
                $observacao = $dadosPost['observacao'];

                if ($id) {
                    $turma = $repositorioORM->getTurmaORM()->encontrarPorId($id);
                } else {
                    $turma = new Turma();
                }

                $turma->setAno($ano);
                $turma->setMes($mes);
                $turma->setObservacao($observacao);
                $turma->setTipo_turma_id($idTipo);

                if ($id) {
                    $repositorioORM->getTurmaORM()->persistir($turma, false);
                } else {
                    $repositorioORM->getTurmaORM()->persistir($turma);
                }

                $repositorioORM->fecharTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_LISTAR_TURMA,
                ));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
    }

    public function listarTurmaAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $turmas = $repositorioORM->getTurmaORM()->encontrarTodas();
        $view = new ViewModel(array(
            'turmas' => $turmas,
        ));

        return $view;
    }

    public function turmaFormEditAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idTurma = $sessao->idSessao;
        $turma = $repositorioORM->getTurmaORM()->encontrarPorId($idTurma);
        $formCadastroTurma = new TurmaForm('formulario', $turma);

        $view = new ViewModel(array(
            'formCadastroTurma' => $formCadastroTurma,
        ));

        return $view;
    }

    public function turmaExcluirAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idTurma = $sessao->idSessao;
        $turma = $repositorioORM->getTurmaORM()->encontrarPorId($idTurma);
        $turma->setDataEHoraDeInativacao();
        $repositorioORM->getTurmaORM()->persistir($turma, false);

        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => Constantes::$PAGINA_LISTAR_TURMA,
        ));
    }

    public function solicitacoesAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $solicitacoes = $grupo->getSolicitacao();
        $view = new ViewModel(array(
            'solicitacoes' => $solicitacoes,
        ));
        return $view;
    }

    public function solicitacaoAction() {

        $view = new ViewModel(array());
        return $view;
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
        $turma = $repositorioORM->getTurmaORM()->encontrarPorId($idTurma);

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

    public function listarTurmaInativaAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $turmas = $repositorioORM->getTurmaORM()->encontrarTodas();
        $view = new ViewModel(array(
            'turmas' => $turmas,
        ));

        return $view;
    }

    public function turmaSelecionarAlunosAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idTurma = $sessao->idTurma;
        $idRevisao = $sessao->idRevisao;
        $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);

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

    /**
     * Função de listagem de curso
     */
    public function cursoListarAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $cursos = $repositorioORM->getCursoORM()->buscarTodosRegistrosEntidade();
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $repositorioORM->iniciarTransacao();

                $dadosPost = $request->getPost();
                $id = $dadosPost['id'];
                $nome = $dadosPost['nome'];
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $idPessoaLogada = $sessao->idPessoa;
                $pessoaLogada = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoaLogada);
                if ($id) {
                    $curso = $repositorioORM->getCursoORM()->encontrarPorId($id);
                } else {
                    $curso = new Curso();
                }

                $curso->setNome($nome);
                $curso->setPessoa($pessoaLogada);

                if ($id) {
                    $repositorioORM->getCursoORM()->persistir($curso, false);
                } else {
                    $repositorioORM->getCursoORM()->persistir($curso);
                }

                $repositorioORM->fecharTransacao();
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CURSO_LISTAR,
                ));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
    }

    public function cursoFormEditAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idCurso = $sessao->idSessao;
        $curso = $repositorioORM->getCursoORM()->encontrarPorId($idCurso);
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
        $curso = $repositorioORM->getCursoORM()->encontrarPorId($idCurso);

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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idCurso = $sessao->idSessao;
        $curso = $repositorioORM->getCursoORM()->encontrarPorId($idCurso);
        $curso->setDataEHoraDeInativacao();
        $repositorioORM->getCursoORM()->persistir($curso, false);

        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => Constantes::$PAGINA_CURSO_LISTAR,
        ));
    }
    
    /**
     * Função de listagem de disciplina
     */
    public function disciplinaListarAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idCurso = $sessao->idSessao;
        $disciplinas = $repositorioORM->getDisciplinaORM()->buscarTodosRegistrosEntidade('posicao', 'ASC');
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idCurso = $sessao->idSessao;
        $curso = $repositorioORM->getCursoORM()->encontrarPorId($idCurso);
        $disciplinas = $curso->getDisciplina();
        $formCadastroDisciplina = new DisciplinaForm('formulario', $idCurso, $disciplinas);
        $view = new ViewModel(array(
            'formCadastroDisciplina' => $formCadastroDisciplina,
            'idCurso' => $idCurso, 
        ));

        return $view;
    }

    public function disciplinaSalvarAction() {
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
                $idCurso = $dadosPost['idCurso'];
                if ($id) {
                    $disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($id);
                } else {
                    $disciplina = new Disciplina();
                }
                $curso = $repositorioORM->getCursoORM()->encontrarPorId($idCurso);
                $disciplina->setNome($nome);
                $disciplina->setPosicao($posicao);
                $disciplina->setCurso($curso);

                if ($id) {
                    $repositorioORM->getDisciplinaORM()->persistir($disciplina, false);
                } else {
                    $repositorioORM->getDisciplinaORM()->persistir($disciplina);
                }

                $repositorioORM->fecharTransacao();
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idSessao = $idCurso;
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_DISCIPLINA_LISTAR,
                ));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
    }

    public function disciplinaFormEditAction() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idDisciplina = $sessao->idSessao;
        $disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
        $disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);

        $view = new ViewModel(array(
            Constantes::$NOME_ENTIDADE_DISCIPLINA => $disciplina,
            Constantes::$ENTIDADE => $entidade,
            'idCurso' => $disciplina->getCurso_id(),
            
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$LAYOUT_JS_EXCLUSAO_DISCIPLINA);
        $view->addChild($layoutJS, Constantes::$LAYOUT_STRING_JS_EXCLUSAO_DISCIPLINA);

        return $view;
    }

    public function disciplinaExcluirAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idDisciplina = $sessao->idSessao;
        $disciplina = $repositorioORM->getDisciplinaORM()->encontrarPorId($idDisciplina);
        $disciplina->setDataEHoraDeInativacao();
        $repositorioORM->getCursoORM()->persistir($disciplina, false);
        $sessao->idSessao = $disciplina->getCurso_id();
        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => Constantes::$PAGINA_DISCIPLINA_LISTAR,  
        ));
    }

}
