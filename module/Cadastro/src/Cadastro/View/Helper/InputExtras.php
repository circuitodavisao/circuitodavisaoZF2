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
            $html .= '</div>';
            /* Dados do Hospedeiro */
            $html .= '<div class="section-divider mv40">';
            $html .= '<span>' . $this->view->translate(ConstantesForm::$TRADUCAO_DADOS_DO_HOSPEDEIRO) . '</span>';
            $html .= '</div>';
            $html .= '<div class="section row">';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<div class="section">';
            $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_NOME_HOSPEDEIRO, $this->getForm(), ConstantesForm::$FORM_NOME_HOSPEDEIRO, ConstantesForm::$FORM_ICONE_NOME_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="section">';
            $html .= '<div class="row">';
            $html .= '<div class="col-xs-5 col-sm-5 col-md-2">';
            $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_DDD_HOSPEDEIRO, $this->getForm(), ConstantesForm::$FORM_DDD_HOSPEDEIRO, ConstantesForm::$FORM_ICONE_DDD_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '<div class="col-xs-7 col-sm-7 col-md-10">';
            $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_TELEFONE_HOSPEDEIRO, $this->getForm(), ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO, ConstantesForm::$FORM_ICONE_TELEFONE_HOSPEDEIRO);
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            if ($this->getForm() instanceof EventoForm) {
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="section">';
                $html .= $this->view->inputFormulario(ConstantesForm::$TRADUCAO_NOME, $this->getForm(), ConstantesForm::$FORM_NOME, ConstantesForm::$FORM_ICONE_NOME_HOSPEDEIRO);
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
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
