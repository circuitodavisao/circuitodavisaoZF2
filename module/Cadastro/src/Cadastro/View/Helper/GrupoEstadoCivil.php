<?php

namespace Cadastro\View\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: GrupoEstadoCivil.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar radio button para seleção de estado civil dos responsaveis pelo grupo
 */
class GrupoEstadoCivil extends AbstractHelper {

    private $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $labelContinuar = ConstantesForm::$TRADUCAO_CONTINUAR;
        $extraContinuar = $this->view->FuncaoOnClick('validarEstadoCivil()');

        $html .= '<div id="divEstadoCivil">';

        $html .= '<div class="section-divider mt20">';
        $html .= '<span>' . $this->view->translate(ConstantesCadastro::$TRADUCAO_SELECIONE_ESTADO_CIVIL) . '</span>';
        $html .= '</div>';

        $html .= $this->view->translate(ConstantesCadastro::$TRADUCAO_LIDERARA) . ':';

        $html .= '<div class="option-group field mb10">';
        $html .= $this->view->formRadio($this->getForm()->get(ConstantesCadastro::$INPUT_ESTADO_CIVIL));
        $html .= '</div>';


        $html .= '<div id="divBotaoDeProsseguirDoEstadoCivil" class="text-right hidden">';
        $html .= $this->view->botaoLink($labelContinuar, ConstantesForm::$STRING_HASHTAG, 0, $extraContinuar);
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
