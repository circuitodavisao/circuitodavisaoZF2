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
                $metas = Funcoes::metaPorHierarquia(Hierarquia::BISPO);
                break;
        }
        $stringProximaHierarquia = 'De ' . $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome() .
                ' para ' . $this->view->repositorio->getHierarquiaORM()->encontrarPorId($idProximaHierarquia)->getNome();
        $perfomanceMembresia = $this->getRelatorioEquipe()[(count($this->getRelatorioEquipe()) - 1)]['mediaMembresia'] / $metas[0] * 100;
        if ($perfomanceMembresia > 100) {
            $perfomanceMembresia = 100;
        }
        $perfomanceLideres = $this->getRelatorioEquipe()[(count($this->getRelatorioEquipe()) - 1)][-1]['quantidadeLideres'] / $metas[1] * 100;
        if ($perfomanceLideres > 100) {
            $perfomanceLideres = 100;
        }
        $validacaoMembresia = $perfomanceMembresia / 2;
        $validacaoLideres = $perfomanceLideres / 2;
        $valorBarra = $validacaoMembresia + $validacaoLideres;
        $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
        $labelBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra, 2);
        $html = '';

        $html .= '<p class=" well bg-default text-' . $corDaBarra . ' text-center">' . $labelBarra . '</p>';

        $html .= $this->view->barraDeProgressoBonita(
                $stringProximaHierarquia . ' <span class="badge">?</span>', $corDaBarra, $valorBarra, 'm0', false, 0, 0, $extra = 'onclick="$(\'#divProximoNivel\').toggleClass(\'hidden\');"');

        $html .= '<div id = "divProximoNivel" class = "row p10 hidden">';
        $html .= '<div class = "panel">';

        $html .= '<div class = "panel-body">';
        for ($indice = 0; $indice <= 1; $indice++) {
            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceMembresia);
                    $valorBarra = $perfomanceMembresia;
                    $alcancado = $this->getRelatorioEquipe()[(count($this->getRelatorioEquipe()) - 1)]['mediaMembresia'];
                    $meta = $metas[0];
                    break;
                case 1:
                    $stringMeta = 'Líderes';
                    $indiceRelatorio = 'quantidadeLideres';
                    $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceLideres);
                    $valorBarra = $perfomanceLideres;
                    $alcancado = $this->getRelatorioEquipe()[(count($this->getRelatorioEquipe()) - 1)][-1]['quantidadeLideres'];
                    $meta = $metas[1];
                    break;
            }

            $html .= $this->view->barraDeProgressoBonita(
                    $stringMeta, $corDaBarra, $valorBarra, 'm25', true, $meta, $alcancado);
        }
        $html .= '</div>';

        $html .= '</div>';

        $html .= '</div>';


        return $html;
    }

    function getRelatorioEquipe() {
        return $this->relatorioEquipe;
    }

    function setRelatorioEquipe($relatorioEquipe) {
        $this->relatorioEquipe = $relatorioEquipe;
    }

}
