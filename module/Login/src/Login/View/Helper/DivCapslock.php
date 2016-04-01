<?php

namespace Login\View\Helper;

use Login\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DivCapslock.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a div com capslock
 */
class DivCapslock extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {

        $html = '<!-- Capslock -->';
        $html .= ' <div id="divCapslock" class="alert alert-danger hidden"> ';
        $html .= $this->view->translate(Constantes::$TRADUCAO_CAPSLOCK);
        $html .= ' </div> ';
        return $html;
    }

}
