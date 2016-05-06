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
    private $_grupoORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do Helper EntidadeORM
     * @return EntidadeORM
     */
    public function getEntidadeORM() {
        if (is_null($this->_entidadeORM)) {
            $this->_entidadeORM = new EntidadeORM($this->getDoctrineORMEntityManager());
        }
        return $this->_entidadeORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoORM
     * @return GrupoORM
     */
    public function getGrupoORM() {
        if (is_null($this->_grupoORM)) {
            $this->_grupoORM = new GrupoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
