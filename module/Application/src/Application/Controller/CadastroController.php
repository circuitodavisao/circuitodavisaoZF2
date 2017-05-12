<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Correios;
use Application\Controller\Helper\Funcoes;
use Application\Form\AtualizarCadastroForm;
use Application\Form\CadastrarPessoaRevisaoForm;
use Application\Form\CelulaForm;
use Application\Form\EventoForm;
use Application\Form\GrupoForm;
use Application\Form\RevisaoForm;
use Application\Form\TransferenciaForm;
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
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Exception;
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
        if ($pagina == Constantes::$PAGINA_CADASTRO_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRO_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_INSERIR_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_INSERIR_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SALVAR_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SALVAR_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CADASTRO_TRANSFERENCIA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRO_TRANSFERENCIA,
            ));
        }
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
        if ($pagina == Constantes::$PAGINA_CELULAS) {
            $listagemDeEventos = $grupo->getGrupoEventoCelula();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CELULAS . ' <b class="text-danger">' . Constantes::$TRADUCAO_MULTIPLICACAO . '</b>';
            $tipoEvento = 2;
        }
        if ($pagina == Constantes::$PAGINA_CULTOS) {
            $listagemDeEventos = $grupo->getGrupoEventoCulto();
            $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CULTOS;
            $tipoEvento = 1;
            $extra = $grupo->getId();
        }
        if ($pagina == Constantes::$PAGINA_REVISAO) {
            $listagemDeEventos = $grupo->getGrupoEventoRevisao();
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
        $stringCheckEquipe = 'checkEquipe';
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
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

                    /* Repositorios */
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                    /* ALTERANDO */
                    if (!empty($post_data[Constantes::$FORM_ID])) {
                        $criarNovoEvento = false;
                        $eventoAtual = $repositorioORM->getEventoORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);
                        $grupoEventoAtivos = $eventoAtual->getGrupoEventoAtivos();
                        /* Dia foi alterado */
                        if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoAtual->getDia()) {
                            /* Persistindo */
                            /* Inativando o Evento */
                            $eventoParaInativar = $eventoAtual;
                            $eventoParaInativar->setData_inativacao(Funcoes::dataAtual());
                            $eventoParaInativar->setHora_inativacao(Funcoes::horaAtual());
                            $repositorioORM->getEventoORM()->persistir($eventoParaInativar);
                            /* Inativando todos Grupo Evento */
                            foreach ($grupoEventoAtivos as $gea) {
                                $gea->setData_inativacao(Funcoes::dataAtual());
                                $gea->setHora_inativacao(Funcoes::horaAtual());
                                $repositorioORM->getGrupoEventoORM()->persistir($gea);
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
                            $repositorioORM->getEventoORM()->persistir($eventoAtual);
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
                                    $grupoEventoEquipe->setData_criacao(Funcoes::dataAtual());
                                    $grupoEventoEquipe->setHora_criacao(Funcoes::horaAtual());
                                    $grupoEventoEquipe->setGrupo($grupoEquipe);
                                    $grupoEventoEquipe->setEvento($eventoAtual);
                                    $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoEquipe);
                                }
                            }
                        }
                        /* Desmarcação */
                        foreach ($grupoEventoAtivos as $gea) {
                            $idEntidadeAtual = $sessao->idEntidadeAtual;
                            $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
                            $grupo = $entidade->getGrupo();
                            if ($gea->getGrupo()->getId() != $grupo->getId()) {
                                $validacaoMarcado = false;
                                foreach ($post_data as $key => $value) {
                                    $stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
                                    if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
                                        $stringValor = substr($key, strlen($stringParaVerificar));
                                        if ($gea->getGrupo()->getId() == $stringValor) {
                                            $validacaoMarcado = true;
                                        }
                                    }
                                }
                                /* Equipe esta marcada mas não foi gerada ainda */
                                if (!$validacaoMarcado) {
                                    $gea->setData_inativacao(Funcoes::dataAtual());
                                    $gea->setHora_inativacao(Funcoes::horaAtual());
                                    $repositorioORM->getGrupoEventoORM()->persistir($gea);
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
                }
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CULTOS,
                ));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    /**
     * Função para persistir o evento célula
     * POST /eventoCelulaPersistir
     */
    public function eventoCelulaPersistirAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                /* Entidades */
                $eventoCelula = new EventoCelula();
                $celulaForm = new CelulaForm(Constantes::$FORM_CELULA, $eventoCelula);
                $celulaForm->setInputFilter($eventoCelula->getInputFilter());
                $post_data[Constantes::$FORM_CEP_LOGRADOURO] = $post_data[Constantes::$FORM_CEP];
                $celulaForm->setData($post_data);

                /* validação */
                if ($celulaForm->isValid()) {
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $criarNovaCelula = true;
                    $mudarDataDeCadstroParaProximoDomingo = false;
                    $validatedData = $celulaForm->getData();

                    /* Entidades */
                    $evento = new Evento();
                    $grupoEvento = new GrupoEvento();

                    /* Repositorios */
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                    /* ALTERANDO */
                    if (!empty($post_data[Constantes::$FORM_ID])) {
                        $criarNovaCelula = false;
                        $eventoCelulaAtual = $repositorioORM->getEventoCelulaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

                        /* Dia foi alterado */
                        if ($post_data[Constantes::$FORM_DIA_DA_SEMANA] != $eventoCelulaAtual->getEvento()->getDia()) {
                            /* Persistindo */
                            /* Inativando o Evento */
                            $eventoParaInativar = $eventoCelulaAtual->getEvento();
                            $eventoParaInativar->setData_inativacao(Funcoes::dataAtual());
                            $eventoParaInativar->setHora_inativacao(Funcoes::horaAtual());
                            $repositorioORM->getEventoORM()->persistir($eventoParaInativar);
                            /* Inativando o Grupo Evento */
                            $grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();
                            $grupoEventoAtivos[0]->setData_inativacao(Funcoes::dataAtual());
                            $grupoEventoAtivos[0]->setHora_inativacao(Funcoes::horaAtual());
                            $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoAtivos[0]);
                            $criarNovaCelula = true;
                            $mudarDataDeCadstroParaProximoDomingo = true;
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
                        $eventoCelula->setCep($post_data[Constantes::$FORM_CEP]);
                        $eventoCelula->setEvento($evento);

                        $dataParaCadastro = Funcoes::dataAtual();
                        if ($mudarDataDeCadstroParaProximoDomingo) {
                            $dataParaCadastro = Funcoes::proximoDomingo();
                        }
                        $evento->setData_criacao($dataParaCadastro);
                        $evento->setHora_criacao(Funcoes::horaAtual());
                        $evento->setHora($validatedData[Constantes::$FORM_HORA] . ':' . $validatedData[Constantes::$FORM_MINUTOS]);
                        $evento->setDia($validatedData[Constantes::$FORM_DIA_DA_SEMANA]);
                        $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorId(2));

                        $grupoEvento->setData_criacao(Funcoes::dataAtual());
                        $grupoEvento->setHora_criacao(Funcoes::horaAtual());
                        $grupoEvento->setGrupo($entidade->getGrupo());
                        $grupoEvento->setEvento($evento);

                        /* Persistindo */
                        $repositorioORM->getEventoORM()->persistir($evento);
                        $repositorioORM->getEventoCelulaORM()->persistir($eventoCelula, false);
                        $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
                        /* Sessão */
                        $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_CELULA;
                        $sessao->textoMensagem = $eventoCelula->getNome_hospedeiro();
                        $sessao->idSessao = $eventoCelula->getId();

                        /* Cadastro do fato celula */
                        $mesSelecionado = date('n');
                        $anoSelecionado = date('Y');
                        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
                        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($entidade->getGrupo());
                        $fatoCiclo = $repositorioORM->getFatoCicloORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $repositorioORM);
                        $repositorioORM->getFatoCelulaORM()->criarFatoCelula($fatoCiclo, $eventoCelula->getId());
                    }
                } else {
                    $this->direcionaErroDeCadastro($celulaForm->getMessages());
                }
                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CELULAS,
                ));
            } catch (Exception $exc) {
                $this->direcionaErroDeCadastro($celulaForm->getMessages());
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
        return new ViewModel(array(
            Constantes::$EVENTO => $eventoNaSessao,
            Constantes::$ENTIDADE => $entidade,
            Constantes::$EXTRA => $extra,
        ));
    }

    /**
     * Tela com formulário de exclusão de celula
     * GET /cadastroEventoConfirmacao
     */
    public function eventoExclusaoConfirmacaoAction() {
        /* Verificando a se tem algum id na sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $eventoNaSessao = new Evento();
        if (!empty($sessao->idSessao)) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $eventoNaSessao = $repositorioORM->getEventoORM()->encontrarPorId($sessao->idSessao);

            $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CULTO;
            $sessao->textoMensagem = $eventoNaSessao->getNome();
            if ($eventoNaSessao->verificaSeECelula()) {
                $celula = $eventoNaSessao->getEventoCelula();
                $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_EXCLUIR_CELULA;
                $sessao->textoMensagem = $celula->getNome_hospedeiro();
            }

            /* Persistindo */
            /* Inativando o Evento */
            $eventoParaInativar = $eventoNaSessao;

            /* Relatório de célula */
            if ($eventoParaInativar->getEventoCelula()) {
                /* Somente inativar caso o dia do evento seja posterior ao dia da exclusao */
                $timeNow = new DateTime();
                $format = 'N';
                $diaDaSemana = $timeNow->format($format);
                $eventoParaInativar->getDia();
                if ($diaDaSemana == 7) {
                    $diaDaSemana = 1;
                } else {
                    $diaDaSemana++;
                }
                if ($diaDaSemana < $eventoParaInativar->getDia()) {
                    $fatoCelula = $repositorioORM->getFatoCelulaORM()->encontrarPorEventoCelulaId($eventoParaInativar->getEventoCelula()->getId());
                    $fatoCelula->setDataEHoraDeInativacao();
                    $repositorioORM->getFatoCelulaORM()->persistir($fatoCelula, false);
                }
            }

            $eventoParaInativar->setDataEHoraDeInativacao();
            $repositorioORM->getEventoORM()->persistir($eventoParaInativar, false);

            /* Inativando o Grupo Evento */
            $grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();
            foreach ($grupoEventoAtivos as $gea) {
                $gea->setDataEHoraDeInativacao();
                $repositorioORM->getGrupoEventoORM()->persistir($gea, false);
            }
        }

        /* Sessão */
        $sessao->nomeEventoExcluido = $eventoNaSessao->getNome();

        $tipoCelula = !empty($eventoNaSessao->verificaSeECelula());
        $pagina = Constantes::$PAGINA_CULTOS;
        if ($tipoCelula) {
            $pagina = Constantes::$PAGINA_CELULAS;
        }

        return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                    Constantes::$PAGINA => $pagina,
        ));
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
        $arrayHierarquia = $repositorioORM->getHierarquiaORM()->encontrarTodas();

        $form = new GrupoForm(Constantes::$FORM, $arrayGrupoAlunos, $arrayHierarquia);

        $view = new ViewModel(array(
            Constantes::$FORM => $form,
            'tipoEntidade' => $entidade->getTipo_id(),
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
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $idEntidadeAtual = $sessao->idEntidadeAtual;
                $entidadeLogada = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

                /* Criar Grupo */
                $grupoNovo = new Grupo();
                $repositorioORM->getGrupoORM()->persistir($grupoNovo);

                /* Entidade abaixo do perfil selecionado/logado */
                $tipoEntidadeAbaixo = 8; // sub equipe por padrao
                if ($entidadeLogada->getTipo_id() != 8) {
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
                    $matricula = $post_data[Constantes::$FORM_ID_ALUNO_SELECIONADO . $indicePessoas];
                    $turmaAluno = $repositorioORM->getTurmaAlunoORM()->encontrarPorId($matricula);
                    $pessoaSelecionada = $turmaAluno->getPessoa();
                    $tokenDeAgora = $pessoaSelecionada->gerarToken($indicePessoas);
                    $pessoaSelecionada->setToken($tokenDeAgora);
                    $pessoaAtualizada = $loginORM->getPessoaORM()->
                            atualizarAlunoComDadosDaBuscaPorCPF($pessoaSelecionada, $post_data, $indicePessoas);

                    /* Criar hierarquia */
                    $idHierarquia = $post_data[Constantes::$FORM_HIERARQUIA . $indicePessoas];
                    $hierarquia = $repositorioORM->getHierarquiaORM()->encontrarPorId($idHierarquia);
                    $pessoaHierarquia = new PessoaHierarquia();
                    $pessoaHierarquia->setPessoa($pessoaAtualizada);
                    $pessoaHierarquia->setHierarquia($hierarquia);
                    $repositorioORM->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);

                    /* Criar Grupo_Responsavel */
                    $grupoResponsavelNovo = new GrupoResponsavel();
                    $grupoResponsavelNovo->setPessoa($pessoaAtualizada);
                    $grupoResponsavelNovo->setGrupo($grupoNovo);
                    $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);

                    // Enviar Email
                    $this->enviarEmailParaCompletarOsDados($tokenDeAgora, $pessoaSelecionada);
                }

                /* Criar Grupo_Pai_Filho */
                $grupoAtualSelecionado = $entidadeLogada->getGrupo();
                $grupoPaiFilhoNovo = new GrupoPaiFilho();
                $grupoPaiFilhoNovo->setGrupoPaiFilhoPai($grupoAtualSelecionado);
                $grupoPaiFilhoNovo->setGrupoPaiFilhoFilho($grupoNovo);
                $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoNovo);

                $view = new ViewModel();
                return $view;
            } catch (Exception $exc) {
                $this->direcionaErroDeCadastro($exc->getMessage());
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
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $pessoa = $loginORM->getPessoaORM()->encontrarPorId($post_data[Constantes::$ID]);
                $pessoa->setTelefone($post_data[Constantes::$FORM_INPUT_DDD] + $post_data[Constantes::$FORM_INPUT_CELULAR]);
                $pessoa->setAtualizar_dados($atualizar_dados);
                $pessoa->dadosAtualizados();
                $loginORM->getPessoaORM()->persistir($pessoa);
            } catch (Exception $exc) {
                $this->direcionaErroDeCadastro($exc->getMessage());
            }
            return $this->redirect()->toRoute('principal', array(
                        'mostrarMenu' => 1
            ));
        }
    }

    /**
     * Mostrar as mensagens de erro
     * @param type $mensagens
     */
    public function direcionaErroDeCadastro($mensagens) {
        echo "ERRO: Cadastro invalido!<br /><br />########################<br />";
        foreach ($mensagens as $l => $value) {
            echo "key? $l<br >";
            foreach ($value as $key => $value) {
                echo "$key => $value <br />";
            }
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
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
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
                /* Sucesso */
                if ($json[$stringCode] === '000') {
                    $nomeDaPesquisa = $json[$stringContent][$stringNome];
                    $dataDeNascimentoDaPesquisa = $json[$stringContent][$stringDataNascimento];
                    $resposta = 1;

                    /* CPF encontrado na receita verificando se tem cadastro no sistema */
                    $loginORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                    if ($loginORM->getPessoaORM()->encontrarPorCPF($cpf)) {
                        $resposta = 3;
                    }
                }
                if ($json[$stringCode] === '001' || $json[$stringCode] === '999') {
                    $resposta = 2;
                }

                $dadosDeResposta = array(
                    'resposta' => $resposta,
                    'cpf' => $cpf,
                    'nome' => $nomeDaPesquisa,
                    'dataNascimento' => $dataDeNascimentoDaPesquisa,
                );
                $response->setContent(Json::encode($dadosDeResposta));
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
        $ToEmail = 'falecomleonardopereira@gmail.com';
        $avatar = 'placeholder.png';
        if ($pessoaLogada->getFoto()) {
            $avatar = $pessoaLogada->getFoto();
        }
        $nomeLider = str_replace(' ', '', $pessoaLogada->getNomePrimeiro());
        $nomePessoaEmail = str_replace(' ', '', $pessoa->getNomePrimeiro());
        $url = "http://158.69.124.139/convite.php?nomeLider=$nomeLider&avatar=$avatar&token=$tokenDeAgora&nomePessoaEmail=$nomePessoaEmail";
        $Content = file_get_contents($url);
        Funcoes::enviarEmail($ToEmail, $Subject, $Content);
    }

    public function cadastrarRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();

        foreach ($gruposAbaixo as $gpFilho) {
            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
            $entidadeFilho = $grupoFilho->getEntidadeAtiva();
            if ($entidadeFilho->getTipo_id() == 5) {
                $gruposIgrejas[] = $entidadeFilho;
            }
        }
        $form = new RevisaoForm('revisaoForm', $gruposIgrejas);
        $view = new ViewModel(array(
            'revisaoForm' => $form,
            'igrejas' => $gruposIgrejas,
        ));

        return $view;
    }

    public function salvarRevisaoAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                /* Entidades * */
                $evento = new Evento();

                /* validação * */
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $criarNovoEvento = true;

                /* Entidades * */
                $evento = new Evento();
                $grupoEvento = new GrupoEvento();

                /* Repositorios * */
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $validatedData = $post_data;


                if ($criarNovoEvento) {
                    /* Entidade selecionada * */
                    $idEntidadeAtual = $sessao->idEntidadeAtual;
                    $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

                    $evento->setDia(6);
                    $evento->setHora('21:00:00');
                    $dataParaCadastro = Funcoes::dataAtual();
                    $evento->setData_criacao(Funcoes::mudarPadraoData($validatedData['dataRevisao'], 0));
                    $evento->setHora_criacao(Funcoes::horaAtual());

                    $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorId(3));

                    $grupoEvento->setData_criacao(Funcoes::dataAtual());
                    $grupoEvento->setHora_criacao(Funcoes::horaAtual());
                    $grupoEvento->setGrupo($entidade->getGrupo());
                    $grupoEvento->setEvento($evento);

                    /* Persistindo * */
                    $repositorioORM->getEventoORM()->persistir($evento);
                    $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
                    /* Sessão * */
                    $sessao->tipoMensagem = Constantes::$TIPO_MENSAGEM_CADASTRAR_CULTO;
                    $sessao->textoMensagem = $evento->getNome();
                    $sessao->idSessao = $evento->getId();

                    /* Grupos Abaixos ou Equipes * */
                    foreach ($post_data['igrejas'] as $value) {
                        $stringValor = explode('#', $value);
                        $entidadeIgreja = $repositorioORM->getEntidadeORM()->encontrarPorId($stringValor[1]);
                        $grupoIgreja = $repositorioORM->getGrupoORM()->encontrarPorId($entidadeIgreja->getGrupo_id());
                        $grupoEventoIgreja = new GrupoEvento();
                        $grupoEventoIgreja->setData_criacao(Funcoes::dataAtual());
                        $grupoEventoIgreja->setHora_criacao(Funcoes::horaAtual());
                        $grupoEventoIgreja->setGrupo($grupoIgreja);
                        $grupoEventoIgreja->setEvento($evento);
                        $repositorioORM->getGrupoEventoORM()->persistir($grupoEventoIgreja);
                    }
                }

                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$PAGINA => Constantes::$PAGINA_CADASTRO_REVISAO,
                ));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    public function listRevisao() {
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        $listagemDeEventos = $grupo->getGrupoEventoRevisao();
        $tituloDaPagina = Constantes::$TRADUCAO_LISTAGEM_CELULAS . ' <b class="text-danger">' . Constantes::$TRADUCAO_MULTIPLICACAO . '</b>';
        $tipoEvento = 2;
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
        if ($request->isPost()) {
//            try {
                $post_data = $request->getPost();

                
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($post_data[Constantes::$FORM_ID]);

                /* validação */
                $pessoa->setNome($post_data[Constantes::$INPUT_PRIMEIRO_NOME]." ".$post_data[Constantes::$INPUT_ULTIMO_NOME]);
                $pessoa->setTelefone($post_data[Constantes::$INPUT_DDD] . $post_data[Constantes::$INPUT_TELEFONE]);
                $pessoa->setData_nascimento($post_data[Constantes::$FORM_INPUT_ANO]."-".$post_data[Constantes::$FORM_INPUT_MES]."-".
                $post_data[Constantes::$FORM_INPUT_DIA]);
                $pessoa->setSexo($post_data[Constantes::$INPUT_NUCLEO_PERFEITO]);
                
                $grupoPessoaAntigo = $pessoa->getGrupoPessoaAtivo();
//              $grupoPessoaAntigo->setDataEHoraDeInativacao();  
                $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoaAntigo, false);
               
                /* Grupo selecionado */
                $grupo = $this->getGrupoSelecionado($repositorioORM);

                /* Salvar a pessoa e o grupo pessoa correspondente */
                $repositorioORM->getPessoaORM()->persistir($pessoa, false);
                /* Todo revisionista é Membro. Id = 3 */
                $grupoPessoaTipo = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorId(3);
                
                /* Bloco para inclusao da pessoa no grupo Pessoa */
                $grupoPessoa = new GrupoPessoa();
                $grupoPessoa->setPessoa($pessoa);
                $grupoPessoa->setGrupo($grupo);
                $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo); 
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idRevisionista = $pessoa->getId();
                $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);
                
                /* Bloco para inclusao da pessoa no evento frequencia */
                $idRevisao = $sessao->idRevisao;
                $eventoFrequencia = new EventoFrequencia();
                $eventoRevisao = $repositorioORM->getEventoORM()->encontrarPorId($idRevisao);
                $eventoFrequencia->setEvento($eventoRevisao);
                $eventoFrequencia->setPessoa($pessoa);
                $eventoFrequencia->setCiclo(0);
                $eventoFrequencia->setMes(date('N'));
                $eventoFrequencia->setAno(date('Y'));
                $eventoFrequencia->setFrequencia('N');
                $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia);

                /* Pondo valores na sessao */
                
                

                return $this->redirect()->toRoute(Constantes::$ROUTE_CADASTRO, array(
                            Constantes::$ACTION => Constantes::$PAGINA_FICHA_REVISAO,
                ));
//            } catch (Exception $exc) {
//                echo $exc->getMessage();
//            }
        }
    }
    
    public function fichaRevisaoAction(){
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idRevisionista = $sessao->idRevisionista;
        $pessoaRevisionista = $repositorioORM->getPessoaORM()->encontrarPorId($idRevisionista);
        $view = new ViewModel(array(
           'pessoa' => $pessoaRevisionista,
        ));

        /* Javascript especifico */
//        $layoutJS = new ViewModel();
//        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA_REVISAO);
//        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA_REVISAO);

        

        return $view;
    }
}
