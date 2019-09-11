<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: ResolucaoResponsabilidadeORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity ResolucaoResponsabilidadeORM
 */
class ResolucaoResponsabilidadeORM extends CircuitoORM {

    public function encontrarResolucaoResponsabilidadePorGrupo($grupo_id){
		$dql = "SELECT "
			. " r "
			. "FROM  " . Constantes::$ENTITY_RESOLUCAO_RESPONSABILIDADE . " r "
			. "WHERE "
            . "r.grupo_id = ?1 "
            . "AND r.data_inativacao is null ";
		try {
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $grupo_id)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
        }

	}

}
