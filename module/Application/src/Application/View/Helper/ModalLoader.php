<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ModalLoader.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar o modal com loader
 */
class ModalLoader extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div id="modalBuscando" class="admin-form mfp-with-anim mfp-hide">';
        $html .= '<div align="center">';
        $html .= '<img src="' . Constantes::$LOADER_GIF_GRANDE . '"></i>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
