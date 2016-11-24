<?php

namespace Cadastro\Controller;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Controller\Helper\Correios;
use Cadastro\Controller\Helper\FuncoesCadastro;
use Cadastro\Controller\Helper\RepositorioORM;
use Cadastro\Form\CelulaForm;
use Cadastro\Form\ConstantesForm;
use Cadastro\Form\EventoForm;
use Cadastro\Form\GrupoForm;
use DateTime;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\Entidade;
use Entidade\Entity\Evento;
use Entidade\Entity\EventoCelula;
use Entidade\Entity\Grupo;
use Entidade\Entity\GrupoEvento;
use Entidade\Entity\GrupoPaiFilho;
use Entidade\Entity\GrupoResponsavel;
use Entidade\Entity\PessoaHierarquia;
use Exception;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\Funcoes;
use Login\Controller\Helper\LoginORM;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class CadastroController extends AbstractActionController {

    private $_doctrineORMEntityManager;

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /cadastro[:pagina]
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $sessao->pagina = '';
        $extra = '';
        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesCadastro::$PAGINA, 1);
        if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_CULTO || $pagina == ConstantesCadastro::$PAGINA_EVENTO_CELULA) {
            if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_CULTO) {
                $sessao->pagina = ConstantesCadastro::$PAGINA_EVENTO_CULTO;
            }
            if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_CELULA) {
                $sessao->pagina = ConstantesCadastro::$PAGINA_EVENTO_CELULA;
            }
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_EVENTO,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_GRUPO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_GRUPO,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_GRUPO_FINALIZAR) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_GRUPO_FINALIZAR,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_GRUPO_ATUALIZAR) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_GRUPO_ATUALIZAR,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_CULTO_PERSISTIR) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_EVENTO_CULTO_PERSISTIR,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_CELULA_PERSISTIR) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_EVENTO_CELULA_PERSISTIR,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_EXCLUSAO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_EVENTO_EXCLUSAO,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO,
            ));
        }
        /* Busca de endereço por CEP */
        if ($pagina == ConstantesCadastro::$PAGINA_BUSCAR_ENDERECO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_BUSCAR_ENDERECO,
            ));
        }
        /* Busca de CPF */
        if ($pagina == ConstantesCadastro::$PAGINA_BUSCAR_CPF) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_BUSCAR_CPF,
            ));
        }
        /* Busca de Email */
        if ($pagina == ConstantesCadastro::$PAGINA_BUSCAR_EMAIL) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_BUSCAR_EMAIL,
            ));
        }
        /* Funcoes */
        if ($pagina == ConstantesLancamento::$PAGINA_FUNCOES) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_FUNCOES,
            ));
        }

        /* Por titulo e eventos na sessão para passar a tela */
        $listagemDeEventos = null;
        $tituloDaPagina = '';
        /* Listagem de celulas */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        $extra = '';
        $tipoEvento = 0;
        if ($pagina == ConstantesCadastro::$PAGINA_CELULAS) {
            $listagemDeEventos = $grupo->getGrupoEventoCelula();
            $tituloDaPagina = ConstantesForm::$TRADUCAO_LISTAGEM_CELULAS . ' <b class="text-danger">' . ConstantesForm::$TRADUCAO_MULTIPLICACAO . '</b>';
            $tipoEvento = 2;
        }
        if ($pagina == ConstantesCadastro::$PAGINA_CULTOS) {
            $listagemDeEventos = $grupo->getGrupoEventoCulto();
            $tituloDaPagina = ConstantesForm::$TRADUCAO_LISTAGEM_CULTOS;
            $tipoEvento = 1;
            $extra = $grupo->getId();
        }

        $view = new ViewModel(array(
            ConstantesForm::$LISTAGEM_EVENTOS => $listagemDeEventos,
            ConstantesForm::$TITULO_DA_PAGINA => $tituloDaPagina,
            ConstantesForm::$TIPO_EVENTO => $tipoEvento,
            ConstantesForm::$EXTRA => $extra,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_EVENTOS);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_EVENTOS);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(ConstantesForm::$LAYOUT_JS_EVENTOS_VALIDACAO);
        $view->addChild($layoutJSValidacao, ConstantesForm::$LAYOUT_STRING_JS_EVENTOS_VALIDACAO);

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
        if ($sessao->pagina == ConstantesCadastro::$PAGINA_EVENTO_CULTO) {
            /* Verificando a se tem algum id na sessão */
            $eventoNaSessao = new Evento();
            $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
            if (!empty($sessao->idSessao)) {
                $eventoNaSessao = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($sessao->idSessao);
            }
            $form = new EventoForm(ConstantesForm::$FORM, $eventoNaSessao);
            $idEntidadeAtual = $sessao->idEntidadeAtual;
            $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
            $grupo = $entidade->getGrupo();
            $extra = $grupo->getGrupoPaiFilhoFilhos();
        }
        if ($sessao->pagina == ConstantesCadastro::$PAGINA_EVENTO_CELULA) {
            /* Verificando a se tem algum id na sessão */
            $eventoCelulaNaSessao = new EventoCelula();
            if (!empty($sessao->idSessao)) {
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $eventoCelulaNaSessao = $repositorioORM->getEventoCelulaORM()->encontrarPorIdEventoCelula($sessao->idSessao);
            } else {
                $enderecoHidden = ConstantesForm::$FORM_HIDDEN;
            }
            $form = new CelulaForm(ConstantesForm::$FORM_CELULA, $eventoCelulaNaSessao);
        }

        $view = new ViewModel(array(
            ConstantesForm::$FORM => $form,
            ConstantesForm::$FORM_ENDERECO_HIDDEN => $enderecoHidden,
            ConstantesForm::$EXTRA => $extra,
        ));

        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_EVENTO);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_EVENTO);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(ConstantesForm::$LAYOUT_JS_EVENTO_VALIDACAO);
        $view->addChild($layoutJSValidacao, ConstantesForm::$LAYOUT_STRING_JS_EVENTO_VALIDACAO);

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
                $eventoForm = new EventoForm(ConstantesForm::$FORM, $evento);
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
                    $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

                    /* ALTERANDO */
                    if (!empty($post_data[ConstantesForm::$FORM_ID])) {
                        $criarNovoEvento = false;
                        $eventoAtual = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($post_data[ConstantesForm::$FORM_ID]);
                        $grupoEventoAtivos = $eventoAtual->getGrupoEventoAtivos();
                        /* Dia foi alterado */
                        if ($post_data[ConstantesForm::$FORM_DIA_DA_SEMANA] != $eventoAtual->getDia()) {
                            /* Persistindo */
                            /* Inativando o Evento */
                            $eventoParaInativar = $eventoAtual;
                            $eventoParaInativar->setData_inativacao(FuncoesCadastro::dataAtual());
                            $eventoParaInativar->setHora_inativacao(FuncoesCadastro::horaAtual());
                            $lancamentoORM->getEventoORM()->persistirEvento($eventoParaInativar);
                            /* Inativando todos Grupo Evento */
                            foreach ($grupoEventoAtivos as $gea) {
                                $gea->setData_inativacao(FuncoesCadastro::dataAtual());
                                $gea->setHora_inativacao(FuncoesCadastro::horaAtual());
                                $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($gea);
                            }
                            $criarNovoEvento = true;
                            $mudarDataDeCadastroParaProximoDomingo = true;
                        } else {
                            /* Dia não foi alterado */

                            /* Dados exclusivo do Culto */
                            if ($post_data[(ConstantesForm::$FORM_NOME)] != $eventoAtual->getNome()) {
                                $eventoAtual->setNome(strtoupper($post_data[(ConstantesForm::$FORM_NOME)]));
                            }
                            $eventoAtual->setHora($post_data[(ConstantesForm::$FORM_HORA)] . ':' . $post_data[(ConstantesForm::$FORM_MINUTOS)] . ':00');
                            $lancamentoORM->getEventoORM()->persistirEvento($eventoAtual);
                            /* Sessão */
                            $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_ALTERAR_CULTO;
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
                                    $grupoEquipe = $lancamentoORM->getGrupoORM()->encontrarPorIdGrupoPessoa($stringValor);
                                    $grupoEventoEquipe = new GrupoEvento();
                                    $grupoEventoEquipe->setData_criacao(FuncoesCadastro::dataAtual());
                                    $grupoEventoEquipe->setHora_criacao(FuncoesCadastro::horaAtual());
                                    $grupoEventoEquipe->setGrupo($grupoEquipe);
                                    $grupoEventoEquipe->setEvento($eventoAtual);
                                    $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEventoEquipe);
                                }
                            }
                        }
                        /* Desmarcação */
                        foreach ($grupoEventoAtivos as $gea) {
                            $idEntidadeAtual = $sessao->idEntidadeAtual;
                            $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
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
                                    $gea->setData_inativacao(FuncoesCadastro::dataAtual());
                                    $gea->setHora_inativacao(FuncoesCadastro::horaAtual());
                                    $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($gea);
                                }
                            }
                        }
                    }
                    if ($criarNovoEvento) {
                        /* Entidade selecionada */
                        $idEntidadeAtual = $sessao->idEntidadeAtual;
                        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

                        $evento->exchangeArray($eventoForm->getData());
                        $dataParaCadastro = FuncoesCadastro::dataAtual();
                        if ($mudarDataDeCadastroParaProximoDomingo) {
                            $dataParaCadastro = FuncoesCadastro::proximoDomingo();
                        }
                        $evento->setData_criacao($dataParaCadastro);
                        $evento->setHora_criacao(FuncoesCadastro::horaAtual());
                        $evento->setHora($validatedData[ConstantesForm::$FORM_HORA] . ':' . $validatedData[ConstantesForm::$FORM_MINUTOS]);
                        $evento->setDia($validatedData[ConstantesForm::$FORM_DIA_DA_SEMANA]);
                        $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorIdEventoTipo(1));

                        $grupoEvento->setData_criacao(FuncoesCadastro::dataAtual());
                        $grupoEvento->setHora_criacao(FuncoesCadastro::horaAtual());
                        $grupoEvento->setGrupo($entidade->getGrupo());
                        $grupoEvento->setEvento($evento);

                        /* Persistindo */
                        $lancamentoORM->getEventoORM()->persistirEvento($evento);
                        $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEvento);
                        /* Sessão */
                        $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_CADASTRAR_CULTO;
                        $sessao->textoMensagem = $evento->getNome();
                        $sessao->idSessao = $evento->getId();

                        /* Grupos Abaixos ou Equipes */
                        foreach ($post_data as $key => $value) {
                            $stringParaVerificar = substr($key, 0, strlen($stringCheckEquipe));
                            if (!\strcmp($stringParaVerificar, $stringCheckEquipe)) {
                                $stringValor = substr($key, strlen($stringParaVerificar));
                                $grupoEquipe = $lancamentoORM->getGrupoORM()->encontrarPorIdGrupoPessoa($stringValor);
                                $grupoEventoEquipe = new GrupoEvento();
                                $grupoEventoEquipe->setData_criacao(FuncoesCadastro::dataAtual());
                                $grupoEventoEquipe->setHora_criacao(FuncoesCadastro::horaAtual());
                                $grupoEventoEquipe->setGrupo($grupoEquipe);
                                $grupoEventoEquipe->setEvento($evento);
                                $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEventoEquipe);
                            }
                        }
                    }
                } else {
                    $this->direcionaErroDeCadastro($eventoForm->getMessages());
                }
                return $this->redirect()->toRoute(ConstantesCadastro::$ROUTE_CADASTRO, array(
                            ConstantesCadastro::$PAGINA => ConstantesCadastro::$PAGINA_CULTOS,
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
                $celulaForm = new CelulaForm(ConstantesForm::$FORM_CELULA, $eventoCelula);
                $celulaForm->setInputFilter($eventoCelula->getInputFilter());
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
                    $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

                    /* ALTERANDO */
                    if (!empty($post_data[ConstantesForm::$FORM_ID])) {
                        $criarNovaCelula = false;
                        $eventoCelulaAtual = $repositorioORM->getEventoCelulaORM()->encontrarPorIdEventoCelula($post_data[ConstantesForm::$FORM_ID]);

                        /* Dia foi alterado */
                        if ($post_data[ConstantesForm::$FORM_DIA_DA_SEMANA] != $eventoCelulaAtual->getEvento()->getDia()) {
                            /* Persistindo */
                            /* Inativando o Evento */
                            $eventoParaInativar = $eventoCelulaAtual->getEvento();
                            $eventoParaInativar->setData_inativacao(FuncoesCadastro::dataAtual());
                            $eventoParaInativar->setHora_inativacao(FuncoesCadastro::horaAtual());
                            $lancamentoORM->getEventoORM()->persistirEvento($eventoParaInativar);
                            /* Inativando o Grupo Evento */
                            $grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();
                            $grupoEventoAtivos[0]->setData_inativacao(FuncoesCadastro::dataAtual());
                            $grupoEventoAtivos[0]->setHora_inativacao(FuncoesCadastro::horaAtual());
                            $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEventoAtivos[0]);
                            $criarNovaCelula = true;
                            $mudarDataDeCadstroParaProximoDomingo = true;
                        } else {
                            /* Dia não foi alterado */

                            /* Dados exclusivo da célula */
                            if ($validatedData[ConstantesForm::$FORM_NOME_HOSPEDEIRO] != $eventoCelulaAtual->getNome_hospedeiro()) {
                                $eventoCelulaAtual->setNome_hospedeiro($validatedData[ConstantesForm::$FORM_NOME_HOSPEDEIRO]);
                            }
                            if ($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroDDDSemTelefone()) {
                                $eventoCelulaAtual->setTelefone_hospedeiro($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] . $validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO]);
                            }
                            if ($validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO] != $eventoCelulaAtual->getTelefone_hospedeiroTelefoneSemDDD()) {
                                $eventoCelulaAtual->setTelefone_hospedeiro($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] . $validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO]);
                            }
                            if ($validatedData[ConstantesForm::$FORM_CEP_LOGRADOURO] != $eventoCelulaAtual->getCep()) {
                                $eventoCelulaAtual->setCep($validatedData[ConstantesForm::$FORM_CEP_LOGRADOURO]);
                            }
                            if ($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_UF)] != $eventoCelulaAtual->getUf()) {
                                $eventoCelulaAtual->setUf($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_UF)]);
                            }
                            if ($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_CIDADE)] != $eventoCelulaAtual->getCidade()) {
                                $eventoCelulaAtual->setCidade($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_CIDADE)]);
                            }
                            if ($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_BAIRRO)] != $eventoCelulaAtual->getBairro()) {
                                $eventoCelulaAtual->setBairro($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_BAIRRO)]);
                            }
                            if ($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_LOGRADOURO)] != $eventoCelulaAtual->getLogradouro()) {
                                $eventoCelulaAtual->setLogradouro($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_LOGRADOURO)]);
                            }
                            if ($post_data[(ConstantesForm::$FORM_COMPLEMENTO)] != $eventoCelulaAtual->getComplemento()) {
                                $eventoCelulaAtual->setComplemento(strtoupper($post_data[(ConstantesForm::$FORM_COMPLEMENTO)]));
                            }
                            $repositorioORM->getEventoCelulaORM()->persistirEventoCelula($eventoCelulaAtual);
                            /* Dados do Evento - Hora */
                            $eventoAtual = $eventoCelulaAtual->getEvento();
                            if ($validatedData[ConstantesForm::$FORM_HORA] != $eventoAtual->getHoraSemMinutosESegundos()) {
                                $eventoAtual->setHora($validatedData[ConstantesForm::$FORM_HORA] . ':' . $validatedData[ConstantesForm::$FORM_MINUTOS]);
                            }
                            if ($validatedData[ConstantesForm::$FORM_MINUTOS] != $eventoAtual->getMinutosSemHorasESegundos()) {
                                $eventoAtual->setHora($validatedData[ConstantesForm::$FORM_HORA] . ':' . $validatedData[ConstantesForm::$FORM_MINUTOS]);
                            }
                            $lancamentoORM->getEventoORM()->persistirEvento($eventoAtual);
                            /* Sessão */
                            $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_ALTERAR_CELULA;
                            $sessao->textoMensagem = $eventoCelulaAtual->getNome_hospedeiro();
                        }
                    }
                    if ($criarNovaCelula) {
                        /* Entidade selecionada */
                        $idEntidadeAtual = $sessao->idEntidadeAtual;
                        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

                        $eventoCelula->exchangeArray($celulaForm->getData());
                        $eventoCelula->setTelefone_hospedeiro($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] . $validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO]);
                        $eventoCelula->setUf($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_UF)]);
                        $eventoCelula->setCidade($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_CIDADE)]);
                        $eventoCelula->setLogradouro($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_LOGRADOURO)]);
                        $eventoCelula->setBairro($post_data[(ConstantesForm::$FORM_HIDDEN . ConstantesForm::$FORM_BAIRRO)]);
                        $eventoCelula->setComplemento(strtoupper($post_data[ConstantesForm::$FORM_COMPLEMENTO]));
                        $eventoCelula->setCep($validatedData[ConstantesForm::$FORM_CEP_LOGRADOURO]);
                        $eventoCelula->setEvento($evento);

                        $dataParaCadastro = FuncoesCadastro::dataAtual();
                        if ($mudarDataDeCadstroParaProximoDomingo) {
                            $dataParaCadastro = FuncoesCadastro::proximoDomingo();
                        }
                        $evento->setData_criacao($dataParaCadastro);
                        $evento->setHora_criacao(FuncoesCadastro::horaAtual());
                        $evento->setHora($validatedData[ConstantesForm::$FORM_HORA] . ':' . $validatedData[ConstantesForm::$FORM_MINUTOS]);
                        $evento->setDia($validatedData[ConstantesForm::$FORM_DIA_DA_SEMANA]);
                        $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorIdEventoTipo(2));

                        $grupoEvento->setData_criacao(FuncoesCadastro::dataAtual());
                        $grupoEvento->setHora_criacao(FuncoesCadastro::horaAtual());
                        $grupoEvento->setGrupo($entidade->getGrupo());
                        $grupoEvento->setEvento($evento);

                        /* Persistindo */
                        $lancamentoORM->getEventoORM()->persistirEvento($evento);
                        $repositorioORM->getEventoCelulaORM()->persistirEventoCelula($eventoCelula);
                        $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEvento);
                        /* Sessão */
                        $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_CADASTRAR_CELULA;
                        $sessao->textoMensagem = $eventoCelula->getNome_hospedeiro();
                        $sessao->idSessao = $eventoCelula->getId();
                    }
                } else {
                    $this->direcionaErroDeCadastro($celulaForm->getMessages());
                }
                return $this->redirect()->toRoute(ConstantesCadastro::$ROUTE_CADASTRO, array(
                            ConstantesCadastro::$PAGINA => ConstantesCadastro::$PAGINA_CELULAS,
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
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($sessao->idEntidadeAtual);
        if (!empty($sessao->idSessao)) {
            $eventoNaSessao = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($sessao->idSessao);
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
            ConstantesForm::$EVENTO => $eventoNaSessao,
            ConstantesLancamento::$ENTIDADE => $entidade,
            ConstantesForm::$EXTRA => $extra,
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
            $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $eventoNaSessao = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($sessao->idSessao);

            $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_EXCLUIR_CULTO;
            $sessao->textoMensagem = $eventoNaSessao->getNome();
            if ($eventoNaSessao->verificaSeECelula()) {
                $celula = $eventoNaSessao->getEventoCelula();
                $sessao->tipoMensagem = ConstantesCadastro::$TIPO_MENSAGEM_EXCLUIR_CELULA;
                $sessao->textoMensagem = $celula->getNome_hospedeiro();
            }

            /* Persistindo */
            /* Inativando o Evento */
            $eventoParaInativar = $eventoNaSessao;
            $eventoParaInativar->setData_inativacao(FuncoesCadastro::dataAtual());
            $eventoParaInativar->setHora_inativacao(FuncoesCadastro::horaAtual());
            $lancamentoORM->getEventoORM()->persistirEvento($eventoParaInativar);

            /* Inativando o Grupo Evento */
            $grupoEventoAtivos = $eventoParaInativar->getGrupoEventoAtivos();
            foreach ($grupoEventoAtivos as $gea) {
                $gea->setData_inativacao(FuncoesCadastro::dataAtual());
                $gea->setHora_inativacao(FuncoesCadastro::horaAtual());
                $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($gea);
            }
        }

        /* Sessão */
        $sessao->nomeEventoExcluido = $eventoNaSessao->getNome();

        $tipoCelula = !empty($eventoNaSessao->verificaSeECelula());
        $pagina = ConstantesCadastro::$PAGINA_CULTOS;
        if ($tipoCelula) {
            $pagina = ConstantesCadastro::$PAGINA_CELULAS;
        }

        return $this->redirect()->toRoute(ConstantesCadastro::$ROUTE_CADASTRO, array(
                    ConstantesCadastro::$PAGINA => $pagina,
        ));
    }

    /**
     * Tela com formulário de cadastro de grupo
     * GET /cadastroGrupo
     */
    public function grupoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $arrayGrupoAlunos = $grupo->getGrupoAlunoAtivos();
        $arrayHierarquia = $repositorioORM->getHierarquiaORM()->encontrarTodos();

        $form = new GrupoForm(ConstantesForm::$FORM, $arrayGrupoAlunos, $arrayHierarquia);

        $view = new ViewModel(array(
            ConstantesForm::$FORM => $form
        ));
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_GRUPO);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_GRUPO);
        return $view;
    }

    /**
     * Tela com confrmação de cadastro de grupo
     * GET /cadastroGrupoFinalizar
     */
    public function grupoFinalizarAction() {
        $request = $this->getRequest();
        $stringZero = '0';
        if ($request->isPost()) {
            try {
//                $post_data = $request->getPost();
//                $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
//                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
//                $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
//
//                /* Solteiro */
//                /* Alterar dados do aluno */
//                $matricula = $post_data[ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . $stringZero];
//                $turmaAluno = $repositorioORM->getTurmaAlunoORM()->encontrarPorIdTurmaAluno($matricula);
//                $pessoaSelecionada = $turmaAluno->getPessoa();
//                $tokenDeAgora = $pessoaSelecionada->gerarToken();
//                $pessoaSelecionada->setToken($tokenDeAgora);
//                $pessoaAtualizada = $loginORM->getPessoaORM()->
//                        atualizarAlunoComDadosDaBuscaPorCPF($pessoaSelecionada, $post_data);
//
//                /* Criar hierarquia */
//                $idHierarquia = $post_data[ConstantesForm::$FORM_HIERARQUIA . $stringZero];
//                $hierarquia = $repositorioORM->getHierarquiaORM()->encontrarPorIdHierarquia($idHierarquia);
//                $pessoaHierarquia = new PessoaHierarquia();
//                $pessoaHierarquia->setPessoa($pessoaAtualizada);
//                $pessoaHierarquia->setHierarquia($hierarquia);
//                $repositorioORM->getPessoaHierarquiaORM()->persistirPessoaHierarquia($pessoaHierarquia);
//
//                /* Criar Grupo */
//                $grupoNovo = new Grupo();
//                $lancamentoORM->getGrupoORM()->persistirGrupo($grupoNovo);
//
//                /* Entidade abaixo do perfil selecionado/logado */
//                $entidadeNova = new Entidade();
//                $entidadeNova->setEntidadeTipo(
//                        $repositorioORM->getEntidadeTipoORM()->encontrarPorIdEntidade(8)
//                );
//                $entidadeNova->setGrupo($grupoNovo);
//                $entidadeNova->setNumero($post_data[ConstantesForm::$FORM_NUMERACAO]);
//                $lancamentoORM->getEntidadeORM()->persistirEntidade($entidadeNova);
//
//                /* Criar Grupo_Responsavel */
//                $grupoResponsavelNovo = new GrupoResponsavel();
//                $grupoResponsavelNovo->setPessoa($pessoaAtualizada);
//                $grupoResponsavelNovo->setGrupo($grupoNovo);
//                $repositorioORM->getGrupoResponsavelORM()->persistirGrupoResponsavel($grupoResponsavelNovo);
//
//                /* Criar Grupo_Pai_Filho */
//                $sessao = new Container(Constantes::$NOME_APLICACAO);
//                $idEntidadeAtual = $sessao->idEntidadeAtual;
//                $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
//                $grupoAtualSelecionado = $entidade->getGrupo();
//                $grupoPaiFilhoNovo = new GrupoPaiFilho();
//                $grupoPaiFilhoNovo->setGrupoPaiFilhoPai($grupoAtualSelecionado);
//                $grupoPaiFilhoNovo->setGrupoPaiFilhoFilho($grupoNovo);
//                $repositorioORM->getGrupoPaiFilhoORM()->persistirGrupoResponsavel($grupoPaiFilhoNovo);
//
//                // Enviar Email
//                $this->enviarEmailParaCompletarOsDados($tokenDeAgora);
//                // Dados Extras

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
    public function grupoAtualizarAction() {
        return new ViewModel();
    }

    /**
     * Mostrar as mensagens de erro
     * @param type $mensagens
     */
    public function direcionaErroDeCadastro($mensagens) {
        echo "ERRO: Cadastro invalido!<br /><br />########################<br />";
        foreach ($mensagens as $value) {
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
                $cep_logradouro = $post_data[ConstantesForm::$FORM_CEP_LOGRADOURO];

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
                $email = $post_data[ConstantesForm::$FORM_EMAIL];

                $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
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
        $mensagem = '';
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $cpf = $post_data[ConstantesForm::$FORM_CPF];

                $nomeDaPesquisa = '';
                $dataDeNascimentoDaPesquisa = '';

                $dataNascimento = str_replace('/', '', $post_data[ConstantesForm::$FORM_DATA_NASCIMENTO]);
                $urlUsada = ConstantesCadastro::$PROCOB_URL . ConstantesCadastro::$PROCOB_URL_RECEITA_FEDERAL . $cpf . '?dataNascimento=' . $dataNascimento;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, ConstantesCadastro::$PROCOB_USUARIO . ':' . ConstantesCadastro::$PROCOB_SENHA);
                curl_setopt($curl, CURLOPT_URL, $urlUsada);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($curl);
                curl_close($curl);
                $result = str_replace('":"', '#', $result);
                $result = str_replace('","', '#', $result);
                $result = str_replace('"{"', '#', $result);
                $result = str_replace('"}"', '#', $result);
                $explodeResultado = explode('#', $result);
//                var_dump($explodeResultado);
                /* Sucesso */
                if ($explodeResultado[1] === '000') {
                    $nomeDaPesquisa = $explodeResultado[9];
                    $dataDeNascimentoDaPesquisa = Funcoes::mudarPadraoData(str_replace('\/', '-', $explodeResultado[15]), 2);
                    $resposta = 1;
                }
                if ($explodeResultado[1] === '001') {
                    $resposta = 2;
                    $mensagem = 'CPF não encontrado na base de dados!';
                }
                if ($explodeResultado[1] === '999') {
                    $resposta = 2;
                    $mensagem = $explodeResultado[3];
                }

                $dadosDeResposta = array(
                    'resposta' => $resposta,
                    'cpf' => $cpf,
                    'nome' => $nomeDaPesquisa,
                    'dataNascimento' => $dataDeNascimentoDaPesquisa,
                    'mensagem' => $mensagem,
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
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function enviarEmailParaCompletarOsDados($tokenDeAgora) {
        $Subject = 'Convite';
        $ToEmail = 'falecomleonardopereira@gmail.com';
        $avatar = 'placeholder.png';
        $nomeLider = 'LeonardoPereiraMagalhães';
        $Content = file_get_contents("http://158.69.124.139/convite.php?nomeLider=$nomeLider&avatar=$avatar&token=$tokenDeAgora");
        Funcoes::enviarEmail($ToEmail, $Subject, $Content);
    }

}
