<?php

namespace Application\View\Helper;

use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DadosEntidade.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados da entidade
 */
class DadosEntidade extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $grupo = $this->view->entidade->getGrupo();
        $grupoResponsavel = $grupo->getResponsabilidadesAtivas();
        if ($grupoResponsavel) {
            $pessoas = array();
            foreach ($grupoResponsavel as $gr) {
                $p = $gr->getPessoa();
                $pessoas[] = $p;
            }
        }
        $html = '';

        $html .= '<div class="media media-dados-entidade" style="margin-right:0px;">';
        if ($pessoas) {
            /* Fotos */
            $html .= '<div class="row">';
            $contagemFotos = 1;
            $html .= '<span class="media-left" href="#" style="padding-right:2px;">';
            foreach ($pessoas as $p) {
                if ($contagemFotos == 2) {
                    $html .= '&nbsp;';
                }

                $html .= FuncoesEntidade::tagImgComFotoDaPessoa($p);

                $contagemFotos++;
            }
            $html .= '</span>';
            $html .= '</div>';

            /* Nomes */
            $html .= '<div class="row">';
            $html .= '<div class="media-body" style="line-height:1px; padding-top:11px;">';
            $contagem = 1;
            $totalPessoas = count($pessoas);
            $html .= '<h5 class="media-heading">';
            foreach ($pessoas as $p) {
                if ($contagem == 2) {
                    $html .= '&nbsp;&&nbsp;';
                }

                if ($totalPessoas == 1) {
                    $html .= $p->getNomePrimeiroUltimo();
                } else {// duas pessoas
                    $html .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                }
                $contagem++;
            }
            $html .= '</h5>';
            $html .= '</div>';

            /* Entidade */
            $entidadeTipo = $this->view->entidade->getEntidadeTipo();

            $html .= '<small style="font-size:10px;">';
            $html .= $this->view->entidade->infoEntidade();
            $html .= '</small>';
            $html .= '&nbsp;-&nbsp;';
            $html .= '<small style="font-size:10px; font-style:italic;">';
            $html .= $entidadeTipo->getNome();
            $html .= '</small>';
            $html .= '</div>';
        }
        return $html;
    }

}
