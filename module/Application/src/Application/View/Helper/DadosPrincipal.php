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

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $pessoa = $this->view->pessoa;

        $html .= '<div class="page-heading">';
        $html .= '<div class="media clearfix">';

        $html .= '<div class="media-left pr5">';
        $html .= '<a href="/perfil" onClick="mostrarSplash();">';
        $html .= FuncoesEntidade::tagImgComFotoDaPessoa($pessoa, 128);
        $html .= '</a>';
        /* media-left pr30 */
        $html .= '</div>';

        $html .= '<div class="media-body va-m">';
        $html .= '<h2 class="media-heading">' . $pessoa->getNomePrimeiroUltimo() . '</h2>';
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

        return $html;
    }

}
