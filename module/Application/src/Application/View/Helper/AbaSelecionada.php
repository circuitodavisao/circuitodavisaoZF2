<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AlertaEnvioRelatorio.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para selecionar a aba
 */
class AbaSelecionada extends AbstractHelper {

    protected $abaSelecionada;

    public function __construct() {
        
    }

    public function __invoke($abaSelecionada, $aba) {
        $resposta = '';
        if ($abaSelecionada == $aba) {
            $resposta = 'class="active"';
        }
        return $resposta;
    }

}
