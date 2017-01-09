<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_CEP_LOGRADOURO, $this->getForm(), Constantes::$FORM_CEP_LOGRADOURO);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="divBotaoBuscarCep" class="row">';
        $html .= '<div class="col-md-12 col-sm-12 col-xs-12">';
        $html .= '<div class="section">';
        $html .= $this->view->BotaoLink('Buscar CEP', '#', 7, $this->view->funcaoOnClick(Constantes::$FORM_FUNCAO_BUSCAR_CEP));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="endereco" class="' . $this->view->enderecoHidden . '">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-6 col-sm-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_UF, $this->getForm(), Constantes::$FORM_UF, 1);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-6 col-sm-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_BAIRRO, $this->getForm(), Constantes::$FORM_BAIRRO, 1);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= '<div class="section">';
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_CIDADE, $this->getForm(), Constantes::$FORM_CIDADE, 1);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<div class="section">';
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_LOGRADOURO, $this->getForm(), Constantes::$FORM_LOGRADOURO, 1);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="section">';
        $html .= $this->view->inputCampoEndereco(Constantes::$TRADUCAO_COMPLEMENTO, $this->getForm(), Constantes::$FORM_COMPLEMENTO);
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
