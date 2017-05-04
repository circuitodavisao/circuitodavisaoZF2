<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;

/**
 * Nome: InstitutoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações do instituto de vencedores
 */
class InstitutoController extends CircuitoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela principal
     * GET /instituto
     */
    public function indexAction() {
        
    }

}
