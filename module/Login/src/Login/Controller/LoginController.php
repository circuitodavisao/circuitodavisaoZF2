<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Login\Controller\Helper\LoginORM;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */
class LoginController extends AbstractActionController {

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
     * Função padrão, traz a tela para login
     * @return ViewModel
     */
    public function indexAction() {
        $this->flashMessenger()->addInfoMessage("testando a message");

// Helper LoginORM
        $loginORM = new LoginORM($this->_doctrineORMEntityManager);

        $idPessoa = 1;
        $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);

        var_dump($pessoa->getNome());

        return new ViewModel();
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
