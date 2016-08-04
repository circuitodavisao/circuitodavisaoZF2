<?php

namespace Login\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoLink.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao com link
 */
class BotaoLink extends AbstractHelper {

    protected $link;
    protected $label;
    protected $tipo;
    protected $extra;

    public function __construct() {
        
    }

    public function __invoke($label, $link, $tipo = 0, $extra = '') {
        $this->setLabel($label);
        $this->setLink($link);
        $this->setTipo($tipo);
        $this->setExtra($extra);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $classCor = 'primary-circuito';
        if ($this->getTipo() == 2) {// tipo de menor importancia
            $classCor = 'primary-default dark';
        }
        $html = '';
        $html .= '<button type="button" ' . $this->getExtra() . ' onclick=\'location.href="' . $this->getLink() . '";\' class="btn ladda-button btn-' . $classCor . ' mr10" data-style="zoom-in">'
                . '<span class="ladda-label">' . $this->view->translate($this->getLabel()) . '</span>'
                . '</button>';
        return $html;
    }

    function getLink() {
        return $this->link;
    }

    function setLink($link) {
        $this->link = $link;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getExtra() {
        return $this->extra;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

}
