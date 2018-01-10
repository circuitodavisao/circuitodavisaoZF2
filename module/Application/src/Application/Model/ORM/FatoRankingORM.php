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

}
