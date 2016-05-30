<?php

namespace Lancamento\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TabelaLancamento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a tabela para lançamento de dados
 */
class TabelaLancamento extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<table class="table table-condensed scroll text-center">';

        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="tdTipo"></th>';
        $html .= '<th class="tdNome"></th>';
        $html .= $this->view->cabecalhoDeEventos();
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $html .= $this->view->ListagemDePessoasComEventos();
        $html .= '</tbody>';

        $html .= '</table>';
        return $html;
    }

}
