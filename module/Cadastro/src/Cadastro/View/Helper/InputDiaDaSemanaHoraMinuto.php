<?php

namespace Cadastro\View\Helper;

use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputDiaDaSemanaHoraMinuto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os input do dia da semana, hora e minutos
 */
class InputDiaDaSemanaHoraMinuto extends AbstractHelper {

    protected $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $validacaoNaoCelula = (!$this->getForm() instanceof CelulaForm);
        $validacaoSemId = $this->getForm()->get(ConstantesForm::$FORM_ID)->getValue();
        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-12 col-xs-12">';
        $html .= '<div class="section">';
        $html .= '<label class="field-label">';
        $html .= $this->view->translate(ConstantesForm::$TRADUCAO_DIA_DA_SEMANA);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $elemento = $this->getForm()->get(ConstantesForm::$FORM_DIA_DA_SEMANA);
        if ($validacaoNaoCelula && $validacaoSemId) {
            $html .= $elemento->getValueOptions()[$elemento->getValue()];
            $elemento->setAttribute(ConstantesForm::$FORM_CLASS, ConstantesForm::$FORM_CLASS_GUI_INPUT . ' ' . ConstantesForm::$FORM_HIDDEN);
        }
        $html .= $this->view->formSelect($elemento);
        $html .= '</label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-6 col-xs-6">';
        $html .= '<div class="section">';
        $html .= '<label class="field-label">';
        $html .= $this->view->translate(ConstantesForm::$TRADUCAO_HORA);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $html .= $this->view->formSelect($this->getForm()->get(ConstantesForm::$FORM_HORA));
        $html .= '</label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-6 col-xs-6">';
        $html .= '<div class="section">';
        $html .= '<label class="field-label">';
        $html .= $this->view->translate(ConstantesForm::$TRADUCAO_MINUTOS);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $html .= $this->view->formSelect($this->getForm()->get(ConstantesForm::$FORM_MINUTOS));
        $html .= '</label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
    }

}
