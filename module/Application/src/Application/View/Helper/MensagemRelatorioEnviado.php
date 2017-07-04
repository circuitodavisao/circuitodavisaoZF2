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

        $mostrar = false;
        if ($this->view->statusEnvio) {
            $mostrar = true;
        }
        $mensagem = Constantes::$TRADUCAO_RELATORIO_ATUALIZADO;
        if ($this->view->statusEnvio == 2) {
            $mensagem = Constantes::$TRADUCAO_RELATORIO_DEZATUALIZADO;
        }
        $html .= $this->view->divMensagens($mensagem, $this->view->statusEnvio, $mostrar);

        return $html;
    }

}
