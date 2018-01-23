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
        $classe = '?';
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);
        if ($this->view->idRelatorio == 1) {
            $metaDeQuem = Hierarquia::LIDER_DE_CELULA;
        }
        if ($this->view->idRelatorio == 2) {
            $metaDeQuem = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId();
        }
        $metas = Funcoes::metaPorHierarquia($metaDeQuem);
        $multiplicadorDaMeta = 1;
        if ($this->view->eCasal) {
            $multiplicadorDaMeta = 2;
        }
        $respostaBase = 'Porque a sua eficiência com relação a média de membresia e quantidade de célula do mês de #mes #periodo';

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center"><b>O que é a Classe?</b></h1>';
        $mensagemModalClasse .= '<p><b>Resposta:</b> É a classificação do resultado do líder baseado na média de membresia e quantidade de pesoas em células do mês anterior.</p>';

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

            $nomeRelatorio = 'pessoal' . $atualOuAnterior;
            $qualRelatorio = $this->getRelatorioMedio()[$nomeRelatorio];
            $qualRelatorioCelula = $this->getRelatorioMedio()['celulas' . $atualOuAnterior];

            $fimIndice = 0;
            if ($this->view->idRelatorio == 1) {
                $fimIndice += count($qualRelatorioCelula);
            }
            if ($this->view->idRelatorio == 2) {
                $fimIndice += 1;
            }

            $perfomanceMembresia = $qualRelatorio['membresiaPerformance'];
            $perfomanceCelula = $qualRelatorio['celulaPerformance'];

            if ($this->view->idRelatorio == 2) {
                $perfomanceMembresia = $qualRelatorio['membresia'] / $metas[0] * 100;
                $perfomanceCelula = $qualRelatorio['celula'] / $metas[0] * 100;
            }

            $perfomanceMembresiaVisual = $perfomanceMembresia;
            if ($perfomanceMembresia > 100) {
                $perfomanceMembresiaVisual = 100;
            }

            $perfomanceCelulaVisual = $perfomanceCelula;
            if ($perfomanceCelula > 100) {
                $perfomanceCelulaVisual = 100;
            }

            $classeMaxima = '100';
            if ($this->view->idRelatorio == 1 && $qualRelatorioCelula) {
                foreach ($qualRelatorioCelula as $valorCelula) {
                    $perfomanceCelulaDeElite = $valorCelula['valor'] / Constantes::$META_LIDER * 100;
                    if ($perfomanceCelulaDeElite > 100) {
                        $perfomanceCelulaDeElite = 100;
                    }
                    if ($perfomanceCelulaDeElite < $classeMaxima) {
                        $classeMaxima = $perfomanceCelulaDeElite;
                    }
                }
            }
            if ($perfomanceMembresiaVisual < $classeMaxima) {
                $classeMaxima = $perfomanceMembresiaVisual;
            }
            if ($this->view->idRelatorio == 2) {
                if ($perfomanceCelulaVisual < $classeMaxima) {
                    $classeMaxima = $perfomanceCelulaVisual;
                }
            }

            if ($indiceDeRelatorios === 2) {
                $mesPorExtenso = Funcoes::mesPorExtenso(date('m'), 1);
            }
            if ($indiceDeRelatorios === 1) {
                $mesAnterior = date('m') - 1;
                if (date('m') == 1) {
                    $mesAnterior = 12;
                }
                $mesPorExtenso = Funcoes::mesPorExtenso($mesAnterior, 1);
            }
            $respostaMesAjustado = str_replace('#mes', $mesPorExtenso, $respostaBase);

            if ($classeMaxima >= RelatorioController::MARGEM_D && $classeMaxima < RelatorioController::MARGEM_C) {
                $classe = 'D';
                $periodo = 'é menor que 50%';
            }
            if ($classeMaxima >= RelatorioController::MARGEM_C && $classeMaxima < RelatorioController::MARGEM_B) {
                $classe = 'C';
                $periodo = 'ficou entre 50% a 74%';
            }
            if ($classeMaxima >= RelatorioController::MARGEM_B && $classeMaxima < RelatorioController::MARGEM_A) {
                $classe = 'B';
                $periodo = 'ficou entre 75% a 99%';
            }
            if ($classeMaxima >= RelatorioController::MARGEM_A) {
                $classe = 'A';
                $periodo = 'é maior que 100%';
            }
            $respostaAjustada = str_replace('#periodo', $periodo, $respostaMesAjustado);

            $classClasse = RelatorioController::corDaLinhaPelaPerformanceClasse($classe);

            $mensagemModalClasse .= "<div class='alert alert-default alert-sm'>";
            if ($indiceDeRelatorios === 1) {
                $mensagemModalClasse .= '<p><b>Porque sou Classe </b><span class="label label-' . $classClasse . ' label-sm">' . $classe . ' </span></p>';
                $mensagemModalClasse .= '<p><b>Resposta:</b> ' . $respostaAjustada . '</p>';
            }
            if ($indiceDeRelatorios === 2) {
                $mensagemModalClasse .= '<p><b>Como estou em ' . $mesPorExtenso . '?</b></p>';
                $mensagemModalClasse .= '<p><b>Resposta:</b> Estima-se que no mês de ' . $mesPorExtenso . ' você provavelmente será classe <span class="label label-' . $classClasse . ' label-sm">' . $classe . ' </span></p>';
            }
            $mensagemModalClasse .= "</div>";
            $mensagemModalClasse .= $this->montaBarrasDeProgresso($fimIndice, $qualRelatorio, $multiplicadorDaMeta, $metas, $qualRelatorioCelula, $this->view->idRelatorio);

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
        $html .= '<p class="lead">';
        $html .= 'Classe <span onclick="mostrarModalClasse();" ><span class="label label-' . $classClasseTela . ' label-sm">' . $classeTela . ' </span>&nbsp;<span class="badge">?</span></span>';
        $html .= '</p>';
        $html .= '</div>';

        $html .= '<div class="media-links">';
        $minhaHierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia();
        if ($minhaHierarquia->getId() < Hierarquia::LIDER_DE_CELULA) {
            for ($indiceIdRelatorio = 1; $indiceIdRelatorio <= 2; $indiceIdRelatorio++) {
                $checked = '';
                if ($indiceIdRelatorio == 1) {
                    $label = 'Pessoal';
                    if ($this->view->idRelatorio == 1) {
                        $checked = 'checked';
                    }
                }
                if ($indiceIdRelatorio == 2) {
                    $label = 'Equipe';
                    if ($this->view->idRelatorio == 2) {
                        $checked = 'checked';
                    }
                }
                $html .= ' ' . $label . ' <input type="radio" name="qualRelatorio" onclick=" $(\'.splash\').css(\'display\', \'block\'); location.href=\'/principal/' . $indiceIdRelatorio . '\'" ' . $checked . ' />';
            }
        }
        $html .= '</div>';

        if ($minhaHierarquia->getId() < Hierarquia::LIDER_DE_CELULA) {
            $html .= '<div class = "media-links">';
            $html .= '<ul class = "list-inline list-unstyled">';

            foreach ($this->view->hierarquias as $hierarquia) {
                $corDaMedalha = 'default';
                if ($hierarquia->getId() >= $minhaHierarquia->getId()) {
                    $corDaMedalha = 'info';
                }
                $html .= '<li>';
                $html .= '<span class = "label label-xs label-' . $corDaMedalha . '">' . $hierarquia->getSigla() . '</span>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
        }
        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        /* Modal */
        $html .= '<div id = "modalClassificacao" class = "popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div >' . $mensagemModalClasse . '</div>';
        $html .= '<button tittle = "Close (Esc)" type = "button" class = "mfp-close bg-dark">x</button>';
        $html .= '</div>';

        return $html;
    }

    function montaBarrasDeProgresso($fimIndice, $qualRelatorio, $multiplicadorDaMeta, $metas, $qualRelatorioCelula, $idRelatorio) {
        $html = '';
        for ($indice = 0; $indice <= $fimIndice; $indice++) {

            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $performance = $qualRelatorio[$indiceRelatorio . 'Performance'];
                    if ($this->view->idRelatorio == 2) {
                        $performance = $qualRelatorio[$indiceRelatorio] / $metas[0] * 100;
                    }
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($performance);
                    $valorBarra = $performance > 100 ? 100 : $performance;
                    $valorApresentado = RelatorioController::formataNumeroRelatorio($qualRelatorio[$indiceRelatorio]);
                    $labelBarra = $performance;
                    $valorMeta = $metas[0] * $multiplicadorDaMeta;
                    if ($this->view->idRelatorio == 2) {
                        $valorMeta = $metas[0];
                    }
                    break;
                case 1:
                    if ($idRelatorio == 1) {
                        $indiceRelatorio = 0;
                        $stringMeta = 'Cél. ' . $qualRelatorioCelula[$indiceRelatorio]['hospedeiro'];
                        $valorApresentado = RelatorioController::formataNumeroRelatorio($qualRelatorioCelula[$indiceRelatorio]['valor']);
                        $valorMeta = $metas[0];
                        $labelBarra = $valorApresentado / $valorMeta * 100;
                        $valorBarra = $labelBarra;
                        $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    }
                    if ($idRelatorio == 2) {
                        $stringMeta = 'Célula';
                        $indiceRelatorio = 'celula';
                        $performance = $qualRelatorio[$indiceRelatorio . 'Performance'];
                        if ($this->view->idRelatorio == 2) {
                            $performance = $qualRelatorio[$indiceRelatorio] / $metas[0] * 100;
                        }
                        $corBarra = RelatorioController::corDaLinhaPelaPerformance($performance);
                        $valorBarra = $performance > 100 ? 100 : $performance;
                        $valorApresentado = RelatorioController::formataNumeroRelatorio($qualRelatorio[$indiceRelatorio]);
                        $labelBarra = $performance;
                        $valorMeta = $metas[0] * $multiplicadorDaMeta;
                        if ($this->view->idRelatorio == 2) {
                            $valorMeta = $metas[0];
                        }
                    }
                    break;
                case 2:
                    $indiceRelatorio = 1;
                    $stringMeta = 'Cél. ' . $qualRelatorioCelula[$indiceRelatorio]['hospedeiro'];
                    $valorApresentado = RelatorioController::formataNumeroRelatorio($qualRelatorioCelula[$indiceRelatorio]['valor']);
                    $valorMeta = $metas[0];
                    $labelBarra = $valorApresentado / $valorMeta * 100;
                    $valorBarra = $labelBarra;
                    $corBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
                    break;
            }
            $labelBarra = RelatorioController::formataNumeroRelatorio($labelBarra);
            if ($valorBarra > 100) {
                $valorBarra = 100;
            }
            $html .= '<div class = "row">';
            $html .= '<div class = "col-xs-4 text-right" style="font-size:10px;">' . $stringMeta . '</div>';
            $html .= '<div class = "col-xs-5">';
            $html .= '<div class = "progress">';
            $html .= '<div class = "progress-bar progress-bar-' . $corBarra . '" role = "progressbar" aria-valuenow = "' . $valorBarra . '" aria-valuemin = "0" aria-valuemax = "100" style = "width: ' . $valorBarra . '%;">' . $labelBarra . '%</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class = "col-xs-3" style="font-size:10px;">' . $valorApresentado . ' de ' . $valorMeta . '</div>';
            $html .= '</div>

            

            ';
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
