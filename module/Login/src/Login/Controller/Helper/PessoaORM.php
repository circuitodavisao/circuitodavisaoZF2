<?php

namespace Login\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Exception;
use Login\Entity\Pessoa;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class PessoaORM {

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
        $this->_entity = Constantes::$ENTITY_PESSOA;
    }

    /**
     * Localizar pessoa por idPessoa
     * 
     * @param integer $idPessoa
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorIdPessoa($idPessoa) {
        $id = (int) $idPessoa;

        $pessoa = $this->getEntityManager()->find($this->getEntity(), $idPessoa);
        if (!$pessoa) {
            throw new Exception("Não foi encontrado a pessoa de id = {$id}");
        }
        return $pessoa;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
