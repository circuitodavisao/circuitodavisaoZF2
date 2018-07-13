<?php

namespace Application\View\Helper;

use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DadosPrincipal.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados na tela principal
 */
class DadosPrincipal extends AbstractHelper {   

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $classeTela = '?';
        $classClasseTela = 'default';

        $html .= '<div class="page-heading">';
        $html .= '<div class="media clearfix">';

        $html .= '<div class="media-left pr5">';
        $html .= '<a href="/perfil" onClick="mostrarSplash();">';
        $html .= FuncoesEntidade::tagImgComFotoDaPessoa(null, 128);
        $html .= '</a>';
        /* media-left pr30 */
        $html .= '</div>';

        $html .= '<div class="media-body va-m">';
        $html .= '<h2 class="media-heading" id="spanNomeDeQuemEstaLogado"></h2>';
        $html .= '<p class="lead">';
        $html .= 'Classe <span onclick="mostrarModalClasse();" >';
        $html .= '<span id="classePessoal" class="relatorioPessoal label label-sm"></span>';
        $html .= '<span id="classeEquipe" class="relatorioEquipe hidden label label-sm"></span>';
        $html .= '&nbsp;<span class="badge">?</span>';
        $html .= '</span>';
        $html .= '</p>';
        $html .= '</div>';

        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        /* Modal */
        $html .= '<div id = "modalClassificacao" class="popup-basic p25 mfp-with-anim mfp-hide">';
        $html .= '<div id="divModalDadosPrincipais"></div>';
        $html .= '<button tittle = "Close (Esc)" type = "button" class="mfp-close bg-dark">x</button>';
        $html .= '</div>';

        return $html;
    }

}
