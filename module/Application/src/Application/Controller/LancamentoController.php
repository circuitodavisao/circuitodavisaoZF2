<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\AtendimentoComentarioForm;
use Application\Form\CadastrarPessoaForm;
use Application\Form\ParceiroDeDeusForm;
use Application\Model\Entity\DimensaoTipo;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoAtendimento;
use Application\Model\Entity\GrupoAtendimentoComentario;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\FatoParceiroDeDeus;
use Application\Model\Entity\FatoFinanceiro;
use Application\Model\Entity\FatoFinanceiroTipo;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\FatoFinanceiroSituacao;
use Application\Model\Entity\PessoaFatoFinanceiroAcesso;
use Application\Model\Entity\FatoFinanceiroAcesso;
use Application\Model\Entity\RegistroAcao;
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

		$possoAlterar = true;
		if($sessao->idSessao > 0){
			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($sessao->idSessao);
			$possoAlterar = false;
		}else{
			$grupo = $entidade->getGrupo();
		}

        $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

        if (!$entidade->verificarSeEstaAtivo()) {
			$periodoVerificar = Funcoes::encontrarPeriodoDadoDataDeInativacao($entidade->getData_inativacaoStringPadraoBanco());
            if ($periodo > $periodoVerificar) {
                $periodo = $periodoVerificar;
            }
        }

		if ($grupo->getGrupoPaiFilhoPaiAtivo()) {
			/* Verificando se posso recuar no periodo */
			$mostrarBotaoPeriodoAnterior = false;
			$mostrarBotaoPeriodoAfrente = false;
			$arrayPeriodo = Funcoes::montaPeriodo($periodo);
			$stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
			$dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);

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
		$validacaoPessoasCadastradas = 1;

        $view = new ViewModel(
                array(
            Constantes::$REPOSITORIO_ORM => $this->getRepositorio(),
            Constantes::$GRUPO => $grupo,
            Constantes::$PERIODO => $periodo,
            Constantes::$VALIDACAO => $validacaoPessoasCadastradas,
            'mostrarBotaoPeriodoAnterior' => $mostrarBotaoPeriodoAnterior,
            'mostrarBotaoPeriodoAfrente' => $mostrarBotaoPeriodoAfrente,
            'possoAlterar' => $possoAlterar,
                )
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO);

        if ($sessao->jaMostreiANotificacao) {
            unset($sessao->mostrarNotificacao);
            unset($sessao->nomePessoa);
            unset($sessao->exclusao);
            unset($sessao->jaMostreiANotificacao);
        }

		self::registrarLog(RegistroAcao::LANCAR_ARREGIMENTACAO, $extra = '');
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

        if ($entidade->getData_inativacaoStringPadraoBanco()) {
            $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(
                    $this->getRepositorio(), $grupo, $entidade->getData_inativacaoStringPadraoBanco());
        } else {
            $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
        }
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

		self::registrarLog(RegistroAcao::ENVIOU_RELATORIO_, $extra = '');
        return new ViewModel(array('periodo'=>$periodo));
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

                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $idEntidadeAtual = $sessao->idEntidadeAtual;
                $entidadeSelecionada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
                if ($entidadeSelecionada->getData_inativacaoStringPadraoBanco()) {
                    $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(
                            $this->getRepositorio(), $entidadeSelecionada->getGrupo(), $entidadeSelecionada->getData_inativacaoStringPadraoBanco());
                } else {
                    $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
                }

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
                    $fatoCelulaSelecionado = null;
                    foreach ($fatoCelulas as $fatoCelula) {
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
        }  /* Helper Controller */

        $tipos = $this->getRepositorio()->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        /* Formulario */
        $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos);
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
                if($post_data['aluno'] === 'true'){
                    $formCadastrarPessoa->setInputFilter($pessoa->getInputFilterPessoaAlunoFrequencia());
                } else {
                    $formCadastrarPessoa->setInputFilter($pessoa->getInputFilterPessoaFrequencia());
                }
                $formCadastrarPessoa->setData($post_data);


                /* validação */

                if ($formCadastrarPessoa->isValid()) {

                    $validatedData = $formCadastrarPessoa->getData();

                    if($idPessoa = $validatedData[Constantes::$ID]){
                      $sessao = new Container(Constantes::$NOME_APLICACAO);
                      $entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
                      $grupo = $entidade->getGrupo();
                      $possoAlterar = false;
                      $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                      $aluno = $pessoa->verificaSeParticipouDoRevisao() || $pessoa->verificarSeEhAluno() == 1 ? 'true':'false';

                      // Validação para os hackers javascript não alterar quem não deve
                      $grupoPessoas = $grupo->getGrupoPessoasNoPeriodo($post_data[Constantes::$PERIODO]);
                      foreach ($grupoPessoas as $grupoPessoa) {
                        if($grupoPessoa->getPessoa()->getId() == $idPessoa){
                          $possoAlterar = true;
                          break;
                        }
                      }
                      if(!$possoAlterar){
                        return  $this->redirect()->toRoute(Constantes::$ROUTE_LOGIN, array(
                                    Constantes::$ACTION => 'index',
                        ));
                      }
                    }


                    if($aluno){
                      if($aluno === 'false'){
                          $pessoa->exchangeArray($formCadastrarPessoa->getData());
                      }
                    } else {
                      $pessoa->exchangeArray($formCadastrarPessoa->getData());
                    }

                    $pessoa->setTelefone($validatedData[Constantes::$INPUT_DDD] . $validatedData[Constantes::$INPUT_TELEFONE]);

                    /* Helper Controller */
                    $periodo = $post_data[Constantes::$PERIODO];

                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($this->getRepositorio());

                    /* Salvar a pessoa e o grupo pessoa correspondente */

                    if($validatedData[Constantes::$ID]){
                        $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora = false);
                    } else {
                        $this->getRepositorio()->getPessoaORM()->persistir($pessoa);
                        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
                        $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];                      
                        $grupoPessoaTipo = $this->getRepositorio()->getGrupoPessoaTipoORM()->encontrarPorId($post_data[Constantes::$INPUT_TIPO]);
                        $grupoPessoa = new GrupoPessoa();
                        $grupoPessoa->setPessoa($pessoa);
                        $grupoPessoa->setGrupo($grupo);
                        $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
                        $grupoPessoa->setDataEHoraDeCriacao($stringComecoDoPeriodo);
                        $nucleoPerfeito = $validatedData[Constantes::$INPUT_NUCLEO_PERFEITO];
                        if ($nucleoPerfeito != 0) {
                            $grupoPessoa->setNucleo_perfeito($nucleoPerfeito);
                        }

                        $this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa, $setDataAtual = false);
                    }

                    /* Pondo valores na sessao */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->mostrarNotificacao = true;
                    $sessao->nomePessoa = $pessoa->getNome();
                    unset($sessao->exclusao);

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

    public function alterarPessoaAction(){
      $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
      $sessao = new Container(Constantes::$NOME_APLICACAO);
      $dadosConcatenados = $sessao['idSessao'];
      $dados = explode('_', $dadosConcatenados);
      unset($sessao->idSessao);
      $idPessoa = $dados[0];
      $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
      $pessoa->setTipo($dados[1]);
      /* Helper Controller */

      $tipos = $this->getRepositorio()->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
      /* Formulario */
      $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos, $pessoa, $aluno = $dados[2]);
      $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

      $view = new ViewModel(array(
          Constantes::$FORM_CADASTRAR_PESSOA => $formCadastrarPessoa,
          Constantes::$PERIODO => $periodo,
          'dados' => $dados,
      ));

      /* Javascript especifico */
      $layoutJS = new ViewModel();
      $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA);
      $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA);

      return $view;
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
     * Alterar telefone de uma pessoa
     * @return Json
     */
    public function alterarTelefoneAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idPessoa = $post_data['idPessoa'];
                $telefone = $post_data['telefone'];

                /* Helper Controller */
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setTelefone($telefone);
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa);

                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'telefone' => $pessoa->getTelefone(),
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
            unset($sessao->idSessao);

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

        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesSelecionado, $anoSelecionado);
        $todosFilhos = array();
        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
            $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
            if ($grupoPaiFilhoFilhos) {
                foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
                    $adicionar = true;
                    if (count($todosFilhos) > 0) {
                        foreach ($todosFilhos as $filho) {
                            if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
                                $adicionar = false;
                                break;
                            }
                        }
                    }
                    if ($adicionar) {
                        $todosFilhos[] = $grupoPaiFilhoFilho;
                    }
                }
            }
        }

        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }

        $view = new ViewModel(array(
            Constantes::$GRUPOS_ABAIXO => $todosFilhos,
            Constantes::$MES => $mesSelecionado,
            Constantes::$ANO => $anoSelecionado,
            Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            Constantes::$ABA_SELECIONADA => $abaSelecionada,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO_ATENDIMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO_ATENDIMENTO);

		self::registrarLog(RegistroAcao::LANCAR_ATENDIMENTO, $extra = '');
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


	public function parceiroDeDeusAction(){

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		if($pessoaFatoFinanceiroAcessoAtivo = $pessoa->getPessoaFatoFinanceiroAcessoAtivo()){
			if($pessoaFatoFinanceiroAcessoAtivo->getFatoFinanceiroAcesso()->getId() === FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS){
				$grupo = $grupo->getGrupoEquipe();
			}
		}

		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
		$formulario = new ParceiroDeDeusForm();
		$fatoFinanceiroTipos = $this->getRepositorio()->getFatoFinanceiroTipoORM()->buscarTodosRegistrosEntidade();
		$fatoFinanceiroTiposParceiroDeDeus = array();
		foreach($fatoFinanceiroTipos as $fatoFinanceiroTipo){
			if($fatoFinanceiroTipo->getId() === FatoFinanceiroTipo::parceiroDeDeusIndividual
				|| $fatoFinanceiroTipo->getId() === FatoFinanceiroTipo::parceiroDeDeusCelula){
					$fatoFinanceiroTiposParceiroDeDeus[] = $fatoFinanceiroTipo;
				}
		}

		$dados = array();
		$dados['formulario'] = $formulario;
		$dados['grupo'] = $grupo;
		$dados['discipulos'] = $grupoPaiFilhoFilhos;
		$dados['fatoFinanceiroTiposParceiroDeDeus'] = $fatoFinanceiroTiposParceiroDeDeus;

		$view = new ViewModel($dados);

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-lancamento-parceiro-de-deus');
		$view->addChild($layoutJS, 'layoutJsLancamentoParceiroDeDeus');

		
		return $view;

	}

	public function parceiroDeDeusFinalizarAction(){

		$request = $this->getRequest();
		if($request->isPost()){
			try{
				$this->getRepositorio()->iniciarTransacao();
				$dadosPost = $request->getPost();
				$idGrupoEPessoa = $dadosPost['idGrupo'];
				$explodeId = explode('_', $idGrupoEPessoa);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($explodeId[0]);
				$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($explodeId[1]);
				if($dadosPost['idFatoFinanceiroTipo'] == FatoFinanceiroTipo::parceiroDeDeusIndividual){
					$individualFiltrado = number_format(str_replace(',','.',$dadosPost['individual']),2,'.','');
					$dataLancamento = $dadosPost['Ano'].'-'.$dadosPost['Mes'].'-'.$dadosPost['Dia'];
					$fatoFinanceiroTipo = $this->getRepositorio()->getFatoFinanceiroTipoORM()->encontrarPorId(FatoFinanceiroTipo::parceiroDeDeusIndividual);
					$fatoFinanceiro = new FatoFinanceiro();
					$fatoFinanceiro->setNumero_identificador($numeroIdentificador);
					$fatoFinanceiro->setPessoa($pessoa);
					$fatoFinanceiro->setFatoFinanceiroTipo($fatoFinanceiroTipo);
					$fatoFinanceiro->setValor($individualFiltrado);
					$fatoFinanceiro->setData($dataLancamento);
					$fatoFinanceiro->setSituacao_id(Situacao::PENDENTE_DE_ACEITACAO);
					$this->getRepositorio()->getFatoFinanceiroORM()->persistir($fatoFinanceiro);

					$fatoFinanceiroSituacao = new FatoFinanceiroSituacao();
					$fatoFinanceiroSituacao->setFatoFinanceiro($fatoFinanceiro);
					$fatoFinanceiroSituacao->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::PENDENTE_DE_ACEITACAO));
					$fatoFinanceiroSituacao->setPessoa($pessoa);
					$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacao);
				}

				if($dadosPost['idFatoFinanceiroTipo'] == FatoFinanceiroTipo::parceiroDeDeusCelula){
					$celulaFiltrado = number_format(str_replace(',','.',$dadosPost['celula']),2,'.','');
					$evento = $this->getRepositorio()->getGrupoEventoORM()->encontrarPorId($dadosPost['idGrupoEvento'])->getEvento();
					$fatoFinanceiroTipo = $this->getRepositorio()->getFatoFinanceiroTipoORM()->encontrarPorId(FatoFinanceiroTipo::parceiroDeDeusCelula);
					$fatoFinanceiro = new FatoFinanceiro();
					$fatoFinanceiro->setNumero_identificador($numeroIdentificador);
					$fatoFinanceiro->setPessoa($pessoa);
					$fatoFinanceiro->setFatoFinanceiroTipo($fatoFinanceiroTipo);
					$fatoFinanceiro->setValor($celulaFiltrado);
					$fatoFinanceiro->setData($dadosPost['data']);
					$fatoFinanceiro->setEvento($evento);
					$fatoFinanceiro->setSituacao_id(Situacao::PENDENTE_DE_ACEITACAO);
					$this->getRepositorio()->getFatoFinanceiroORM()->persistir($fatoFinanceiro);

					$fatoFinanceiroSituacao = new FatoFinanceiroSituacao();
					$fatoFinanceiroSituacao->setFatoFinanceiro($fatoFinanceiro);
					$fatoFinanceiroSituacao->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::PENDENTE_DE_ACEITACAO));
					$fatoFinanceiroSituacao->setPessoa($pessoa);
					$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacao);
				}

				self::registrarLog(RegistroAcao::LANCOU_PARCEIRO_DE_DEUS, $extra = 'Id: '.$fatoFinanceiro->getId());
				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
					Constantes::$ACTION => 'ParceiroDeDeusExtrato',
				));

			}catch(Exception $exception){
				echo $exception->getTraceAsString();
				$this->getRepositorio()->desfazerTransacao();
			}
		}
	}

	public function parceiroDeDeusExtratoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$grupo = $entidade->getGrupo();
		if($pessoaFatoFinanceiroAcessoAtivo = $pessoa->getPessoaFatoFinanceiroAcessoAtivo()){
			if($pessoaFatoFinanceiroAcessoAtivo->getFatoFinanceiroAcesso()->getId() === FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS){
				$grupo = $grupo->getGrupoEquipe();
			}
		}

		$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
		$fatos = $this->getRepositorio()->getFatoFinanceiroORM()->encontrarFatosPorNumeroIdentificador($numeroIdentificador);
		$fatosAtivos = array();
		if($fatos){
			foreach($fatos as $fatoFinanceiro){
				if($fatoFinanceiro->verificarSeEstaAtivo()){
					$idGrupo = substr($fatoFinanceiro->getNumero_identificador(), strlen($fatoFinanceiro->getNumero_identificador())-8);
					$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
					$fatoFinanceiro->setGrupo($grupo);
					$fatosAtivos[] = $fatoFinanceiro;
				}
			}
		}

		self::registrarLog(RegistroAcao::VER_PARCEIRO_DE_DEUS, $extra = '');
		return new ViewModel(array(
			'fatos' => $fatosAtivos,
			'entidade' => $entidade,
			'pessoa' => $pessoa,
		));
	}



	public function parceiroDeDeusExcluirAction(){

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		try{

			$this->getRepositorio()->iniciarTransacao();



			$idSessao = $sessao->idSessao;
			$fatoFinanceiro = $this->getRepositorio()->getFatoFinanceiroORM()->encontrarPorId($idSessao);
			$fatoFinanceiro->setSituacao_id(Situacao::RECUSAO);
			$this->getRepositorio()->getFatoFinanceiroORM()->persistir($fatoFinanceiro, $mudarDataDeCriacao = false);

			$fatoFinanceiroSituacaoAtivo = $fatoFinanceiro->getFatoFinanceiroSituacaoAtiva();
			$fatoFinanceiroSituacaoAtivo->setDataEHoraDeInativacao();
			$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacaoAtivo);

			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
			$fatoFinanceiroSituacao = new FatoFinanceiroSituacao();
			$fatoFinanceiroSituacao->setFatoFinanceiro($fatoFinanceiro);
			$fatoFinanceiroSituacao->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::RECUSAO));
			$fatoFinanceiroSituacao->setPessoa($pessoa);
			$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacao);

			self::registrarLog(RegistroAcao::EXLUIU_PARCEIRO_DE_DEUS, $extra = 'Id: '.$fatoFinanceiro->getId());
			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
				Constantes::$ACTION => 'ParceiroDeDeusExtrato', 
			));

		}catch(Exception $exception){
			echo $exception.getMessage();
			$this->getRepositorio()->desfazerTransacao();
		}

	}



	public function parceiroDeDeusAceitarAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		try{
			$this->getRepositorio()->iniciarTransacao();

			$idSessao = $sessao->idSessao;
			$fatoFinanceiro = $this->getRepositorio()->getFatoFinanceiroORM()->encontrarPorId($idSessao);
			$fatoFinanceiro->setSituacao_id(Situacao::ACEITO_AGENDADO);
			$this->getRepositorio()->getFatoFinanceiroORM()->persistir($fatoFinanceiro, $mudarDataDeCriacao = false);

			$fatoFinanceiroSituacaoAtivo = $fatoFinanceiro->getFatoFinanceiroSituacaoAtiva();
			$fatoFinanceiroSituacaoAtivo->setDataEHoraDeInativacao();
			$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacaoAtivo);

			$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
			$fatoFinanceiroSituacao = new FatoFinanceiroSituacao();
			$fatoFinanceiroSituacao->setFatoFinanceiro($fatoFinanceiro);
			$fatoFinanceiroSituacao->setSituacao($this->getRepositorio()->getSituacaoORM()->encontrarPorId(Situacao::ACEITO_AGENDADO));
			$fatoFinanceiroSituacao->setPessoa($pessoa);
			$this->getRepositorio()->getFatoFinanceiroSituacaoORM()->persistir($fatoFinanceiroSituacao);

			self::registrarLog(RegistroAcao::ACEITOU_PARCEIRO_DE_DEUS, $extra = 'Id: '.$fatoFinanceiro->getId());
			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
				Constantes::$ACTION => 'ParceiroDeDeusExtrato', 
			));
		}catch(Exception $exception){
			echo $exception.getMessage();
			$this->getRepositorio()->desfazerTransacao();
		}
	}



	public function parceiroDeDeusUsuariosAction(){

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$grupo = $entidade->getGrupo();

		$usuarios = $grupo->getGrupoEquipe()->getPessoaFatoFinanceiroAcesso();

		$view = new ViewModel(array(

			'usuarios' => $usuarios

		));

		self::registrarLog(RegistroAcao::VER_SECRETARIOS_DO_PD, $extra = '');

		return $view;

	}



	public function parceiroDeDeusUsuarioAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;

		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$grupo = $entidade->getGrupo();

		$lideres = $this->todosLideresAPartirDoGrupo($grupo, true);

		return new ViewModel(array(

			'lideres' => $lideres,

		));

	}



	public function parceiroDeDeusUsuarioFinalizarAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$request = $this->getRequest();

		if ($request->isPost()) {

			$this->getRepositorio()->iniciarTransacao();

			try {

				$idEntidadeAtual = $sessao->idEntidadeAtual;

				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

				$post_data = $request->getPost();

				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($post_data['idPessoa']);

				$grupo = $entidade->getGrupo();



				$pessoaFatoFinanceiroAcesso = new PessoaFatoFinanceiroAcesso();

				$pessoaFatoFinanceiroAcesso->setPessoa($pessoa);

				$pessoaFatoFinanceiroAcesso->setGrupo($grupo->getGrupoEquipe());

				$pessoaFatoFinanceiroAcesso->setFatoFinanceiroAcesso($this->getRepositorio()->getFatoFinanceiroAcessoORM()->encontrarPorId(FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS));

				$pessoaFatoFinanceiroAcesso->setDataEHoraDeCriacao();

				$this->getRepositorio()->getPessoaFatoFinanceiroAcessoORM()->persistir($pessoaFatoFinanceiroAcesso);

				self::registrarLog(RegistroAcao::CADASTROU_UM_SECRETARIO_DO_PD, $extra = 'Id: '.$pessoaFatoFinanceiroAcesso->getId());

				$this->getRepositorio()->fecharTransacao();

				return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(

					Constantes::$ACTION => 'ParceiroDeDeusUsuarios',

				));

			} catch (Exception $exc) {

				$this->getRepositorio()->desfazerTransacao();

				echo $exc->getTraceAsString();

			}

		}

	}



	public function parceiroDeDeusUsuarioInativarAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$this->getRepositorio()->iniciarTransacao();

		try {

			$pessoaFatoFinanceiroAcesso = $this->getRepositorio()->getPessoaFatoFinanceiroAcessoORM()->encontrarPorId($sessao->idSessao);

			$pessoaFatoFinanceiroAcesso->setDataEHoraDeInativacao();

			$this->getRepositorio()->getPessoaFatoFinanceiroAcessoORM()->persistir($pessoaFatoFinanceiroAcesso, false);



			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(

				Constantes::$ACTION => 'ParceiroDeDeusUsuarios',

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

}
