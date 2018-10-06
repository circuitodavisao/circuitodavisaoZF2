<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;

class EleitorORM extends CircuitoORM {

	public function encontrarPorBloco($bloco) {
		$entidades = null;
		try {
			$dql = "SELECT e.id, e.telefone, e.situacao, e.lista "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e ORDER BY e.id ASC ";

			$offset = $bloco * 500;
			$entidades = $this->getEntityManager()->createQuery($dql)
				->setMaxResults(500)
				->setFirstResult($offset)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $entidades;
	}

	public function encontrarPorLista($lista) {
		$entidades = null;
		try {
			$dql = "SELECT e "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e WHERE e.lista = ?1 ORDER BY e.id ASC ";

			$entidades = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $lista)
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

	public function relatorioDeEnvio() {
		$resultado = null;
		try {
			$dql = "SELECT e.lista, e.situacao, COUNT(e.id) valor "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e GROUP BY e.lista, e.situacao";
			$resultado = $this->getEntityManager()->createQuery($dql)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $resultado;
	}



}
