<?php

namespace Application\View\Helper;

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

    const MEMBRESIA = 'membresia';
    const PESSOAL = 'pessoal';
    const EQUIPE = 'equipe';
    const ORIGINAL = 'original';
    const ORIGINAL_PERFORMANCE = 'originalPerformance';
    const VISUAL = 'visual';
    const CLASSE_VALOR = 'classeValor';
    const CLASSE_PERIODO = 'classePeriodo';
    const CLASSE_COR = 'classeCor';
    const CLASSE_COR_MOSTRAGEM = 'classeCorMostragem';
    const CLASSE_STRING = 'classeString';
    const CLASSE_STRING_MOSTRAGEM = 'classeStringMostragem';

    public function __construct() {
        
    }

    public function __invoke($relatorioMedio) {
        $this->setRelatorioMedio($relatorioMedio);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $classeTela = '?';
        $classClasseTela = 'default';

        $pessoa = $this->view->pessoa;
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);
        $metas[0] = Funcoes::metaPorHierarquia(Hierarquia::LIDER_DE_CELULA);
        $metas[1] = Funcoes::metaPorHierarquia($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
        $multiplicadorDaMeta = 1;
        if ($this->view->eCasal) {
            $multiplicadorDaMeta = 2;
        }
        $metas[0][0] *= $multiplicadorDaMeta;
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $metas[1][0] *= $multiplicadorDaMeta;
        }

        $respostaBase = 'Porque a sua eficiência com relação a média de membresia e quantidade de célula do mês de #mes #periodo';

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center"><b>O que é a Classe?</b></h1>';
        $mensagemModalClasse .= '<p><b>Resposta:</b> É a classificação do resultado do líder baseado na média de membresia e quantidade de pesoas em células do mês anterior.</p>';

        for ($indiceDeRelatorios = 1; $indiceDeRelatorios <= 2; $indiceDeRelatorios++) {
            if ($indiceDeRelatorios === 1) {
                $relatorio = $this->view->relatorioAnterior;
            }
            if ($indiceDeRelatorios === 2) {
                $relatorio = $this->view->relatorio;
            }

            /* Mês de apresentação */
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
            /* FIM Mês de apresentação */

            $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL] = $relatorio[0]['mediaMembresia'];
            $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE] = $relatorio[0]['mediaMembresia'] / $metas[0][0] * 100;
            $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL] = $relatorio[(count($relatorio) - 1)]['mediaMembresia'];
            $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE] = $relatorio[(count($relatorio) - 1)]['mediaMembresia'] / $metas[1][0] * 100;

            $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] = $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE];
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] > 100) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] = 100;
            }
            $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] = $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE];
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] > 100) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] = 100;
            }

            $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] = 100;
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] < $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR]) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] = $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL];
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_D &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_C) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'D';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'é menor que 50%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_C &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_B) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'C';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'ficou entre 50% a 74%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_B &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'B';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'ficou entre 75% a 99%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'A';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'é maior que 100%';
            }

            $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] = 100;
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] < $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR]) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] = $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL];
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_D &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_C) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'D';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'é menor que 50%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_C &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_B) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'C';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'ficou entre 50% a 74%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_B &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'B';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'ficou entre 75% a 99%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'A';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'é maior que 100%';
            }

            $respostaAjustada[self::PESSOAL] = str_replace('#periodo', $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO], $respostaMesAjustado);
            $respostaAjustada[self::EQUIPE] = str_replace('#periodo', $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO], $respostaMesAjustado);
            $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] = RelatorioController::corDaLinhaPelaPerformanceClasse($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING]);
            $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] = RelatorioController::corDaLinhaPelaPerformanceClasse($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING]);

            if ($indiceDeRelatorios === 1) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR_MOSTRAGEM] = $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR];
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING_MOSTRAGEM] = $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING];
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR_MOSTRAGEM] = $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR];
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING_MOSTRAGEM] = $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING];
            }

            $mensagemModalClasse .= '<div class="alert alert-default alert-sm">';
            if ($indiceDeRelatorios === 1) {
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Porque sou Classe </b><span class="label label-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . ' label-sm">' .
                        $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] . ' </span></p>';
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Resposta:</b> ' . $respostaAjustada[self::PESSOAL] . '</p>';

                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Porque sou Classe </b><span class="label label-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . ' label-sm">' .
                        $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] . ' </span></p>';
                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Resposta:</b> ' . $respostaAjustada[self::EQUIPE] . '</p>';
            }
            if ($indiceDeRelatorios === 2) {
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Como estou em ' . $mesPorExtenso . '?</b></p>';
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Resposta:</b> Estima-se que no mês de ' . $mesPorExtenso . ' você provavelmente será classe '
                        . '<span class="label label-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . ' label-sm">' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] . ' </span></p>';

                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Como estou em ' . $mesPorExtenso . '?</b></p>';
                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Resposta:</b> Estima-se que no mês de ' . $mesPorExtenso . ' você provavelmente será classe '
                        . '<span class="label label-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . ' label-sm">' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] . ' </span></p>';
            }
            $mensagemModalClasse .= '</div>';

            /* Barra Membresia */
            $mensagemModalClasse .= '<div class="row">';
            $mensagemModalClasse .= '<div class="col-xs-4 text-right" style="font-size:10px;">Membresia</div>';
            $mensagemModalClasse .= '<div class="col-xs-5">';
            $mensagemModalClasse .= '<div class="progress">';
            $mensagemModalClasse .= '<div class="relatorioPessoal progress-bar progress-bar-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . '" role = "progressbar" '
                    . 'aria-valuenow="' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] . '" '
                    . 'aria-valuemin="0" aria-valuemax="100" style="width: ' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] . '%;">' .
                    number_format($performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE], 2, ',', '.') . '%</div>';
            $mensagemModalClasse .= '<div class="relatorioEquipe hidden progress-bar progress-bar-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . '" role = "progressbar" '
                    . 'aria-valuenow="' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] . '" '
                    . 'aria-valuemin="0" aria-valuemax="100" style="width: ' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] . '%;">' .
                    number_format($performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE], 2, ',', '.') . '%</div>';
            $mensagemModalClasse .= '</div>';
            $mensagemModalClasse .= '</div>';
            $mensagemModalClasse .= '<div class="relatorioPessoal col-xs-3" style="font-size:10px;">' . number_format($performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL], 2, ',', '.') . ' de ' . $metas[0][0] . '</div>';
            $mensagemModalClasse .= '<div class="relatorioEquipe hidden col-xs-3" style="font-size:10px;">' . number_format($performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL], 2, ',', '.') . ' de ' . $metas[1][0] . '</div>';
            $mensagemModalClasse .= '</div>';
        }

        $html .= '<div class="page-heading">';
        $html .= '<div class="media clearfix">';

        $html .= '<div class="media-left pr30">';
        $html .= '<a href="#">';
        $html .= '<img width="64px" height="64px" class="media-object img-rounded" src="/img/fotos/' . $imagem . '" alt="...">';
        $html .= '</a>';
        /* media-left pr30 */
        $html .= '</div>';

        $html .= '<div class="media-body va-m">';
        $html .= '<h2 class="media-heading">' . $pessoa->getNomePrimeiroUltimo() . '</h2>';
        $html .= '<p class="lead">';
        $html .= 'Classe <span onclick="mostrarModalClasse();" >';
        $html .= '<span class="relatorioPessoal label label-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR_MOSTRAGEM] . ' label-sm">' .
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING_MOSTRAGEM] . ' </span>';
        $html .= '<span class="relatorioEquipe hidden label label-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR_MOSTRAGEM] . ' label-sm">' .
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING_MOSTRAGEM] . ' </span>';
        $html .= '&nbsp;<span class="badge">?</span>';
        $html .= '</span>';
        $html .= '</p>';
        $html .= '</div>';

        /* MEDALHAS */
        $minhaHierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia();
        if ($minhaHierarquia->getId() < Hierarquia::LIDER_DE_CELULA) {
            $html .= '<div class="media-links">';
            $html .= '<ul class="list-inline list-unstyled">';

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
        }
        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        /* Modal */
        $html .= '<div id = "modalClassificacao" class="popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div >' . $mensagemModalClasse . '</div>';
        $html .= '<button tittle = "Close (Esc)" type = "button" class="mfp-close bg-dark">x</button>';
        $html .= '</div>';

        return $html;
    }

    function getRelatorioMedio() {
        return $this->relatorioMedio;
    }

    function setRelatorioMedio($relatorioMedio) {
        $this->relatorioMedio = $relatorioMedio;
    }

}
