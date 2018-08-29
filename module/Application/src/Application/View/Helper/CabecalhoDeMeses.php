<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeMeses.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o mês
 */
class CabecalhoDeMeses extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $url = $this->view->tipoRelatorio . '/' . $this->view->mes . '/' . $this->view->ano;
        $html .= '<div class="row p10">';
        $html .= '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">';
        $html .= 'Mês';
        $html .= '</div>';
        $html .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">';
        $html .= '<select name="mes" id="mes" class="form-control">';
        for ($indice = 1; $indice <= 12; $indice++) {
            $selected = '';
            if ($this->view->mes == $indice) {
                $selected = 'selected';
            }

            $html .= '<option value="' . $indice . '" ' . $selected . '>' . Funcoes::mesPorExtenso($indice, 1) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">';
        $html .= 'Ano';
        $html .= '</div>';
        $html .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">';
        $html .= '<select name="ano" id="ano" class="form-control">';
        for ($indice = date('Y'); $indice >= 2017; $indice--) {
            $selected = '';
            if ($this->view->ano == $indice) {
                $selected = 'selected';
            }
            $html .= '<option value="' . $indice . '" ' . $selected . '>' . $indice . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">';
        $html .= $this->view->botaoSimples('Filtrar', $this->view->funcaoOnClick('this.form.submit()'), BotaoSimples::botaoImportante, BotaoSimples::larguraMaxima);
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
