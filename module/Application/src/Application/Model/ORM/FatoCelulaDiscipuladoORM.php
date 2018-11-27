<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;
use DateTime;

class FatoCelulaDiscipuladoORM extends CircuitoORM {

	public function totalAtivosPorNumeroIdentificador($numeroIdentificador) {
		$fatos = null;
		try {
			$dql = "SELECT COUNT(fcd.id) total "
				. "FROM  " . Constantes::$ENTITY_FATO_CELULA_DISCIPULADO . " fcd "
				. "WHERE "
				. "fcd.numero_identificador LIKE ?1 AND fcd.data_inativacao IS NULL";

			$numeroAjustado = $numeroIdentificador . '%';
			$resultado = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroAjustado)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $resultado[0]['total'];
	}

    public function encontrarPorGrupoEvento($grupo_evento_id) {
        try {
            $fatoCelulaDiscipulado = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array('grupo_evento_id' => $grupo_evento_id));
            return $fatoCelulaDiscipulado;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
