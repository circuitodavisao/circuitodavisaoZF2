<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDePeriodos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o perido atual assim voltar até a data de cadastro
 */
class CabecalhoDePeriodos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $urlBase = $this->view->url(Constantes::$ROUTE_LANCAMENTO);
        $urlBaseCiclo = $urlBase . 'Arregimentacao';

        $periodo = $this->view->periodo;
        $urlCicloAnterior = $urlBaseCiclo . '/' . ($periodo - 1);
        $urlCicloPosterior = $urlBaseCiclo . '/' . ($periodo + 1);

        $iconeFlechaEsquerda = '<i class="fa fa-angle-double-left"></i>';
        $iconeFlechaDireita = '<i class="fa fa-angle-double-right"></i>';
        $funcaoOnclickEsquerda = $this->view->funcaoOnClick('location.href="' . $urlCicloAnterior . '"');
        $funcaoOnclickDireita = $this->view->funcaoOnClick('location.href="' . $urlCicloPosterior . '"');
        $botaoEsquerdo = $this->view->botaoSimples(
                $iconeFlechaEsquerda, $funcaoOnclickEsquerda, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro);
        $botaoDireito = $this->view->botaoSimples(
                $iconeFlechaDireita, $funcaoOnclickDireita, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro);

        $html .= '<div class="center-block text-center mb10"> ';
        $html .= $botaoEsquerdo;
        $html .= Constantes::$NBSP;
        $html .= $this->view->translate(Constantes::$TRADUCAO_PERIODO) . '&nbsp;-&nbsp;' . Funcoes::montaPeriodo($periodo)[0];
        $html .= Constantes::$NBSP;
        $html .= $botaoDireito;
        $html .= '</div>';
        return $html;
    }

}
