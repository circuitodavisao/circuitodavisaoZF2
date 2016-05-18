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
        if ($pessoas) {
            /* Fotos */
            $contagemFotos = 1;
            foreach ($pessoas as $p) {
                if ($contagemFotos == 2) {
                    $html .= '&nbsp;';
                }
                $html .= '<img class="img-rounded" width="64px" height="64px" src="/img/avatars/1.jpg" />&nbsp;';
                $contagemFotos++;
            }
            $html .= '<p>';
            /* Nomes */
            $contagem = 1;
            foreach ($pessoas as $p) {
                if ($contagem == 2) {
                    $html .= '&nbsp;&&nbsp;';
                }
                $html .= $p->getNomePrimeiroUltimo();
                $contagem++;
            }
            $html .= '</p>';
            /* Entidade */
            $entidadeTipo = $this->view->entidade->getEntidadeTipo();
            $html .= '<p>';
            $html .= $this->view->entidade->infoEntidade();
            $html .= '&nbsp;-&nbsp;';
            $html .= $entidadeTipo->getNome();
            $html .= '</p>';
        }
        return $html;
    }

}
