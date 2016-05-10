<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\EventoFrequencia;
use Exception;

/**
 * Nome: EventoFrequenciaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity EventoFrequencia
 */
class EventoFrequenciaORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_EVENTO_FREQUENCIA;
    }

    /**
     * Atualiza a evento_frequencia no banco de dados
     * 
     * @param EventoFrequencia $eventoFrequencia
     */
    public function persistirSemDispacharEventoFrequencia(EventoFrequencia $eventoFrequencia) {
        $eventoFrequencia->setDataEHoraCriacao();
        try {
            $this->getEntityManager()->persist($eventoFrequencia);
            $this->getEntityManager()->flush($eventoFrequencia);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
