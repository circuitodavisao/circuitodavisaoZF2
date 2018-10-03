<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;

class EleitorORM extends CircuitoORM {

	public function encontrarPorBloco($bloco) {
		$entidades = null;
		try {
			$dql = "SELECT e "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e ORDER BY e.id ASC ";

			$offset = $bloco * 2000;
			$entidades = $this->getEntityManager()->createQuery($dql)
				->setMaxResults(2000)
				->setFirstResult($offset)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $entidades;
	}

	public function totalDeRegistros(){
		$dql = " SELECT count(e.id) FROM ". Constantes::$ENTITY_ELEITOR . " e ";
		$resultado = $this->getEntityManager()->createQuery($dql)
			->getResult();
		return $resultado[0][1];
	}

}
