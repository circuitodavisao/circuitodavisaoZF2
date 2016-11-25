<?php

namespace Login\View\Helper;

use Login\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoSubmitDesabilitado.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao submit com o texto informado desabilitado e com id
 */
class BotaoSubmitDesabilitado extends AbstractHelper {

    protected $label;
    protected $id;
    protected $extra;
    protected $corBotao;

    public function __construct() {
        
    }

    public function __invoke($label, $id, $extra = '', $corBotao = '') {
        $this->setLabel($label);
        $this->setId($id);
        $this->setExtra($extra);
        $this->setCorBotao($corBotao);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $corBotao = 'btn-' . Constantes::$COR_BOTAO;
        if ($this->getCorBotao() != '') {
            $corBotao = $this->getCorBotao();
        }
        $html .= '<button ' . $this->getExtra() . ' id="' . $this->getId() . '" name="' . $this->getId() . '" class="btn ladda-button ' . $corBotao . ' mr10 pull-right" data-style="zoom-in" disabled>'
                . '<span class="ladda-label">' . $this->view->translate($this->getLabel()) . '</span>'
                . '</button>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getExtra() {
        return $this->extra;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

    function getCorBotao() {
        return $this->corBotao;
    }

    function setCorBotao($corBotao) {
        $this->corBotao = $corBotao;
    }

}
