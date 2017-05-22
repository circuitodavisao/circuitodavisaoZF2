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
    private $tipoInput;

    public function __construct() {
        
    }

    /**
     * Monta um input na tela
     * TipoInput 1: text
     * TipoInput 2: select
     * TipoInput 3: radio
     * @param string $label
     * @param Element $input
     * @param int $tamanhoGrid
     * @param int $tipoInput
     * @return string
     */
    public function __invoke($label = '', $input = '', $tamanhoGrid = null, $tipoInput = 1) {
        $this->setLabel($label);
        $this->setInput($input);
        $this->setTamanhoGrid($tamanhoGrid);
        $this->setTipoInput($tipoInput);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tamanhoGrid = 12;
        if ($this->getTamanhoGrid()) {
            $tamanhoGrid = $this->getTamanhoGrid();
        }
        $html .= '<div class="form-group col-lg-' . $tamanhoGrid . '">';
        if($this->getLabel() != -1){
            $html .= '<label class="field-label text-muted fs18 mb10">' . $this->view->translate($this->getLabel()) . '</label>';
        }
        if ($this->getTipoInput() === 1) {
            $html .= $this->view->formInput($this->getInput());
        }
        if ($this->getTipoInput() === 2) {
            $html .= $this->view->formSelect($this->getInput());
        }
        if ($this->getTipoInput() === 3) {
            $html .= $this->view->formRadio($this->getInput());
        }
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

    function getTipoInput() {
        return $this->tipoInput;
    }

    function setTipoInput($tipoInput) {
        $this->tipoInput = $tipoInput;
        return $this;
    }

}
