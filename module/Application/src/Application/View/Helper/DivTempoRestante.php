<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DivTempoRestante.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o span com o tempo restante
 */
class DivTempoRestante extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="alert alert-info p15" role="alert">';
        $html .= 'Tempo Restante: <span id="sessao"></span>';
        $html .= '</div>';
        return $html;
    }

}
