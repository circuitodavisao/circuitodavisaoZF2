<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

class FatoFinanceiroInstitutoORM extends CircuitoORM {

	public function encontrarFatosPorNumeroIdentificadorPorMesEAno($numeroIdentificador, $mes, $ano) {
		$fatos = null;
		try {
			$dql = "SELECT ff "
				. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO_INSTITUTO . " ff "
				. "WHERE "
				. "ff.numero_identificador LIKE ?1 "
				. "AND ff.mes = ?2 AND ff.ano = ?3 order by ff.data asc";

			$numeroAjustado = $numeroIdentificador . '%';
			$fatos = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroAjustado)
				->setParameter(2, intVal($mes))
				->setParameter(3, intVal($ano))
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $fatos;
	}
}
