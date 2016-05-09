<?php

namespace Lancamento\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ModalAba.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar modal com loader gigante
 */
class ModalAba extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';
        /* Modal */
        $html .= '<div id="modalAba" class="popup-basic admin-form mfp-with-anim mfp-hide">';

        $html .= '<div class="well"><h1>CARREGANDO TRUTA</h1></div>';
        /* FIM Modal */
        $html .= '</div>';
        return $html;
    }

}
