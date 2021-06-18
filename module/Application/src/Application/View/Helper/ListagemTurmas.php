<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;
use Application\Model\Entity\Situacao;

/**
 * Nome: ListagemTurmas.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de pesoas ativas no revisão seleiconado
 */
class ListagemTurmas extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke() {
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';
		$turmas = $this->view->turmas;
		$turmasAtivas = array();
		foreach ($turmas as $turma) {
			if ($turma->verificarSeEstaAtivo() && ($turma->getAno() === intVal(date('y')) || $turma->getAno() === intVal(date('Y') - 1))) {
				$turmasAtivas[] = $turma;
			}
		}
		$html .= $this->view->templateFormularioTopo('Turmas', '', 'style="max-width: 100%; margin-top: 0%;"');
		$html .= '<div class="panel-body bg-light">';
		/* Sem pessoas cadastrados */
		if (count($turmasAtivas) == 0) {
			$html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Turmas</div>';
		} else {
			$html .= '<table class="table table-condensed">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th class="text-center">Curso</th>';
			$html .= '<th class="text-center">M&ecirc;s/Ano</th>';
			$html .= '<th class="text-center hidden-xs">Observação</th>';			
			$html .= '<th class="text-center hidden-xs">Alunos</th>';
			$html .= '<th class="text-center hidden-xs">Aula Aberta</th>';
			$html .= '<th class="text-center"></th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ($turmasAtivas as $turma) {
				$stringNomeDaFuncaoOnClick = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_EDITAR_TURMA . '", ' . $turma->getId() . ')';
				$stringNomeDaFuncaoOnClickExclusao = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_EXCLUSAO_TURMA . '", ' . $turma->getId() . ')';
				$stringNomeDaFuncaoOnClickIncluirAlunos = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CADASTRO . Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA . '",' . $turma->getId() . ')';
				$stringNomeDaFuncaoOnClickAbrirAula = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . 'AbrirAula' . '",' . $turma->getId() . ')';
				$stringNomeDaFuncaoOnClickEditarTurma = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . 'TurmaFormEdit' . '",' . $turma->getId() . ')';
				$stringNomeDaFuncaoOnClickFecharTurma = 'let resposta = confirm("confirma exclusao?"); if(resposta){ mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . 'FecharTurma' . '",' . $turma->getId() . ')}';
				$totalAlunos = 0;
				if($this->view->relatorio[$turma->getId()]){
					$totalAlunos = ($this->view->relatorio[$turma->getId()][Situacao::ATIVO] + $this->view->relatorio[$turma->getId()][Situacao::ESPECIAL]);
				}

				$html .= '<tr>';
				$html .= '<td class="text-center">';
				$html .= '<span class="hidden-xs">' . $turma->getCurso()->getNome() . '</span>';
				$html .= '<span class="hidden-sm hidden-md hidden-lg">' . $turma->getCurso()->getNomeSigla() . '<span>';
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= '<span class="hidden-xs">' . Funcoes::mesPorExtenso($turma->getMes(), 1) . '</span>';
				$html .= '<span class="hidden-sm hidden-md hidden-lg">' . str_pad($turma->getMes(), 2, 0, STR_PAD_LEFT) . '</span>';
				$html .= '/' . $turma->getAno() . '</td>';
				$html .= '<td class="text-center hidden-xs">' . $turma->getObservacao() . '</td>';
				if(!$turma->getTurmaAulaAtiva()){
					$html .= '<td class="text-center hidden-xs">SEM AULA ABERTA</td>';
				} else {
					$html .= '<td class="text-center hidden-xs">' . $totalAlunos . '</td>';
				}				
				$html .= '<td class="text-center hidden-xs">';
				$nomeAulaAberta = '<span class="label label-';
				if ($turma->getTurmaAulaAtiva()) {
					$nomeAulaAberta .= 'success">'.$turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getNome() . ' - AULA ' . $turma->getTurmaAulaAtiva()->getAula()->getPosicao();				} else {
					$nomeAulaAberta .= 'danger">SEM AULA ABERTA';
				}
				$html .= $nomeAulaAberta.'</span>';
				$html .= '</td>';
				$html .= '<td class="text-center">';
				if(!$turma->getTurmaAulaAtiva()){
					$html .= $this->view->botaoLink('<i class="fa fa-user-plus" ></i>', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickIncluirAlunos));
				}
				$html .= $this->view->botaoLink('<i class="fa fa-font" ></i>', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickAbrirAula));
				$html .= $this->view->botaoLink('<i class="fa fa-pencil" ></i>', Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickEditarTurma));
				$html .= $this->view->botaoLink('<i class="fa fa-times" ></i>', Constantes::$STRING_HASHTAG, 9, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickFecharTurma));				
				$html .= '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
		}
		$html .= '</div>';
		/* Fim panel-body */

		$html .= '<div class="panel-footer">';
		$html .= '<div class="text-right">';
		$stringNomeDaFuncaoOnClickCadastro = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_CADASTRAR_TURMA . '", 0)';
		$html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CADASTRAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
		$html .= '</div>';
		/* Fim Botões */
		$html .= '</div>';
		/* Fim panel-footer */

		$html .= $this->view->templateFormularioRodape();
		return $html;
	}

}
