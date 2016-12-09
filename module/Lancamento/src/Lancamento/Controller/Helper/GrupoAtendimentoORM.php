<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoAtendimento;
use Exception;

/**
 * Nome: GrupoAtendimentoORM.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo atendimento.
 */
class GrupoAtendimentoORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_GRUPO_ATENDIMENTO;
    }

    /**
     * Atualiza o grupo no banco de dados
     * 
     * 
     * @param GrupoAtendimento $grupoAtendimento
     */
    public function persistirGrupoAtendimento($grupoAtendimento) {
        try {
            $grupoAtendimento->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($grupoAtendimento);
            $this->getEntityManager()->flush($grupoAtendimento);
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
