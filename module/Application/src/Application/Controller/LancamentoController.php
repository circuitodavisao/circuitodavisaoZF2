<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\CadastrarPessoaForm;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoAtendimento;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\Pessoa;
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: LancamentoContgetLiderroller.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class LancamentoController extends CircuitoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM 
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /lancamento[:pagina[/:id]]
     */
    public function indexAction() {
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

        /* Teste de funcao */
//        echo "testando funcao de alterarVisitanteParaConsolidacao <br />";
//        $repositorioORM->getGrupoPessoaORM()->alterarVisitanteParaConsolidacao($repositorioORM);

        /* Aba selecionada e ciclo */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        $explodeParamentro = explode('_', $parametro);
        if (empty($explodeParamentro[0])) {
            $abaSelecionada = 1;
        } else {
            if ($explodeParamentro[0] == 1) {
                $abaSelecionada = 1;
            } else {
                if ($explodeParamentro[0] == 2) {
                    $abaSelecionada = 2;
                }
            }
        }

        if (empty($explodeParamentro[1])) {
            $mes1 = Funcoes::mesPorAbaSelecionada($abaSelecionada);
            $ano1 = Funcoes::anoPorAbaSelecionada($abaSelecionada);
            if ($abaSelecionada == 1) {
                $cicloSelecionado = Funcoes::cicloAtual($mes1, $ano1);
            }
            if ($abaSelecionada == 2) {
                $cicloSelecionado = Funcoes::totalCiclosMes($mes1, $ano1);
            }
        } else {
            $cicloSelecionado = $explodeParamentro[1];
        }

        $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);
        $grupo = $entidade->getGrupo();
        $eventos = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Verificar quantidade de pessoas já cadastradas */
        $contagemDePessoasCadastradas = count($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado));
        $validacaoPessoasCadastradas = 0;
        if ($contagemDePessoasCadastradas > Constantes::$QUANTIDADE_MAXIMA_PESSOAS_NO_LANÇAMENTO) {
            $validacaoPessoasCadastradas = 1;
        }

        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }
        /* Verficar se tem grupo inativado caso seja cadastrado nesse mes */
        $validacaoEntidadeInativa = 0;
        $entidadeInativa = 0;
        if ($validacaoNesseMes == 1) {
            /* Preciso encontrar a pessoa logada e verificar as responsabilidade dele */
            $pessoa = $grupoResponsavel->getPessoa();
            if ($pessoa->verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($grupoResponsavel->getData_criacao())) {
                $grupoResponsavelInativadoNessaData = $pessoa->verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($grupoResponsavel->getData_criacao());
                $grupoInativo = $grupoResponsavelInativadoNessaData->getGrupo();
                $entidadeInativa = $grupoInativo->getEntidadeAtiva();
                if ($entidadeInativa->getTipo_id() === Entidade::SUBEQUIPE ||
                        $entidadeInativa->getTipo_id() === Entidade::EQUIPE ||
                        $entidadeInativa->getTipo_id() === Entidade::IGREJA) {
                    $validacaoEntidadeInativa = 1;
                }
            }
        }

        $view = new ViewModel(
                array(
            Constantes::$ENTIDADE => $entidade,
            Constantes::$ABA_SELECIONADA => $abaSelecionada,
            Constantes::$CICLO_SELECIONADO => (int) $cicloSelecionado,
            Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),
            Constantes::$VALIDACAO => $validacaoPessoasCadastradas,
            Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            Constantes::$VALIDACAO_ENTIDADE_INATIVA => $validacaoEntidadeInativa,
            Constantes::$ENTIDADE_INATIVA => $entidadeInativa,
            Constantes::$REPOSITORIO_ORM => $repositorioORM,
            Constantes::$GRUPO => $grupo,
                )
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO);

        $layoutJS2 = new ViewModel(array(Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),));
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

    /**
     * Traz a tela para lancamento de arregimentação
     * GET /lancamentoArregimetnacaoAction
     */
    public function arregimentacaoAction() {
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $entidade = CircuitoController::getEntidadeLogada($repositorioORM, $sessao);
        $grupo = $entidade->getGrupo();

        $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

        $grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($periodo);

        $view = new ViewModel(
                array(
            Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($grupoEventoNoPeriodo),
            Constantes::$REPOSITORIO_ORM => $repositorioORM,
            Constantes::$GRUPO => $grupo,
            'periodo' => $periodo,
                )
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO);

        $layoutJS2 = new ViewModel(array(Constantes::$QUANTIDADE_EVENTOS_CICLOS => 5,));
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

    /**
     * Muda a frequência de um evento
     * @return Json
     */
    public function mudarFrequenciaAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            /* Helper Controller */
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            try {
                $repositorioORM->iniciarTransacao();

                $post_data = $request->getPost();
                $valor = $post_data['valor'];
                $idPessoa = $post_data['idPessoa'];
                $idEvento = $post_data['idEvento'];
                $diaRealDoEvento = $post_data['diaRealDoEvento'];
                $dateFormatada = DateTime::createFromFormat('Y-m-d', $diaRealDoEvento);
                $idGrupo = $post_data['idGrupo'];
                $periodo = $post_data['periodo'];

                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
                $evento = $repositorioORM->getEventoORM()->encontrarPorId($idEvento);

                /* Verificar se a frequencia ja existe */
                $eventosFiltrados = $pessoa->getEventoFrequenciasFiltradosPorEventoEDia($idEvento, $dateFormatada);
                if ($eventosFiltrados->count() === 1) {
                    /* Frequencia existe */
                    $frequencia = $eventosFiltrados->first();
                    $frequencia->setFrequencia($valor);
                    $repositorioORM->getEventoFrequenciaORM()->persistir($frequencia);
                } else {
                    /* Persitir frequencia */
                    $eventoFrequencia = new EventoFrequencia();
                    $eventoFrequencia->setPessoa($pessoa);
                    $eventoFrequencia->setEvento($evento);
                    $eventoFrequencia->setFrequencia($valor);
                    $eventoFrequencia->setDia($dateFormatada);
                    $repositorioORM->getEventoFrequenciaORM()->persistir($eventoFrequencia);
                }

                $valorParaSomar = 0;
                if ($valor === 'S') {
                    $valorParaSomar = 1;
                } else {
                    $valorParaSomar = -1;
                }

                $grupoPassado = $repositorioORM->getGrupoORM()->encontrarPorId($idGrupo);
                $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupoPassado);
                $eventoTipoCulto = 1;
                $eventoTipoCelula = 2;
                $dimensaoTipoCelula = 1;
                $dimensaoTipoCulto = 2;
                $dimensaoTipoArena = 3;
                $dimensaoTipoDomingo = 4;
                $dimensaoSelecionada = null;

                $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
                $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
                $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
                $fatoCicloSelecionado = $repositorioORM->getFatoCicloORM()->encontrarPorNumeroIdentificadorEDataCriacao(
                        $numeroIdentificador, $dataDoPeriodoFormatada, $repositorioORM);

                if ($fatoCicloSelecionado->getDimensao()) {
                    foreach ($fatoCicloSelecionado->getDimensao() as $dimensao) {
                        switch ($dimensao->getDimensaoTipo()->getId()) {
                            case $dimensaoTipoCelula:
                                $dimensoes[$dimensaoTipoCelula] = $dimensao;
                                break;
                            case $dimensaoTipoCulto:
                                $dimensoes[$dimensaoTipoCulto] = $dimensao;
                                break;
                            case $dimensaoTipoArena:
                                $dimensoes[$dimensaoTipoArena] = $dimensao;
                                break;
                            case $dimensaoTipoDomingo:
                                $dimensoes[$dimensaoTipoDomingo] = $dimensao;
                                break;
                        }
                    }
                }
                $tipoCampo = 0;
                if ($evento->getEventoTipo()->getId() === $eventoTipoCulto) {
                    $diaDeSabado = 7;
                    $diaDeDomingo = 1;
                    switch ($evento->getDia()) {
                        case $diaDeSabado:
                            $dimensaoSelecionada = $dimensoes[$dimensaoTipoArena];
                            $tipoCampo = 3;
                            break;
                        case $diaDeDomingo:
                            $dimensaoSelecionada = $dimensoes[$dimensaoTipoDomingo];
                            $tipoCampo = 4;
                            break;
                        default:
                            $dimensaoSelecionada = $dimensoes[$dimensaoTipoCulto];
                            $tipoCampo = 2;
                            break;
                    };
                }
                if ($evento->getEventoTipo()->getId() === $eventoTipoCelula) {
                    $tipoCampo = 1;
                    $dimensaoSelecionada = $dimensoes[$dimensaoTipoCelula];

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

                    if ($somaFrequencias === 0) {
                        $realizada = 0;
                    } else {
                        $realizada = 1;
                    }

                    $eventoCelulaId = $evento->getEventoCelula()->getId();
                    $fatoCelulas = $fatoCicloSelecionado->getFatoCelula();

                    $fatoCelulaSelecionado = null;
                    foreach ($fatoCelulas as $fc) {
                        if ($fc->getEvento_celula_id() == $eventoCelulaId) {
                            $fatoCelulaSelecionado = $fc;
                        }
                    }
                    $realizadaAntesDeMudar = $fatoCelulaSelecionado->getRealizada();
                    $fatoCelulaSelecionado->setRealizada($realizada);
                    $setarDataEHora = false;
                    $repositorioORM->getFatoCelulaORM()->persistir($fatoCelulaSelecionado, $setarDataEHora);

                    /* Atualizar DW celulas circuito antigo */
//                    $grupoCv = $grupoPassado->getGrupoCv();
//                    IndexController::mudarCelulasRealizadas($grupoCv->getNumero_identificador(), $mes, $ano, $ciclo, $realizada, $realizadaAntesDeMudar);
                }
                $tipoPessoa = 0;
                if ($pessoa->getGrupoPessoaAtivo()) {
                    /* Pessoa volateis */
                    $pessoaTipoVisitante = 1;
                    $pessoaTipoConsolidacao = 2;
                    $pessoaTipoMembro = 3;
                    $valorDoCampo = 0;
                    switch ($pessoa->getGrupoPessoaAtivo()->getGrupoPessoaTipo()->getId()) {
                        case $pessoaTipoVisitante:
                            $valorDoCampo = $dimensaoSelecionada->getVisitante();
                            $dimensaoSelecionada->setVisitante($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = 1;
                            break;
                        case $pessoaTipoConsolidacao:
                            $valorDoCampo = $dimensaoSelecionada->getConsolidacao();
                            $dimensaoSelecionada->setConsolidacao($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = 2;
                            break;
                        case $pessoaTipoMembro:
                            $valorDoCampo = $dimensaoSelecionada->getMembro();
                            $dimensaoSelecionada->setMembro($valorDoCampo + $valorParaSomar);
                            $tipoPessoa = 3;
                            break;
                    }
                } else {
                    $tipoPessoa = 4;
                    $valorDoCampo = $dimensaoSelecionada->getLider();
                    $dimensaoSelecionada->setLider($valorDoCampo + $valorParaSomar);
                }
                $repositorioORM->getDimensaoORM()->persistir($dimensaoSelecionada, false);

                /* Atualizar DW circuito antigo */
//                $grupoCv = $grupoPassado->getGrupoCv();
//                IndexController::mudarFrequencia($grupoCv->getNumero_identificador(), $mes, $ano, $tipoCampo, $tipoPessoa, $ciclo, $valorParaSomar);

                $repositorioORM->fecharTransacao();
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    'idEvento' => $evento->getId())));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $tipos = $repositorioORM->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        /* Formulario */
        $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos);

        $view = new ViewModel(array(
            Constantes::$FORM_CADASTRAR_PESSOA => $formCadastrarPessoa,
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
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($repositorioORM);

                    /* Salvar a pessoa e o grupo pessoa correspondente */
                    $repositorioORM->getPessoaORM()->persistir($pessoa);
                    $grupoPessoaTipo = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorId($post_data[Constantes::$INPUT_TIPO]);

                    $grupoPessoa = new GrupoPessoa();
                    $grupoPessoa->setPessoa($pessoa);
                    $grupoPessoa->setGrupo($grupo);
                    $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
                    $nucleoPerfeito = $validatedData[Constantes::$INPUT_NUCLEO_PERFEITO];
                    if ($nucleoPerfeito != 0) {
                        $grupoPessoa->setNucleo_perfeito($nucleoPerfeito);
                    }

                    $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);

                    /* Pondo valores na sessao */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->mostrarNotificacao = true;
                    $sessao->nomePessoa = $pessoa->getNome();
                    unset($sessao->exclusao);

                    return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
                                Constantes::$ACTION => Constantes::$ROUTE_INDEX,
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
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setNome($nome);
                $repositorioORM->getPessoaORM()->persistir($pessoa);

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
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

            $grupoPessoa = $repositorioORM->getGrupoPessoaORM()->encontrarPorId($sessao->idSessao);
            $grupoPessoa->setDataEHoraDeInativacao();
            $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa, false);

            /* Pondo valores na sessao */
            $sessao->mostrarNotificacao = true;
            $sessao->nomePessoa = $grupoPessoa->getPessoa()->getNome();
            $sessao->exclusao = true;

            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$ROUTE_INDEX,
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        $gruposAbaixo = null;
        if ($grupo->getGrupoPaiFilhoFilhosAtivos()) {
            $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhosAtivos();
        }

        $parametro = $this->params()->fromRoute(Constantes::$ID);
        if (empty($parametro)) {
            $abaSelecionada = 1;
        } else {
            $abaSelecionada = $parametro;
        }
        $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);

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
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            try {
                $repositorioORM->iniciarTransacao();

                $post_data = $request->getPost();
                $tipo = (int) $post_data['tipo'];
                $idGrupo = $post_data['idGrupo'];
                $abaSelecionada = $post_data['abaSelecionada'];
                $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
                $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);

                $grupoAtendimentosFiltrados = array();
                $grupoLancado = $repositorioORM->getGrupoORM()->encontrarPorId($idGrupo);

                if ($tipo === $tipoLancar) {
                    $grupoAtendimento = new GrupoAtendimento();
                    $grupoAtendimento->setDataEHoraDeCriacao();
                    $grupoAtendimento->setGrupo($grupoLancado);
                    $repositorioORM->getGrupoAtendimentoORM()->persistir($grupoAtendimento);
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
                        $repositorioORM->getGrupoAtendimentoORM()->persistir($grupoAtendimentoParaDesativar, false);
                    }
                }

                $numeroAtendimentos = $grupoLancado->totalDeAtendimentos($mesSelecionado, $anoSelecionado);

                $explodeProgresso = explode('_', $this->retornaProgressoUsuarioNoMesEAno($repositorioORM, $mesSelecionado, $anoSelecionado));
                $progresso = number_format($explodeProgresso[0], 2, '.', '');
                $colorBarTotal = LancamentoController::retornaClassBarradeProgressoPeloValor($progresso);

                /* Cadastrar atendimento no circuito antigo */
//                $idAtendimento = IndexController::buscaIdAtendimentoPorLideres(
//                                $mesSelecionado, $anoSelecionado, $grupoLancado->getGrupoCv()->getLider1(), $grupoLancado->getGrupoCv()->getLider2()
//                );
//
//                unset($atendimentoLancado);
//                for ($index = 1; $index <= 5; $index++) {
//                    if ($index <= $numeroAtendimentos) {
//                        $atendimentoLancado[$index] = 'S';
//                    } else {
//                        $atendimentoLancado[$index] = 'N';
//                    }
//                }
//                IndexController::cadastrarAtendimentoPorid($idAtendimento, $atendimentoLancado);

                $repositorioORM->fecharTransacao();
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    'numeroAtendimentos' => $numeroAtendimentos,
                                    'progresso' => $progresso,
                                    'corBarraTotal' => $colorBarTotal,
                                    'totalGruposAtendidos' => $explodeProgresso[1],)));
            } catch (Exception $exc) {
                $repositorioORM->desfazerTransacao();
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
