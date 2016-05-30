<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o cabecalhos de eventos para ciclo, mes e entidade
 */
class CabecalhoDeEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($this->view->abaSelecionada);
        $grupo = $this->view->entidade->getGrupo();
        $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);
        if (count($eventos) > 0) {
            foreach ($eventos as $ge) {
                $diaDaSemanaAjustado = FuncoesLancamento::diaDaSemanaPorDia($ge->getEvento()->getDia());
                $html .= "<th class='text-center'>";
                $html .= '<span>' . $ge->getEvento()->getEventoTipo()->getNomeAjustado() . '</span><br />';
                $html .= '<span>' . $diaDaSemanaAjustado . '</span><br />';
                $html .= '<span>' . $ge->getEvento()->getHoraFormatoHoraMinuto() . '</span>';
                $html .= "</th>";
            }
        }
        return $html;
    }

}
