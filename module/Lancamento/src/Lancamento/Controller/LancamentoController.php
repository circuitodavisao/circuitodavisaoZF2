<?php

namespace Lancamento\Controller;

use Doctrine\ORM\EntityManager;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;
use Login\Controller\Helper\Constantes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Container;
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
        /* Helper Controller */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

        /* Aba selecionada */
        $abaSelecionada = $this->params()->fromRoute(Constantes::$ID);
        if (empty($abaSelecionada)) {
            $abaSelecionada = 1;
        }

        /* Teste de alteracao de envio */
        $grupo = $entidade->getGrupo();
        $resposta = $grupo->verificarSeFoiEnviadoORelatorio();
        if ($resposta) {
            $resposta = 'Enviado';
        } else {
            $resposta = 'Nao Enviado';
        }
        echo "<br />Grupo ";
        echo "<br />Verificar Status: " . $resposta;
        echo "<br />Data envio: " . $grupo->getEnvio_data();
        echo "<br />Hora envio: " . $grupo->getEnvio_hora();

        $view = new ViewModel(array(
            ConstantesLancamento::$ENTIDADE => $entidade,
            ConstantesLancamento::$ABA_SELECIONADA => $abaSelecionada,)
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesLancamento::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, ConstantesLancamento::$STRING_JS_LANCAMENTO);

        return $view;
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
