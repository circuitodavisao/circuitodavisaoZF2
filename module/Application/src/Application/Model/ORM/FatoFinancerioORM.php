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

	public function fatosPorNumeroIdentificador($numeroIdentificador, $periodo, $mes, $ano, $tipoComparacao) {
		$resultadoPeriodo = Funcoes::montaPeriodo($periodo);

		$dqlBase = "SELECT "
			. "SUM(ff.valor) valor "
			. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO . " ff "
                . "WHERE "
                . " ff.numero_identificador #tipoComparacao ?1 "
                . "AND ff.data_inativacao is null "
                . "AND ff.data >= ?2 AND ff.data <= ?3 ";
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

}
