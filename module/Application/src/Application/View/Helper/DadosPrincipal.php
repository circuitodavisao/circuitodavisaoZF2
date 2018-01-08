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

    private $relatorioMedio;

    public function __construct() {
        
    }

    public function __invoke($relatorioMedio) {
        $this->setRelatorioMedio($relatorioMedio);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $pessoa = $this->view->pessoa;
        $hierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
        $classe = '?';
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);

        $metas = Funcoes::metaPorHierarquia($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
        $multiplicadorDaMeta = 1;
        if ($this->view->eCasal) {
            $multiplicadorDaMeta = 2;
        }

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center">Cálculo da Classe</h1>';
        $mensagemModalClasse .= '<p>Média de membresia e células pela meta da sua hierarquia no mês anterior.</p>';

        for ($indiceDeRelatorios = 1; $indiceDeRelatorios <= 2; $indiceDeRelatorios++) {
            unset($qualRelatorio);
            unset($qualRelatorioCelula);
            $nomeRelatorio = '';
            $atualOuAnterior = '';

            if ($indiceDeRelatorios === 2) {
                $atualOuAnterior = 'Atual';
            }
            if ($indiceDeRelatorios === 1) {
                $atualOuAnterior = 'Anterior';
            }

            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
                $nomeRelatorio = 'pessoal' . $atualOuAnterior;
            } else {
                $nomeRelatorio = 'equipe' . $atualOuAnterior;
            }
            $qualRelatorio = $this->getRelatorioMedio()[$nomeRelatorio];
            $qualRelatorioCelula = $this->getRelatorioMedio()['celulas' . $atualOuAnterior];

            $fimIndice = 1;
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
                $fimIndice += count($qualRelatorioCelula);
            }

            $valorMembresia = $qualRelatorio['membresia'];
            $valorCelulaQuantidade = $qualRelatorio['celulaQuantidade'];

            $perfomanceMembresia = $valorMembresia / ($metas[0] * $multiplicadorDaMeta) * 100;
            $perfomanceCelula = $valorCelulaQuantidade / $metas[1] * 100;

            $perfomanceMembresiaVisual = $perfomanceMembresia;
            if ($perfomanceMembresia > 100) {
                $perfomanceMembresiaVisual = 100;
            }

            $perfomanceCelulaVisual = $perfomanceCelula;
            if ($perfomanceCelula > 100) {
                $perfomanceCelulaVisual = 100;
            }

            $somaCelulaDeElite = 0;
            $somaCelulaQuantidade = 0;
            $contagemDeEventos = 1;
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
                if ($qualRelatorioCelula) {
                    foreach ($qualRelatorioCelula as $valorCelula) {
                        $perfomanceCelulaDeElite = $valorCelula['valor'] / Constantes::$META_LIDER * 100;
                        if ($perfomanceCelulaDeElite > 100) {
                            $perfomanceCelulaDeElite = 100;
                        }
                        $somaCelulaDeElite += $perfomanceCelulaDeElite;
                    }
                    $contagemDeEventos += count($qualRelatorioCelula);
                }
            }
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::LIDER_DE_CELULA) {
                $somaCelulaQuantidade += $perfomanceCelulaVisual;
                $contagemDeEventos++;
            }
            $somaClasse = ($somaCelulaDeElite + $somaCelulaQuantidade + $perfomanceMembresiaVisual) / $contagemDeEventos;
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

            $mensagemModalClasse .= "<div class='alert alert-info alert-sm'>";
            if ($indiceDeRelatorios === 2) {
                $mensagemModalClasse .= Funcoes::mesPorExtenso(date('m'), 1);
            }
            if ($indiceDeRelatorios === 1) {
                $mesAnterior = date('m') - 1;
                if (date('m') == 1) {
                    $mesAnterior = 12;
                }
                $mensagemModalClasse .= Funcoes::mesPorExtenso($mesAnterior, 1);
            }
            $mensagemModalClasse .= ' - Classe <span class="label label-' . $classClasse . ' label-sm">' . $classe . ' </span>';
            $mensagemModalClasse .= "</div>";
            $mensagemModalClasse .= $this->montaBarrasDeProgresso($fimIndice, $perfomanceMembresia, $qualRelatorio, $multiplicadorDaMeta, $metas, $qualRelatorioCelula, $perfomanceCelula, $pessoa);

            if ($indiceDeRelatorios === 1) {
                $classeTela = $classe;
                $classClasseTela = $classClasse;
            }
        }

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
        $html .= ' - Classe <span onclick="mostrarModalClasse();" ><span class="label label-' . $classClasseTela . ' label-sm">' . $classeTela . ' </span>&nbsp;<span class="badge">?</span></span>';
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

        /* Modal */
        $html .= '<div id="modalClassificacao" class="popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div class="mw1000">' . $mensagemModalClasse . '</div>';
        $html .= '<button tittle="Close (Esc)" type="button" class="mfp-close bg-dark">x</button>';
        $html .= '</div>';

        return $html;
    }

    function montaBarrasDeProgresso($fimIndice, $qualRelatorio, $multiplicadorDaMeta, $metas, $qualRelatorioCelula, $pessoa) {
        $html = '';
        for ($indice = 0; $indice <= $fimIndice; $indice++) {
            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($qualRelatorio[$indiceRelatorio]);
                    $valorBarra = $qualRelatorio[$indiceRelatorio] > 100 ? 100 : $qualRelatorio[$indiceRelatorio];
                    $valorApresentado = RelatorioController::formataNumeroRelatorio($qualRelatorio[$indiceRelatorio]);
                    $labelBarra = $valorApresentado;
                    $valorMeta = $metas[0] * $multiplicadorDaMeta;
                    break;
                case 1:
                    $stringMeta = 'Célula';
                    $indiceRelatorio = 'celulaQuantidade';
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($qualRelatorio[$indiceRelatorio]);
                    $valorBarra = $qualRelatorio[$indiceRelatorio] > 100 ? 100 : $qualRelatorio[$indiceRelatorio];
                    $valorApresentado = $qualRelatorio[$indiceRelatorio];
                    $labelBarra = $valorApresentado;
                    $valorMeta = $metas[1];
                    break;
                case 2:
                    $indiceRelatorio = 0;
                    $stringMeta = 'Cél. ' . $qualRelatorioCelula[$indiceRelatorio]['hospedeiro'];
                    $valorApresentado = $qualRelatorioCelula[$indiceRelatorio]['valor'];
                    $valorMeta = $metas[0];
                    $labelBarra = $valorApresentado / $valorMeta * 100;
                    $valorBarra = $labelBarra;
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    break;
                case 3:
                    $indiceRelatorio = 1;
                    $stringMeta = 'Cél. ' . $qualRelatorioCelula[$indiceRelatorio]['hospedeiro'];
                    $valorApresentado = $qualRelatorioCelula[$indiceRelatorio]['valor'];
                    $valorMeta = $metas[0];
                    $labelBarra = $valorApresentado / $valorMeta * 100;
                    $valorBarra = $labelBarra;
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    break;
            }
            $labelBarra = RelatorioController::formataNumeroRelatorio($valorBarra);
            if ($valorBarra > 100) {
                $valorBarra = 100;
            }
            if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() !== Hierarquia::LIDER_DE_CELULA ||
                    ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA && $indice !== 1)) {
                $html .= '<div class = "row">';
                $html .= '<div class = "col-xs-4 text-right">' . $stringMeta . '</div>';
                $html .= '<div class = "col-xs-5">';
                $html .= '<div class = "progress">';
                $html .= '<div class = "progress-bar progress-bar-' . $corBarra . '" role="progressbar" aria-valuenow="' . $valorBarra . '" aria-valuemin = "0" aria-valuemax="100" style="width: ' . $valorBarra . '%;">' . $labelBarra . '%</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class = "col-xs-3">' . $valorApresentado . ' de ' . $valorMeta . '</div>';
                $html .= '</div>';
            }
        }
        return $html;
    }

    function getRelatorioMedio() {
        return $this->relatorioMedio;
    }

    function setRelatorioMedio($relatorioMedio) {
        $this->relatorioMedio = $relatorioMedio;
    }

}
