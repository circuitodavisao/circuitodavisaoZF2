<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\CursoController;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Situacao;
use Application\Model\Entity\CursoAcesso;
use Application\View\Helper\BotaoSimples;

class FiltroCursos extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke($urlFiltro) {
		$html = '';
		$html .= '<form method="post" action="/'.$urlFiltro.'">';
		$html .= '<div class="form-group">';
		$html .= '<label for="selecionaTurma">' . $this->view->translate('Selecione a Turma') . '</label>';
		$html .= '<select class="form-control" name="idTurma" id="selecionaTurma" >';
		$html .= '<option value="0" >' . $this->view->translate('Todas') . '</option>';
		foreach ($this->view->turmas as $turma) {
			$nomeDisciplina = 'PÓS REVISÃO';
			if($turma->getTurmaAulaAtiva()){
				$nomeDisciplina = $turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getNome();
			}
			$selected = '';
			if($this->view->postado['idTurma'] == $turma->getId()){
				$selected = 'selected';
			}
			$html .= '<option '.$selected.' value="' . $turma->getId() . '" >' . $turma->getCurso()->getNomeSigla() . ' - ' . Funcoes::mesPorExtenso($turma->getMes(), 1) . '/' . $turma->getAno() . ' - ' . $nomeDisciplina . '</option>';
		}
		$html .= '</select>';
		$html .= '</div>';

		$html .= '<div class="form-group">';
		$html .= '<label for="selecionaEquipe">' . $this->view->translate('Selecione a Equipe') . '</label>';
		$html .= '<select class="form-control" name="idEquipe" id="selecionaEquipe" onChange="selecionarEquipe()">';
		$html .= '<option value="0" >' . $this->view->translate('Todas') . '</option>';
		foreach ($this->view->filhos as $grupoPaiFilhoFilho) {
			$informacao = $grupoPaiFilhoFilho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNome();
			$selected = '';
			if($this->view->postado['idEquipe'] == $grupoPaiFilhoFilho->getGrupoPaiFilhoFilho()->getId()){
				$selected = 'selected';
			}
			$html .= '<option '.$selected.' value="' . $grupoPaiFilhoFilho->getGrupoPaiFilhoFilho()->getId() . '" >' . $informacao . '</option>';
		}
		$html .= '</select>';
		$html .= '</div>';

		if($urlFiltro === 'cursoChamada' || $urlFiltro === 'cursoListagem'){
			$html .= '<div class="form-group">';
			$html .= '<label for="selecionarSub">' . $this->view->translate('Sub Equipe') . '<img src="img/loader.gif" id="loaderSub" class="hidden" /></label>';
			$html .= '<select class="form-control" name="idSub" id="selecionarSub" >';
			$html .= '<option value="0" >' . $this->view->translate('Todas') . '</option>';
			if($this->view->subs){
				foreach($this->view->subs as $sub){
					$selected = '';
					if($this->view->postado['idSub'] == $sub['id']){
						$selected = 'selected';
					}
					$html .= '<option value="'.$sub['id'].'" ' . $selected . '>' . $sub['informacao'] . '</option>';
				}
			}
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="form-group">';
			$html .= '<label for="selecionaSituacao">' . $this->view->translate('Situa&ccedil;&atilde;o') . '</label>';
			$html .= '<select class="form-control" name="idSituacao" id="selecionaSituacao" >';
			$html .= '<option value="0" >ATIVO E ESPECIAL</option>';
			foreach ($this->view->situacoes as $situacao) {
				if ($situacao->getId() === Situacao::DESISTENTE ||
					$situacao->getId() === Situacao::REPROVADO_POR_FALTA) {
						$selected = '';
						if($this->view->postado['idSituacao'] == $situacao->getId()){
							$selected = 'selected';
						}
						$html .= '<option '.$selected.' value="' . $situacao->getId() . '" >' . $situacao->getNome() . '</option>';
					}
			}
			$html .= '</select>';
			$html .= '</div>';

			if($this->view->pessoa->getPessoaCursoAcessoAtivo()){
				$selectedNao = '';
				$selectedSim = '';
				if($this->view->postado['mostrarAulas'] == 0){
					$selectedNao = 'selected';
				}
				if($this->view->postado['mostrarAulas'] == 1){
					$selectedSim = 'selected';
				}
				$html .= '<div class="form-group">';
				$html .= '<label for="">' . $this->view->translate('Mostrar Todas aulas') . '</label>';
				$html .= '<select class="form-control" name="mostrarAulas" id="mostrarAulas" >';
				$html .= '<option value="0" '.$selectedNao.'>Não (Carrega mais rápido)</option>';
				$html .= '<option value="1" '.$selectedSim.'>Sim (Aumentará o tempo de carregamento)</option>';
				$html .= '</select>';
				$html .= '</div>';
			}
		}

		$html .= $this->view->botaoSimples('Filtrar', $this->view->funcaoOnClick('mostrarSplash(); this.form.submit()'), BotaoSimples::botaoSucesso, BotaoSimples::larguraMaxima);
		$html .= '</form>';

		return $html;
	}

}
