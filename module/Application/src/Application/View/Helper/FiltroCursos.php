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
		if($urlFiltro != 'cursoFormatura'){
			$html .= '<option value="0" >' . $this->view->translate('Todas') . '</option>';
		}		
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

		if($urlFiltro === 'cursoChamada' || $urlFiltro === 'cursoListagem' || $urlFiltro === 'cursoSelecionarReposicoes'
		 || $urlFiltro === 'cursoFinanceiroPorModulos' ){
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

			if($urlFiltro === 'cursoChamada' || $urlFiltro === 'cursoListagem' || 'cursoFinanceiroPorModulos' 
			 || $urlFiltro === 'cursoFinanceiroPorModulos'){
				$html .= '<div class="form-group">';
				$html .= '<label for="selecionaSituacao">' . $this->view->translate('Situa&ccedil;&atilde;o') . '</label>';
				$html .= '<select class="form-control" name="idSituacao" id="selecionaSituacao" >';
				$html .= '<option value="0" >ATIVO E ESPECIAL</option>';
				foreach ($this->view->situacoes as $situacao) {
					if (
						$situacao->getId() === Situacao::DESISTENTE ||
						$situacao->getId() === Situacao::REPROVADO_POR_FALTA ||
						$situacao->getId() === Situacao::REPROVADO_POR_FINANCEIRO
					) {
							$selected = '';
							if($this->view->postado['idSituacao'] == $situacao->getId()){
								$selected = 'selected';
							}
							$html .= '<option '.$selected.' value="' . $situacao->getId() . '" >' . $situacao->getNome() . '</option>';
						}
				}
				$html .= '</select>';
				$html .= '</div>';
			}

			if($urlFiltro === 'cursoChamada'){
				if($this->view->pessoa->getPessoaCursoAcessoAtivo()
					|| $this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
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

					if($this->view->postado['mostrarFinanceiro'] == 0){
						$selectedNao = 'selected';
					}
					if($this->view->postado['mostrarFinanceiro'] == 1){
						$selectedInadimplente = 'selected';
					}
					if($this->view->postado['mostrarFinanceiro'] == 2){
						$selectedAdimplente = 'selected';
					}
					if($this->view->postado['mostrarFinanceiro'] == 3){
						$selectedTodas = 'selected';
					}
					$html .= '<div class="form-group">';
					$html .= '<label for="">' . $this->view->translate('Mostrar Financeiro') . '</label>';
					$html .= '<select class="form-control" name="mostrarFinanceiro" id="mostrarFinanceiro" >';
					$html .= '<option value="0" '.$selectedNao.'>Não</option>';
					$html .= '<option value="3" '.$selectedTodas.'>Todas Situações</option>';
					$html .= '<option value="1" '.$selectedInadimplente.'>Inadiplente</option>';
					$html .= '<option value="2" '.$selectedAdimplente.'>Adimplente</option>';
					$html .= '</select>';
					$html .= '</div>';
				}
			}
		}

		if($urlFiltro === 'cursoSelecionarReposicoes'){
			$selectedNao = '';
			$selectedSim = '';
			if($this->view->postado['somenteUltimaAula'] == 0){
				$selectedNao = 'selected';
			}
			if($this->view->postado['somenteUltimaAula'] == 1){
				$selectedSim = 'selected';
			}
			$html .= '<div class="form-group">';
			$html .= '<label for="">' . $this->view->translate('Somente última aula') . '</label>';
			$html .= '<select class="form-control" name="somenteUltimaAula" id="somenteUltimaAula" >';
			$html .= '<option value="0" '.$selectedNao.'>Não</option>';
			$html .= '<option value="1" '.$selectedSim.'>Sim</option>';
			$html .= '</select>';
			$html .= '</div>';
		}		
		
		$html .= $this->view->botaoSimples('Filtrar', $this->view->funcaoOnClick('mostrarSplash(); this.form.submit()'), BotaoSimples::botaoSucesso, BotaoSimples::larguraMaxima);
		$html .= '</form>';

		return $html;
	}
}
?>
<script type="text/javascript">

function selecionarEquipe(){
	$('#loaderSub').removeClass('hidden')
	let idEquipe = $('#selecionaEquipe').val()
	if(idEquipe != 0){
		$.post(
			"/cursoBuscarSubs",
			{ id: idEquipe, },
			function (data) {
				$('#loaderSub').addClass('hidden')
				$('#selecionarSub').html('<option value="0">Todas</option>')
				data.filhos.map((filho) => $('#selecionarSub').append('<option value="'+filho.id+'">'+filho.informacao+'</option>'))
			}, 'json');
	}else{
		$('#loaderSub').addClass('hidden')
	}
}

</script>
