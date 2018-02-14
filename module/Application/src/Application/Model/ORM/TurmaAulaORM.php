<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TurmaAulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_aula
 */
class TurmaAulaORM extends CircuitoORM {

    public function encontrarTodosPorTurmaEData($idTurma, $data) {
        $dql = "SELECT ta.id "
                . "FROM  " . Constantes::$ENTITY_TURMA_AULA . " ta "
                . "WHERE "
                . "ta.turma_id = ?1 AND ta.data_criacao <= ?2 AND ta.data_inativacao IS NULL";
        try {
            $result = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $idTurma)
                    ->setParameter(2, $data)
                    ->getResult();

            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
