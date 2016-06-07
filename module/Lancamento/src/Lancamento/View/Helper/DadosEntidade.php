<?php

namespace Lancamento\View\Helper;

use Entidade\Entity\FuncoesEntidade;
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
        $html .= '<div class="media visible-xs-block" style="padding: 15px 0px 15px 10px;">';
        if ($pessoas) {
            /* Fotos */
            $contagemFotos = 1;
            foreach ($pessoas as $p) {
                if ($contagemFotos == 2) {
                    $html .= '&nbsp;';
                }
                $html .= '<a class="media-left" href="#">';
                $html .= FuncoesEntidade::tagImgComFotoDaPessoa($p);
                $html .= '</a>';
                $contagemFotos++;
            }
            $html .= '<div class="media-body">';
            /* Nomes */
            $contagem = 1;
            $totalPessoas = count($pessoas);
            foreach ($pessoas as $p) {
                if ($contagem == 2) {
                    $html .= '&nbsp;&&nbsp;';
                }
                $html .= '<h4 class="media-heading">';
                if ($totalPessoas == 1) {
                    $html .= $p->getNomePrimeiroUltimo();
                } else {
                    $html .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                }
                $contagem++;
            }

            /* Entidade */
            $entidadeTipo = $this->view->entidade->getEntidadeTipo();
            $html .= '</h4>';
            $html .= $this->view->entidade->infoEntidade();
            $html .= '&nbsp;-&nbsp;';
            $html .= $entidadeTipo->getNome();
            $html .= '</div>';
        }
        return $html;
    }

}
