<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\FuncoesLancamento;
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
        $urlBase = $this->view->url(Constantes::$ROUTE_LANCAMENTO);
        $urlBaseCiclo = $urlBase . '/' . $this->view->abaSelecionada . '_';

        $html .= '<div class="center-block text-center" style="padding:10px;"> ';

        $mostrarAnterior = false;
        if ($this->view->cicloSelecionado > 1) {
            $mostrarAnterior = true;
        }
//        if ($this->view->validacaoNesseMes == 1) {
//            $mostrarAnterior = false;
//        }

        if ($mostrarAnterior) {
            $urlCicloAnterior = $urlBaseCiclo . ($this->view->cicloSelecionado - 1);
            $html .= '<a ' . Constantes::$ONCLICK_ABRIR_MODAL . ' href="' . $urlCicloAnterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-left"></i></button></a>&nbsp;';
        }
        $traducaoPeriodo = $this->view->translate(Constantes::$TRADUCAO_PERIODO);
        $html .= $this->view->translate(Constantes::$TRADUCAO_CICLO) . Constantes::$NBSP . $this->view->cicloSelecionado . '&nbsp;-&nbsp;' . FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, $traducaoPeriodo);
        $totalDeCiclos = FuncoesLancamento::totalCiclosMes($mesSelecionado, $anoSelecionado);
        if ($this->view->cicloSelecionado < $totalDeCiclos) {
            $urlCicloPosterior = $urlBaseCiclo . ($this->view->cicloSelecionado + 1);
            $html .= '&nbsp;<a ' . Constantes::$ONCLICK_ABRIR_MODAL . ' href="' . $urlCicloPosterior . '"><button class="btn btn-default btn-sm"><i class="fa fa-angle-double-right"></i></button></a>';
        }

        $html .= '</div>';
        return $html;
    }

}
