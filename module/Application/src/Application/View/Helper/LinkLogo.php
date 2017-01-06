<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: LinkLogo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a logo do sistema com um link para a index
 */
class LinkLogo extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= ' <a href="' . Constantes::$INDEX . '" title="' . $this->view->translate(Constantes::$TRADUCAO_NOME_APLICACAO) . '">';
        $html .= ' <img src="' . Constantes::$IMAGEM_LOGO . '" ';
        $html .= ' title="' . $this->view->translate(Constantes::$TRADUCAO_NOME_APLICACAO) . '" ';
        $html .= ' class="center-block img-responsive" style="max-width: 275px;"> ';
        $html .= ' </a> ';
        return $html;
    }

}
