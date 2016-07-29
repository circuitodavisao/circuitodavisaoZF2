<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
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
        /* Validacao para poucos eventos 1 a 4 */
//        switch ($this->view->quantidadeDeEventosNoCiclo) {
//            case 1:
//                $centerBlock = ConstantesLancamento::$CLASS_CENTER_BLOCk;
//                $style = 'style="width:100%;"';
//                break;
//            case 2:
//                $centerBlock = ConstantesLancamento::$CLASS_CENTER_BLOCk;
//                $style = 'style="width:100%;"';
//                break;
//            case 3:
//                $centerBlock = ConstantesLancamento::$CLASS_CENTER_BLOCk;
//                $style = 'style="width:260px;"';
//                break;
//            case 4:
//                $centerBlock = ConstantesLancamento::$CLASS_CENTER_BLOCk;
//                $style = 'style="width:100%;"';
//                break;
//            default:
//                $centerBlock = '';
//                $style = '';
//                break;
//        }
        $centerBlock = ConstantesLancamento::$CLASS_CENTER_BLOCk;
        $style = 'style="width:100%;"';

        $html = '';
        $html .= '<table ' . $style . ' class="' . $centerBlock . ' table table-condensed scroll text-center bordas-tabela-principal">';

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
