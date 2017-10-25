<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Disciplina;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: DisciplinaORM.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe com acesso doctrine a entity disciplina
 */
class DisciplinaORM extends CircuitoORM {

    /**
     * Localizar todas as disciplinas
     * @return Disciplina[]
     * @throws Exception
     */
    public function encontrarTodas() { 
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findBy(array(), array("posicao" => "ASC"));
        if (!$entidades) { 
            return false;
        }
        return $entidades;
    }
    
    /**
     * Localizar todas as disciplinas por curso
     * @return Disciplina[]
     * @throws Exception
     */
    public function encontrarTodasPorIdCurso($idCurso) { 
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findBy(array('curso_id' => (int)$idCurso));
        if (!$entidades) {
            return false;
        }
        return $entidades;
    }
    
    

}
