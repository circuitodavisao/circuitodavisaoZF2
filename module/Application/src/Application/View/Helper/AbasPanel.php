<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AbasPanel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar abas de panel
 */
class AbasPanel extends AbstractHelper {

    protected $rota;
    protected $complemento;

    public function __construct() {
        
    }

    public function __invoke($rota, $complemento = '') {
        $this->setRota($rota);
        $this->setComplemento($complemento);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $urlBase = $this->view->url($this->getRota()) . $this->getComplemento();
        $urlBase2 = $urlBase . '/2';

        $html .= '<div class="panel-heading">';
        $html .= '<ul class="nav panel-tabs-border panel-tabs panel-tabs-left">';
        $html .= '<li role="presentation" ' . $this->view->abaSelecionada($this->view->abaSelecionada, 1) . '><a href="' . $urlBase . '">' . $this->view->translate(Constantes::$TRADUCAO_MES_ATUAL) . '</a></li>';
        if ($this->view->validacaoNesseMes == 0) {
            $html .= '<li role="presentation" ' . $this->view->abaSelecionada($this->view->abaSelecionada, 2) . '><a href="' . $urlBase2 . '">' . $this->view->translate(Constantes::$TRADUCAO_MES_ANTERIOR) . '</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    function getRota() {
        return $this->rota;
    }

    function setRota($rota) {
        $this->rota = $rota;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

}
