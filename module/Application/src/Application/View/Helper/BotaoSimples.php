<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoSimples.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao
 */
class BotaoSimples extends AbstractHelper {

    private $label;
    private $extra;

    public function __construct() {
        
    }

    public function __invoke($label, $extra = '') {
        $this->setLabel($label);
        $this->setExtra($extra);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $classCor = Constantes::$COR_BOTAO;
        $html .= '<button type="button" ' . $this->getExtra() . ' class="btn ladda-button btn-' . $classCor . ' pull-right" data-style="zoom-in">';
        $html .= '<span class="ladda-label">';
        $html .= $this->view->translate($this->getLabel());
        $html .= '</span>';
        $html .= '</button>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getExtra() {
        return $this->extra;
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function setExtra($extra) {
        $this->extra = $extra;
        return $this;
    }

}
