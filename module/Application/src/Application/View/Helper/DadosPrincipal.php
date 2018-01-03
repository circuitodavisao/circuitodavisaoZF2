<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Application\Model\Entity\Hierarquia;
use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DadosPrincipal.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados na tela principal
 */
class DadosPrincipal extends AbstractHelper {

    private $relatorioPessoal;
    private $relatorioEquipe;

    public function __construct() {
        
    }

    public function __invoke($relatorioPessoal, $relatorioEquipe) {
        $this->setRelatorioPessoal($relatorioPessoal);
        $this->setRelatorioEquipe($relatorioEquipe);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $pessoa = $this->view->pessoa;
        $hierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
        $classe = '?';
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);

        /* Calculo da sua classe */
        $metas = Funcoes::metaPorHierarquia($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
        $multiplicadorDaMeta = 1;
        if ($this->view->eCasal) {
            $multiplicadorDaMeta = 2;
        }
        $qualRelatorio = array();
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $qualRelatorio = $this->getRelatorioPessoal();
        } else {
            $qualRelatorio = $this->getRelatorioEquipe();
        }
        $valorMembresia = $qualRelatorio['membresia'];
        $valorCelulaQuantidade = $qualRelatorio['celulaQuantidade'];

        $perfomanceMembresia = $valorMembresia / ($metas[0] * $multiplicadorDaMeta) * 100;
        $perfomanceCelula = $valorCelulaQuantidade / $metas[1] * 100;

        if ($perfomanceMembresia > 100) {
            $perfomanceMembresia = 100;
        }
        if ($perfomanceCelula > 100) {
            $perfomanceCelula = 100;
        }

        $somaClasse = 0;
        $contagemDeEventos = 1;
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $validacaoCelulaDeElite = 0;
            if ($this->view->celulasValores) {
                foreach ($this->view->celulasValores as $valorCelula) {
                    if ($valorCelula['valor'] >= Constantes::$META_LIDER) {
                        $validacaoCelulaDeElite += 100;
                    }
                }
                $validacaoCelulaDeElite /= count($this->view->celulasValores);
            }

            if ($validacaoCelulaDeElite == 100) {
                $perfomanceCelulaDeElite = 34;
            } else {
                if ($validacaoCelulaDeElite == 50) {
                    $perfomanceCelulaDeElite = 17;
                } else {
                    $perfomanceCelulaDeElite = 0;
                }
            }

            $contagemDeEventos += count($this->view->celulasValores);
            $somaClasse += $perfomanceCelulaDeElite;
        }
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::LIDER_DE_CELULA) {
            $somaClasse += $perfomanceCelula;
            $contagemDeEventos++;
        }
        $somaClasse = ($somaClasse + $perfomanceMembresia) / $contagemDeEventos;
        if ($somaClasse >= RelatorioController::MARGEM_D && $somaClasse < RelatorioController::MARGEM_C) {
            $classe = 'D';
        }
        if ($somaClasse >= RelatorioController::MARGEM_C && $somaClasse < RelatorioController::MARGEM_B) {
            $classe = 'C';
        }
        if ($somaClasse >= RelatorioController::MARGEM_B && $somaClasse < RelatorioController::MARGEM_A) {
            $classe = 'B';
        }
        if ($somaClasse >= RelatorioController::MARGEM_A) {
            $classe = 'A';
        }
        $classClasse = RelatorioController::corDaLinhaPelaPerformance($classe);

        $html .= '<div class="page-heading">';
        $html .= '<div class="media clearfix">';

        $html .= '<div class="media-left pr30">';
        $html .= '<a href="#">';
        $html .= '<img width="64px" height="64px" class="media-object img-rounded" src="/img/avatars/' . $imagem . '" alt="...">';
        $html .= '</a>';
        /* media-left pr30 */
        $html .= '</div>';

        $html .= '<div class="media-body va-m">';
        $html .= '<h2 class="media-heading">' . $pessoa->getNomePrimeiroUltimo() . '</h2>';
        $html .= '<p class="lead" style="font-size: 10px;">' . $hierarquia;
        $html .= ' - Classe <span onclick="mostrarModalClasse();" ><span class="label label-' . $classClasse . ' label-sm">' . $classe . ' </span>&nbsp;<span class="badge badge-default">?</span></span>';
        $html .= '</p>';
        /* media-body va-m */
        $html .= '</div>';

        $html .= '<div class="media-links">';
        $html .= '<ul class="list-inline list-unstyled">';
        $minhaHierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia();
        foreach ($this->view->hierarquias as $hierarquia) {
            $corDaMedalha = 'default';
            if ($hierarquia->getId() >= $minhaHierarquia->getId()) {
                $corDaMedalha = 'info';
            }
            $html .= '<li>';
            $html .= '<span class="label label-xs label-' . $corDaMedalha . '">' . $hierarquia->getSigla() . '</span>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';

        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center">Cálculo da Classe</h1>';
        $mensagemModalClasse .= '<p>Média dos ultimos 4 periodos de membresia e células pela meta da sua hierarquia.</p>';

        $fimIndice = 1;
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $fimIndice += count($this->view->celulasValores);
        }

        for ($indice = 0; $indice <= $fimIndice; $indice++) {
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
                $arrayRelatorio = $this->getRelatorioPessoal();
            } else {
                $arrayRelatorio = $this->getRelatorioEquipe();
            }
            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceMembresia);
                    $valorBarra = $perfomanceMembresia;
                    $valorApresentado = RelatorioController::formataNumeroRelatorio($arrayRelatorio[$indiceRelatorio]);
                    $labelBarra = RelatorioController::formataNumeroRelatorio($valorBarra) . '%';
                    $valorMeta = $metas[0] * $multiplicadorDaMeta;
                    break;
                case 1:
                    $stringMeta = 'Célula';
                    $indiceRelatorio = 'celulaQuantidade';
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceCelula);
                    $valorBarra = $perfomanceCelula;
                    $valorApresentado = $arrayRelatorio[$indiceRelatorio];
                    $labelBarra = $valorBarra . '%';
                    $valorMeta = $metas[1];
                    break;
                case 2:
                    $indiceRelatorio = 0;
                    $stringMeta = 'Cél. ' . $this->view->celulasValores[$indiceRelatorio]['hospedeiro'];
                    $valorApresentado = $this->view->celulasValores[$indiceRelatorio]['valor'];
                    $labelBarra = $this->view->celulasValores[$indiceRelatorio]['valor'];
                    $valorMeta = $metas[0];
                    $valorBarra = $valorApresentado / $valorMeta * 100;
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    break;
                case 3:
                    $indiceRelatorio = 1;
                    $stringMeta = 'Cél. ' . $this->view->celulasValores[$indiceRelatorio]['hospedeiro'];
                    $valorApresentado = $this->view->celulasValores[$indiceRelatorio]['valor'];
                    $labelBarra = $this->view->celulasValores[$indiceRelatorio]['valor'];
                    $valorMeta = $metas[0];
                    $valorBarra = $valorApresentado / $valorMeta * 100;
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    break;
            }
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::LIDER_DE_CELULA ||
                    ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA && $indice !== 1)) {
                $mensagemModalClasse .= '<div class = "row">';
                $mensagemModalClasse .= '<div class = "col-xs-4 text-right">' . $stringMeta . '</div>';
                $mensagemModalClasse .= '<div class = "col-xs-5">';
                $mensagemModalClasse .= '<div class = "progress">';
                $mensagemModalClasse .= '<div class = "progress-bar progress-bar-' . $corBarra . '" role="progressbar" aria-valuenow="' . $valorBarra . '" aria-valuemin = "0" aria-valuemax="100" style="width: ' . $valorBarra . '%;">' . $labelBarra . '</div>';
                $mensagemModalClasse .= '</div>';
                $mensagemModalClasse .= '</div>';
                $mensagemModalClasse .= '<div class = "col-xs-3">' . $valorApresentado . '/' . $valorMeta . '</div>';
                $mensagemModalClasse .= '</div>';
            }
        }

        $html .= '<div id="modalClassificacao" class="popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div class="mw1000">' . $mensagemModalClasse . '</div>';
        $html .= '<button tittle="Close (Esc)" type="button" class="mfp-close bg-dark">x</button>';
        $html .= '</div>';

        return $html;
    }

    function getRelatorioPessoal() {
        return $this->relatorioPessoal;
    }

    function setRelatorioPessoal($relatorioPessoal) {
        $this->relatorioPessoal = $relatorioPessoal;
    }

    function getRelatorioEquipe() {
        return $this->relatorioEquipe;
    }

    function setRelatorioEquipe($relatorioEquipe) {
        $this->relatorioEquipe = $relatorioEquipe;
    }

}
