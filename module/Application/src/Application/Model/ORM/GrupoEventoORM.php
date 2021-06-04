<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Doctrine\Common\Collections\Criteria;
use Exception;
use DateTime;

/**
 * Nome: GrupoEventoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_evento
 */
class GrupoEventoORM extends CircuitoORM {

	public function discipuladosParaAjustar() {
		$dql = "SELECT "
			. " ge.id, ge.grupo_id "
			. "FROM  " . Constantes::$ENTITY_GRUPO_EVENTO . " ge "
			. "JOIN ge.evento e "
			. "WHERE "
			. "ge.evento_id = e.id AND "
			. "e.tipo_id = 4 AND "
			. "(ge.data_criacao = '2019-11-11' OR "
			. " ge.data_criacao = '2019-11-18' OR "
			. " ge.data_criacao = '2019-11-25')";
		try {
			return $this->getEntityManager()->createQuery($dql)->getResult();
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function pegarCultosPorGrupoId($idGrupo) {
		$dql = "SELECT "
			. " ge "
			. "FROM  " . Constantes::$ENTITY_GRUPO_EVENTO . " ge "
			. "JOIN ge.evento e "
			. "WHERE "
			. "ge.evento_id = e.id AND "
			. "e.tipo_id = 2 AND ge.grupo_id = ?1 ";
		try {
			return $this->getEntityManager()->createQuery($dql)  
								   ->setParameter(1, $idGrupo)
								   ->getResult();
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

}
