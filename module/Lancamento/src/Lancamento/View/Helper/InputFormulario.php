<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputFormulario.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um input com labels
 */
class InputFormulario extends AbstractHelper {

    protected $traducao;
    protected $form;
    protected $idInput;
    protected $icone;
    protected $tipo;

    public function __construct() {
        
    }

    public function __invoke($traducao, $form, $idInput, $icone, $tipo = 0) {
        $this->setTraducao($traducao);
        $this->setForm($form);
        $this->setIdInput($idInput);
        $this->setIcone($icone);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<label for=' . $this->getTraducao() . ' class="field-label">';
        $html .= $this->view->translate($this->getTraducao());
        $html .= '</label>';
        if ($this->getTipo() == 0) {
            $html .= '<label for="' . $this->getTraducao() . '" class="field prepend-icon">';
        }
        if ($this->getTipo() == 1) {
            $html .= '<div class="smart-widget sm-right smr-50">';
            $html .= '<label class="field">';
        }

        $html .= $this->view->formInput($this->getForm()->get($this->getIdInput()));

        if ($this->getTipo() == 0) {
            $html .= '<label for="' . $this->getTraducao() . '" class="field-icon">';
            $html .= '<i class="fa ' . $this->getIcone() . '"></i>';
            $html .= '</label>';
        }
        if ($this->getTipo() == 1) {

            $html .= '</label>';
            $html .= '<button type="button" class="button btn-danger">';
            $html .= '<i class="fa fa-search"></i>';
            $html .= '</button>';
            $html .= '</div>';
        }
        return $html;
    }

    function getTraducao() {
        return $this->traducao;
    }

    function getForm() {
        return $this->form;
    }

    function getIdInput() {
        return $this->idInput;
    }

    function getIcone() {
        return $this->icone;
    }

    function setTraducao($traducao) {
        $this->traducao = $traducao;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setIdInput($idInput) {
        $this->idInput = $idInput;
    }

    function setIcone($icone) {
        $this->icone = $icone;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
