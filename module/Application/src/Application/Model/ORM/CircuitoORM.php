<?php

namespace Application\Model\ORM;

use Doctrine\ORM\EntityManager;

/**
 * Nome: CircuitoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class CircuitoORM {

    private $_entityManager;
    private $_entity;

    /**
     * Construtor Sobrecarregado
     * @param EntityManager $entityManager
     * @param type $entity
     */
    public function __construct(EntityManager $entityManager = null, $entity = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        if (!is_null($entity)) {
            $this->_entity = $entity;
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
