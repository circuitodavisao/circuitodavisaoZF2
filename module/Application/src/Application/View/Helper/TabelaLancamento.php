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
		$html .= '<div class="panel bg-info" style="margin-bottom: 5px">';
		$html .= '<div class="panel-body p5">';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
		$html .= '<input id="filtro" type="text" class="form-control" placeholder="Filtro" onKeyUp="filtrar();" />';
		$html .= '</div>';
		$html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
		$html .= '<div class="row">';
		$html .= $this->view->cabecalhoDeEventos();
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= $this->view->ListagemDePessoasComEventos();
		return $html;
	}
}
