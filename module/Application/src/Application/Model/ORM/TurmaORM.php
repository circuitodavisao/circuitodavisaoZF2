<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Turma;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TurmaORM.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma
 */
class TurmaORM extends CircuitoORM {

    /**
     * Localizar todas as turmas
     * @return Turma[]
     * @throws Exception
     */
    public function encontrarTodas() { 
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhuma turma"); 
        }
        return $entidades;
    }

}
