<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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
        $html = '';
        $classCor = Constantes::$COR_BOTAO;
        $margenRight = 'mr10';
        $float = '';
        if ($this->getTipo() == 2 || $this->getTipo() == 3 || $this->getTipo() == 6 || $this->getTipo() == 8) {// tipo de menor importancia
            $classCor = 'default dark';
        }
        $tamanho = '';
        if ($this->getTipo() == 3 || $this->getTipo() == 4 || $this->getTipo() == 5 || $this->getTipo() == 6) {// tamanho extra pequeno
            $tamanho = 'btn-xs';
            $margenRight = 'mr5';
        }

        if ($this->getTipo() == 5 || $this->getTipo() == 6 || $this->getTipo() == 7 || $this->getTipo() == 8) {// float direita
            $float = 'style="float: right;"';
        }
        $html .= '<button type="button" ' . $this->getExtra() . ' ' . $float . ' onclick=\'location.href="' . $this->getLink() . '";\' class="btn ladda-button btn-' . $classCor . ' ' . $tamanho . ' ' . $margenRight . '" data-style="zoom-in">'
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
