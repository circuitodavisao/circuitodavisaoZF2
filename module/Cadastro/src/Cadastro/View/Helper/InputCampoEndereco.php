<?php

namespace Cadastro\View\Helper;

use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputCampoEndereco.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um input com labels
 */
class InputCampoEndereco extends AbstractHelper {

    protected $traducao;
    protected $form;
    protected $idInput;
    protected $desabilitado;

    public function __construct() {
        
    }

    public function __invoke($traducao, $form, $idInput, $desabilitado = 0) {
        $this->setTraducao($traducao);
        $this->setForm($form);
        $this->setIdInput($idInput);
        $this->setDesabilitado($desabilitado);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $input = $this->getForm()->get($this->getIdInput());
        if ($this->getDesabilitado() == 1) {
            $valor = '';
            if (!empty($input->getValue())) {
                $valor = $input->getValue();
            }
            $html .= '<input type="hidden" id="hidden' . $this->getIdInput() . '" name="hidden' . $this->getIdInput() . '" value="' . $valor . '"/>';
        }
        if ($this->getIdInput() != ConstantesForm::$FORM_CPF) {
            $html .= '<label for=' . $this->getTraducao() . ' class="field-label">';
            $html .= $this->view->translate($this->getTraducao());
            if ($this->getIdInput() == ConstantesForm::$FORM_CEP_LOGRADOURO) {
                $html .= $this->view->translate(ConstantesForm::$TRADUCAO_CEP_LOGRADOURO_SITE_CORREIOS);
            }
            $html .= '</label>';
        }
        $html .= '<label for="' . $this->getTraducao() . '" class="field">';

        /* Desabilitar */
        if ($this->getDesabilitado() == 1) {
            $input->setAttribute(ConstantesForm::$FORM_DISABLED, ConstantesForm::$FORM_DISABLED);
        }
        $html .= $this->view->formInput($input);

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

    function setTraducao($traducao) {
        $this->traducao = $traducao;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setIdInput($idInput) {
        $this->idInput = $idInput;
    }

    function getDesabilitado() {
        return $this->desabilitado;
    }

    function setDesabilitado($desabilitado) {
        $this->desabilitado = $desabilitado;
        return $this;
    }

}
