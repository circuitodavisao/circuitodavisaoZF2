<?php

namespace Cadastro\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TituloDaPagina.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o titulo da página
 */
class TituloDaPagina extends AbstractHelper {

    protected $label;

    public function __construct() {
        
    }

    public function __invoke($label) {
        $this->setLabel($label);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<blockquote class = "blockquote-danger">';
        $html .= '<small>';
        $html .= $this->view->translate($this->getLabel());
        $html .= '</small>';
        $html .= '</blockquote>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

}
