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
        /* Validacao para poucos eventos 1 a 3 */
        switch ($this->view->quantidadeDeEventosNoCiclo) {
            case 1:
                $centerBlock = 'center-block';
                $style = 'style="width:174px;"';
                break;
            case 2:
                $centerBlock = 'center-block';
                $style = 'style="width:217px;"';
                break;
            case 3:
                $centerBlock = 'center-block';
                $style = 'style="width:260px;"';
                break;
            default:
                $centerBlock = '';
                $style = '';
                break;
        }

        $html = '';
        $html .= '<table ' . $style . ' class="' . $centerBlock . ' table table-condensed scroll text-center">';

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
