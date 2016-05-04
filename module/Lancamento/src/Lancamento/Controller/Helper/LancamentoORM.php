<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Nome: LancamentoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ORM
 */
class LancamentoORM {

    private $_doctrineORMEntityManager;
    private $_entidadeORM;

    /** 
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do Helper PessoaORM
     * @return EntidadeORM
     */
    public function getEntidadeORM() {
        if (is_null($this->_entidadeORM)) {
            $this->_entidadeORM = new EntidadeORM($this->getDoctrineORMEntityManager());
        }
        return $this->_entidadeORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
