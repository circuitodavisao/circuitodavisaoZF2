<?php

namespace Lancamento\Controller;

use Doctrine\ORM\EntityManager;
use Login\Controller\Helper\LoginORM;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;

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
        /* Helper Controller */
        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
        $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa(1);
        echo "Nome: " . $pessoa->getNome() . "<br />";
        echo "Grupos { <br />";
        foreach ($pessoa->getResponsabilidadesAtivas() as $gr) {
            $grupo = $gr->getGrupo();
            echo "==== Entidade Nome: " . $grupo->getEntidadeAtiva()->getEntidadeTipo()->getNome() . "<br />";
            echo "==== Entidade Info: " . $grupo->getEntidadeAtiva()->infoEntidade() . "<br />";
            echo "==== | ==== Total de Eventos: " . count($grupo->getGrupoEvento()) . " <br />";
            echo "==== | ==== Eventos { <br />";
            if (count($grupo->getGrupoEvento()) > 0) {
                foreach ($grupo->getGrupoEvento() as $ge) {
                    echo "==== | ==== | ==== Nome Tipo Evento " . $ge->getEvento()->getEventoTipo()->getNome() . "<br />";
                    echo "==== | ==== | ==== Dia " . $ge->getEvento()->getDia() . "<br />";
                    echo "==== | ==== | ==== Hora " . $ge->getEvento()->getHora() . "<br />";
                    if ($ge->getEvento()->getEventoTipo()->getId() == 1) {// celula
//                        $celula = $ge->getEvento()->getEventoCelula();
//                        echo "==== | ==== | ==== | ==== " . $celula->getNome_hospedeiro() . "<br />";
//                        echo "==== | ==== | ==== | ==== " . $celula->getTelefone_hospedeiro() . "<br />";
//                        echo "==== | ==== | ==== | ==== " . $celula->getLogradouro() . "<br />";
//                        echo "==== | ==== | ==== | ==== " . $celula->getComplemento() . "<br />";
                    }
                }
            }
            echo "==== | ==== } <br />";
        }
        echo "} <br />";
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
