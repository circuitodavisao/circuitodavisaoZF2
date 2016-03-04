<?php

namespace Login\Controller;

use Doctrine\ORM\EntityManager;
use Login\Form\LoginForm;
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
     * GET /
     */
    public function indexAction() {

        $formLogin = new LoginForm("LoginForm");
        return [
            'formLogin' => $formLogin,
        ];
    }

    /**
     * Função que tenta logar
     * POST /logar
     */
    public function logarAction() {
        
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}

//        // TESTE DO ORM
//        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
//        $idPessoa = 1;
//        $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);
//        $this->flashMessenger()->addInfoMessage("Pessoa: " . $pessoa->getNome());
//        $formLoginDaRota = $this->params()->fromRoute('formLogin', 0);
//        if (!empty($formLoginDaRota)) {
//            $formLogin = $formLoginDaRota;
//        } else {
//            $formLogin = new LoginForm("LoginForm");
//        }
