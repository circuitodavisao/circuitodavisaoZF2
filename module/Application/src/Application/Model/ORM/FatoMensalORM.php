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

}
