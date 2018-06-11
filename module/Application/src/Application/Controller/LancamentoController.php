<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\AtendimentoComentarioForm;
use Application\Form\CadastrarPessoaForm;
use Application\Model\Entity\DimensaoTipo;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoAtendimento;
use Application\Model\Entity\GrupoAtendimentoComentario;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\Pessoa;
use Application\Model\ORM\RepositorioORM;
use Application\View\Helper\ListagemDePessoasComEventos;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Exception;
use Migracao\Controller\IndexController;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: LancamentoContgetLiderroller.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class LancamentoController extends CircuitoController {

    const TIPO_CAMPO_CELULA = 1;
    const TIPO_CAMPO_CULTO = 2;
    const TIPO_CAMPO_ARENA = 3;
    const TIPO_CAMPO_DOMINGO = 4;
    const TIPO_PESSOA_LIDER = 4;

    /**
     * Traz a tela para lancamento de arregimentação
     * GET /lancamentoArregimentacao
     */
    public function arregimentacaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
        $grupo = $entidade->getGrupo();

        $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

        if (!$entidade->verificarSeEstaAtivo()) {
            $periodoVerificar = Funcoes::encontrarPeriodoDeUmMes($entidade->getData_inativacaoMes());
            if ($periodo > $periodoVerificar) {
                $periodo = $periodoVerificar;
            }
        }

        /* Verificando se posso recuar no periodo */
        $mostrarBotaoPeriodoAnterior = false;
        $mostrarBotaoPeriodoAfrente = false;
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
        $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);

        if ($grupo->getGrupoPaiFilhoPaiAtivo()) {
            $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($grupo->getGrupoPaiFilhoPaiAtivo()->getData_criacaoStringPadraoBanco());

            $validarCadastroAntesDoPeriodo = false;
            if ($dataDoGrupoPaiFilhoCriacaoParaComparar < $dataDoInicioDoPeriodoParaComparar) {
                $validarCadastroAntesDoPeriodo = true;
            }
            $validarCadastroDaEntidade = false;
            $dataDaEntidadeCriacaoParaComparar = strtotime($grupo->getEntidadeAtiva()->getData_criacaoStringPadraoBanco());
            if ($dataDaEntidadeCriacaoParaComparar < $dataDoInicioDoPeriodoParaComparar) {
                $validarCadastroDaEntidade = true;
            }
            if ($validarCadastroAntesDoPeriodo) {
                $mostrarBotaoPeriodoAnterior = true;
            }
        }

        if ($periodo < 0 && $entidade->verificarSeEstaAtivo()) {
            $mostrarBotaoPeriodoAfrente = true;
        }

        $grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($periodo);

        $contagemDePessoasCadastradas = count($grupo->getGrupoPessoasNoPeriodo($periodo));
        $validacaoPessoasCadastradas = 0;
        if ($contagemDePessoasCadastradas > Constantes::$QUANTIDADE_MAXIMA_PESSOAS_NO_LANÇAMENTO) {
            $validacaoPessoasCadastradas = 1;
        }

        $view = new ViewModel(
                array(
            Constantes::$REPOSITORIO_ORM => $this->getRepositorio(),
            Constantes::$GRUPO => $grupo,
            Constantes::$PERIODO => $periodo,
            Constantes::$VALIDACAO => $validacaoPessoasCadastradas,
            'mostrarBotaoPeriodoAnterior' => $mostrarBotaoPeriodoAnterior,
            'mostrarBotaoPeriodoAfrente' => $mostrarBotaoPeriodoAfrente,
                )
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO);

        $layoutJS2 = new ViewModel(array(Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($grupoEventoNoPeriodo),));
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_LANCAMENTO_MODAL_EVENTOS);

        if ($sessao->jaMostreiANotificacao) {
            unset($sessao->mostrarNotificacao);
            unset($sessao->nomePessoa);
            unset($sessao->exclusao);
            unset($sessao->jaMostreiANotificacao);
        }

        return $view;
    }

    public function enviarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
        $grupo = $entidade->getGrupo();
        $periodo = $sessao->idSessao;
        $grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($periodo);

        $relatorio = array();
        foreach ($grupoEventoNoPeriodo as $grupoEvento) {
            $tipoCampo = 0;
            if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCulto) {
                $diaDeSabado = 7;
                $diaDeDomingo = 1;
                switch ($grupoEvento->getEvento()->getDia()) {
                    case $diaDeSabado:
                        $tipoCampo = LancamentoController::TIPO_CAMPO_ARENA;
                        break;
                    case $diaDeDomingo:
                        $tipoCampo = LancamentoController::TIPO_CAMPO_DOMINGO;
                        break;
                    default:
                        $tipoCampo = LancamentoController::TIPO_CAMPO_CULTO;
                        break;
                };
            }
            if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula) {
                $tipoCampo = LancamentoController::TIPO_CAMPO_CELULA;
            }

            $diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
            if ($diaDaSemanaDoEvento === 1) {
                $diaDaSemanaDoEvento = 7; // domingo
            } else {
                $diaDaSemanaDoEvento--;
            }
            $diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $periodo);
            $eventoFrequencia = $grupoEvento->getEvento()->getEventoFrequencia();
            if ($eventoFrequencia) {
                /* Lideres */
                if ($grupoResponsabilidades = $grupo->getResponsabilidadesAtivas()) {
                    foreach ($grupoResponsabilidades as $grupoResponsavel) {
                        if ($eventosFrequenciaSelecionado = $grupoResponsavel->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento)) {
                            $valor = $eventosFrequenciaSelecionado->getFrequencia();
                            if ($valor == 'S') {
                                $tipoPessoa = LancamentoController::TIPO_PESSOA_LIDER;
                                $relatorio[$tipoCampo][$tipoPessoa] ++;

                                if ($grupoEvento->getEvento()->verificaSeECelula()) {
                                    $eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
                                    $relatorio['celula'][$eventoCelulaId] ++;
                                }
                            }
                        }
                    }
                }
                /* Pessoas Volateis */
                if ($grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($periodo)) {
                    foreach ($grupoPessoasNoPeriodo as $grupoPessoa) {
                        if ($eventosFrequenciaSelecionado = $grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento)) {
                            $valor = $eventosFrequenciaSelecionado->getFrequencia();
                            if ($valor == 'S') {
                                $tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();
                                $relatorio[$tipoCampo][$tipoPessoa] ++;

                                if ($grupoEvento->getEvento()->verificaSeECelula()) {
                                    $eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
                                    $relatorio['celula'][$eventoCelulaId] ++;
                                }
                            }
                        }
                    }
                }
            }
        }

        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
        $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
        $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
        $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
        $fatoCicloSelecionado = $this->getRepositorio()->getFatoCicloORM()->encontrarPorNumeroIdentificadorEDataCriacao(
                $numeroIdentificador, $dataDoPeriodoFormatada, $this->getRepositorio());

        $dimensoes = array();
        if ($fatoCicloSelecionado->getDimensao()) {
            foreach ($fatoCicloSelecionado->getDimensao() as $dimensao) {
                switch ($dimensao->getDimensaoTipo()->getId()) {
                    case DimensaoTipo::CELULA:
                        $dimensoes[DimensaoTipo::CELULA] = $dimensao;
                        break;
                    case DimensaoTipo::CULTO:
                        $dimensoes[DimensaoTipo::CULTO] = $dimensao;
                        break;
                    case DimensaoTipo::ARENA:
                        $dimensoes[DimensaoTipo::ARENA] = $dimensao;
                        break;
                    case DimensaoTipo::DOMINGO:
                        $dimensoes[DimensaoTipo::DOMINGO] = $dimensao;
                        break;
                }
            }
            $this->getRepositorio()->iniciarTransacao();
            foreach ($dimensoes as $dimensao) {
                if (!$relatorio[$dimensao->getDimensaoTipo()->getId()][LancamentoController::TIPO_PESSOA_LIDER]) {
                    $relatorio[$dimensao->getDimensaoTipo()->getId()][LancamentoController::TIPO_PESSOA_LIDER] = 0;
                }

                if (!$relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::MEMBRO]) {
                    $relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::MEMBRO] = 0;
                }

                if (!$relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::CONSOLIDACAO]) {
                    $relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::CONSOLIDACAO] = 0;
                }

                if (!$relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::VISITANTE]) {
                    $relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::VISITANTE] = 0;
                }

                $dimensao->setLider($relatorio[$dimensao->getDimensaoTipo()->getId()][LancamentoController::TIPO_PESSOA_LIDER]);
                $dimensao->setMembro($relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::MEMBRO]);
                $dimensao->setConsolidacao($relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::CONSOLIDACAO]);
                $dimensao->setVisitante($relatorio[$dimensao->getDimensaoTipo()->getId()][GrupoPessoaTipo::VISITANTE]);
                $this->getRepositorio()->getDimensaoORM()->persistir($dimensao, false);

                if ($dimensao->getDimensaoTipo()->getId() === DimensaoTipo::CELULA) {
                    $fatoCelulas = $fatoCicloSelecionado->getFatoCelula();
                    foreach ($fatoCelulas as $fatoCelula) {
                        $realizada = 0;
                        if ($relatorio['celula'][$fatoCelula->getEvento_celula_id()] > 0) {
                            $realizada = 1;
                        }
                        $fatoCelula->setRealizada($realizada);
                        $this->getRepositorio()->getFatoCelulaORM()->persistir($fatoCelula, false);
                    }
                }
            }
            $this->getRepositorio()->fecharTransacao();
        }

        return new ViewModel();
    }

    /**
     * Muda a frequência de um evento
     * @return Json
     */
    public function mudarFrequenciaAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $resposta = false;
        $hostname = 'www.google.com.br';
        if (!$sock = @fsockopen($hostname, 80, $num, $error, 5)) {
            $response->setContent(Json::encode(array('response' => $resposta)));
            return $response;
        }
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();

                $post_data = $request->getPost();
                $valor = $post_data['valor'];
                $idPessoa = $post_data['idPessoa'];
                $idEvento = $post_data['idEvento'];
                $diaRealDoEvento = $post_data['diaRealDoEvento'];
                $periodo = $post_data['periodo'];
                $dateFormatada = DateTime::createFromFormat('Y-m-d', $diaRealDoEvento);

                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $evento = $this->getRepositorio()->getEventoORM()->encontrarPorId($idEvento);

                $valorParaSomar = 0;
                if ($valor === 'S') {
                    $valorParaSomar = 1;
                } else {
                    $valorParaSomar = -1;
                }

                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
                $dimensaoSelecionada = null;
                $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
                $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
                $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
                $fatoCicloSelecionado = $this->getRepositorio()->getFatoCicloORM()->encontrarPorNumeroIdentificadorEDataCriacao(
                        $numeroIdentificador, $dataDoPeriodoFormatada, $this->getRepositorio());

                if ($fatoCicloSelecionado->getDimensao()) {
                    foreach ($fatoCicloSelecionado->getDimensao() as $dimensao) {
                        switch ($dimensao->getDimensaoTipo()->getId()) {
                            case DimensaoTipo::CELULA:
                                $dimensoes[DimensaoTipo::CELULA] = $dimensao;
                                break;
                            case DimensaoTipo::CULTO:
                                $dimensoes[DimensaoTipo::CULTO] = $dimensao;
                                break;
                            case DimensaoTipo::ARENA:
                                $dimensoes[DimensaoTipo::ARENA] = $dimensao;
                                break;
                            case DimensaoTipo::DOMINGO:
                                $dimensoes[DimensaoTipo::DOMINGO] = $dimensao;
                                break;
                        }
                    }
                }
                $tipoCampo = 0;
                if ($evento->getEventoTipo()->getId() === EventoTipo::tipoCulto) {
                    $diaDeSabado = 7;
                    $diaDeDomingo = 1;
                    switch ($evento->getDia()) {
                        case $diaDeSabado:
                            $dimensaoSelecionada = $dimensoes[DimensaoTipo::ARENA];
                            $tipoCampo = LancamentoController::TIPO_CAMPO_ARENA;
                            break;
                        case $diaDeDomingo:
                            $dimensaoSelecionada = $dimensoes[DimensaoTipo::DOMINGO];
                            $tipoCampo = LancamentoController::TIPO_CAMPO_DOMINGO;
                            break;
                        default:
                            $dimensaoSelecionada = $dimensoes[DimensaoTipo::CULTO];
                            $tipoCampo = LancamentoController::TIPO_CAMPO_CULTO;
                            break;
                    };
                }
                if ($evento->getEventoTipo()->getId() === EventoTipo::tipoCelula) {
                    $tipoCampo = LancamentoController::TIPO_CAMPO_CELULA;
                    $dimensaoSelecionada = $dimensoes[DimensaoTipo::CELULA];

                    /* Atualiza o relatorio de celulas */
                    $criteria = Criteria::create()
                            ->andWhere(Criteria::expr()->eq("dia", $dateFormatada));

                    $frequencias = $evento->getEventoFrequencia()->matching($criteria);
                    $somaFrequencias = 0;
                    foreach ($frequencias as $frequenca) {
                        if ($frequenca->getFrequencia() === 'S') {
                            $somaFrequencias++;
                        }
                    }

                    /* Atualizando relatorio individual da celula no periodo */
                    if ($somaFrequencias === 0) {
                        $realizada = 0;
                    } else {
                        $realizada = 1;
                    }

                    $eventoCelulaId = $evento->getEventoCelula()->getId();
                    $fatoCelulas = $fatoCicloSelecionado->getFatoCelula();
                    echo "fatoCicloSelecionado" . $fatoCicloSelecionado->getId();

                    $fatoCelulaSelecionado = null;
                    foreach ($fatoCelulas as $fatoCelula) {
                        echo "fatoCelula" . $fatoCelula->getId();
                        echo "getEvento_celula_id" . $fatoCelula->getEvento_celula_id();
                        echo "eventoCelulaId" . $eventoCelulaId;
                        if ($fatoCelula->getEvento_celula_id() == $eventoCelulaId) {
                            $fatoCelulaSelecionado = $fatoCelula;
                        }
                    }

                    $realizadaAntesDeMudar = $fatoCelulaSelecionado->getRealizada();
                    $fatoCelulaSelecionado->setRealizada($realizada);
                    $setarDataEHora = false;
                    $this->getRepositorio()->getFatoCelulaORM()->persistir($fatoCelulaSelecionado, $setarDataEHora);
                }
                $tipoPessoa = 0;
                if ($pessoa->getGrupoPessoaAtivo()) {
                    /* Pessoa volateis */
                    $valorDoCampo = 0;
                    switch ($pessoa->getGrupoPessoaAtivo()->getGrupoPessoaTipo()->getId()) {
                        case GrupoPessoaTipo::VISITANTE:
                            $valorDoCampo = $dimensaoSelecionada->getVisitante();
                            if ($valorDoCampo === 0 && $valorParaSomar === -1) {
                                $valorParaSomar = 0;
                            }
                            $dimensaoSelecionada->setVisitante($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = GrupoPessoaTipo::VISITANTE;
                            break;
                        case GrupoPessoaTipo::CONSOLIDACAO:
                            $valorDoCampo = $dimensaoSelecionada->getConsolidacao();
                            if ($valorDoCampo === 0 && $valorParaSomar === -1) {
                                $valorParaSomar = 0;
                            }
                            $dimensaoSelecionada->setConsolidacao($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = GrupoPessoaTipo::CONSOLIDACAO;
                            break;
                        case GrupoPessoaTipo::MEMBRO:
                            $valorDoCampo = $dimensaoSelecionada->getMembro();
                            if ($valorDoCampo === 0 && $valorParaSomar === -1) {
                                $valorParaSomar = 0;
                            }
                            $dimensaoSelecionada->setMembro($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = GrupoPessoaTipo::MEMBRO;
                            break;
                    }
                } else {
                    $tipoPessoa = LancamentoController::TIPO_PESSOA_LIDER;
                    $valorDoCampo = $dimensaoSelecionada->getLider();
                    if ($valorDoCampo === 0 && $valorParaSomar === -1) {
                        $valorParaSomar = 0;
                    }
                    $dimensaoSelecionada->setLider($valorDoCampo + $valorParaSomar);
                }
                $this->getRepositorio()->getDimensaoORM()->persistir($dimensaoSelecionada, false);

                /* Frequencia */
                $eventosFiltrado = $pessoa->getEventoFrequenciaFiltradoPorEventoEDia($idEvento, $diaRealDoEvento);
                if ($eventosFiltrado) {
                    /* Frequencia existe */
                    $frequencia = $eventosFiltrado;
                    $frequencia->setFrequencia($valor);
                } else {
                    /* Persitir frequencia */
                    $frequencia = new EventoFrequencia();
                    $frequencia->setPessoa($pessoa);
                    $frequencia->setEvento($evento);
                    $frequencia->setFrequencia($valor);
                    $frequencia->setDia($dateFormatada);
                }
                $this->getRepositorio()->getEventoFrequenciaORM()->persistir($frequencia);
                if ($pessoa->getEventoFrequenciaFiltradoPorEventoEDia($idEvento, $diaRealDoEvento)) {
                    $resposta = true;
                }
                $this->getRepositorio()->fecharTransacao();
                $response->setContent(Json::encode(
                                array('response' => $resposta,
                                    'idEvento' => $evento->getId())));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Abri tela para cadastro de pessoa para lançamento
     * @return ViewModel
     */
    public function cadastrarPessoaAction() {
        /* Helper Controller */

        $tipos = $this->getRepositorio()->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        /* Formulario */
        $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos);
        $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

        $view = new ViewModel(array(
            Constantes::$FORM_CADASTRAR_PESSOA => $formCadastrarPessoa,
            Constantes::$PERIODO => $periodo,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA);
        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA);

        return $view;
    }

    /**
     * Salva uma nova pessoa na linha de lançamento
     */
    public function salvarPessoaAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                $pessoa = new Pessoa();
                $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA);
                $formCadastrarPessoa->setInputFilter($pessoa->getInputFilterPessoaFrequencia());
                $formCadastrarPessoa->setData($post_data);

                /* validação */
                if ($formCadastrarPessoa->isValid()) {
                    $validatedData = $formCadastrarPessoa->getData();

                    $pessoa->exchangeArray($formCadastrarPessoa->getData());
                    $pessoa->setTelefone($validatedData[Constantes::$INPUT_DDD] . $validatedData[Constantes::$INPUT_TELEFONE]);

                    /* Helper Controller */


                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($this->getRepositorio());

                    /* Salvar a pessoa e o grupo pessoa correspondente */
                    $this->getRepositorio()->getPessoaORM()->persistir($pessoa);
                    $grupoPessoaTipo = $this->getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId($post_data[Constantes::$INPUT_TIPO]);

                    $grupoPessoa = new GrupoPessoa();
                    $grupoPessoa->setPessoa($pessoa);
                    $grupoPessoa->setGrupo($grupo);
                    $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
                    $nucleoPerfeito = $validatedData[Constantes::$INPUT_NUCLEO_PERFEITO];
                    if ($nucleoPerfeito != 0) {
                        $grupoPessoa->setNucleo_perfeito($nucleoPerfeito);
                    }

                    $this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa);

                    /* Pondo valores na sessao */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->mostrarNotificacao = true;
                    $sessao->nomePessoa = $pessoa->getNome();
                    unset($sessao->exclusao);

                    $periodo = $post_data[Constantes::$PERIODO];

                    return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
                                Constantes::$ACTION => 'Arregimentacao',
                                Constantes::$ID => $periodo,
                    ));
                }
            } catch (Exception $exc) {
                CircuitoController::direcionaErroDeCadastro($exc->getMessage());
            }
        }
    }

    /**
     * Alterar nome de uma pessoa
     * @return Json
     */
    public function alterarNomeAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idPessoa = $post_data['idPessoa'];
                $nome = $post_data['nome'];

                /* Helper Controller */
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setNome($nome);
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa);

                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'nomeAjustado' => $pessoa->getNomeListaDeLancamento(),
                                    'nome' => $pessoa->getNome(),
                )));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Remover pessoa da listagem
     * @return Json
     */
    public function removerPessoaAction() {
        try {
            $sessao = new Container(Constantes::$NOME_APLICACAO);

            /* Helper Controller */


            $grupoPessoa = $this->getRepositorio()->getGrupoPessoaORM()->encontrarPorId($sessao->idSessao);
            $grupoPessoa->setDataEHoraDeInativacao();
            $this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa, false);

            /* Pondo valores na sessao */
            $sessao->mostrarNotificacao = true;
            $sessao->nomePessoa = $grupoPessoa->getPessoa()->getNome();
            $sessao->exclusao = true;

            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => 'Arregimentacao',
            ));
        } catch (Exception $exc) {
            CircuitoController::direcionaErroDeCadastro($exc->getMessage());
        }
    }

    /**
     * Controle de funçoes da tela de lançamento
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
                                    'url' => '/lancamento' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        return $response;
    }

    /**
     * Abri tela para relatorio de atendimento
     * @return ViewModel
     */
    public function atendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        $parametro = $this->params()->fromRoute(Constantes::$ID);
        if (empty($parametro)) {
            $abaSelecionada = 1;
        } else {
            $abaSelecionada = $parametro;
        }

        $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);

        $gruposAbaixo = null;
        if ($grupo->getGrupoPaiFilhoFilhosPorMes($mesSelecionado)) {
            $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhosPorMes($mesSelecionado);
        }

        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }

        $view = new ViewModel(array(
            Constantes::$GRUPOS_ABAIXO => $gruposAbaixo,
            Constantes::$MES => $mesSelecionado,
            Constantes::$ANO => $anoSelecionado,
            Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            Constantes::$ABA_SELECIONADA => $abaSelecionada,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO_ATENDIMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO_ATENDIMENTO);

        return $view;
    }

    /**
     * Muda atendimento
     * @return Json
     */
    public function mudarAtendimentoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $tipoLancar = 1;
        $tipoRemover = 2;
        if ($request->isPost()) {

            try {
                $this->getRepositorio()->iniciarTransacao();

                $post_data = $request->getPost();
                $tipo = (int) $post_data['tipo'];
                $idGrupo = $post_data['idGrupo'];
                $abaSelecionada = $post_data['abaSelecionada'];
                $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
                $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);

                $grupoAtendimentosFiltrados = array();
                $grupoLancado = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);

                if ($tipo === $tipoLancar) {
                    $dataCriacao = $anoSelecionado . '-' . $mesSelecionado . '-01';
                    $grupoAtendimento = new GrupoAtendimento();
                    $grupoAtendimento->setDataEHoraDeCriacao($dataCriacao);
                    $grupoAtendimento->setGrupo($grupoLancado);
                    $this->getRepositorio()->getGrupoAtendimentoORM()->persistir($grupoAtendimento, false);
                }
                if ($tipo === $tipoRemover) {
                    $grupoAtendimentoParaDesativar = null;
                    $grupoAtendimentosAtuais = $grupoLancado->getGrupoAtendimento();
                    $contador = 0;
                    foreach ($grupoAtendimentosAtuais as $grupoAtendimento) {
                        if ($grupoAtendimento->verificaSeTemNesseMesEAno($mesSelecionado, $anoSelecionado)) {
                            if ($contador === 0) {
                                $grupoAtendimentoParaDesativar = $grupoAtendimento;
                                break;
                            }
                        }
                    }
                    if ($grupoAtendimentoParaDesativar) {
                        $grupoAtendimentoParaDesativar->setDataEHoraDeInativacao();
                        $this->getRepositorio()->getGrupoAtendimentoORM()->persistir($grupoAtendimentoParaDesativar, false);
                    }
                }

                $numeroAtendimentos = $grupoLancado->totalDeAtendimentos($mesSelecionado, $anoSelecionado);
                $explodeProgresso = explode('_', $this->retornaProgressoUsuarioNoMesEAno($this->getRepositorio(), $mesSelecionado, $anoSelecionado));
                $progresso = number_format($explodeProgresso[0], 2, '.', '');
                $colorBarTotal = LancamentoController::retornaClassBarradeProgressoPeloValor($progresso);

                if ($grupoLancado->getGrupoCv()) {
                    /* Cadastrar atendimento no circuito antigo */
                    $idAtendimento = IndexController::buscaIdAtendimentoPorLideres(
                                    $mesSelecionado, $anoSelecionado, $grupoLancado->getGrupoCv()->getLider1(), $grupoLancado->getGrupoCv()->getLider2()
                    );

                    unset($atendimentoLancado);
                    for ($index = 1; $index <= 5; $index++) {
                        if ($index <= $numeroAtendimentos) {
                            $atendimentoLancado[$index] = 'S';
                        } else {
                            $atendimentoLancado[$index] = 'N';
                        }
                    }
                    IndexController::cadastrarAtendimentoPorid($idAtendimento, $atendimentoLancado);
                }
                $this->getRepositorio()->fecharTransacao();
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    'numeroAtendimentos' => $numeroAtendimentos,
                                    'progresso' => $progresso,
                                    'corBarraTotal' => $colorBarTotal,
                                    'totalGruposAtendidos' => $explodeProgresso[1],)));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    public function atendimentoComentarioAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $explodeIdSessao = explode(99, $sessao->idSessao);
        $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($explodeIdSessao[0]);
        $formulario = new AtendimentoComentarioForm('formulario', $grupo, $explodeIdSessao[1]);
        $dados = array();
        $dados['grupo'] = $grupo;
        $dados['formulario'] = $formulario;
        return new ViewModel($dados);
    }

    public function atendimentoComentarioSalvarAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->getRepositorio()->iniciarTransacao();
            try {
                $post_data = $request->getPost();
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($post_data[Constantes::$FORM_STRING_ID]);

                $grupoAtendimentoComentario = new GrupoAtendimentoComentario();
                $grupoAtendimentoComentario->setGrupo($grupo);
                $grupoAtendimentoComentario->setComentario($post_data['comentario']);
                if ($post_data['abaSelecionada'] == 2) {
                    if (date('m') == 1) {
                        $mesAnterior = 12;
                        $anoAnteriror = (date('Y') - 1);
                    } else {
                        $mesAnterior = (date('m') - 1);
                        $anoAnteriror = date('Y');
                    }
                    $grupoAtendimentoComentario->setDataEHoraDeCriacao("$anoAnteriror-$mesAnterior-01");
                } else {
                    $grupoAtendimentoComentario->setDataEHoraDeCriacao();
                }
                $this->getRepositorio()->getGrupoAtendimentoComentarioORM()->persistir($grupoAtendimentoComentario, $mudarDataDeCriacao = false);

                $this->getRepositorio()->fecharTransacao();

                return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
                            Constantes::$ACTION => 'Atendimento',
                            Constantes::$ID => $post_data['abaSelecionada'],
                ));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function atendimentoComentarioRemoverAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $id = $post_data['id'];
                $grupoAtendimentoComentario = $this->getRepositorio()->getGrupoAtendimentoComentarioORM()->encontrarPorId($id);
                $grupoAtendimentoComentario->setDataEHoraDeInativacao();
                $this->getRepositorio()->getGrupoAtendimentoComentarioORM()->persistir($grupoAtendimentoComentario, $naoAlterarData = false);

                $this->getRepositorio()->fecharTransacao();
                $response->setContent(Json::encode(array('response' => 'true')));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    public function retornaProgressoUsuarioNoMesEAno($repositorioORM, $mes, $ano) {
        $grupo = $this->getGrupoSelecionado($repositorioORM);
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();
        $totalGruposFilhosAtivos = 0;
        $totalGruposAtendidos = 0;
        foreach ($gruposAbaixo as $gpFilho) {
            $encontrouAtendimento = false;
            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
            $grupoResponsavelAtivos = $grupoFilho->getResponsabilidadesAtivas();
            if ($grupoResponsavelAtivos) {
                $atendimentosDoGrupo = $grupoFilho->getGrupoAtendimento();
                foreach ($atendimentosDoGrupo as $atendimentos) {
                    if ($atendimentos->verificaSeTemNesseMesEAno($mes, $ano)) {
                        $encontrouAtendimento = true;
                    }
                }
                if ($encontrouAtendimento) {
                    $totalGruposAtendidos++;
                }
            }
            if ($grupoFilho->verificarSeEstaAtivo()) {
                $totalGruposFilhosAtivos++;
            }
        }
        $progresso = ($totalGruposAtendidos / $totalGruposFilhosAtivos) * 100;

        return $progresso . "_" . $totalGruposAtendidos;
    }

    public static function retornaClassBarradeProgressoPeloValor($valor) {
        $class = '';
        if ($valor > 50 && $valor < 80) {
            $class = "progress-bar-warning";
        } else if ($valor >= 80) {
            $class = "progress-bar-success";
        } else {
            $class = "progress-bar-danger";
        }
        return $class;
    }

    /**
     * Recupera o grupo do perfil selecionado
     * @param RepositorioORM $repositorioORM
     * @return Grupo
     */
    public function getGrupoSelecionado($repositorioORM) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        return $entidade->getGrupo();
    }

}
