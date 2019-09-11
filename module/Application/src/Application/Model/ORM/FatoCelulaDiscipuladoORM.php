<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;
use DateTime;

class FatoCelulaDiscipuladoORM extends CircuitoORM {

	public function totalAtivosPorNumeroIdentificador($numeroIdentificador, $tipoComparacao = 2) {
		$resultado = null;
		$dqlBase = "SELECT COUNT(fcd.id) total "
				. "FROM  " . Constantes::$ENTITY_FATO_CELULA_DISCIPULADO . " fcd "
				. "WHERE "
				. "fcd.numero_identificador #tipoComparacao ?1 "
				. " AND fcd.data_inativacao IS NULL";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}						
			$resultado = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
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
