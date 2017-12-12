<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Application\Model\Entity\Hierarquia;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DadosPrincipal.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados na tela principal
 */
class DadosProximoNivel extends AbstractHelper {

    private $relatorioEquipe;

    public function __construct() {
        
    }

    public function __invoke($relatorioEquipe) {
        $this->setRelatorioEquipe($relatorioEquipe);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $pessoa = $this->view->pessoa;
        switch ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId()) {
            case Hierarquia::LIDER_EM_TREINAMENTO:
                $idProximaHierarquia = Hierarquia::LIDER_DE_CELULA;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::LIDER_DE_CELULA);
                break;
            case Hierarquia::LIDER_DE_CELULA:
                $idProximaHierarquia = Hierarquia::OBREIRO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::OBREIRO);
                break;
            case Hierarquia::OBREIRO:
                $idProximaHierarquia = Hierarquia::DIACONO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::DIACONO);
                break;
            case Hierarquia::DIACONO:
                $idProximaHierarquia = Hierarquia::MISSIONARIO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::MISSIONARIO);
                break;
            case Hierarquia::MISSIONARIO:
                $idProximaHierarquia = Hierarquia::PASTOR;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::PASTOR);
                break;
            case Hierarquia::PASTOR:
                $idProximaHierarquia = Hierarquia::BISPO;
                break;
        }
        $stringProximaHierarquia = $this->view->repositorio->getHierarquiaORM()->encontrarPorId($idProximaHierarquia)->getNome();
        $perfomanceMembresia = $this->getRelatorioEquipe()['membresia'] / $metas[0] * 100;
        if ($perfomanceMembresia > 100) {
            $perfomanceMembresia = 100;
        }
        $perfomanceCelula = $this->getRelatorioEquipe()['celulaQuantidade'] / $metas[1] * 100;
        if ($perfomanceCelula > 100) {
            $perfomanceCelula = 100;
        }
        $validacaoMembresia = $perfomanceMembresia / 2;
        $validacaoCulto = $perfomanceCelula / 2;

        $html = '';

        $html .= '<table class="table table-condensed p5 text-center">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td>' . $stringProximaHierarquia . '</td>';

        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::PASTOR &&
                $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::BISPO) {
            $html .= '<td class="block-center">';
            $html .= '<div class = "progress mt20">';
            $html .= '<div class = "progress-bar progress-bar-info" style = "width: ' . $validacaoMembresia . '%;">M.</div>';
            $html .= '<div class = "progress-bar progress-bar-system" style = "width: ' . $validacaoCulto . '%;">C.</div>';
            $html .= RelatorioController::formataNumeroRelatorio($validacaoMembresia + $validacaoCulto) . '%';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<button onclick = "$(\'#divProximoNivel\').toggleClass(\'hidden\');" class = "btn btn-xs btn-primary"><i class = "fa fa-caret-down"></i></button>';
            $html .= '</td>';
        }
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '<div id = "divProximoNivel" class = "row p10 hidden">';
        $html .= '<div class = "panel">';

        $html .= '<div class = "panel-body">';
        for ($indice = 0; $indice <= 1; $indice++) {
            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $corBarra = 'info';
                    $valorBarra = $perfomanceMembresia;
                    break;
                case 1:
                    $stringMeta = 'Célula';
                    $indiceRelatorio = 'celulaQuantidade';
                    $corBarra = 'system';
                    $valorBarra = $perfomanceCelula;
                    break;
            }
            $html .= '<div class = "row">';

            $html .= '<div class = "col-xs-3">' . $stringMeta . '</div>';
            $html .= '<div class = "col-xs-6">';
            $html .= '<div class = "progress">';
            $html .= '<div class = "progress-bar progress-bar-' . $corBarra . '" role = "progressbar" aria-valuenow = "' . $valorBarra . '" aria-valuemin = "0" aria-valuemax = "100" style = "width: ' . $valorBarra . '%;">' . RelatorioController::formataNumeroRelatorio($valorBarra) . '%</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class = "col-xs-3">' . RelatorioController::formataNumeroRelatorio($this->getRelatorioEquipe()[$indiceRelatorio]) . '/' . $metas[$indice] . '</div>';

            $html .= '</div>';
        }
        $html .= '</div>';

        $html .= '</div>

        

        ';


        return $html;
    }

    function getRelatorioEquipe() {
        return $this->relatorioEquipe;
    }

    function setRelatorioEquipe($relatorioEquipe) {
        $this->relatorioEquipe = $relatorioEquipe;
    }

}
