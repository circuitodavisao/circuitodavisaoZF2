<?php

namespace Application\View\Helper;

use Doctrine\Common\Collections\Criteria;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemConsolidacaoParaRevisao extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke($todos = null) {
		return $this->renderHtml($todos);
	}

	public function renderHtml($todos) {
		$html = '';
		$mesSelecionado = date("m");
		$anoSelecionado = date("Y");
		$pessoas = array();
		$pessoasGrupo = array();
		$grupo = $this->view->entidade->getGrupo();
		$quantidade = 5;
		if($todos){
			$quantidade = null;
		}
		if($grupoPessoasAtivasDoMes = $grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado, $quantidade)) {
			foreach ($grupoPessoasAtivasDoMes as $gp) {
				$pessoasGrupo[] = $gp->getPessoa();
			}
		}

		/* Ordenacao de pessoas */
		$valores = array();
		foreach ($pessoasGrupo as $pg) {
			$valor = 0;
			switch ($pg->getTipo()) {
			case 'CO':
				$valor = 4;
				break;
			case 'VI':
				$valor = 1;
				break;
			}
			$valores[$pg->getId()] = $valor;
		}

		$pA = array();
		$res = array();
		for ($i = 0; $i < count($pessoasGrupo); $i++) {
			for ($j = 0; $j < count($pessoasGrupo); $j++) {
				$pA[1] = $pessoasGrupo[$i];
				$pA[2] = $pessoasGrupo[$j];
				$res[1] = $valores[$pA[1]->getId()];
				$res[2] = $valores[$pA[2]->getId()];
				if ($res[1] > $res[2]) {
					$pessoasGrupo[$i] = $pA[2];
					$pessoasGrupo[$j] = $pA[1];
				}
			}
		}
		foreach ($pessoasGrupo as $pgA) {
			$pessoas[] = $pgA;
		}
		/* FIM Ordenacao de pessoas */

		/* Sem pessoas cadastrados */
		if (count($pessoas) == 0) {
			$html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Pessoas Cadastradas como visitante ou consolidação no lançamento de dados!</div>';
		} else {

			$html .= $this->view->templateFormularioTopo('Selecionar pessoa para o revisão');
			$html .= '<div class="panel-body bg-light">';
			$html .= '<div class="alert alert-info alert-sm">';

			if($todos === null){	
				$html .= 'Últimos 5 cadastrados - ' ;
				$html .= $this->view->botaoLink('Ver Todos', '/cadastroSelecionarRevisionistaTodos', 4);
			}
			if($todos){	
				$html .= 'Todos cadastrados - ' ;
				$html .= $this->view->botaoLink('Ver Últimos 5', '/cadastroSelecionarRevisionista', 4);
			}

			$html .= '</div>';

			$html .= '<table class="table">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_TIPO_REVISIONISTA);
			$html .= '</th>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
			$html .= '</th>';
			$html .= '<th class="text-center"></th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ($pessoas as $pessoa) {
				$html .= '<tr>';
				$html .= '<td class="text-center">' . $pessoa->getTipo() . '</td>';

				$stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO . '", ' . $pessoa->getId() . ')';

				$html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';

				$html .= '<td class="text-center">';

				$html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . '  ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
				$html .= '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			$html .= '</div>';
			/* Fim panel-body */
			$html .= '<div class="panel-footer text-right">';
			/* Botões */
			$stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
			$html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));

			/* Fim Botões */
			$html .= '</div>';
			/* Fim panel-footer */
			$html .= $this->view->templateFormularioRodape();
		}

		return $html;
	}

}
