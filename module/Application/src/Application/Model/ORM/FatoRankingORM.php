<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: FatoRankingORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ranking
 */
class FatoRankingORM extends CircuitoORM {

    public function apagarTodos() {
        $dql = "DELETE "
                . Constantes::$ENTITY_FATO_RANKING . " fr "
                . "WHERE "
                . "fr.id > 0";
        try {
            $result = $this->getEntityManager()->createQuery($dql)->getResult();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    const RANKING_MEMBRESIA = 1;
    const RANKING_CELULA = 2;

    public function encontrarPorRankingETipo($ranking, $tipo) {
        if ($tipo === FatoRankingORM::RANKING_MEMBRESIA) {
            $campo = 'ranking_membresia';
        }
        if ($tipo === FatoRankingORM::RANKING_CELULA) {
            $campo = 'ranking_celula';
        }
        $entidades = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->findOneBy(array($campo => $ranking));
        if (!$entidades) {
            return false;
        }
        return $entidades;
    }

}
