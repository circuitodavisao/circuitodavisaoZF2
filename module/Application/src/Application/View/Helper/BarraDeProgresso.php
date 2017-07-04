<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BarraDeProgresso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar blocos div com barra de progresso
 */
class BarraDeProgresso extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $id = 'divProgress';
        $class = 'col-xs-12';
        $conteudo = '';
        $conteudo .= '<div class="progress">';
        $conteudo .= '<div id="divProgressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>';
        $conteudo .= '</div>';
        $html .= $this->view->blocoDiv($id, $class, $conteudo);
        return $html;
    }

}
