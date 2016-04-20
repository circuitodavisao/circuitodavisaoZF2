<?php

namespace Login\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoSubmit.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao submit com o texto informado
 */
class BotaoSubmit extends AbstractHelper {

    protected $label;

    public function __construct() {
        
    }

    public function __invoke($label) {
        $this->setLabel($label);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<button class="btn ladda-button btn-primary-circuito mr10 pull-right" data-style="zoom-in">'
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

}
