<?php

namespace Cadastro\Controller;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Login\Controller\Helper\Constantes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class CadastroController extends AbstractActionController {

    private $_doctrineORMEntityManager;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /cadastro[:pagina[/:id]]
     */
    public function indexAction() {

        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesLancamento::$PAGINA, 1);
        if ($pagina == ConstantesCadastro::$PAGINA_CELULAS) {

            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULAS,
            ));
        }

        return new ViewModel();
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelulas
     */
    public function celulasAction() {

        return new ViewModel();
    }

}
