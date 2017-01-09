<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: Abas.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar as abas no lançamento de dados
 */
class Abas extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';

        $urlBase = $this->view->url(Constantes::$ROUTE_LANCAMENTO);
        $urlBase2 = $urlBase . '/2';

        $html .= '<ul class="nav panel-tabs-border panel-tabs panel-tabs-left">';
        $html .= '<li role="presentation" ' . Constantes::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 1) . '><a href="' . $urlBase . '">' . $this->view->translate(Constantes::$TRADUCAO_MES_ATUAL) . '</a></li>';
        if ($this->view->validacaoNesseMes == 0) {
            $html .= '<li role="presentation" ' . Constantes::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 2) . '><a href="' . $urlBase2 . '">' . $this->view->translate(Constantes::$TRADUCAO_MES_ANTERIOR) . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

}
