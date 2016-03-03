<?php

namespace Login\Controller\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Nome: LoginORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ORM
 */
class LoginORM {

    private $_doctrineORMEntityManager;

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
     * @return PessoaORM
     */
    public function getPessoaORM() {

        // return Entity PessoaORM
        return new PessoaORM($this->getDoctrineORMEntityManager());
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
