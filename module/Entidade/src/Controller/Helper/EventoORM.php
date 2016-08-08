<?php

namespace Entidade\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\Evento;
use Exception;
use Lancamento\Controller\Helper\ConstantesLancamento;

/**
 * Nome: EventoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity Evento
 */
class EventoORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_EVENTO;
    }

    /**
     * Localizar pessoa por idEvento
     * 
     * @param integer $idEvento
     * @return Evento
     * @throws Exception
     */
    public function encontrarPorIdEvento($idEvento) {
        $id = (int) $idEvento;

        $evento = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$evento) {
            throw new Exception("Não foi encontrado ao evento de id = {$id}");
        }
        return $evento;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
