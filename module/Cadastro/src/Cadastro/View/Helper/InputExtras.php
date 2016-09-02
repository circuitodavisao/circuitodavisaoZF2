<?php

namespace Cadastro\View\Helper;

use Cadastro\Form\CelulaForm;
use Cadastro\Form\ConstantesForm;
use Cadastro\Form\EventoForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputExtras.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os inputs extras do formulário de eventos
 */
class InputExtras extends AbstractHelper {

    protected $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        if ($this->getForm() instanceof CelulaForm) {
            $html .= '<div class="row">';
            $html .= '<div class="col-sm-12 col-xs-12">';
            $html .= '<div class="section">';
            $html .= '<label class="field-label">';
            $html .= $this->view->translate(ConstantesForm::$TRADUCAO_DIA_DA_SEMANA);
            $html .= '</label>';
            $html .= '<label class="field prepend-icon">';
            $html .= $this->view->formSelect($this->getForm()->get(ConstantesForm::$FORM_DIA_DA_SEMANA));
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
        }
        if ($this->getForm() instanceof EventoForm) {
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $html .= '<div class="section">';
            $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_NOME, $this->getForm(), ConstantesForm::$FORM_NOME, ConstantesForm::$FORM_ICONE_NOME_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
    }

}
