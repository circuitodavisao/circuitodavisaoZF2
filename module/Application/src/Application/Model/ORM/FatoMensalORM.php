<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\FatoMensal;
use DateTime;
use Exception;

class FatoMensalORM extends CircuitoORM {

	public function encontrarPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano) {
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
					));
			if (empty($resposta)) {
				$resposta = $this->criarFatoMensal($numeroIdentificador, $mes, $ano);
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function criarFatoMensal($numeroIdentificador, $mes, $ano) {
		$fato = new FatoMensal();
		try {
			$fato->setNumero_identificador($numeroIdentificador);
			$fato->setDataEHoraDeCriacao();
			$fato->setMes($mes);
			$fato->setAno($ano);
			$this->persistir($fato, false);
			return $fato;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function buscarFatosSomadosPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano, $tipoComparacao) {
		$dqlBase = "SELECT "
			. "SUM(fm.c1) c1, "
			. "SUM(fm.c2) c2, "
			. "SUM(fm.c3) c3, "
			. "SUM(fm.c4) c4, "
			. "SUM(fm.c5) c5, "
			. "SUM(fm.cu1) cu1, "
			. "SUM(fm.cu2) cu2, "
			. "SUM(fm.cu3) cu3, "
			. "SUM(fm.cu4) cu4, "
			. "SUM(fm.cu5) cu5, "
			. "SUM(fm.a1) a1, "
			. "SUM(fm.a2) a2, "
			. "SUM(fm.a3) a3, "
			. "SUM(fm.a4) a4, "
			. "SUM(fm.a5) a5, "
			. "SUM(fm.d1) d1, "
			. "SUM(fm.d2) d2, "
			. "SUM(fm.d3) d3, "
			. "SUM(fm.d4) d4, "
			. "SUM(fm.d5) d5 "
			. "FROM  " . Constantes::$ENTITY_FATO_MENSAL . " fm "
			. "WHERE "
			. "fm.numero_identificador #tipoComparacao ?1 "
			. "AND fm.data_inativacao is null "
			. "AND fm.mes = ?2 "
			. "AND fm.ano = ?3";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $mes)
				->setParameter(3, $ano)
				->getResult();
			return $result[0];
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}
}
