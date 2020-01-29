<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;
use DateTime;

class FatoFinanceiroORM extends CircuitoORM {

	public function encontrarFatosPorNumeroIdentificador($numeroIdentificador) {
		$fatos = null;
		try {
			$dql = "SELECT ff "
				. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
				. "WHERE "
				. "ff.numero_identificador LIKE ?1 ";

			$numeroAjustado = $numeroIdentificador . '%';
			$fatos = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroAjustado)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $fatos;
	}

	public function encontrarFatosPorNumeroIdentificadorPorMesEAno($numeroIdentificador, $mes, $ano) {
		$fatos = null;
		try {
			$dql = "SELECT ff "
				. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
				. "WHERE "
				. "ff.numero_identificador LIKE ?1 "
				. "AND ff.data >= ?2 AND ff.data <= ?3 order by ff.data asc";

			$numeroAjustado = $numeroIdentificador . '%';
			$dataInicial = $ano . '-' . $mes . '-01';
			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataInicial);
			$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
			$dataFinal = $ano . '-' . $mes . '-' . $ultimo_dia;
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataFinal);
			$fatos = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroAjustado)
				->setParameter(2, $dataInicialFormatada)
				->setParameter(3, $dataFinalFormatada)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $fatos;
	}

	public function fatosValorPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano, $tipoComparacao = 2) {	
		$fatos = null;
		$dqlBase = "SELECT "
			. "SUM(ff.valor) valor "
			. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
            . "WHERE "
            . " ff.numero_identificador #tipoComparacao ?1 "
			. "AND ff.data_inativacao is null "
			. "AND ff.data >= ?2 AND ff.data <= ?3 AND ff.situacao_id = 3 ";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}			
			$dataInicial = $ano . '-' . $mes . '-01';
			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataInicial);
			$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
			$dataFinal = $ano . '-' . $mes . '-' . $ultimo_dia;
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataFinal);
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dataInicialFormatada)
				->setParameter(3, $dataFinalFormatada)
				->getResult();
			} catch (Exception $exc) {
				echo $exc->getMessage();
			}
			return $result[0];
	}

	public function fatosPorNumeroIdentificador($numeroIdentificador, $periodo, $mes, $ano, $tipoComparacao) {
		$resultadoPeriodo = Funcoes::montaPeriodo($periodo);

		$dqlBase = "SELECT "
			. "SUM(ff.valor) valor "
			. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
                . "WHERE "
                . " ff.numero_identificador #tipoComparacao ?1 "
                . "AND ff.data_inativacao is null "
                . "AND ff.data >= ?2 AND ff.data <= ?3 AND ff.situacao_id = 3 ";
        try {
            if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}

			/* verificando periodo inicial */
			if($mes != $resultadoPeriodo[2]){
				$dataInicial = $ano . '-' . $mes . '-01';
			}else{
				$dataInicial = $ano . '-' . $mes . '-' .$resultadoPeriodo[1];
			}
			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataInicial);
			/* verificando periodo final */
			if($mes != $resultadoPeriodo[5]){
				$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
				$dataFinal = $ano . '-' . $mes . '-' . $ultimo_dia;
			}else{
				$dataFinal = $ano . '-' . $mes . '-' . $resultadoPeriodo[4];
			}		
				
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataFinal);
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dataInicialFormatada)
				->setParameter(3, $dataFinalFormatada)
				->getResult();

			return $result[0];
		} catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

	public function valorPorEventoEPEriodo($idEvento, $periodo, $mes = null) {
        $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
        $dataDoPeriodoInicial = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
        $dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];

		if($mes){
			/* começo do mes */
			if($resultadoPeriodo[5] == $mes && $resultadoPeriodo[2] != $mes){
				if($mes == 1 && $resultadoPeriodo[3] != $resultadoPeriodo[6]){
					$resultadoPeriodo[3] = $resultadoPeriodo[6];
				}
				$dataDoPeriodoInicial = $resultadoPeriodo[3].'-'.str_pad($mes, 2, '0', STR_PAD_LEFT).'-01';
			}
			/* fim do mes */
			if($resultadoPeriodo[2] == $mes && $resultadoPeriodo[5] != $mes){
				$ultimoDiaDomes = 28;
				if(
					$mes == 1 ||
					$mes == 3 ||
					$mes == 5 ||
					$mes == 7 ||
					$mes == 8 ||
					$mes == 10 ||
					$mes == 12
				){
					$ultimoDiaDomes = 31;
				}
				if(
					$mes == 4 ||
					$mes == 6 ||
					$mes == 9 ||
					$mes == 11
				){
					$ultimoDiaDomes = 30;
				}
				if($mes == 12 && $resultadoPeriodo[6] != $resultadoPeriodo[3]){
					$resultadoPeriodo[6] = $resultadoPeriodo[3];
				}
	
				$dataDoPeriodoFinal = $resultadoPeriodo[6].'-'.str_pad($mes, 2, '0', STR_PAD_LEFT).'-'.$ultimoDiaDomes;
			}
		}
		$dql= "SELECT "
			. "SUM(ff.valor) valor "
			. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
                . "WHERE "
                . " ff.evento_id = ?1 "
                . "AND ff.data_inativacao is null "
                . "AND ff.data >= ?2 AND ff.data <= ?3 AND ff.situacao_id = 3 ";
		try {
			error_log('data inicial: '.$dataDoPeriodoInicial);
			error_log('data final: '.$dataDoPeriodoFinal);
			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoInicial);
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, (int) $idEvento)
				->setParameter(2, $dataInicialFormatada)
				->setParameter(3, $dataFinalFormatada)
				->getResult();

			return $result[0]['valor'];
		} catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }


}
