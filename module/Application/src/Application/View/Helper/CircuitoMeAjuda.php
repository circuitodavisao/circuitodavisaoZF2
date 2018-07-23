<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CircuitoMeAjuda.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados consoldiados
 */
class CircuitoMeAjuda extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="row alert alert-default mt10">';
        $html .= '<div class="col-xs-12 col-md-12 col-lg-12">';
        $html .= '<div class="panel">';
        $html .= '<div class="panel-body pn">';

        /* Celulas nao realizadas */
        if ($this->view->relatorio) {

            $periodo = -1;
            $relatorio = $this->view->relatorio;
            $htmlCelulasNaoRealizadas = '';
            for ($indiceCelulasNaoRealizadas = 1; $indiceCelulasNaoRealizadas < (count($relatorio) - 1); $indiceCelulasNaoRealizadas++) {
                $nomeLideres = $relatorio[$indiceCelulasNaoRealizadas]['lideres'];
                $celulasNaoRealizadas = $relatorio[$indiceCelulasNaoRealizadas][$periodo]['celulaQuantidade'] - $relatorio[$indiceCelulasNaoRealizadas][$periodo]['celulaRealizadas'];

                if ($celulasNaoRealizadas > 0) {
                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden info">';
                    $htmlCelulasNaoRealizadas .= '<td colspan="2">EQUIPE - ' . $nomeLideres . '</td>';
                    $htmlCelulasNaoRealizadas .= '</tr>';

                    $idGrupo = $relatorio[$indiceCelulasNaoRealizadas]['grupo'];
                    $grupo = $this->view->repositorio->getGrupoORM()->encontrarPorId($idGrupo);
                    $relatorio12 = RelatorioController::relatorioCompleto($this->view->repositorio, $grupo, RelatorioController::relatorioMembresiaECelula, date('m'), date('Y'));
                    for ($indiceCelulasNaoRealizadas12 = 0; $indiceCelulasNaoRealizadas12 < (count($relatorio12) - 1); $indiceCelulasNaoRealizadas12++) {
                        $nomeLideres12 = $relatorio12[$indiceCelulasNaoRealizadas12]['lideres'];
                        $celulasNaoRealizadas12 = $relatorio12[$indiceCelulasNaoRealizadas12][$periodo]['celulaQuantidade'] - $relatorio12[$indiceCelulasNaoRealizadas12][$periodo]['celulaRealizadas'];
                        if ($celulasNaoRealizadas12 > 0) {
                            $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                            $htmlCelulasNaoRealizadas .= '<td>' . $nomeLideres12 . '</td>';
                            $htmlCelulasNaoRealizadas .= '<td>' . $celulasNaoRealizadas12 . '</td>';
                            $htmlCelulasNaoRealizadas .= '</tr>';
                        }
                    }

                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden primary">';
                    $htmlCelulasNaoRealizadas .= '<td class="text-right">TOTAL</td>';
                    $htmlCelulasNaoRealizadas .= '<td>' . $celulasNaoRealizadas . '</td>';
                    $htmlCelulasNaoRealizadas .= '</tr>';
                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                    $htmlCelulasNaoRealizadas .= '<td colspan="2"></td>';
                    $htmlCelulasNaoRealizadas .= '</tr>';
                }
            }
            $indiceUltimoRegistroDoRelatorio = (count($relatorio) - 1);
            $totalDeCelulasNaoRealizadas = $relatorio[$indiceUltimoRegistroDoRelatorio][$periodo]['celulaQuantidade'] - $relatorio[$indiceUltimoRegistroDoRelatorio][$periodo]['celulaRealizadas'];
        }

        $html .= '<table class="table table-condensed">';
        $html .= '<thead>';
        $html .= '<tr class="info">';
        $html .= '<th colspan="2" class="text-center">Circuito me Ajuda ' . Funcoes::montaPeriodo($periodo)[0] . '</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="text-center">C&eacute;lulas <b>N&atilde;o</b> Realizadas: ' . $totalDeCelulasNaoRealizadas . '</td>';
        $funcao = $this->view->funcaoOnClick('$(".linhaCelulasNaoRealizadas").toggleClass("hidden")');
        $html .= '<td>' . $this->view->botaoSimples('<i class="fa fa-eye" />', $funcao, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro) . '</td>';
        $html .= '</tr>';
        $html .= $htmlCelulasNaoRealizadas;      

        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    static public function functionName($param) {
        
    }

}
