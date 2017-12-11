<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DadosPrincipal.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados na tela principal
 */
class DadosPrincipal extends AbstractHelper {

    private $relatorioEquipe;

    public function __construct() {
        
    }

    public function __invoke($relatorioEquipe) {
        $this->setRelatorioEquipe($relatorioEquipe);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $pessoa = $this->view->pessoa;
        $hierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
        $classe = '?';
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);

        /* Calculo da sua classe */
        $metas = Funcoes::metaPorHierarquia($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
        $metas[0] = $metas[0] * $this->getRelatorioEquipe()['quantidadeLideres'];
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

        $somaClasse = $validacaoMembresia + $validacaoCulto;

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

        $html = '';

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
        $html .= '<p class="lead">' . $hierarquia . ' - Classe <span class="label label-' . $classClasse . ' label-sm">' . $classe . '</span></p>';
        /* media-body va-m */
        $html .= '</div>';

        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
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
