<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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
        $html .= '<div class="">' . Constantes::$TRADUCAO_GIRE_O_CELULAR . '</div>';
        /* FIM Modal */
        $html .= '</div>';
        return $html;
    }

}
