<?php

namespace Cadastro\Controller;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Controller\Helper\Correios;
use Cadastro\Controller\Helper\FuncoesCadastro;
use Cadastro\Controller\Helper\RepositorioORM;
use Cadastro\Form\CelulaForm;
use Cadastro\Form\ConstantesForm;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\Evento;
use Entidade\Entity\EventoCelula;
use Entidade\Entity\GrupoEvento;
use Exception;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;
use Login\Controller\Helper\Constantes;
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
        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesCadastro::$PAGINA, 1);
        if ($pagina == ConstantesCadastro::$PAGINA_CELULAS) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULAS,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_CELULA) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULA,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_CELULA_CONFIRMACAO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULA_CONFIRMACAO,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_SALVAR_CELULA) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_SALVAR_CELULA,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_BUSCAR_ENDERECO) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_BUSCAR_ENDERECO,
            ));
        }
        /* Funcoes */
        if ($pagina == ConstantesLancamento::$PAGINA_FUNCOES) {
            /* Registro de sessão com o id passado na função */
            $request = $this->getRequest();
            $post_data = $request->getPost();

            $sessao->idFuncaoLancamento = $post_data[Constantes::$ID];
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_FUNCOES,
            ));
        }

        return new ViewModel();
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelulas
     */
    public function celulasAction() {
        /* Verificando se alguma célula foi cadastrada */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $nomeHospedeiroCelulaCadastrado = '';
        if (!empty($sessao->nomeHospedeiroCelulaCadastrado)) {
            $nomeHospedeiroCelulaCadastrado = $sessao->nomeHospedeiroCelulaCadastrado;
            unset($sessao->nomeHospedeiroCelulaCadastrado);
        }
        $nomeHospedeiroCelulaAlterada = '';
        if (!empty($sessao->nomeHospedeiroCelulaAlterada)) {
            $nomeHospedeiroCelulaAlterada = $sessao->nomeHospedeiroCelulaAlterada;
            unset($sessao->nomeHospedeiroCelulaAlterada);
        }

        /* Listagem de celulas */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $grupoEventosDoTipoCelula = $grupo->getGrupoEventoCelula();

        $view = new ViewModel(array(ConstantesForm::$LISTAGEM_CELULAS => $grupoEventosDoTipoCelula));
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_CELULAS);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_CELULAS);

        $layoutJSValidacao = new ViewModel(array(
            ConstantesForm::$LAYOUT_NOME_HOSPEDEIRO_CELULA_CADASTRADO => $nomeHospedeiroCelulaCadastrado,
            ConstantesForm::$LAYOUT_NOME_HOSPEDEIRO_CELULA_ALTERADA => $nomeHospedeiroCelulaAlterada
        ));
        $layoutJSValidacao->setTemplate(ConstantesForm::$LAYOUT_JS_CELULAS_VALIDACAO);
        $view->addChild($layoutJSValidacao, ConstantesForm::$LAYOUT_STRING_JS_CELULAS_VALIDACAO);

        return $view;
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelula
     */
    public function celulaAction() {
        $enderecoHidden = '';

        /* Verificando a se tem algum id na sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $eventoCelulaNaSessao = new EventoCelula();
        if (!empty($sessao->idSessao)) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $eventoCelulaNaSessao = $repositorioORM->getEventoCelulaORM()->encontrarPorIdEventoCelula($sessao->idSessao);
        } else {
            $enderecoHidden = ConstantesForm::$FORM_HIDDEN;
        }

        $celulaForm = new CelulaForm(ConstantesForm::$FORM_CELULA, $eventoCelulaNaSessao);

        $view = new ViewModel(array(
            ConstantesForm::$FORM_CELULA => $celulaForm,
            ConstantesForm::$FORM_ENDERECO_HIDDEN => $enderecoHidden
        ));
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_CELULA);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_CELULA);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(ConstantesForm::$LAYOUT_JS_CELULA_VALIDACAO);
        $view->addChild($layoutJSValidacao, ConstantesForm::$LAYOUT_STRING_JS_CELULA_VALIDACAO);

        return $view;
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelulaConfirmacao
     */
    public function celulaConfirmacaoAction() {
        return new ViewModel();
    }

    /**
     * Função para salvar a células
     * POST /cadastroSalvarCelula
     */
    public function salvarCelulaAction() {
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
                            $sessao->nomeHospedeiroCelulaAlterada = $eventoCelulaAtual->getNome_hospedeiro();
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
                        $sessao->nomeHospedeiroCelulaCadastrado = $eventoCelula->getNome_hospedeiro();
                    }
                } else {
                    echo "ERRO: Cadastro invalido!<br />";
                    foreach ($celulaForm->getMessages() as $value) {
                        foreach ($value as $key => $value) {
                            echo "$key => $value <br />";
                        }
                    }
                }

                return $this->redirect()->toRoute(ConstantesCadastro::$ROUTE_CADASTRO, array(
                            ConstantesCadastro::$PAGINA => ConstantesCadastro::$PAGINA_CELULAS,
                ));
//                return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
//                            Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULAS,
//                ));
            } catch (Exception $exc) {
                echo $exc->getMessage();
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

}
