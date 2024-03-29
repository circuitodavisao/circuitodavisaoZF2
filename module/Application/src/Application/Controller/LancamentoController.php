<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Application\Form\AtendimentoComentarioForm;
use Application\Form\CadastrarPessoaForm;
use Application\Form\ParceiroDeDeusForm;
use Application\Form\FatoDiscipuladoForm;
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
use Application\Model\Entity\FatoDiscipulado;
use Application\Model\Entity\FatoSetenta;
use Application\Model\Entity\FatoMensal;
use Application\Model\Entity\Envio;
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
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '180');
	
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

		$ativo = true;
        if (!$entidade->verificarSeEstaAtivo()) {
			$periodoVerificar = Funcoes::encontrarPeriodoDadoDataDeInativacao($entidade->getData_inativacaoStringPadraoBanco());
            if ($periodo > $periodoVerificar) {
                $periodo = $periodoVerificar;
            }
			$ativo = false;
		}

        if ($periodo < 0 && $entidade->verificarSeEstaAtivo()) {
            $mostrarBotaoPeriodoAfrente = true;
        }

		//$grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($periodo, false, $this->getRepositorio());
        $grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($periodo, $this->getRepositorio());       
		$validacaoPessoasCadastradas = 1;
        $view = new ViewModel(
                array(
            Constantes::$REPOSITORIO_ORM => $this->getRepositorio(),
            Constantes::$GRUPO => $grupo,
            Constantes::$PERIODO => $periodo,
            Constantes::$VALIDACAO => $validacaoPessoasCadastradas,
            'mostrarBotaoPeriodoAnterior' => true,
            'mostrarBotaoPeriodoAfrente' => $mostrarBotaoPeriodoAfrente,
            'possoAlterar' => $possoAlterar,
			'grupoEventos' => $grupoEventoNoPeriodo,
			'grupoPessoas' => $grupoPessoasNoPeriodo,
			'ativo' => $ativo,
			'grupo' => $grupo,
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

	public function buscarGrupoEventosAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		$dados = array();
		if ($request->isPost()) {
			try {
				$body = $request->getContent();
				$json = Json::decode($body);
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($json->token);
				$periodo = $json->periodo;
				$idPessoa = $json->idPessoa;
				$grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($periodo, false, $this->getRepositorio());
				$html = '';
				foreach ($grupoEventoNoPeriodo as $grupoEvento) {
					$data = array();
					$idEvento = $grupoEvento->getEvento()->getId();
					$data['idEvento'] = $idEvento;
					$diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
					$data['diaDaSemana'] = Funcoes::diaDaSemanaPorDia($diaDaSemanaDoEvento, 2);
					if ($diaDaSemanaDoEvento === 1) {
						$diaDaSemanaDoEvento = 7; // domingo
					} else {
						$diaDaSemanaDoEvento--;
					}
					
					$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $periodo);
					$data['diaReal'] = $diaRealDoEvento;

					$horaEvento = $grupoEvento->getEvento()->getHoraFormatoHoraMinuto();
					$data['hora'] = $horaEvento;

					$temFrequencia = false;
					if($resposta = $this->getRepositorio()->getEventoFrequenciaORM()->verificarFrequencia($idEvento, $diaRealDoEvento, $idPessoa)){
						if($resposta['frequencia'] === 'S'){
							$temFrequencia = true;
						}
					}
					$data['temFrequencia'] = $temFrequencia;

					$eventoNome = Funcoes::nomeDoEvento($grupoEvento->getEvento()->getTipo_id());
					if($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica){
						$eventoNome = 'Cél. Beta';
					}
					$data['tipo'] = $eventoNome;

					$resultado['grupoEventos'][] = $data;
				}
				$resultado['idGrupo'] = $grupo->getId();
				$resultado['periodo'] = $periodo;
				$dados['resultado'] = $resultado;
			} catch (Exception $exc) {
				$dados['message'] = $exc->getMessage();
			}
		}
		$response->setContent(Json::encode($dados));
		return $response;
	}

	public function enviarAction() {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '180');
	
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$entidade = CircuitoController::getEntidadeLogada($this->getRepositorio(), $sessao);
		$grupo = $entidade->getGrupo();
		$periodo = $sessao->idSessao;

		$mesAtual = date('m');
		$anoAtual = date('Y');
		if(intVal($mesAtual) === 1){
			$mesAnterior = 12;
			$anoAnterior = $anoAtual -1;
		}else{
			$mesAnterior = $mesAtual - 1;
			$anoAnterior = $anoAtual;
		}

		$arrayPeriodoDoMesAtual = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesAtual, $anoAtual);

		if ($entidade->getData_inativacaoStringPadraoBanco()) {
			$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador(
				$this->getRepositorio(), $grupo, $entidade->getData_inativacaoStringPadraoBanco());
		} else {
			$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
		}

		$this->getRepositorio()->iniciarTransacao();

		$fatosMensal[1] = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mesAtual, $anoAtual);
		$numeroDeCelulas = 0;
		$somaVisitantes = 0;
		$somaPorPeriodoETipo = array();
		$celulasRealizadasNoPrimeiroPeriodo = 0;
		for($indiceDePeriodos = $arrayPeriodoDoMesAtual[0]; $indiceDePeriodos <= 0; $indiceDePeriodos++){
			$grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($indiceDePeriodos, false, $this->getRepositorio());

			/* verificando visitante no mes */
			foreach ($grupoEventoNoPeriodo as $grupoEvento) {
				if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula
					|| $grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica) {
						if ($grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($indiceDePeriodos, $this->getRepositorio())) {
							$diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
							if ($diaDaSemanaDoEvento === 1) {
								$diaDaSemanaDoEvento = 7; // domingo
							} else {
								$diaDaSemanaDoEvento--;
							}
							$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $indiceDePeriodos);
							foreach ($grupoPessoasNoPeriodo as $grupoPessoa) {
								if ($grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
									$tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();

									if($tipoPessoa === GrupoPessoaTipo::VISITANTE){
										$somaVisitantes ++;
									}
								}
							}
						}
					}
			}

			if($indiceDePeriodos === -1 || $indiceDePeriodos === 0 || $indiceDePeriodos === -2){
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
					if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula
						|| $grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica) {
							$tipoCampo = LancamentoController::TIPO_CAMPO_CELULA;
							if($indiceDePeriodos === 0){
								$numeroDeCelulas++;
							}
						}

					$diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
					if ($diaDaSemanaDoEvento === 1) {
						$diaDaSemanaDoEvento = 7; // domingo
					} else {
						$diaDaSemanaDoEvento--;
					}
					$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $indiceDePeriodos);
					$eventoFrequencia = $grupoEvento->getEvento()->getEventoFrequencia();
					if ($eventoFrequencia) {
						/* Lideres */
						if ($grupoResponsabilidades = $grupo->getResponsabilidadesAtivas()) {
							foreach ($grupoResponsabilidades as $grupoResponsavel) {
								if ($grupoResponsavel->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
									$tipoPessoa = LancamentoController::TIPO_PESSOA_LIDER;
									$relatorio[$tipoCampo][$tipoPessoa] ++;

									if ($grupoEvento->getEvento()->verificaSeECelula() || $grupoEvento->getEvento()->verificaSeECelulaEstrategica()) {
										$eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
										$relatorio['celula'][$eventoCelulaId] ++;
									}
								}
							}
						}
						/* Pessoas Volateis */
						if ($grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($indiceDePeriodos, $this->getRepositorio())) {
							foreach ($grupoPessoasNoPeriodo as $grupoPessoa) {
								if ($grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
									$tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();
									$relatorio[$tipoCampo][$tipoPessoa] ++;

									if (($grupoEvento->getEvento()->verificaSeECelula() || $grupoEvento->getEvento()->verificaSeECelulaEstrategica())) {
										$eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
										$relatorio['celula'][$eventoCelulaId] ++;
									}
								}
							}
						}
					}
				}

				$resultadoPeriodo = Funcoes::montaPeriodo($indiceDePeriodos);
				$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
				$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

				for($indiceDimensao = 1; $indiceDimensao <= 4; $indiceDimensao++){
					$lider = $relatorio[$indiceDimensao][LancamentoController::TIPO_PESSOA_LIDER];
					$membro = $relatorio[$indiceDimensao][GrupoPessoaTipo::MEMBRO];
					$consolidacao = $relatorio[$indiceDimensao][GrupoPessoaTipo::CONSOLIDACAO];
					$visitante = $relatorio[$indiceDimensao][GrupoPessoaTipo::VISITANTE];

					$contadorCelulasRealizadas = 0;
					if ($indiceDimensao === DimensaoTipo::CELULA) {
						foreach($relatorio['celula'] as $k => $v){
							if($relatorio['celula'][$k] > 0){
								$contadorCelulasRealizadas++;
							}
						}
					}

					$temNoMes[1] = false;
					$contadorDePeriodo[1] = 1;
					$soma = $lider + $membro + $consolidacao + $visitante;

					if($indiceDePeriodos >= $arrayPeriodoDoMesAtual[0]){
						$periodoAtual = 0;
						for($indiceAtual = $arrayPeriodoDoMesAtual[0]; $indiceAtual <= $periodoAtual; $indiceAtual++){
							if($indiceDePeriodos == $indiceAtual){
								$temNoMes[1] = true;
								break;
							}
							$contadorDePeriodo[1]++;
						}
					}

					$indiceFatoMensal = 1;
					if($temNoMes[$indiceFatoMensal]){
						$somaPorPeriodoETipo[$contadorDePeriodo[$indiceFatoMensal]][$indiceDimensao] = $soma;
						if ($indiceDimensao === DimensaoTipo::CELULA) {
							if($contadorDePeriodo[$indiceFatoMensal] === 1){
								$celulasRealizadasNoPrimeiroPeriodo = $contadorCelulasRealizadas;
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 1){
								$fatosMensal[$indiceFatoMensal]->setC1($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada1($contadorCelulasRealizadas);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 2){
								$fatosMensal[$indiceFatoMensal]->setC2($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada2($contadorCelulasRealizadas);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 3){
								$fatosMensal[$indiceFatoMensal]->setC3($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada3($contadorCelulasRealizadas);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 4){
								$fatosMensal[$indiceFatoMensal]->setC4($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada4($contadorCelulasRealizadas);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 5){
								$fatosMensal[$indiceFatoMensal]->setC5($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada5($contadorCelulasRealizadas);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 6){
								$fatosMensal[$indiceFatoMensal]->setC6($soma);
								$fatosMensal[$indiceFatoMensal]->setRealizada6($contadorCelulasRealizadas);
							}
						}
						if ($indiceDimensao === DimensaoTipo::CULTO) {
							if($contadorDePeriodo[$indiceFatoMensal] === 1){
								$fatosMensal[$indiceFatoMensal]->setCu1($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 2){
								$fatosMensal[$indiceFatoMensal]->setCu2($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 3){
								$fatosMensal[$indiceFatoMensal]->setCu3($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 4){
								$fatosMensal[$indiceFatoMensal]->setCu4($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 5){
								$fatosMensal[$indiceFatoMensal]->setCu5($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 6){
								$fatosMensal[$indiceFatoMensal]->setCu6($soma);
							}
						}
						if ($indiceDimensao === DimensaoTipo::ARENA) {
							if($contadorDePeriodo[$indiceFatoMensal] === 1){
								$fatosMensal[$indiceFatoMensal]->setA1($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 2){
								$fatosMensal[$indiceFatoMensal]->setA2($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 3){
								$fatosMensal[$indiceFatoMensal]->setA3($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 4){
								$fatosMensal[$indiceFatoMensal]->setA4($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 5){
								$fatosMensal[$indiceFatoMensal]->setA5($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 6){
								$fatosMensal[$indiceFatoMensal]->setA6($soma);
							}
						}
						if ($indiceDimensao === DimensaoTipo::DOMINGO) {
							if($contadorDePeriodo[$indiceFatoMensal] === 1){
								$fatosMensal[$indiceFatoMensal]->setD1($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 2){
								$fatosMensal[$indiceFatoMensal]->setD2($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 3){
								$fatosMensal[$indiceFatoMensal]->setD3($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 4){
								$fatosMensal[$indiceFatoMensal]->setD4($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 5){
								$fatosMensal[$indiceFatoMensal]->setD5($soma);
							}
							if($contadorDePeriodo[$indiceFatoMensal] === 6){
								$fatosMensal[$indiceFatoMensal]->setD6($soma);
							}
						}
					}
				}
			}
		}

		/* Fato Mensal */
		$infoEntidade = $entidade->infoEntidade();
		$nomeLideres = $grupo->getNomeLideresAtivos();

		$fatosMensal[1]->setEntidade($infoEntidade);
		$fatosMensal[1]->setLideres($nomeLideres);
		$fatosMensal[1]->setNumero_identificador($numeroIdentificador);
		$fatosMensal[1]->setMes($mesAtual);
		$fatosMensal[1]->setAno($anoAtual);

		$somaCelula = 
			$fatosMensal[1]->getC1() +
			$fatosMensal[1]->getC2() +
			$fatosMensal[1]->getC3() +
			$fatosMensal[1]->getC4() +
			$fatosMensal[1]->getC5() +
			$fatosMensal[1]->getC6() ;
		$fatosMensal[1]->setSomacelula($somaCelula);
		$fatosMensal[1]->setMultiplicadormetasetenta($numeroDeCelulas);
		$fatosMensal[1]->setSomavisitantes($somaVisitantes);

		$this->getRepositorio()->getFatoMensalORM()->persistir($fatosMensal[1], false);

		$periodoParaTestar = Funcoes::montaPeriodo(0);
		$periodoEnviado = Funcoes::montaPeriodo($periodo);
		if(
			($contadorDePeriodo[1] === 1 
			|| $contadorDePeriodo[1] === 2)
			&& intval($periodoParaTestar[1]) !== 1
		){
			$mesAtual = date('m');
			$anoAtual = date('Y');
			if(intVal($mesAtual) === 1){
				$mesAnterior = 12;
				$anoAnterior = $anoAtual -1;
			}else{
				$mesAnterior = $mesAtual - 1;
				$anoAnterior = $anoAtual;
			}

			if($fatoMensalAnterior = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mesAnterior, $anoAnterior)){
				$arrayPeriodoDoMesAtual = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesAnterior, $anoAnterior);
				$contadorDePeriodos = 0;
				for($indiceDePeriodos = $arrayPeriodoDoMesAtual[0]; $indiceDePeriodos <= $arrayPeriodoDoMesAtual[1]; $indiceDePeriodos++){
					$contadorDePeriodos++;
				}

				for($indiceDimensao = 1; $indiceDimensao <= 4; $indiceDimensao++){
					if ($indiceDimensao === DimensaoTipo::CELULA) {
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setC5($somaPorPeriodoETipo[1][$indiceDimensao]);
							$fatoMensalAnterior->setRealizada5($celulasRealizadasNoPrimeiroPeriodo);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setC6($somaPorPeriodoETipo[1][$indiceDimensao]);
							$fatoMensalAnterior->setRealizada6($celulasRealizadasNoPrimeiroPeriodo);
						}
					}
					if ($indiceDimensao === DimensaoTipo::CULTO) {
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setCu5($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setCu6($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
					}
					if ($indiceDimensao === DimensaoTipo::ARENA) {
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setA5($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setA6($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
					}
					if ($indiceDimensao === DimensaoTipo::DOMINGO){
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setD5($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setD6($somaPorPeriodoETipo[1][$indiceDimensao]);
						}
					}
				}
				$somaCelula = 
					$fatoMensalAnterior->getC1() +
					$fatoMensalAnterior->getC2() +
					$fatoMensalAnterior->getC3() +
					$fatoMensalAnterior->getC4() +
					$fatoMensalAnterior->getC5() +
					$fatoMensalAnterior->getC6() ;
				$fatoMensalAnterior->setSomacelula($somaCelula);
				$fatoMensalAnterior->setMultiplicadormetasetenta($numeroDeCelulas);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensalAnterior, false);
			}
		}

		$periodoEnviado = Funcoes::montaPeriodo($periodo);
		if(
			($contadorDePeriodo[1] === 1 
			|| $contadorDePeriodo[1] === 2)
			&& 
			(
				intval($periodoParaTestar[1]) === 1
				|| (intVal($periodoEnviado[2]) !== intVal(date('m')) && intVal($periodoEnviado[5]) !== intVal(date('m')))
			)
		){
			$mesAtual = date('m');
			$anoAtual = date('Y');
			if(intVal($mesAtual) === 1){
				$mesAnterior = 12;
				$anoAnterior = $anoAtual -1;
			}else{
				$mesAnterior = $mesAtual - 1;
				$anoAnterior = $anoAtual;
			}

			if($fatoMensalAnterior = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mesAnterior, $anoAnterior)){
				$arrayPeriodoDoMesAtual = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesAnterior, $anoAnterior);
				$contadorDePeriodos = 0;
				for($indiceDePeriodos = $arrayPeriodoDoMesAtual[0]; $indiceDePeriodos <= $arrayPeriodoDoMesAtual[1]; $indiceDePeriodos++){
					$contadorDePeriodos++;
				}

				$numeroDeCelulas = 0;
				$somaVisitantes = 0;
				$somaPorPeriodoETipo = array();
				$indiceDePeriodos = $periodo;

				for($indiceMes = $arrayPeriodoDoMesAtual[0]; $indiceMes <= $arrayPeriodoDoMesAtual[1]; $indiceMes++){
					$grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($indiceMes, false, $this->getRepositorio());
					/* verificando visitante no mes */
					foreach ($grupoEventoNoPeriodo as $grupoEvento) {
						if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula
							|| $grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica) {
							if ($grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($indiceMes, $this->getRepositorio())) {
								$diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
								if ($diaDaSemanaDoEvento === 1) {
									$diaDaSemanaDoEvento = 7; // domingo
								} else {
									$diaDaSemanaDoEvento--;
								}
								$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $indiceMes);
								foreach ($grupoPessoasNoPeriodo as $grupoPessoa) {
									if ($grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
										$tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();

										if($tipoPessoa === GrupoPessoaTipo::VISITANTE){
											$somaVisitantes ++;
										}
									}
								}
							}
						}
					}
				}

				$relatorio = array();
				$grupoEventoNoPeriodo = $grupo->getGrupoEventoNoPeriodo($indiceDePeriodos, false, $this->getRepositorio());
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
					if ($grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula
						|| $grupoEvento->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica) {
						$tipoCampo = LancamentoController::TIPO_CAMPO_CELULA;
						if($indiceDePeriodos === $contadorDePeriodos){
							$numeroDeCelulas++;
						}
					}

					$diaDaSemanaDoEvento = (int) $grupoEvento->getEvento()->getDia();
					if ($diaDaSemanaDoEvento === 1) {
						$diaDaSemanaDoEvento = 7; // domingo
					} else {
						$diaDaSemanaDoEvento--;
					}
					$diaRealDoEvento = ListagemDePessoasComEventos::diaRealDoEvento($diaDaSemanaDoEvento, $indiceDePeriodos);
					$eventoFrequencia = $grupoEvento->getEvento()->getEventoFrequencia();
					if ($eventoFrequencia) {
						/* Lideres */
						if ($grupoResponsabilidades = $grupo->getResponsabilidadesAtivas()) {
							foreach ($grupoResponsabilidades as $grupoResponsavel) {
								if ($grupoResponsavel->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
									$tipoPessoa = LancamentoController::TIPO_PESSOA_LIDER;
									$relatorio[$tipoCampo][$tipoPessoa] ++;

									if ($grupoEvento->verificarSeEstaAtivo() && ($grupoEvento->getEvento()->verificaSeECelula() || $grupoEvento->getEvento()->verificaSeECelulaEstrategica())) {
										$eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
										$relatorio['celula'][$eventoCelulaId] ++;
									}
								}
							}
						}
						/* Pessoas Volateis */
						if ($grupoPessoasNoPeriodo = $grupo->getGrupoPessoasNoPeriodo($indiceDePeriodos, $this->getRepositorio())) {
							foreach ($grupoPessoasNoPeriodo as $grupoPessoa) {
								if ($grupoPessoa->getPessoa()->getEventoFrequenciaFiltradoPorEventoEDia($grupoEvento->getEvento()->getId(), $diaRealDoEvento, $this->getRepositorio())) {
									$tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();
									$relatorio[$tipoCampo][$tipoPessoa] ++;

									if (($grupoEvento->getEvento()->verificaSeECelula() || $grupoEvento->getEvento()->verificaSeECelulaEstrategica())) {
										$eventoCelulaId = $grupoEvento->getEvento()->getEventoCelula()->getId();
										$relatorio['celula'][$eventoCelulaId] ++;
									}
								}
							}
						}
					}
				}

				$resultadoPeriodo = Funcoes::montaPeriodo($indiceDePeriodos);
				$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
				$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

				for($indiceDimensao = 1; $indiceDimensao <= 4; $indiceDimensao++){
					$lider = $relatorio[$indiceDimensao][LancamentoController::TIPO_PESSOA_LIDER];
					$membro = $relatorio[$indiceDimensao][GrupoPessoaTipo::MEMBRO];
					$consolidacao = $relatorio[$indiceDimensao][GrupoPessoaTipo::CONSOLIDACAO];
					$visitante = $relatorio[$indiceDimensao][GrupoPessoaTipo::VISITANTE];

					$contadorCelulasRealizadas = 0;
					if ($indiceDimensao === DimensaoTipo::CELULA) {
						foreach($relatorio['celula'] as $k => $v){
							if($relatorio['celula'][$k] > 0){
								$contadorCelulasRealizadas++;
							}
						}
					}

					$soma = $lider + $membro + $consolidacao + $visitante;

					if ($indiceDimensao === DimensaoTipo::CELULA) {
						if($contadorDePeriodos === 4){
							$fatoMensalAnterior->setC4($soma);
							$fatoMensalAnterior->setRealizada4($contadorCelulasRealizadas);
						}
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setC5($soma);
							$fatoMensalAnterior->setRealizada5($contadorCelulasRealizadas);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setC6($soma);
							$fatoMensalAnterior->setRealizada6($contadorCelulasRealizadas);
						}
					}
					if ($indiceDimensao === DimensaoTipo::CULTO) {
						if($contadorDePeriodos === 4){
							$fatoMensalAnterior->setCu4($soma);
						}
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setCu5($soma);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setCu6($soma);
						}
					}
					if ($indiceDimensao === DimensaoTipo::ARENA) {
						if($contadorDePeriodos === 4){
							$fatoMensalAnterior->setA4($soma);
						}
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setA5($soma);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setA6($soma);
						}
					}
					if ($indiceDimensao === DimensaoTipo::DOMINGO) {
						if($contadorDePeriodos === 4){
							$fatoMensalAnterior->setD4($soma);
						}
						if($contadorDePeriodos === 5){
							$fatoMensalAnterior->setD5($soma);
						}
						if($contadorDePeriodos === 6){
							$fatoMensalAnterior->setD6($soma);
						}
					}
				}

				$somaCelula = 
					$fatoMensalAnterior->getC1() +
					$fatoMensalAnterior->getC2() +
					$fatoMensalAnterior->getC3() +
					$fatoMensalAnterior->getC4() +
					$fatoMensalAnterior->getC5() +
					$fatoMensalAnterior->getC6() ;
				$fatoMensalAnterior->setSomacelula($somaCelula);
				$fatoMensalAnterior->setMultiplicadormetasetenta($numeroDeCelulas);
				$fatoMensalAnterior->setSomavisitantes($somaVisitantes);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensalAnterior, false);
			}
		}

		$envio = new Envio();
		$envio->setGrupo_id($grupo->getId());
		$envio->setStatus(1);
		$envio->setDataEHoraDeCriacao();
		$this->getRepositorio()->getEnvioORM()->persistir($envio);

		$this->getRepositorio()->fecharTransacao();

		self::registrarLog(RegistroAcao::ENVIOU_RELATORIO, $extra = '');
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

				/* Frequencia */

				$eventosFiltrado = $pessoa->getEventoFrequenciaFiltradoPorEventoEDia($idEvento, $diaRealDoEvento);
				if ($eventosFiltrado) {
					$frequencia = $eventosFiltrado;
					$frequencia->setFrequencia($valor);
				} else {
					$frequencia = new EventoFrequencia();
					$frequencia->setPessoa($pessoa);
					$frequencia->setEvento($evento);
					$frequencia->setFrequencia($valor);
					$frequencia->setDia($dateFormatada);
				}
				$this->getRepositorio()->getEventoFrequenciaORM()->persistir($frequencia);
				/* fim frequencia */

				$tipoCampo = 0;
				if ($evento->getEventoTipo()->getId() === EventoTipo::tipoCulto) {
					$diaDeSabado = 7;
					$diaDeDomingo = 1;
					switch ($evento->getDia()) {
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

					self::registrarLog(RegistroAcao::LANCOU_CULTO, $extra = Funcoes::mudarPadraoData($diaRealDoEvento,1) .' - '.$pessoa->getId().' - '.$pessoa->getNome());
				}
				if ($evento->getEventoTipo()->getId() === EventoTipo::tipoCelula
					|| $evento->getEventoTipo()->getId() === EventoTipo::tipoCelulaEstrategica) {
					$tipoCampo = LancamentoController::TIPO_CAMPO_CELULA;
					self::registrarLog(RegistroAcao::LANCOU_CELULA, $extra = Funcoes::mudarPadraoData($diaRealDoEvento,1).' - '.$pessoa->getId().' - '.$pessoa->getNome());
				}

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

				/* descobrir os periodos e o meses */
				$periodoParaUsar[1] = 1;
				$periodoParaUsar[2] = null;
				$mesParaVerificar[1] = null;
				$mesParaVerificar[2] = null;
				$anoParaVerificar[1] = null;
				$anoParaVerificar[2] = null;
				$resultadoPeriodo = Funcoes::montaPeriodo($periodo);

				$periodoParaUsar[1] = 1;
				$mesParaVerificar[1] = $resultadoPeriodo[2];
				$anoParaVerificar[1] = $resultadoPeriodo[3];
				if($resultadoPeriodo[2] !== $resultadoPeriodo[5]){
					$periodoParaUsar[2] = 1;
					$mesParaVerificar[2] = $resultadoPeriodo[5];
					$anoParaVerificar[2] = $resultadoPeriodo[6];
				}

				$periodos = array();
				$periodos[1] = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesParaVerificar[1], $anoParaVerificar[1]);
				$contador[1] = 0;
				if(intVal($periodo) === 0){
					$periodos[1][1] = 0;
				}
				for($indiceDePeriodos = $periodos[1][0]; $indiceDePeriodos <= $periodos[1][1]; $indiceDePeriodos++){
					$contador[1]++;
					if(intVal($indiceDePeriodos) === intVal($periodo)){
						break;
					}
				}
				$fatosMensal[1] = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mesParaVerificar[1], $anoParaVerificar[1]);
				$contadorDePeriodo[1] = $contador[1];
				$fimLancamento = 1;

				if($periodoParaUsar[2] !== null){
					$fatosMensal[2] = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mesParaVerificar[2], $anoParaVerificar[2]);
					$contadorDePeriodo[2] = 1;
					$fimLancamento = 2;
				}
				/* fim */

				$mudarVisitante = false;
				if($grupoPessoa = $pessoa->getGrupoPessoaAtivo()){
					$tipoPessoa = $grupoPessoa->getGrupoPessoaTipo()->getId();
					if($tipoPessoa === GrupoPessoaTipo::VISITANTE){
						self::registrarLog(RegistroAcao::LANCOU_VISITANTE, $extra = $pessoa->getId().' - '.$pessoa->getNome());
						$mudarVisitante = true;
					}
				}

				for($indiceFatoMensal = 1; $indiceFatoMensal <= $fimLancamento; $indiceFatoMensal++){
					if($tipoCampo === LancamentoController::TIPO_CAMPO_CELULA){
						if($mudarVisitante){
							$somaVisitante = $fatosMensal[$indiceFatoMensal]->getSomaVisitantes() + $valorParaSomar;
							if($somaVisitante < 0){
								$somaVisitante = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setSomaVisitantes($somaVisitante);
						}
						$somaCelula = $fatosMensal[$indiceFatoMensal]->getSomaCelula() + $valorParaSomar;
						if($somaCelula < 0){
							$somaCelula = 0;
						}
						$fatosMensal[$indiceFatoMensal]->setSomaCelula($somaCelula);

						if($contadorDePeriodo[$indiceFatoMensal] === 1){
							$soma = $fatosMensal[$indiceFatoMensal]->getC1() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC1($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV1() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV1($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE1(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC1() >= ($fatosMensal[$indiceFatoMensal]->getCq1() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV1() >= ($fatosMensal[$indiceFatoMensal]->getCq1() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE1($fatosMensal[$indiceFatoMensal]->getCq1());
							}
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 2){
							$soma = $fatosMensal[$indiceFatoMensal]->getC2() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC2($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV2() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV2($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE2(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC2() >= ($fatosMensal[$indiceFatoMensal]->getCq2() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV2() >= ($fatosMensal[$indiceFatoMensal]->getCq2() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE2($fatosMensal[$indiceFatoMensal]->getCq2());
							}
	
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 3){
							$soma = $fatosMensal[$indiceFatoMensal]->getC3() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC3($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV3() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV3($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE3(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC3() >= ($fatosMensal[$indiceFatoMensal]->getCq3() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV3() >= ($fatosMensal[$indiceFatoMensal]->getCq3() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE3($fatosMensal[$indiceFatoMensal]->getCq3());
							}
	
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 4){
							$soma = $fatosMensal[$indiceFatoMensal]->getC4() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC4($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV4() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV4($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE4(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC4() >= ($fatosMensal[$indiceFatoMensal]->getCq4() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV4() >= ($fatosMensal[$indiceFatoMensal]->getCq4() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE4($fatosMensal[$indiceFatoMensal]->getCq4());
							}
	
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 5){
							$soma = $fatosMensal[$indiceFatoMensal]->getC5() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC5($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV5() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV5($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE5(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC5() >= ($fatosMensal[$indiceFatoMensal]->getCq5() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV5() >= ($fatosMensal[$indiceFatoMensal]->getCq5() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE5($fatosMensal[$indiceFatoMensal]->getCq5());
							}
	
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 6){
							$soma = $fatosMensal[$indiceFatoMensal]->getC6() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setC6($soma);
							if($mudarVisitante){
								$somaVisitante = $fatosMensal[$indiceFatoMensal]->getV6() + $valorParaSomar;
								if($somaVisitante < 0){
									$somaVisitante = 0;
								}
								$fatosMensal[$indiceFatoMensal]->setV6($somaVisitante);
							}
							$fatosMensal[$indiceFatoMensal]->setE6(0);
							if(
								$fatosMensal[$indiceFatoMensal]->getC6() >= ($fatosMensal[$indiceFatoMensal]->getCq6() * 7) &&
								$fatosMensal[$indiceFatoMensal]->getV6() >= ($fatosMensal[$indiceFatoMensal]->getCq6() * 1)
							){
								$fatosMensal[$indiceFatoMensal]->setE6($fatosMensal[$indiceFatoMensal]->getCq6());
							}
						}
					}
					if($tipoCampo === LancamentoController::TIPO_CAMPO_CULTO){
						if($contadorDePeriodo[$indiceFatoMensal] === 1){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu1() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu1($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 2){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu2() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu2($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 3){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu3() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu3($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 4){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu4() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu4($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 5){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu5() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu5($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 6){
							$soma = $fatosMensal[$indiceFatoMensal]->getCu6() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setCu6($soma);
						}
					}
					if($tipoCampo === LancamentoController::TIPO_CAMPO_ARENA){
						if($contadorDePeriodo[$indiceFatoMensal] === 1){
							$soma = $fatosMensal[$indiceFatoMensal]->getA1() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA1($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 2){
							$soma = $fatosMensal[$indiceFatoMensal]->getA2() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA2($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 3){
							$soma = $fatosMensal[$indiceFatoMensal]->getA3() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA3($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 4){
							$soma = $fatosMensal[$indiceFatoMensal]->getA4() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA4($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 5){
							$soma = $fatosMensal[$indiceFatoMensal]->getA5() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA5($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 6){
							$soma = $fatosMensal[$indiceFatoMensal]->getA6() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA6($soma);
						}
					}
					if($tipoCampo === LancamentoController::TIPO_CAMPO_DOMINGO){
						if($contadorDePeriodo[$indiceFatoMensal] === 1){
							$soma = $fatosMensal[$indiceFatoMensal]->getD1() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setD1($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 2){
							$soma = $fatosMensal[$indiceFatoMensal]->getD2() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setA2($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 3){
							$soma = $fatosMensal[$indiceFatoMensal]->getD3() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setD3($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 4){
							$soma = $fatosMensal[$indiceFatoMensal]->getD4() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setD4($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 5){
							$soma = $fatosMensal[$indiceFatoMensal]->getD5() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setD5($soma);
						}
						if($contadorDePeriodo[$indiceFatoMensal] === 6){
							$soma = $fatosMensal[$indiceFatoMensal]->getD6() + $valorParaSomar;
							if($soma < 0){
								$soma = 0;
							}
							$fatosMensal[$indiceFatoMensal]->setD6($soma);
						}
					}

					$this->getRepositorio()->getFatoMensalORM()->persistir($fatosMensal[$indiceFatoMensal], false);
				}
				$resposta = true;
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
                if($post_data['aluno']){                    
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
                      $grupoPessoas = $grupo->getGrupoPessoasNoPeriodo($post_data[Constantes::$PERIODO], $this->getRepositorio());
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

						$tipoRegistro = RegistroAcao::CADASTROU_VISITANTE;
						if($grupoPessoaTipo->getId() === GrupoPessoaTipo::CONSOLIDACAO){
							$tipoRegistro = RegistroAcao::CADASTROU_CONSOLIDACAO;
						}
						if($grupoPessoaTipo->getId() === GrupoPessoaTipo::MEMBRO){
							$tipoRegistro = RegistroAcao::CADASTROU_MEMBRO;
						}
						self::registrarLog($tipoRegistro, $extra = 'Id: '.$pessoa->getId().' Nome: '.$pessoa->getNome());

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
      $dadosConcatenados = $sessao->idSessao;
      $dados = explode('_', $dadosConcatenados);
      unset($sessao->idSessao);
      $idPessoa = $dados[0];      
      $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
      $pessoa->setTipo($dados[1]);
      /* Helper Controller */

      $tipos = $this->getRepositorio()->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
      /* Formulario */
	  $particiouDeAlgumRevisao = false;
	  if($this->getRepositorio()->getPessoaORM()->verificarSeFezReservaNoRevisaoDeVidas($pessoa->getId())){
		  $particiouDeAlgumRevisao = true;
	  }
	  if($pessoa->verificarSeEhAluno()){
		  $particiouDeAlgumRevisao = true;
	  }
      $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos, $pessoa, $aluno = $particiouDeAlgumRevisao);
      $dados[2] = $particiouDeAlgumRevisao;
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

    public function ajustarPessoaAction() {
        try {
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $idPessoa = $sessao->idSessao;            
			$sessao->idSessao = null;
            self::ajustarPessoa($idPessoa, $this->getRepositorio());

            return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(Constantes::$ACTION => 'Arregimentacao',));
        } catch (Exception $exc) {
			error_log($exc->getMessage());
        }
    }

    public static function ajustarPessoa($idPessoa, $repositorio) {
        try {            
            $pessoa = $repositorio->getPessoaORM()->encontrarPorId($idPessoa);			

			$grupoPessoaMaisRecente = null;
			$grupoPessoas = $pessoa->getGrupoPessoa();
			foreach($grupoPessoas as $grupoPessoa){
				if($grupoPessoa->verificarSeEstaAtivo()){
					if($grupoPessoaMaisRecente === null){
						$grupoPessoaMaisRecente = $grupoPessoa;
					}else{
						if($grupoPessoa->getId() > $grupoPessoaMaisRecente->getId()){
							$grupoPessoaMaisRecente = $grupoPessoa;
						}
					}
				}
			}

			if($grupoPessoaMaisRecente){
				foreach($grupoPessoas as $grupoPessoa){
					if($grupoPessoa->verificarSeEstaAtivo()){
						if($grupoPessoa->getId() !== $grupoPessoaMaisRecente->getId()){
							$grupoPessoa->setDataEHoraDeInativacao($grupoPessoa->getData_criacaoStringPadraoBanco());
							$repositorio->getGrupoPessoaORM()->persistir($grupoPessoa, $mudarDataDeCriacao = false);
						}	
					}
				}
			}
          
        } catch (Exception $exc) {
			error_log($exc->getMessage());
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
        $arrayDePessoas = array();
        foreach($grupo->getPessoasAtivas() as $pessoa){
            $arrayDePessoas[] = $pessoa->getId();
        }

        $request = $this->getRequest();		
		if($request->isPost()){
			$postDados = $request->getPost();
			$mesSelecionado = $postDados['mes'];
			$anoSelecionado = $postDados['ano'];
		} else{
			$mesSelecionado = date('m');
			$anoSelecionado = date('Y');
		}

        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mesSelecionado, $anoSelecionado);
        $todosFilhos = array();  
        if($mesSelecionado == date('m') && $anoSelecionado == date('Y')){
            $arrayPeriodoDoMes[1] = 1;
        }                     
        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {            
            $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
            if ($grupoPaiFilhoFilhos) {
                foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
                    $adicionar1 = true;
                    if (count($todosFilhos) > 0) {
                        foreach ($todosFilhos as $filho) {
                            if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
                                $adicionar1 = false;
                                break;
                            }
                        }
                    }
                    if ($adicionar1) {
                        foreach($grupoPaiFilhoFilho->getGrupoPaiFilhoFilho()->getPessoasAtivas() as $pessoa){
                            $adicionar2 = true;
                            if (in_array($pessoa->getId(), $arrayDePessoas)) { 
                                $adicionar2 = false;
                            }                            
                        }
                        if($adicionar2){
							if($grupoPaiFilhoFilho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getEntidadeTipo($original = true)->getId() !== EntidadeTipo::secretario){
								$todosFilhos[] = $grupoPaiFilhoFilho;						
							}
                        }                        
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
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO_ATENDIMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO_ATENDIMENTO);

		self::registrarLog(RegistroAcao::LANCAR_ATENDIMENTO, $extra = 'Filtro: '.$mesSelecionado.'/'.$anoSelecionado);
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
                $mesSelecionado = $post_data['mes'];
			    $anoSelecionado = $post_data['ano'];

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


				$extra = '';
                if ($tipo === $tipoLancar) {
					$extra = 'Lançou atendimento de '.$grupoLancado->getNomeLideresAtivos();
				}
                if ($tipo === $tipoRemover) {
					$extra = 'Removeu atendimento de '.$grupoLancado->getNomeLideresAtivos();
				}
				$extra .= ' - '.$mesSelecionado.'/'.$anoSelecionado;

				self::registrarLog(RegistroAcao::LANCAR_ATENDIMENTO_LIDER, $extra);

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
        $explodeIdSessao = explode('_', $sessao->idSessao);
        $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($explodeIdSessao[0]);
        $formulario = new AtendimentoComentarioForm('formulario', $grupo, $explodeIdSessao[1], $explodeIdSessao[2]);
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
                if ($post_data['mes'] && $post_data['ano']) {
                    $mes = $post_data['mes'];
                    $ano = $post_data['ano'];
                    $grupoAtendimentoComentario->setDataEHoraDeCriacao("$ano-$mes-01");
                } else {
                    $grupoAtendimentoComentario->setDataEHoraDeCriacao();
                }
                $this->getRepositorio()->getGrupoAtendimentoComentarioORM()->persistir($grupoAtendimentoComentario, $mudarDataDeCriacao = false);

                $this->getRepositorio()->fecharTransacao();

                return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
                            Constantes::$ACTION => 'Atendimento',                            
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
			if($pessoaFatoFinanceiroAcessoAtivo->getFatoFinanceiroAcesso()->getId() === FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS_IGREJA){
				$grupo = $grupo->getGrupoIgreja();
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
                    if(!checkdate($dadosPost['Mes'], $dadosPost['Dia'], $dadosPost['Ano'])){
                        $sessao = new Container(Constantes::$NOME_APLICACAO);
                        if($dadosPost['Mes'] < 10){
                            $mesDaMensagem = '0'. $dadosPost['Mes'];
                        } else {
                            $mesDaMensagem = $dadosPost['Mes'];
                        }
                        if($dadosPost['Dia'] < 10){
                            $diaDaMensagem = '0'. $dadosPost['Dia'];
                        } else {
                            $diaDaMensagem = $dadosPost['Dia'];
                        }
                        $dataMensagem = $diaDaMensagem.'-'.$mesDaMensagem.'-'.$dadosPost['Ano'];                        
                        $sessao->mensagemSemAcesso = '<i class = "fa fa-warning text-danger"></i>';
                        $sessao->mensagemSemAcesso .= ' A data '. $dataMensagem .' é inválida, refaça o lançamento com uma data válida';
                        $sessao->corDoTexto = 'text-danger';
                        return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                            Constantes::$ACTION => 'semAcesso',
                        ));
                    }
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
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){            
			$postDados = $request->getPost();
			$mes = $postDados['mes'];
            $ano = $postDados['ano'];
        } else {
			$mes = date('m');
			$ano = date('Y');
        }
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $grupo = $entidade->getGrupo();
        if($pessoaFatoFinanceiroAcessoAtivo = $pessoa->getPessoaFatoFinanceiroAcessoAtivo()){
            if($pessoaFatoFinanceiroAcessoAtivo->getFatoFinanceiroAcesso()->getId() === FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS){
                $grupo = $grupo->getGrupoEquipe();
            }
            if($pessoaFatoFinanceiroAcessoAtivo->getFatoFinanceiroAcesso()->getId() === FatoFinanceiroAcesso::SECRETARIO_PARCEIRO_DE_DEUS_IGREJA){
                $grupo = $grupo->getGrupoIgreja();
            }
        }

        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
        $fatos = $this->getRepositorio()->getFatoFinanceiroORM()->encontrarFatosPorNumeroIdentificadorPorMesEAno($numeroIdentificador, $mes, $ano);
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
        if(count($fatosAtivos) == 0){
            $dados['semLancamentos'] = true;
        }
        $dados['fatos'] = $fatosAtivos;
        $dados['pessoa'] = $pessoa;
        $dados['entidade'] = $entidade;
		
		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		self::registrarLog(RegistroAcao::VER_PARCEIRO_DE_DEUS, $extra = '');
		return new ViewModel($dados);
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

			/* somar parceiro de deus de celula */
			$mesAtual = date('m');
			$anoAtual = date('Y');
			if(intVal($mesAtual) === 1){
				$mesAnterior = 12;
				$anoAnterior = $anoAtual -1;
			}else{
				$mesAnterior = $mesAtual - 1;
				$anoAnterior = $anoAtual;
			}

			$somaParceiro = $this->getRepositorio()->getFatoFinanceiroORM()->pegarValorSomadoDoMesDeCelulas($fatoFinanceiro->getNumero_identificador(), $mesAtual, $anoAtual);
			if(!($somaParceiro > 0)){
				$somaParceiro = 0.00;
			}
			if($fatoMensal = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($fatoFinanceiro->getNumero_identificador(), $mesAtual, $anoAtual)){
				$fatoMensal->setSomaparceiro($somaParceiro);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensal, false);
			}
			$somaParceiro = $this->getRepositorio()->getFatoFinanceiroORM()->pegarValorSomadoDoMesDeCelulas($fatoFinanceiro->getNumero_identificador(), $mesAnterior, $anoAnterior);
			if(!($somaParceiro > 0)){
				$somaParceiro = 0.00;
			}
			if($fatoMensalAnterior = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($fatoFinanceiro->getNumero_identificador(), $mesAnterior, $anoAnterior)){
				$fatoMensalAnterior->setSomaparceiro($somaParceiro);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensalAnterior, false);
			}
			/* fim */

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

			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId((int) substr($fatoFinanceiro->getNumero_identificador(), (count($fatoFinanceiro->getNumero_identificador())-8)));

			/* somar parceiro de deus de celula */
			$mesAtual = date('m');
			$anoAtual = date('Y');
			if(intVal($mesAtual) === 1){
				$mesAnterior = 12;
				$anoAnterior = $anoAtual -1;
			}else{
				$mesAnterior = $mesAtual - 1;
				$anoAnterior = $anoAtual;
			}

			$somaParceiro = $this->getRepositorio()->getFatoFinanceiroORM()->pegarValorSomadoDoMesDeCelulas($fatoFinanceiro->getNumero_identificador(), $mesAtual, $anoAtual);
			if(!($somaParceiro > 0)){
				$somaParceiro = 0.00;
			}
			if($fatoMensal = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($fatoFinanceiro->getNumero_identificador(), $mesAtual, $anoAtual)){
				$fatoMensal->setSomaparceiro($somaParceiro);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensal, false);
			}
			$somaParceiro = $this->getRepositorio()->getFatoFinanceiroORM()->pegarValorSomadoDoMesDeCelulas($fatoFinanceiro->getNumero_identificador(), $mesAnterior, $anoAnterior);
			if(!($somaParceiro > 0)){
				$somaParceiro = 0.00;
			}
			if($fatoMensalAnterior = $this->getRepositorio()->getFatoMensalORM()->encontrarPorNumeroIdentificadorMesEAno($fatoFinanceiro->getNumero_identificador(), $mesAnterior, $anoAnterior)){
				$fatoMensalAnterior->setSomaparceiro($somaParceiro);
				$this->getRepositorio()->getFatoMensalORM()->persistir($fatoMensalAnterior, false);
			}
				/* fim */

			self::registrarLog(RegistroAcao::ACEITOU_PARCEIRO_DE_DEUS, $extra = 'Id: '.$fatoFinanceiro->getId());
			$this->getRepositorio()->fecharTransacao();

			return $this->redirect()->toRoute(Constantes::$ROUTE_LANCAMENTO, array(
				Constantes::$ACTION => 'ParceiroDeDeusExtrato',
			));
		}catch(Exception $exception){
			echo $exception->getMessage();
			$this->getRepositorio()->desfazerTransacao();
		}
	}

	public function enviarSetenta($grupo, $repositorio){
		/* dados setenta */
		$mesAtual = date('m');
		$anoAtual = date('Y');
		if(intVal($mesAtual) === 1){
			$mesAnterior = 12;
			$anoAnterior = $anoAtual -1;
		}else{
			$mesAnterior = $mesAtual - 1;
			$anoAnterior = $anoAtual;
		}

		$grupoIgreja = $grupo->getGrupoIgreja();
		$grupoEquipe = $grupo->getGrupoEquipe();
		$relatorios = array();
		if($relatorioCelulas =	IndexController::pegarMediaPorCelula($repositorio, $grupo, $celulaDeElite = true, $mesAtual, $anoAtual)){
			foreach($relatorioCelulas as $chave => $valor){
				$dados = array(
					'mes' => $mesAtual,
					'ano' => $anoAtual,
					'idGrupoIgreja' => $grupoIgreja->getId(),
					'idGrupo' => $grupo->getId(),
					'idGrupoEquipe' => $grupoEquipe->getId(),
					'idGrupoEvento' => $chave,
					'setenta' => $valor['setenta'],
					'periodos' => $valor['periodos'],
				);
				$relatorios[] = $dados;
			}
		}
		if($relatorioCelulas =	IndexController::pegarMediaPorCelula($repositorio, $grupo, $celulaDeElite = true, $mesAnterior, $anoAnterior)){
			foreach($relatorioCelulas as $chave => $valor){
				$dados = array(
					'mes' => $mesAnterior,
					'ano' => $anoAnterior,
					'idGrupoIgreja' => $grupoIgreja->getId(),
					'idGrupo' => $grupo->getId(),
					'idGrupoEquipe' => $grupoEquipe->getId(),
					'idGrupoEvento' => $chave,
					'setenta' => $valor['setenta'],
					'periodos' => $valor['periodos'],
				);
				$relatorios[] = $dados;
			}
		}

		$fatosSetentaAtual = $repositorio->getFatoSetentaORM()->encontrarPorIdGrupo($grupo->getId(), $mesAtual, $anoAtual);
		foreach($fatosSetentaAtual as $fato){
			$repositorio->getFatoSetentaORM()->remover($fato);
		}
		$fatosSetentaAnterior = $repositorio->getFatoSetentaORM()->encontrarPorIdGrupo($grupo->getId(), $mesAnterior, $anoAnterior);
		foreach($fatosSetentaAnterior as $fato){
			$repositorio->getFatoSetentaORM()->remover($fato);
		}

		foreach($relatorios as $relatorio){
			$fatoSetenta = new FatoSetenta();
			$fatoSetenta->setGrupo_id($relatorio['idGrupo']);
			$fatoSetenta->setGrupo_igreja_id($relatorio['idGrupoIgreja']);
			$fatoSetenta->setGrupo_equipe_id($relatorio['idGrupoEquipe']);
			$fatoSetenta->setGrupo_evento_id($relatorio['idGrupoEvento']);
			$fatoSetenta->setMes($relatorio['mes']);
			$fatoSetenta->setAno($relatorio['ano']);
			$fatoSetenta->setSetenta($relatorio['setenta'] ? 'S' : 'N');
			$fatoSetenta->setP1($relatorio['periodos'][1]['arregimentacao']);
			$fatoSetenta->setP2($relatorio['periodos'][2]['arregimentacao']);
			$fatoSetenta->setP3($relatorio['periodos'][3]['arregimentacao']);
			$fatoSetenta->setP4($relatorio['periodos'][4]['arregimentacao']);
			$fatoSetenta->setP5($relatorio['periodos'][5]['arregimentacao']);
			$fatoSetenta->setV1($relatorio['periodos'][1]['visitantes']);
			$fatoSetenta->setV2($relatorio['periodos'][2]['visitantes']);
			$fatoSetenta->setV3($relatorio['periodos'][3]['visitantes']);
			$fatoSetenta->setV4($relatorio['periodos'][4]['visitantes']);
			$fatoSetenta->setV5($relatorio['periodos'][5]['visitantes']);
			$fatoSetenta->setPd1($relatorio['periodos'][1]['parceiroDeDeus']);
			$fatoSetenta->setPd2($relatorio['periodos'][2]['parceiroDeDeus']);
			$fatoSetenta->setPd3($relatorio['periodos'][3]['parceiroDeDeus']);
			$fatoSetenta->setPd4($relatorio['periodos'][4]['parceiroDeDeus']);
			$fatoSetenta->setPd5($relatorio['periodos'][5]['parceiroDeDeus']);
			$fatoSetenta->setE1($relatorio['periodos'][1]['elite'] ? 'S' : 'N');
			$fatoSetenta->setE2($relatorio['periodos'][2]['elite'] ? 'S' : 'N');
			$fatoSetenta->setE3($relatorio['periodos'][3]['elite'] ? 'S' : 'N');
			$fatoSetenta->setE4($relatorio['periodos'][4]['elite'] ? 'S' : 'N');
			$fatoSetenta->setE5($relatorio['periodos'][5]['elite'] ? 'S' : 'N');
			$fatoSetenta->setP6($relatorio['periodos'][6]['arregimentacao']);
			$fatoSetenta->setV6($relatorio['periodos'][6]['visitantes']);
			$fatoSetenta->setPd6($relatorio['periodos'][6]['parceiroDeDeus']);
			$fatoSetenta->setE6($relatorio['periodos'][6]['elite'] ? 'S' : 'N');
			$repositorio->getFatoSetentaORM()->persistir($fatoSetenta);
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
                foreach($pessoa->getPessoaFatoFinanceiroAcesso() as $pessoaFatoFinanceiroAcesso){
					if($pessoaFatoFinanceiroAcesso->verificarSeEstaAtivo()){						
						$pessoaFatoFinanceiroAcesso->setDataEHoraDeInativacao();
						$this->getRepositorio()->getPessoaFatoFinanceiroAcessoORM()->persistir($pessoaFatoFinanceiroAcesso, false);	
					}					
				}
				$grupo = $entidade->getGrupo();

				$qualPerfilUsar = $post_data['qualPerfilUsar'];
				$fatoFinanceiroAcesso = $this->getRepositorio()->getFatoFinanceiroAcessoORM()->encontrarPorId($qualPerfilUsar);
				$pessoaFatoFinanceiroAcesso = new PessoaFatoFinanceiroAcesso();
				$pessoaFatoFinanceiroAcesso->setPessoa($pessoa);
				$pessoaFatoFinanceiroAcesso->setGrupo($grupo->getGrupoEquipe());
				$pessoaFatoFinanceiroAcesso->setFatoFinanceiroAcesso($fatoFinanceiroAcesso);
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

	public function discipuladoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoEventoDiscipulados = $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoDiscipulado);

		$viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
		$tradutor = $viewHelperManager->get('translate');
		$formulario = new FatoDiscipuladoForm($grupoEventoDiscipulados, $tradutor);
		$dados = array();
		$dados['formulario'] = $formulario;
		$dados['lideres'] = null;
		if($grupoPaiFilhoPai = $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()){
			$grupoPai = $grupoPaiFilhoPai->getGrupoPaiFilhoPai();
			if($grupoPaiFilhoAvo = $grupoPai->getGrupoPaiFilhoPaiAtivo()){
				$dados['lideres'] = '';
				$grupoAvo = $grupoPaiFilhoAvo->getGrupoPaiFilhoPai();
				$lideresAvos = $grupoAvo->getNomeLideresAtivos();
				$dados['lideres'] .= $lideresAvos;
				if($grupoAvo->getEntidadeAtiva()){
					$dados['lideres'] .= ', líderes de '.$grupoAvo->getEntidadeAtiva()->infoEntidade();
				}

			}
		}
		$view = new ViewModel($dados);
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate('layout/layout-js-lancamento-discipulado');
		$view->addChild($layoutJS, 'layoutJsLancamentoDiscipulado');
		return $view;
	}

	public function discipuladoFinalizarAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$request = $this->getRequest();
		if($request->isPost()){
			try{
				$this->getRepositorio()->iniciarTransacao();

				$idEntidadeAtual = $sessao->idEntidadeAtual;
				$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
				$grupoPai = $entidade->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();

				$dadosPost = $request->getPost();
				if(date('m') == 1){
					$mesAnterior = 12;
					$anoAnterior = date('Y') - 1;
				}else{
					$mesAnterior = date('m') - 1;
					$anoAnterior = date('Y');
				}
				$fatoDiscipulado = new FatoDiscipulado();
				$fatoDiscipulado->setMes($mesAnterior);
				$fatoDiscipulado->setAno($anoAnterior);
				$fatoDiscipulado->setGrupo($grupoPai);
				$fatoDiscipulado->setGrupo_evento_id($dadosPost['idGrupoEvento']);
				$fatoDiscipulado->setPessoa_id($sessao->idPessoa);
				$fatoDiscipulado->setLanche(0);
				$fatoDiscipulado->setAdministrativo($dadosPost['administrativo']);
				$fatoDiscipulado->setOracao($dadosPost['oracao']);
				$fatoDiscipulado->setPalavra($dadosPost['palavra']);
				$fatoDiscipulado->setPontualidade($dadosPost['pontualidade']);
				$fatoDiscipulado->setAssiduidade($dadosPost['assiduidade']);
				$fatoDiscipulado->setObservacao($dadosPost['observacao']);
				$this->getRepositorio()->getFatoDiscipuladoORM()->persistir($fatoDiscipulado);

				$this->getRepositorio()->fecharTransacao();
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL);

			}catch(Exception $exception){
				echo $exception->getTraceAsString();
				$this->getRepositorio()->desfazerTransacao();
			}
		}
	}
}
