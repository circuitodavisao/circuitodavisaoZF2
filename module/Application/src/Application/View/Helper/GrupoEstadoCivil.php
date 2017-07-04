<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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

        $labelContinuar = Constantes::$TRADUCAO_CONTINUAR;
        $extraContinuar = $this->view->FuncaoOnClick('validarEstadoCivil()');

        $html .= '<div id="divEstadoCivil">';

        $html .= '<div class="section-divider mt20">';
        $html .= '<span>' . $this->view->translate(Constantes::$TRADUCAO_SELECIONE_ESTADO_CIVIL) . '</span>';
        $html .= '</div>';

        $html .= $this->view->translate(Constantes::$TRADUCAO_LIDERARA) . ':';

        $html .= '<div class="option-group field mb10">';
        $html .= $this->view->formRadio($this->getForm()->get(Constantes::$INPUT_ESTADO_CIVIL));
        $html .= '</div>';


        $html .= '<div id="divBotaoDeProsseguirDoEstadoCivil" class="text-right hidden">';
        $html .= $this->view->botaoLink($labelContinuar, Constantes::$STRING_HASHTAG, 0, $extraContinuar);
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
