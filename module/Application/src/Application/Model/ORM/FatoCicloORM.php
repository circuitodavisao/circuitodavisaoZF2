<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Dimensao;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\FatoCiclo;
use Application\Model\Entity\GrupoPessoaTipo;
use DateTime;
use Exception;
use Zend\Session\Container;

/**
 * Nome: FatoCicloORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ciclo
 */
class FatoCicloORM extends CircuitoORM {

	public function encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dia, RepositorioORM $repositorioORM) {
		try {
			$resposta = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findOneBy(
					array(
						Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador,
						'data_criacao' => $dia,
					));
			if (empty($resposta)) {
				$resposta = $this->criarFatoNoCiclo($numeroIdentificador, $dia, $repositorioORM);
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Localizar fato_ciclo por numeroIdentificador
	 * @param type $numeroIdentificador
	 * @param type $ciclo
	 * @param type $mes
	 * @param type $ano
	 * @param RepositorioORM $repositorioORM
	 * @return FatoCiclo
	 */
	public function encontrarPorNumeroIdentificador($numeroIdentificador, $ciclo, $mes, $ano, RepositorioORM $repositorioORM) {
		$cicloInt = (int) $ciclo;
		$mesInt = (int) $mes;
		$anoInt = (int) $ano;
		try {
			$resposta = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findOneBy(
					array(
						Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador,
						Constantes::$ENTITY_FATO_CICLO_MES => $mesInt,
						Constantes::$ENTITY_FATO_CICLO_ANO => $anoInt,
						Constantes::$ENTITY_FATO_CICLO_CICLO => $cicloInt,
					));
			if (empty($resposta)) {
				$resposta = $this->criarFatoNoCiclo($numeroIdentificador, $ano, $mes, $ciclo, $repositorioORM);
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Localizar fato_ciclo por numeroIdentificador
	 * @param string $numeroIdentificador
	 * @param int $periodo
	 * @param int $tipoComparacao
	 * @return array
	 */
	public function montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoComparacao, $periodoFinal = null) {
		$dimensaoTipoCelula = 1;
		$dimensaoTipoDomingo = 4;
		$dqlBase = "SELECT "
			. "SUM(d.lider) lideres, "
			. "SUM(d.visitante) visitantes, "
			. "SUM(d.consolidacao) consolidacoes, "
			. "SUM(d.membro) membros "
			. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
			. "JOIN fc.dimensao d "
			. "WHERE "
			. "d.dimensaoTipo = #dimensaoTipo "
			. "AND fc.numero_identificador #tipoComparacao ?1 "
			. "AND fc.data_inativacao is null "
			. "#data";
		try {

			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}

			$resultadoPeriodo = Funcoes::montaPeriodo($periodoInicial);
			$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
			$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

			if ($periodoFinal === null) {
				$dqlAjustadaTipoComparacao = str_replace('#data', 'AND fc.data_criacao = ?2 ', $dqlAjustadaTipoComparacao);
			} else {
				$resultadoPeriodoFinal = Funcoes::montaPeriodo($periodoFinal);
				$dataDoPeriodoFinal = $resultadoPeriodoFinal[6] . '-' . $resultadoPeriodoFinal[5] . '-' . $resultadoPeriodoFinal[4];
				$stringDatas = "AND fc.data_criacao >= ?2 AND fc.data_criacao <= '$dataDoPeriodoFinal' ";
				$dqlAjustadaTipoComparacao = str_replace('#data', $stringDatas, $dqlAjustadaTipoComparacao);
				$dataDoPeriodoFormatada = $dataDoPeriodo;
			}
			for ($indice = $dimensaoTipoCelula; $indice <= $dimensaoTipoDomingo; $indice++) {
				$dqlAjustada = str_replace('#dimensaoTipo', $indice, $dqlAjustadaTipoComparacao);
				$result[$indice] = $this->getEntityManager()->createQuery($dqlAjustada)
					->setParameter(1, $numeroIdentificador)
					->setParameter(2, $dataDoPeriodoFormatada)
					->getResult();
			}
			return $result;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Localizar fato_ciclo por numeroIdentificador
	 * @param string $numeroIdentificador
	 * @param int $periodo
	 * @param int $tipoComparacao
	 * @return array
	 */
	public function montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodo, $tipoComparacao, $estrategica = null) {
		$dqlBase = "SELECT "
			. "COUNT(c.id) quantidade, "
			. "SUM(c.realizada) realizadas "
			. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
			. "JOIN fc.fatoCelula c "
			. "WHERE "
			. "fc.numero_identificador #tipoComparacao ?1 "
			. "AND fc.data_inativacao is null "
			. "AND fc.data_criacao = ?2 "
			. "AND c.estrategica = ?3 ";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
			$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
			$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
			$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
			if($estrategica === null){
				$estrategica = 'N';
			}else{
				$estrategica = 'S';
			}
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dataDoPeriodoFormatada)
				->setParameter(3, $estrategica)
				->getResult();

			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function fatoCelulaPorNumeoIdentificador($numeroIdentificador, $periodo, $tipoComparacao, $estrategica = null) {
		$dqlBase = "SELECT c.id, c.evento_celula_id, c.fato_ciclo_id "
			. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
			. "JOIN fc.fatoCelula c "
			. "WHERE "
			. "fc.numero_identificador #tipoComparacao ?1 "
			. "AND fc.data_inativacao is null "
			//. "AND fc.data_criacao = ?2 "
			. "AND c.estrategica = ?3 ";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
			$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
			$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
			$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
			if($estrategica === null){
				$estrategica = 'N';
			}else{
				$estrategica = 'S';
			}
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
			//	->setParameter(2, $dataDoPeriodoFormatada)
				->setParameter(3, $estrategica)
				->getResult();

			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function montarRelatorioCelulaDeElitePorNumeroIdentificador($numeroIdentificador, $periodo, $tipoComparacao) {
		$dqlBase = "SELECT "
			. "count(d.id) celulaDeElite "
			. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
			. "JOIN fc.dimensao d "
			. "WHERE "
			. "d.dimensaoTipo = 1 "
			. "AND fc.numero_identificador #tipoComparacao ?1 "
			. "AND fc.data_inativacao is null "
			. "AND fc.data_criacao = ?2 "
			. "AND (d.lider + d.visitante + d.consolidacao + d.membro) > 6";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
			$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
			$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
			$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dataDoPeriodoFormatada)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Montar numeroIdentificador
	 */
	public function montarNumeroIdentificador(RepositorioORM $repositorioORM, $grupo = null, $dataInativacao = null) {
		$numeroIdentificador = '';
		$tamanho = 8;
		$grupoSelecionado = null;
		if ($grupo === null) {
			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidadeSelecionada = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$grupoSelecionado = $entidadeSelecionada->getGrupo();
		} else {
			$grupoSelecionado = $grupo;
		}
		try {
			$entidadeSelecionada = null;
			if (!$grupoSelecionado->getEntidadeInativaPorDataInativacao($dataInativacao)) {
				$entidadeSelecionada = $grupoSelecionado->getEntidadeAtiva();
			} else {
				$entidadeSelecionada = $grupoSelecionado->getEntidadeInativaPorDataInativacao($dataInativacao);
			}
			if($entidadeSelecionada && $entidadeSelecionada->getEntidadeTipo()){
				$tipoEntidade = $entidadeSelecionada->getEntidadeTipo()->getId();
				while ($tipoEntidade === Entidade::SUBEQUIPE) {
					$numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
					if (!$grupoSelecionado->getGrupoPaiFilhoPaiPorDataInativacao($dataInativacao)) {
						if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
							$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
						} else {
							break;
							$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiInativo()->getGrupoPaiFilhoPai();
						}
					} else {
						$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiPorDataInativacao($dataInativacao)->getGrupoPaiFilhoPai();
					}
					if (!$grupoSelecionado->getEntidadeInativaPorDataInativacao($dataInativacao)) {
						$tipoEntidade = $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId();
					} else {
						$tipoEntidade = $grupoSelecionado->getEntidadeInativaPorDataInativacao($dataInativacao)->getEntidadeTipo()->getId();
					}
				}
				if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
					$numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
					if (!$grupoSelecionado->getGrupoPaiFilhoPaiPorDataInativacao($dataInativacao)) {
						if($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()){
							$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
						}
					} else {
						$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiPorDataInativacao($dataInativacao)->getGrupoPaiFilhoPai();
					}
				}
				if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
					$numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
				}
			}
			return $numeroIdentificador;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Criar fato ciclo
	 */
	public function criarFatoNoCiclo($numeroIdentificador, $dia, RepositorioORM $repositorioORM) {
		$fatoCiclo = new FatoCiclo();
		try {
			$fatoCiclo->setNumero_identificador($numeroIdentificador);
			$fatoCiclo->setDataEHoraDeCriacao();
			$fatoCiclo->setData_criacao($dia);
			$this->persistir($fatoCiclo, false);
			$dimensoes = $this->criarDimensoes($fatoCiclo, $repositorioORM);
			$fatoCicloPesquisa = $this->encontrarPorId($fatoCiclo->getId());
			$fatoCicloPesquisa->setDimensao($dimensoes);
			return $fatoCicloPesquisa;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	/**
	 * Criar dimensões
	 * @param type $fatoCiclo
	 * @param RepositorioORM $repositorioORM
	 * @return Dimensao
	 */
	public function criarDimensoes($fatoCiclo, RepositorioORM $repositorioORM) {
		$dimensoes = null;
		try {
			for ($indiceDimensoes = 1; $indiceDimensoes <= 4; $indiceDimensoes++) {
				$dimensao = new Dimensao();
				$dimensao->setFatoCiclo($fatoCiclo);
				$dimensaoTipo = $repositorioORM->getDimensaoTipoORM()->encontrarPorId($indiceDimensoes);
				$dimensao->setDimensaoTipo($dimensaoTipo);
				$dimensao->setVisitante(0);
				$dimensao->setConsolidacao(0);
				$dimensao->setMembro(0);
				$dimensao->setLider(0);
				$repositorioORM->getDimensaoORM()->persistir($dimensao);
				$dimensoes[] = $dimensao;
			}
			return $dimensoes;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function verificaFrequenciasPorCelulaEPeriodo($periodo, $eventoId) {
		$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
		$dataDoPeriodoInicial = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
		$dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];

		$dataDoInicioFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoInicial);
		$dataDoFimFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);

		$dqlBase = "SELECT "
			. "count(ef.id) soma "
			. "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
			. "WHERE "
			. "ef.evento_id = ?1 AND "
			. "ef.dia >= ?2 AND ef.dia <= ?3 AND ef.frequencia = 'S' ";

		$resultado = $this->getEntityManager()->createQuery($dqlBase)
			->setParameter(1, (int) $eventoId)
			->setParameter(2, $dataDoInicioFormatada)
			->setParameter(3, $dataDoFimFormatada)
			->getResult();


		$somaResultado = $resultado[0]['soma'];
		return $somaResultado;
	}

	public function verificaFrequenciasPorCelulaEPeriodoESeTemVisitante($periodo, $idEvento, $repositorio, $mes = null) {
		$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
		$dataDoPeriodoInicial = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
		$dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];

		$dataDoInicioFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoInicial);
		$dataDoFimFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);

		$dqlBase = "SELECT "
			. "ef.frequencia, ef.pessoa_id "
			. "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
			. "WHERE "
			. "ef.evento_id = ?1 AND "
			. "ef.dia >= ?2 AND ef.dia <= ?3 ";

		$resultados = $this->getEntityManager()->createQuery($dqlBase)
			->setParameter(1, (int) $idEvento)
			->setParameter(2, $dataDoInicioFormatada)
			->setParameter(3, $dataDoFimFormatada)
			->getResult();

		$relatorio = array();
		$visitantes = 0;
		$somaResultado = 0;
		if($resultados){

			$inicioDoPeriodoEmTempo = strtotime($dataDoPeriodoInicial);
			$fimDoPeriodoEmTempo = strtotime($dataDoPeriodoFinal);
			foreach ($resultados as $resultado) {
				if ($resultado['frequencia'] == 'S') {
					$somaResultado++;
					$idPessoa = $resultado['pessoa_id'];
					if($pessoa = $repositorio->getPessoaORM()->encontrarPorId($idPessoa)){
						if($grupoPessoas = $pessoa->getGrupoPessoa()){
							foreach($grupoPessoas as $grupoPessoa){
								$dataDeCriacaoEmTempo = strtotime($grupoPessoa->getData_criacaoStringPadraoBanco());
								$dataDeInativacaoEmTempo = strtotime($grupoPessoa->getData_inativacaoStringPadraoBanco());
								if(
									($grupoPessoa->verificarSeEstaAtivo() && $grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::VISITANTE)
									|| (
										!$grupoPessoa->verificarSeEstaAtivo()
										&& $grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::VISITANTE
										&& $dataDeInativacaoEmTempo >= $inicioDoPeriodoEmTempo										
									)
								){
									$visitantes++;
									break;
								}
							}
						}
					}
				}
			}
		}

		$relatorio['elite'] = false;
		$relatorio['parceiroDeDeus'] = 0;

		if($valorParceiroDeDeus = $repositorio->getFatoFinanceiroORM()->valorPorEventoEPEriodo($idEvento, $periodo, $mes)){
			error_log('parceiro: '.$valorParceiroDeDeus);
			$relatorio['parceiroDeDeus'] = $valorParceiroDeDeus;
		}

		if($somaResultado >= 7 && $visitantes > 0 && $valorParceiroDeDeus >= 10){
			$relatorio['elite'] = true;
		}

		$relatorio['visitantes'] = $visitantes;
		$relatorio['arregimentacao'] = $somaResultado;
		return $relatorio;
	}

	public function fatoCicloExcluirRelatorioSegunda() {
		$dateFormatada = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
		$dataEmString = $dateFormatada->format('Y-m-d');
		$dqlExcluirFatoCiclo = "DELETE from ".Constantes::$ENTITY_FATO_CICLO." fcic where fcic.data_criacao = ?1";
		$result = $this->getEntityManager()->createQuery($dqlExcluirFatoCiclo)
			->setParameter(1, $dataEmString)
			->getResult();
	}

	public function  montarRelatorioResumoPorNumeroIdentificadoEPeriodo($numeroIdentificador, $periodo, $tipoComparacao){
		$dqlBase = "SELECT "
			. "(SUM(d.lider) + SUM(d.visitante) + SUM(d.consolidacao) + SUM(d.membro)) valor "
			. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
			. "JOIN fc.dimensao d "
			. "WHERE "
			. "d.dimensaoTipo = #dimensaoTipo "
			. "AND fc.numero_identificador #tipoComparacao ?1 "
			. "AND fc.data_inativacao is null "
			. "AND fc.data_criacao = ?2 ";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}

			$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
			$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
			$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

			$dimensaoTipoCelula = 1;
			$dimensaoTipoDomingo = 4;
			for ($indice = $dimensaoTipoCelula; $indice <= $dimensaoTipoDomingo; $indice++) {
				$dqlAjustada = str_replace('#dimensaoTipo', $indice, $dqlAjustadaTipoComparacao);
				$result[$indice] = $this->getEntityManager()->createQuery($dqlAjustada)
					->setParameter(1, $numeroIdentificador)
					->setParameter(2, $dataDoPeriodoFormatada)
					->getResult()[0];
			}
			return $result;
		} catch (Exception $exc) {
			var_dump($exc->getMessage());
		}
	}

}
