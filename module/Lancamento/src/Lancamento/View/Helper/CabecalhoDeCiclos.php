<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeCiclos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o ciclo atual com link para os proximos
 */
class CabecalhoDeCiclos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($this->view->abaSelecionada);
        $urlBase = $this->view->url(ConstantesLancamento::$ROUTE_LANCAMENTO);
        $urlBaseCiclo = $urlBase . '/' . $this->view->abaSelecionada . '_';

        $html .= '<div class="panel-heading row text-center" style="margin:0px;"> ';
        $html .= '<span class="panel-title">';
        if ($this->view->cicloSelecionado > 1) {
            $urlCicloAnterior = $urlBaseCiclo . ($this->view->cicloSelecionado - 1);
            $html .= '<a ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' href="' . $urlCicloAnterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-left"></i></button></a>&nbsp;';
        }
        $html .= 'Ciclo ' . $this->view->cicloSelecionado . '&nbsp;' . FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);
        $totalDeCiclos = FuncoesLancamento::totalCiclosMes($mesSelecionado, $anoSelecionado);
        if ($this->view->cicloSelecionado < $totalDeCiclos) {
            $urlCicloPosterior = $urlBaseCiclo . ($this->view->cicloSelecionado + 1);
            $html .= '&nbsp;<a ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' href="' . $urlCicloPosterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-right"></i></button></a>';
        }

        $html .= '</span>';
        $html .= '</div>';
        return $html;
    }

}
