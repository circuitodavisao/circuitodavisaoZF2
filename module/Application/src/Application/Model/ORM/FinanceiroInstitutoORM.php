<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

class FinanceiroInstitutoORM extends CircuitoORM {

	public function encontrarFatosPorNumeroIdentificadorPorMesEAno($numeroIdentificador, $mes, $ano) {
		$fatos = null;
		try {
			$dql = "SELECT ffi "
				. "FROM  " . Constantes::$ENTITY_FATO_FINANCEIRO_INSTITUTO . " ffi "
				. "WHERE "
				. "ffi.numero_identificador LIKE ?1 "
				. "AND ffi.mes = ?2 AND ffi.ano = ?3 order by ffi.id asc";

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
