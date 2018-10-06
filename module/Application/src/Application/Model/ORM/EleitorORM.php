<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;

class EleitorORM extends CircuitoORM {

	public function encontrarPorBloco($bloco, $lista = 2) {
		$entidades = null;
		try {
			$dql = "SELECT e.id, e.telefone, e.situacao, e.lista "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e WHERE e.lista = ?1 ORDER BY e.id ASC ";

			$offset = $bloco * 100;
			$entidades = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $lista)
				->setMaxResults(100)
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
			$dql = "SELECT e.id, e.telefone "
				. "FROM  " . Constantes::$ENTITY_ELEITOR . " e WHERE e.lista = ?1 ORDER BY e.id ASC ";

			$entidades = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $lista)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $entidades;
	}

	public function totalDeRegistros($lista = 2){
		$dql = " SELECT count(e.id) FROM ". Constantes::$ENTITY_ELEITOR . " e WHERE e.lista = ?1 ";
		$resultado = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $lista)
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
