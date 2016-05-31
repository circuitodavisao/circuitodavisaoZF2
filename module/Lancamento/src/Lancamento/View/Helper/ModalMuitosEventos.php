<?php

namespace Lancamento\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ModalMuitosEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar modal com mensagem para virar o celular
 */
class ModalMuitosEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';
        /* Modal */
        $html .= '<div id="modalMuitosEventos" class="popup-basic admin-form mfp-with-anim mfp-hide">';

        $html .= '<div class="well"><h1>VIRE O CELULAR DOIDO</h1></div>';
        /* FIM Modal */
        $html .= '</div>';
        return $html;
    }

}
