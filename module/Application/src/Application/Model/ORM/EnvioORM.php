<?php

namespace Application\Model\ORM;

use Application\Model\ORM\CircuitoORM;
use Application\Controller\Helper\Constantes;

class EnvioORM extends CircuitoORM {

	public function encontrarPendentes() {
		$dql = "SELECT "
			. "e "
			. "FROM  " . Constantes::$ENTITY_ENVIO . " e "
			. "WHERE "
			. "e.status = 1";

		try {
			$result = $this->getEntityManager()->createQuery($dql)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}
}
