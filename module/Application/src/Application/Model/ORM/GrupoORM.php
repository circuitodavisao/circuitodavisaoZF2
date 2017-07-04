<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Grupo;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: GrupoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity hierarquia
 */
class GrupoORM extends CircuitoORM {

    /**
     * Localizar todos os grupos
     * @return Grupo[]
     * @throws Exception
     */
    public function encontrarTodos() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum grupo");
        }
        return $entidades;
    }

}
