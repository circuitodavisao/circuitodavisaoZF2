<?php

namespace Application\Model\ORM;

use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: HierarquiaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity hierarquia
 */
class HierarquiaORM extends CircuitoORM {

    /**
     * Localizar todos as hierarquias
     * @return Hieraquia[]
     * @throws Exception
     */
    public function encontrarTodas() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum hierarquia");
        }
        return $entidades;
    }

}
