<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Form\CelulaForm;
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
        $validacaoSemId = $this->getForm()->get(Constantes::$FORM_ID)->getValue();
		$ehDiscipulado = false;
		if($this->view->extra == 'discipulado'){
			$ehDiscipulado = true;
		}
        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-12 col-xs-12">';
        $html .= '<div class="section">';
        $html .= '<label class="field-label">';
        $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $elemento = $this->getForm()->get(Constantes::$FORM_DIA_DA_SEMANA);
        if ($validacaoNaoCelula && $validacaoSemId && !$ehDiscipulado) {
            $html .= $this->view->translate($elemento->getValueOptions()[$elemento->getValue()]);
            $elemento->setAttribute(Constantes::$FORM_CLASS, Constantes::$FORM_CLASS_GUI_INPUT . ' ' . Constantes::$FORM_HIDDEN);
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
        $html .= $this->view->translate(Constantes::$TRADUCAO_HORA);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $html .= $this->view->formSelect($this->getForm()->get(Constantes::$FORM_HORA));
        $html .= '</label>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-sm-6 col-xs-6">';
        $html .= '<div class="section">';
        $html .= '<label class="field-label">';
        $html .= $this->view->translate(Constantes::$TRADUCAO_MINUTOS);
        $html .= '</label>';
        $html .= '<label class="field prepend-icon">';
        $html .= $this->view->formSelect($this->getForm()->get(Constantes::$FORM_MINUTOS));
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
