<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\View\Model\ViewModel;

/**
 * Nome: IndexController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de migração
 */
class IndexController extends CircuitoController {

    private $conexao;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /
     */
    public function indexAction() {
        $view = new ViewModel();
        echo "abrindo a conexao";
        $this->abreConexao();
        return $view;
    }

    private function abreConexao() {
        try {
            if (empty($this->getConexao())) {
                $this->setConexao(mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_visao', '3306'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    function getConexao() {
        return $this->conexao;
    }

    function setConexao($conexao) {
        $this->conexao = $conexao;
        return $this;
    }

}
