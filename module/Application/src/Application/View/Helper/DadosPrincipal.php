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
        $pessoa = $this->view->pessoa;
        $hierarquia = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
        $classe = '?';
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);
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
        $html .= '<p class="lead">' . $hierarquia . ' - <b>Classe <span class="label label-default label-sm">' . $classe . '</span></b></p>';
        /* media-body va-m */
        $html .= '</div>';

        /* media clearfix */
        $html .= '</div>';
        /* page-heading */
        $html .= '</div>';

        return $html;
    }

}
