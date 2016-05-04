<?php

namespace Lancamento\Controller\Helper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Exception;
use Entidade\Entity\Entidade;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class EntidadeORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_ENTIDADE;
    }

    /**
     * Localizar entidade por idEntidade
     * 
     * @param integer $idEntidade
     * @return Entidade
     * @throws Exception
     */
    public function encontrarPorIdEntidade($idEntidade) {
        $id = (int) $idEntidade;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a pessoa de id = {$id}");
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
