<?php

/**
 * Nome: LoginController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do login
 */

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController {

    /**
     * Função padrão, traz a tela para login
     * @return ViewModel
     */
    public function indexAction() {
        $this->flashMessenger()->addInfoMessage("testando a message");
//        $objectManager = $this->getDoctrineORM();
        return new ViewModel();
    }

    /**
     * Metodo privado para obter a instancia do Doctrine\ORM\EntityManager
     * @return type
     */
    private function getDoctrineORM() {
        //obter instancia Doctrine\ORM\EntityManager 
        $objectManager = $this->getServiceLocator()->get('DoctrineORMEntityManager');
        // return model GrupoDao
        return $objectManager;
    }

}
