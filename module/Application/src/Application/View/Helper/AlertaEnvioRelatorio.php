<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AlertaEnvioRelatorio.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar alerta de envio
 */
class AlertaEnvioRelatorio extends AbstractHelper {

    protected $tipo;

    public function __construct() {
        
    }

    public function __invoke($tipo) {
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $classe = '';
        switch ($this->getTipo()) {
            case 1: /* Enviado  */
                $classe = 'btn-success';
                break;
            case 2: /* Não Enviado mesma tela */
                $classe = 'btn-warning';
                break;
            case 3: /* Não Enviado outra tela  */
                $classe = 'btn-danger';
                break;
        }
        $html .= '<div class=' . $classe . '>alerta de envio</div>';
        return $html;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
