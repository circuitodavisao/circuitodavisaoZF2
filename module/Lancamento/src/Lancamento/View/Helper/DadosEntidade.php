<?php

namespace Lancamento\View\Helper;

use Entidade\Entity\Entidade;
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
        $html .= '<div class="media">';
        if ($pessoas) {
            /* Fotos */
            $contagemFotos = 1;
            foreach ($pessoas as $p) {
                if ($contagemFotos == 2) {
                    $html .= '&nbsp;';
                }
                $html .= '<a class="media-left" href="#">';
                $html .= '<img src="/img/avatars/1.jpg" data-holder-rendered="true" style="width:50px; height:50px;"/>&nbsp;';
                $html .= '</a>';
                $contagemFotos++;
            }
            $html .= '<div class="media-body">';
            /* Nomes */
            $contagem = 1;
            foreach ($pessoas as $p) {
                if ($contagem == 2) {
                    $html .= '&nbsp;&&nbsp;';
                }
                $html .= '<h4 class="media-heading">';
                $html .= $p->getNomePrimeiroUltimo();
                $html .= '<small class="text-muted">&nbsp;@blackbelt</small>';
                $contagem++;
            }
            
            /* Entidade */
            $entidadeTipo = $this->view->entidade->getEntidadeTipo();
            $html .= '</h4>';
            $html .= $this->view->entidade->infoEntidade();
            $html .= '<br />';
            $html .= '<a class="text-system" href="#">';
            $html .= $entidadeTipo->getNome();
            $html .= '</a>';
            $html .= '</div>';
        }
        return $html;
    }

}
