<?php

namespace Cadastro\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TemplateFormularioRodape.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o template do fim da pagina de formularios
 */
class TemplateFormularioRodape extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
