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
        $html .= '<div id="modalMuitosEventos" class="popup-basic admin-form mfp-with-anim mfp-hide p25" data-effect="mfp-with-fade">';
        $html .= '<div class=""><h3>GIRE O CELULAR</h3><span>Vire seu celular para horizontal para visualizar todos os seus eventos.</span></div>';
        /* FIM Modal */
        $html .= '</div>';
        return $html;
    }

}
