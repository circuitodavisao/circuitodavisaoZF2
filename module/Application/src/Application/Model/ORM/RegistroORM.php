<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: RegistroORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity registro
 */
class RegistroORM extends CircuitoORM {

   public function encontrarUltimoRegistroDeLogin() {
        $dql = "SELECT "
                . " r "
                . "FROM  " . Constantes::$ENTITY_REGISTRO . " r "
                . "WHERE "
                . "r.registro_acao_id = 1 ORDER BY r.id DESC ";
        try {
            $result = $this->getEntityManager()->createQuery($dql)
					->setMaxResults(1)
                    ->getResult();
            return $result[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
}
