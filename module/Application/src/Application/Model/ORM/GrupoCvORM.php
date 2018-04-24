<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Grupo;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: GrupoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_cv
 */
class GrupoCvORM extends CircuitoORM {

    public function encontrarLider1($lider1) {
        try {
            $entidade = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array('lider1' => $lider1));
            return $entidade;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
