<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;

class FatoParceiroDeDeusORM extends CircuitoORM {

	public function encontrarFatosPorNumeroIdentificador($numeroIdentificador) {
		$fatos = null;
		try {
			$dql = "SELECT fpd "
				. "FROM  " . Constantes::$ENTITY_FATO_PARCEIRO_DE_DEUS . " fpd "
				. "WHERE "
				. "fpd.numero_identificador LIKE ?1 ";

			$numeroAjustado = $numeroIdentificador . '%';
			$fatos = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroAjustado)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $fatos;
	}

}
