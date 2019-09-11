<?php

namespace Application\Model\ORM;

use Application\Model\Entity\TrocaResponsavel;
use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TrocaResponsavelORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_cv
 */
class TrocaResponsavelORM extends CircuitoORM {

    public function encontrarTrocasDeResponsavelAtivasEPendentes(){
		$dql = "SELECT "
			. " t "
			. "FROM  " . Constantes::$ENTITY_TROCA_RESPONSAVEL . " t "
			. "WHERE "
            . "t.situacao = 'P' "
            . "AND t.data_inativacao is null ";
		try {
			$result = $this->getEntityManager()->createQuery($dql)				
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
        }
	}
	
	public function encontrarTodosPorDataDeCriacao($dataDeCriacaoInicial, $dataDeCriacaoFinal, $regiao_id = null) {
        $dql = "SELECT "
                . " t "
                . "FROM  " . Constantes::$ENTITY_TROCA_RESPONSAVEL . " t "
                . "WHERE "
                . "t.data_criacao >= ?1 AND t.data_criacao <= ?2 #regiao_id";
        try {
			if ($regiao_id) {
				$dqlAjustada = str_replace('#regiao_id', 'AND t.regiao_id = '.(int)$regiao_id, $dql);
			} else {
				$dqlAjustada = str_replace('#regiao_id', ' ', $dql);
			}                       
            $result = $this->getEntityManager()->createQuery($dqlAjustada)
                    ->setParameter(1, $dataDeCriacaoInicial)
                    ->setParameter(2, $dataDeCriacaoFinal)
                    ->getResult();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
