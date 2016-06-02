<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
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

        $urlBase = $this->view->url(ConstantesLancamento::$ROUTE_LANCAMENTO);
        $urlBase2 = $urlBase . '/2';

        $html .= '<ul class="nav panel-tabs-border panel-tabs panel-tabs-left">';
        $html .= '<li role="presentation" ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 1) . '><a href="' . $urlBase . '">M&ecirc;s Atual</a></li>';
        $html .= '<li role="presentation" ' . ConstantesLancamento::$ONCLICK_ABRIR_MODAL . ' ' . $this->view->abaSelecionada($this->view->abaSelecionada, 2) . '><a href="' . $urlBase2 . '">M&ecirc;s Anterior</a></li>';
        $html .= '</ul>';
        $html .= '<div class="pull-right">';
        $html .= '<div class="btn-group">';
        $html .= '<button class="btn btn-primary btn-sm" onclick="location.href=\'/lancamentoCadastrarPessoa\';" title="Adicionar Pessoa"><i class="fa fa-plus"></i></button>';
        $html .= '</div>';
        $html .= '<div class="btn-group">';
        $html .= '<button class="btn btn-success btn-sm" onclick="location.href=\'/lancamentoEnviarRelatorio\';" title="Enviar Relatório"><i class="fa fa-file-text-o"></i></button>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
