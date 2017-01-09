<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoPopover.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao com popover
 */
class BotaoPopover extends AbstractHelper {

    protected $label;
    protected $texto;

    public function __construct() {
        
    }

    public function __invoke($label, $texto) {
        $this->setLabel($label);
        $this->setTexto($texto);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<button type="button" class="btn btn-danger btn-xs" data-html="true" '
                . 'data-container="body" data-toggle="popover" data-placement="bottom" '
                . 'data-content="' . $this->getTexto() . '">';
        $html .= $this->getLabel();
        $html .= '</button>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getTexto() {
        return $this->texto;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

}
