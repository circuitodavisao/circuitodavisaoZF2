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
        $html .= '<div class="row p10">';
        $html .= '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">';
        $html .= 'Mês';
        $html .= '</div>';
        $html .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
        $html .= '<select class="form-control">';
        for ($indice = 1; $indice <= 11; $indice++) {
            $selected = '';
            if ($this->view->mesSelecionado == $indice) {
                $selected = 'selected';
            }

            $html .= '<option value="' . $indice . '" ' . $selected . '>' . Funcoes::mesPorExtenso($indice, 1) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">';
        $html .= 'Ano';
        $html .= '</div>';
        $html .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
        $html .= '<select class="form-control">';
        for ($indice = date('Y'); $indice >= 2017; $indice--) {
            $html .= '<option value="' . $indice . '">' . $indice . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">';
        $html .= $this->view->botaoSimples('Filtrar', '');
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
