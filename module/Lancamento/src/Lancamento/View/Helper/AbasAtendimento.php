<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: Abas.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para abas do atendimento.
 */
class AbasAtendimento extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        $html = '';

        $urlBase = '/'.ConstantesLancamento::$ROUTE_LANCAMENTO_ATENDIMENTO;
        $urlBase2 = $urlBase . '/2';

        $html .= '<ul class="nav panel-tabs-border panel-tabs panel-tabs-left">';
        $html .= '<li role="presentation" ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 1) . '><a href="' . $urlBase . '">' . $this->view->translate(ConstantesLancamento::$TRADUCAO_MES_ATUAL) . '</a></li>';

        $html .= '<li role="presentation" ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 2) . '><a href="' . $urlBase2 . '">' . $this->view->translate(ConstantesLancamento::$TRADUCAO_MES_ANTERIOR) . '</a></li>';

        $html .= '</ul>';

        return $html;
    }
}    
    