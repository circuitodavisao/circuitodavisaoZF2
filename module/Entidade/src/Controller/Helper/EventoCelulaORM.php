<?php

namespace Entidade\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\EventoCelula;
use Exception;

/**
 * Nome: EventoCelulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity entidade_celula
 */
class EventoCelulaORM {

    private $_entityManager;
    private $_entity;

    /**
     * Construtor
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesEntidade::$ENTITY_EVENTO_CELULA;
    }

    /**
     * Localizar entidade por $idEventoCelula
     * 
     * @param integer $idEventoCelula
     * @return EventoCelula
     * @throws Exception
     */
    public function encontrarPorIdEventoCelula($idEventoCelula) {
        $id = (int) $idEventoCelula;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado celula de id = {$id}");
        }
        return $entidade;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
