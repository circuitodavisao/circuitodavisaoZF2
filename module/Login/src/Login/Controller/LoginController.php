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

        return new ViewModel();
    }

}
