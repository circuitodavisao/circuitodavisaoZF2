<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\FatoCurso;
use DateTime;
use Exception;

/**
 * Nome: FatoRevisaoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_revisao
 */
class FatoRevisaoORM extends CircuitoORM {

	public function encontrarPorMatricula($matricula) {
		$dql = "SELECT fr "
			. "FROM  " . Constantes::$ENTITY_FATO_REVISAO . " fr "
			. "WHERE "
			. "fr.matricula = ?1";

		try {
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $matricula)
				->getResult();
			return $result[0];
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function encontrarPorIdEvento($idEvento) {
		$dql = "SELECT fr "
			. "FROM  " . Constantes::$ENTITY_FATO_REVISAO . " fr "
			. "WHERE "
			. "fr.evento_id = ?1";

		try {
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $idEvento)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

}
