<?php

namespace Lancamento\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\ViewModel;

/**
 * Nome: LancamentoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class LancamentoController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $_translator;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, Translator $translator = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }

        if (!is_null($translator)) {
            $this->_translator = $translator;
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /lancamento
     */
    public function indexAction() {

        return [];
    }

    /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Recupera translator
     * @return translator
     */
    public function getTranslator() {
        return $this->_translator;
    }

}
