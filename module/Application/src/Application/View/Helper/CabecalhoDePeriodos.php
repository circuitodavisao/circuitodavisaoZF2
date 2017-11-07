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

    private $rota;
    private $tipoRelatorio;

    public function __construct() {
        
    }

    public function __invoke($rota = null, $tipoRelatorio = 0) {
        if ($rota === null) {
            $rota = Constantes::$ROUTE_LANCAMENTO;
        }
        $this->setRota($rota);
        $this->setTipoRelatorio($tipoRelatorio);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $urlBase = $this->view->url($this->getRota());
        $urlBaseCiclo = $urlBase;

        if ($this->getTipoRelatorio() != 0) {
            $urlBaseCiclo .= '/' . $this->getTipoRelatorio();
        } else {
            $urlBaseCiclo .= 'Arregimentacao';
        }

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
        if ($this->view->mostrarBotaoPeriodoAnterior) {
            $html .= $botaoEsquerdo;
//            $html .= '<select id="periodoInicial">';
//            $html .= '<option value="-8">06/11 - 12/11</option>';
//            $html .= '</select>';
        }
        $html .= Constantes::$NBSP;
        $html .= $this->view->translate(Constantes::$TRADUCAO_PERIODO)
                . '&nbsp;-&nbsp;'
                . Funcoes::montaPeriodo($periodo)[0]
        ;
        $html .= Constantes::$NBSP;
        if ($this->view->mostrarBotaoPeriodoAfrente) {
            $html .= $botaoDireito;

//            $html .= '<select id="periodoFinal">';
//            $html .= '<option value="-2">06/11 - 12/11</option>';
//            $html .= '</select>';
        }
        $html .= '</div>';
        return $html;
    }

    function getRota() {
        return $this->rota;
    }

    function setRota($rota) {
        $this->rota = $rota;
    }

    function getTipoRelatorio() {
        return $this->tipoRelatorio;
    }

    function setTipoRelatorio($tipoRelatorio) {
        $this->tipoRelatorio = $tipoRelatorio;
    }

}
