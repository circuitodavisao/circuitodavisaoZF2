<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Grupo;
use Application\Model\ORM\CircuitoORM;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Exception;

/**
 * Nome: GrupoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity hierarquia
 */
class GrupoORM extends CircuitoORM {

    /**
     * Localizar todos os grupos
     * @return Grupo[]
     * @throws Exception
     */
    public function encontrarTodos($somenteAtivos = false) {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum grupo");
        }
        if ($somenteAtivos) {
            $entidadesParaVerificar = $entidades;
            unset($entidades);
            foreach ($entidadesParaVerificar as $entidade) {
                if (count($entidade->getResponsabilidadesAtivas()) > 0) {
                    $entidades[] = $entidade;
                }
            }
        }
        return $entidades;
    }

	public function pegarTodasIgrejas(){
        $dqlBase = "SELECT g "
                . "FROM  " . Constantes::$ENTITY_GRUPO . " g "
                . "JOIN g.entidade e "
                . "WHERE "
                . "e.tipo_id = 5 "
                . "AND g.data_inativacao is null "
                . "AND e.data_inativacao is null "
                . "AND g.id != 1 AND g.id != 1225 ";
         try {
            $result = $this->getEntityManager()->createQuery($dqlBase)
                    ->getResult();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
	}

	public function gruposPorParte($qualParte){
		$dqlBase = "SELECT g.id "
			. "FROM  " . Constantes::$ENTITY_GRUPO . " g "
			. "ORDER BY g.id DESC ";
		$totalDivisoes = 50;
		try {
			$totalDeGrupos = $this->getEntityManager()
				->createQuery($dqlBase)
				->setMaxResults(1)
				->getResult()[0]['id'];
			$fracaoParaMontar = $totalDeGrupos/$totalDivisoes;
			$inicio = 1;
			if($qualParte > 1){
				$inicio = $fracaoParaMontar * ($qualParte - 1);
			}

			$dqlBase = "SELECT g "
				. "FROM  " . Constantes::$ENTITY_GRUPO . " g ";
			$grupos = $this->getEntityManager()
				->createQuery($dqlBase)
				->setFirstResult(intval($inicio))
				->setMaxResults(intval($fracaoParaMontar))
				->getResult();

			$gruposAtivos = array();
			foreach ($grupos as $grupo) {
				$gruposAtivos[] = $grupo;
			}
 
			return $gruposAtivos;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function gruposPorParteCem($qualParte){
		$dqlBase = "SELECT g.id "
			. "FROM  " . Constantes::$ENTITY_GRUPO . " g "
			. "ORDER BY g.id DESC ";
		$totalDivisoes = 100;
		try {
			$totalDeGrupos = $this->getEntityManager()
				->createQuery($dqlBase)
				->setMaxResults(1)
				->getResult()[0]['id'];
			$fracaoParaMontar = $totalDeGrupos/$totalDivisoes;
			$inicio = 1;
			if($qualParte > 1){
				$inicio = $fracaoParaMontar * ($qualParte - 1);
			}

			$dqlBase = "SELECT g "
				. "FROM  " . Constantes::$ENTITY_GRUPO . " g ";
			$grupos = $this->getEntityManager()
				->createQuery($dqlBase)
				->setFirstResult($inicio)
				->setMaxResults(number_format($fracaoParaMontar))
				->getResult();

			$gruposAtivos = array();
            foreach ($grupos as $grupo) {
                //if (count($grupo->getResponsabilidadesAtivas()) > 0) {
                    $gruposAtivos[] = $grupo;
                //}
            }
 
			return $gruposAtivos;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

}
