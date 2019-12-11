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
			. "SUM(fm.l1) l1, "
			. "SUM(fm.l2) l2, "
			. "SUM(fm.l3) l3, "
			. "SUM(fm.l4) l4, "
			. "SUM(fm.l5) l5, "
			. "SUM(fm.l6) l6, "
			. "SUM(fm.lb1) lb1, "
			. "SUM(fm.lb2) lb2, "
			. "SUM(fm.lb3) lb3, "
			. "SUM(fm.lb4) lb4, "
			. "SUM(fm.lb5) lb5, "
			. "SUM(fm.lb6) lb6, "
			. "SUM(fm.cq1) cq1, "
			. "SUM(fm.cq2) cq2, "
			. "SUM(fm.cq3) cq3, "
			. "SUM(fm.cq4) cq4, "
			. "SUM(fm.cq5) cq5, "
			. "SUM(fm.cq6) cq6, "
			. "SUM(fm.cbq1) cbq1, "
			. "SUM(fm.cbq2) cbq2, "
			. "SUM(fm.cbq3) cbq3, "
			. "SUM(fm.cbq4) cbq4, "
			. "SUM(fm.cbq5) cbq5, "
			. "SUM(fm.cbq6) cbq6, "
			. "SUM(fm.cqmeta1) cqmeta1, "
			. "SUM(fm.cqmeta2) cqmeta2, "
			. "SUM(fm.cqmeta3) cqmeta3, "
			. "SUM(fm.cqmeta4) cqmeta4, "
			. "SUM(fm.cqmeta5) cqmeta5, "
			. "SUM(fm.cqmeta6) cqmeta6, "
			. "SUM(fm.cbqmeta1) cbqmeta1, "
			. "SUM(fm.cbqmeta2) cbqmeta2, "
			. "SUM(fm.cbqmeta3) cbqmeta3, "
			. "SUM(fm.cbqmeta4) cbqmeta4, "
			. "SUM(fm.cbqmeta5) cbqmeta5, "
			. "SUM(fm.cbqmeta6) cbqmeta6, "
			. "SUM(fm.realizada1) realizada1, "
			. "SUM(fm.realizada2) realizada2, "
			. "SUM(fm.realizada3) realizada3, "
			. "SUM(fm.realizada4) realizada4, "
			. "SUM(fm.realizada5) realizada5, "
			. "SUM(fm.realizada6) realizada6, "
			. "SUM(fm.mediarealizada) mediarealizada, "
			. "SUM(fm.c1) c1, "
			. "SUM(fm.c2) c2, "
			. "SUM(fm.c3) c3, "
			. "SUM(fm.c4) c4, "
			. "SUM(fm.c5) c5, "
			. "SUM(fm.c6) c6, "
			. "SUM(fm.cp1) cp1, "
			. "SUM(fm.cp2) cp2, "
			. "SUM(fm.cp3) cp3, "
			. "SUM(fm.cp4) cp4, "
			. "SUM(fm.cp5) cp5, "
			. "SUM(fm.cp6) cp6, "
			. "SUM(fm.mediac) mediac, "
			. "SUM(fm.cu1) cu1, "
			. "SUM(fm.cu2) cu2, "
			. "SUM(fm.cu3) cu3, "
			. "SUM(fm.cu4) cu4, "
			. "SUM(fm.cu5) cu5, "
			. "SUM(fm.cu6) cu6, "
			. "SUM(fm.a1) a1, "
			. "SUM(fm.a2) a2, "
			. "SUM(fm.a3) a3, "
			. "SUM(fm.a4) a4, "
			. "SUM(fm.a5) a5, "
			. "SUM(fm.a6) a6, "
			. "SUM(fm.d1) d1, "
			. "SUM(fm.d2) d2, "
			. "SUM(fm.d3) d3, "
			. "SUM(fm.d4) d4, "
			. "SUM(fm.d5) d5, "
			. "SUM(fm.d6) d6, "
			. "SUM(fm.mem1) mem1, "
			. "SUM(fm.mem2) mem2, "
			. "SUM(fm.mem3) mem3, "
			. "SUM(fm.mem4) mem4, "
			. "SUM(fm.mem5) mem5, "
			. "SUM(fm.mem6) mem6, "
			. "SUM(fm.mediamem) mediamem "
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

	public function buscarFatosPorNumeroIdentificadorMesEAno($numeroIdentificador, $mes, $ano) {
		$dqlBase = "SELECT fm "
			. "FROM  " . Constantes::$ENTITY_FATO_MENSAL . " fm "
			. "WHERE "
			. "fm.numero_identificador #tipoComparacao ?1 "
			. "AND fm.data_inativacao is null "
			. "AND fm.mes = ?2 "
			. "AND fm.ano = ?3";
		try {
			$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
			$numeroIdentificador .= '%';
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $mes)
				->setParameter(3, $ano)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}
}
