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

        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $valorMembresia = $this->getRelatorioPessoal()['membresia'];
            $valorCelulaQuantidade = $this->getRelatorioPessoal()['celulaQuantidade'];
            $valorCelulaValor = $this->getRelatorioPessoal()['celula'];
        } else {
            $valorMembresia = $this->getRelatorioEquipe()['membresia'];
            $valorCelulaQuantidade = $this->getRelatorioEquipe()['celulaQuantidade'];
            $valorCelulaValor = $this->getRelatorioEquipe()['celula'];
        }
        $perfomanceMembresia = $valorMembresia / $metas[0] * 100;
        $perfomanceCelula = $valorCelulaQuantidade / $metas[1] * 100;
        $validacaoCelulaDeElite = $valorCelulaValor >= Constantes::$META_CELULA_DE_ELITE;
        if ($perfomanceMembresia > 100) {
            $perfomanceMembresia = 100;
        }
        if ($perfomanceCelula > 100) {
            $perfomanceCelula = 100;
        }
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            if ($validacaoCelulaDeElite) {
                $perfomanceCelulaDeElite = 34;
            } else {
                $perfomanceCelulaDeElite = 0;
            }
            $validacaoMembresia = $perfomanceMembresia / 3;
            $validacaoCulto = $perfomanceCelula / 3;

            $somaClasse = $validacaoMembresia + $validacaoCulto + $perfomanceCelulaDeElite;
        } else {
            $validacaoMembresia = $perfomanceMembresia / 2;
            $validacaoCulto = $perfomanceCelula / 2;

            $somaClasse = $validacaoMembresia + $validacaoCulto;
        }

        $classClasse = 'default';
        if ($somaClasse < 50) {
            $classe = 'C';
            $classClasse = 'danger';
        }
        if ($somaClasse >= 50 && $somaClasse < 100) {
            $classe = 'B';
            $classClasse = 'warning';
        }
        if ($somaClasse >= 100) {
            $classe = 'A';
            $classClasse = 'success';
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
        $html .= '<h2 class="media-heading">' . $pessoa->getNomePrimeiroUltimo();
//        $html .= '<small> - </small><button disabled class="btn btn-xs btn-info">Perfil</button>';
        $html .= '</h2>';
        $html .= '<p class="lead">' . $hierarquia . ' - Classe <span onclick="mostrarModalClasse();" class="label label-' . $classClasse . ' label-sm">' . $classe . '</span></p>';
        /* media-body va-m */
        $html .= '</div>';

        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center">Cálculo da Classe</h1>';
        $mensagemModalClasse .= '<p>Média dos ultimos 4 periodos de membresia e células pela meta da sua hierarquia.</p>';
        $mensagemModalClasse .= '<ul><li>Meta de membresia: 6 vezes numeros de líderes</li>';
        $mensagemModalClasse .= '<li>Meta de Célula: 1 célula</li>';
        $fimIndice = 1;
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $mensagemModalClasse .= '<li>Meta de Célula de Elite: Todas as suas celulas pessoais tem que ser de Elite</li>';
            $fimIndice = 2;
        }
        $mensagemModalClasse .= '</ul>';

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
                    $corBarra = 'info';
                    $valorBarra = $perfomanceMembresia;
                    break;
                case 1:
                    $stringMeta = 'Célula';
                    $indiceRelatorio = 'celulaQuantidade';
                    $corBarra = 'system';
                    $valorBarra = $perfomanceCelula;
                    break;
                case 2:
                    $stringMeta = 'Cél. Elite';
                    $indiceRelatorio = 'celulaQuantidade';
                    $corBarra = 'success';
                    $valorBarra = $validacaoCelulaDeElite * 100;
                    break;
            }

            $mensagemModalClasse .= '<div class = "row">';

            $mensagemModalClasse .= '<div class = "col-xs-3">' . $stringMeta . '</div>';
            $mensagemModalClasse .= '<div class = "col-xs-6">';
            $mensagemModalClasse .= '<div class = "progress">';
            $mensagemModalClasse .= '<div class = "progress-bar progress-bar-' . $corBarra . '" role = "progressbar" aria-valuenow = "' . $valorBarra . '" aria-valuemin = "0" aria-valuemax = "100" style = "width: ' . $valorBarra . '%;">' . RelatorioController::formataNumeroRelatorio($valorBarra) . '%</div>';
            $mensagemModalClasse .= '</div>';
            $mensagemModalClasse .= '</div>';
            if ($indice != 2) {
                $mensagemModalClasse .= '<div class = "col-xs-3">' . RelatorioController::formataNumeroRelatorio($arrayRelatorio[$indiceRelatorio]) . '/' . $metas[$indice] . '</div>';
            }

            $mensagemModalClasse .= '</div>';
        }

        $html .= '<div id="modalClassificacao" class="popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div>' . $mensagemModalClasse . '</div>';
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
