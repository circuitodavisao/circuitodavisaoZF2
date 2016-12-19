<?php

namespace Cadastro\View\Helper;

use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: MontarEndereco.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar o endereço e busca
 */
class MontarEndereco extends AbstractHelper {

    private $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12 col-sm-12 col-xs-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_CEP_LOGRADOURO, $this->getForm(), ConstantesForm::$FORM_CEP_LOGRADOURO, ConstantesForm::$FORM_ICONE_COMPLEMENTO);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="endereco" class="' . $this->view->enderecoHidden . '">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-6 col-sm-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_UF, $this->getForm(), ConstantesForm::$FORM_UF, ConstantesForm::$FORM_ICONE_COMPLEMENTO, 2);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-6 col-sm-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_BAIRRO, $this->getForm(), ConstantesForm::$FORM_BAIRRO, ConstantesForm::$FORM_ICONE_COMPLEMENTO, 2);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_CIDADE, $this->getForm(), ConstantesForm::$FORM_CIDADE, ConstantesForm::$FORM_ICONE_COMPLEMENTO, 2);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_LOGRADOURO, $this->getForm(), ConstantesForm::$FORM_LOGRADOURO, ConstantesForm::$FORM_ICONE_COMPLEMENTO, 2);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_COMPLEMENTO, $this->getForm(), ConstantesForm::$FORM_COMPLEMENTO, ConstantesForm::$FORM_ICONE_COMPLEMENTO);
        $html .= '</div>';
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
