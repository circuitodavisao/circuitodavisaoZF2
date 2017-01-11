<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputFormulario.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um input com labels
 */
class InputFormularioSimples extends AbstractHelper {

    private $label;
    private $input;
    private $tamanhoGrid;

    public function __construct() {
        
    }

    public function __invoke($label = '', $input = '', $tamanhoGrid = null) {
        $this->setLabel($label);
        $this->setInput($input);
        $this->setTamanhoGrid($tamanhoGrid);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tamanhoGrid = 12;
        if ($this->getTamanhoGrid()) {
            $tamanhoGrid = $this->getTamanhoGrid();
        }
        $html .= '<div class="form-group col-lg-' . $tamanhoGrid . '">';
        $html .= '<label class="field-label text-muted fs18 mb10">' . $this->view->translate($this->getLabel()) . '</label>';
        $html .= $this->view->formInput($this->getInput());
        $html .= '</div>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getInput() {
        return $this->input;
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function setInput($input) {
        $this->input = $input;
        return $this;
    }

    function getTamanhoGrid() {
        return $this->tamanhoGrid;
    }

    function setTamanhoGrid($tamanhoGrid) {
        $this->tamanhoGrid = $tamanhoGrid;
        return $this;
    }

}
