<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CircuitoMeAjuda.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados consoldiados
 */
class CircuitoMeAjuda extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="row alert alert-default mt10">';
        $html .= '<div class="col-xs-12 col-md-12 col-lg-12">';
        $html .= '<div class="panel">';
        $html .= '<div class="panel-body pn">';        
        $html .= '<div id="divTabelaCircuitoMeAjuda"></div>'; 
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    static public function functionName($param) {
        
    }

}
