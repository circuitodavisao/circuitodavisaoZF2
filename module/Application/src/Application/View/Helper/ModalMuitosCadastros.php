<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ModalMuitosCadastros.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar modal com mensagem quando passa de 60 cadastros
 */
class ModalMuitosCadastros extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';
        /* Modal */
        $html .= '<div id="ModalMuitosCadastros" class="popup-basic admin-form mfp-with-anim mfp-hide p25" data-effect="mfp-with-fade">';
        $html .= '<button class="mfp-close">x</button>';
        $html .= '<div class="">' . $this->view->translate(Constantes::$TRADUCAO_LIMITE_CADASTROS) . '</div>';
        /* FIM Modal */
        $html .= '</div>';
        return $html;
    }

}
