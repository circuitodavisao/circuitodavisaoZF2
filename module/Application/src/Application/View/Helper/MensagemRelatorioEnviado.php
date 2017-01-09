<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: MensagemRelatorioEnviado.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar as mensagens de relatorio enviado
 */
class MensagemRelatorioEnviado extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';

        $hiddenMensagemRelatorioAtualizado = 1;
        $hiddenMensagemRelatorioDesatualizado = 1;
        if ($this->view->statusEnvio == 1) {
            $hiddenMensagemRelatorioAtualizado = 0;
        }
        if ($this->view->statusEnvio == 2) {
            $hiddenMensagemRelatorioDesatualizado = 0;
        }
        $html .= $this->view->mensagemStatica($this->view->translate(Constantes::$TRADUCAO_RELATORIO_ATUALIZADO), 3, 0, $hiddenMensagemRelatorioAtualizado, Constantes::$STATUS_ENVIO . '1');
        $html .= $this->view->mensagemStatica($this->view->translate(Constantes::$TRADUCAO_RELATORIO_DEZATUALIZADO), 1, 0, $hiddenMensagemRelatorioDesatualizado, Constantes::$STATUS_ENVIO . '2');

        return $html;
    }

}
