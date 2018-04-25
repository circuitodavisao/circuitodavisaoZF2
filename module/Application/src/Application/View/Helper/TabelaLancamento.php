<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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
        $centerBlock = Constantes::$CLASS_CENTER_BLOCk;
        $style = 'style="width:100%;"';

        $html = '';
        $html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Filtro">';
        $html .= '<table ' . $style . ' class="' . $centerBlock . ' table table-condensed scroll text-center bordas-tabela-principal footable" data-filter="#fooFilter">';

        $html .= '<thead>';
        $html .= $this->view->cabecalhoDeEventos();
        $html .= '</thead>';

        $html .= '<tbody>';
        $html .= $this->view->ListagemDePessoasComEventos();
        $html .= '</tbody>';

        $html .= '</table>';
        return $html;
    }

}
