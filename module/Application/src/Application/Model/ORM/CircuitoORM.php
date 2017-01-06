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

    /**
     * Localizar entidade por id
     * @param integer $id
     * @return Entidade
     * @throws Exception
     */
    public function encontrarPorId($id) {
        $idInteiro = (int) $id;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a entidade de id = {$idInteiro}");
        }
        return $entidade;
    }

    /**
     * Atualiza a entidade no banco de dados
     * @param $entidade
     */
    public function persistir($entidade) {
        try {
            $entidade->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($entidade);
            $this->getEntityManager()->flush($entidade);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
