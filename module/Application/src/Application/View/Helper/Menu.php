<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\CursoAcesso;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Helper\FuncoesEntidade;
use Entidade\Entity\Pessoa;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Placeholder\Container;

/**
 * Nome: Menu.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar menu
 */
class Menu extends AbstractHelper {

	private $responsabilidades;
	private $pessoa;

	public function __construct() {

	}

	public function __invoke() {
		return $this->renderHtml();
	}

	public function renderHtml() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$html = '';
		$htmlEspecifoUL = '';

		// Start: Header
		$html .= '<header class="navbar navbar-fixed-top navbar-shadow">';
		$html .= '<div class="navbar-branding">';
		$html .= '<a class="navbar-brand" href="#" style="padding-top: 12px;">';
		$html .= '<img src="' . Constantes::$IMAGEM_LOGO_PEQUENA . '" title="' .
		$this->view->translate(Constantes::$TRADUCAO_NOME_APLICACAO) . '" class="img-responsive" style="max-width:90%;">';
		$html .= '</a>';
		$html .= '<span id="toggle_sidemenu_l" class="ad ad-lines"></span>';
		$html .= '</div>';

		
		$htmlEspecifoUL .= '<li class="dropdown menu-merge">';
		$htmlEspecifoUL .= '<a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">';
		$htmlEspecifoUL .= '<img src="/img/fotos/'.$this->view->pessoa->getFoto().'" alt="avatar" class="mw30 br64">';
		$htmlEspecifoUL .= '<span class="hidden-xs pl15">' .$this->view->pessoa->getNomePrimeiroPrimeiraSiglaUltimo(). '</span>';
		$htmlEspecifoUL .= '<span class="caret caret-tp"></span>';
		$htmlEspecifoUL .= '</a>';

		$htmlEspecifoUL .= '<ul class="dropdown-menu list-group dropdown-persist w250" role="menu">';

		/* Laço para mostrar as responsabilidades ativas */
			 if (count($responsabilidades = $this->view->pessoa->getResponsabilidadesAtivas()) > 1) {
					 foreach ($responsabilidades as $responsabilidade) {
							 /* Grupo da responsabilidades */
							 $grupo = $responsabilidade->getGrupo();
							 /* Entidades do grupo */
							 $entidades = $grupo->getEntidade();
							 foreach ($entidades as $entidade) {
									 if ($entidade->verificarSeEstaAtivo()) {
											 $htmlEspecifoUL .= $this->view->perfilDropDown($entidade, 1);
									 }
							 }
					 }
			 }

		$htmlEspecifoUL .= '<li class="dropdown-footer">';

		$htmlEspecifoUL .= '<a href="' . $this->view->url(Constantes::$ROUTE_LOGIN) . 'perfil' . '" class="animated animated-short fadeInUp">';
		$htmlEspecifoUL .= '<span class="fa fa-user pr5"></span>' . $this->view->translate('Perfil') . '</a>';

		$htmlEspecifoUL .= '<a href="' . $this->view->url(Constantes::$ROUTE_LOGIN) . Constantes::$URL_PRE_SAIDA . '" class="animated animated-short fadeInUp">';
		$htmlEspecifoUL .= '<span class="fa fa-power-off pr5"></span>' . $this->view->translate(Constantes::$TRADUCAO_SAIR) . '</a>';
		$htmlEspecifoUL .= '</li>';
		$htmlEspecifoUL .= '</ul>';
		$htmlEspecifoUL .= '</li>';

		$html .= '<ul class="nav navbar-nav navbar-right  hidden-xs">';
		$html .= $htmlEspecifoUL;
		$html .= '</ul>';
		$html .= '<ul class="nav navbar-nav navbar-right hidden-sm hidden-md hidden-lg" style="position: relative; float: right; right: 100%;">';
		$html .= $htmlEspecifoUL;
		$html .= '</ul>';
		$html .= '</header>';
		// End: Header
		//
		// Start: Sidebar
		$html .= '<aside id="sidebar_left" class="nano nano-light affix">';

		// Start: Sidebar Left Content
		$html .= '<div class="sidebar-left-content nano-content">';

		$html .= '<ul class="nav sidebar-menu">';

		$html .= '<li class="sidebar-label pt20">Principal</li>';
		$html .= '<li>';
		$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'principal\', null);">';
		$html .= '<span class="fa fa-home"></span>';
		$html .= '<span class="sidebar-title">Principal</span>';
		$html .= '</a>';
		$html .= '</li>';

		/* Start: Sidebar Menu */
		$html .= '<ul class="nav sidebar-menu">';
		$html .= '<li class="sidebar-label pt20">Menu</li>';

		/* Menu Cadastro */
		

if ($this->view->entidade->verificarSeEstaAtivo()){
			$html .= '<li>';
			$html .= '<a class="accordion-toggle" href="#">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= '<span class="sidebar-title">Cadastro</span>';
			$html .= '<span class="caret"></span>';
			$html .= '</a>';			

			$html .= '<ul class="nav sub-nav">';


			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroCelulas\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Minha Célula';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$html .= '<li>';
				$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroCultos\', null);">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Cultos';
				$html .= '</a>';
				$html .= '</li>';

				$html .= '<li>';
				$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroRevisoes\', null);">';
				$html .= '<span class="fa fa-calendar"></span>';
				$html .= 'Revisão de Vidas';
				$html .= '</a>';
				$html .= '</li>';
			}

		}

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroGrupo\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Time';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroDiscipulados\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Discipulados';
			$html .= '</a>';
			$html .= '</li>';

			if($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::presidencial){
				$html .= '<li>';
				$html .= '<a href="/cadastroMetas" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-bullseye"></span>';
				$html .= 'Metas Ordenação';
				$html .= '</a>';
				$html .= '</li>';			
			}

			if ($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::subEquipe){
				$html .= '<li>';
				$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroSolicitacoes\', null);">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Solicita&ccedil;&otilde;es';
				$html .= '</a>';
				$html .= '</li>';			
			}
			
			if ($this->view->entidade->verificarSeEstaAtivo()
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroRevisionistas\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Revisionistas';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroLideresRevisao\', null);">';
			$html .= '<span class="fa fa-user"></span>';
			$html .= 'Trabalhar no Revis&atilde;o de Vidas';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){							
					$html .= '<li>';
					$html .= '<a href="/lancamentoParceiroDeDeusUsuarios" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-user"></span>';
					$html .= 'Secretário Parceiro de Deus';
					$html .= '</a>';
					$html .= '</li>';
				}
		}
			$html .= '</ul>';

			$html .= '</li>';
			/* Fim Menu Cadastro */
		
}
		
		/* Menu Lançamento */
		$html .= '<li>';
		$html .= '<a class="accordion-toggle" href="#">';
		$html .= '<span class="fa fa-pencil"></span>';
		$html .= '<span class="sidebar-title">Lançar</span>';
		$html .= '<span class="caret"></span>';
		$html .= '</a>';
		$html .= '<ul class="nav sub-nav">';

		if ($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

		$qualPeriodo = '';
		$diaDaSemana = date('N');
		$SEGUNDA = 1;
		if(intVal($diaDaSemana) === $SEGUNDA){
			$qualPeriodo = '/-1';
		}
    
			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'lancamentoArregimentacao'.$qualPeriodo.'\', null);">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= 'Arregimentação';
			$html .= '</a>';
			$html .= '</li>';

		}

		if ($this->view->entidade->verificarSeEstaAtivo()) {
			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'lancamentoAtendimento\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Atendimento';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$html .= '<li>';
				$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'cadastroAtivarFichas\', null);">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Fichas Revisão de Vidas';
				$html .= '</a>';
				$html .= '</li>';
			}
		}

		if ($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'lancamentoParceiroDeDeusExtrato\', null);">';
			$html .= '<span class="fa fa-money"></span>';
			$html .= 'Parceiro de Deus';
			$html .= '</a>';
			$html .= '</li>';

		}

		$html .= '</ul>';
		$html .= '</li>';

		

		if ($this->view->entidade->verificarSeEstaAtivo()) {
			$html .= '<li>';
			$html .= '<a class="accordion-toggle" href="#">';
			$html .= '<span class="fa fa-bar-chart"></span>';
			$html .= '<span class="sidebar-title">Relatórios</span>';
			$html .= '<span class="caret"></span>';
			$html .= '</a>';

			$html .= '<ul class="nav sub-nav">';

			for ($indiceMenuRelatorio = 1; $indiceMenuRelatorio <= 9; $indiceMenuRelatorio++) {

				$label = '';
				$mostrar = false;
$link = 'relatorio';
				switch ($indiceMenuRelatorio) {
				case 1:
					$label = 'Membresia';
					$mostrar = true;
$link = 'relatorioNovo';
					break;
				case 2:
					$label = 'C&eacute;lulas Realizadas';
					$mostrar = true;
$link = 'relatorioNovo';
					break;
				case 3:
					$label = 'C&eacute;lulas Quantidade';
					$mostrar = true;
$link = 'relatorioNovo';
					break;
			case 5:
					$label = 'C&eacute;lulas de Elite';
					$mostrar = true;
$link = 'relatorioNovo';
					break;
				case 9:
					$label = 'Parceiro de Deus Consolidado';
					$mostrar = true;
		if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		|| $this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao) {
			if($indiceMenuRelatorio == 9){
				$mostrar = true;
			} else {
				$mostrar = false;
			}
						
					}
					break;
				}
				if ($mostrar) {
					$html .= '<li>';
					$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\''.$link.'/' . $indiceMenuRelatorio . '\', null);">';
					$html .= '<span class="fa fa-table"></span>';
					$html .= $label;
					$html .= '</a>';
					$html .= '</li>';
				}
			}
			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
		&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {
				$html .= '<li>';
				$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'relatorioExclusaoCelulas\', null);">';
				$html .= '<span class="fa fa-table"></span>';
				$html .= 'Células Excluídas';
				$html .= '</a>';
				$html .= '</li>';
			}		

//			$html .= '<li>';
//			$html .= '<a href="/relatorioPessoasFrequentes" onClick="mostrarSplash();">';
//			$html .= '<span class="fa fa-users"></span>';
//			$html .= 'Pessoas Frequentes';
//			$html .= '</a>';
//			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'relatorioAtendimento\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Atendimento';
			$html .= '</a>';
			$html .= '</li>';						

			$html .= '<li>';
			$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'relatorioDiscipulado\', null);">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Discipulado';
			$html .= '</a>';
			$html .= '</li>';		

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao 
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

			$html .= '<li>';
			$html .= '<a href="/relatorioInstituto" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Aproveitamento Instituto de Vencedores - Por Turmas';
			$html .= '</a>';
			$html .= '</li>';	
			
			}

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial) {

			$html .= '<li>';
			$html .= '<a href="/relatorioAproveitamentoDoIv" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Aproveitamento Instituto de Vencedores - Por Módulos';
			$html .= '</a>';
			$html .= '</li>';
			
			}
			
			$html .= '<li>';
			$html .= '<a href="/relatorioRankingCelula" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Ranking Célula';
			$html .= '</a>';
			$html .= '</li>';
			
			//Maneira de verificar se a entidade logada está ou não abaixo da região do bispo Lucas, indenpendente do tipo, para mostrar o ranking de 70
			$idGrupoRegiaoLucasEPriscila = 3110;
			$verificarSeEstaAbaixoDoBispoLucas = $this->view->entidade->getGrupo()->getGrupoRegiao();
			if($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao &&
				$this->view->entidade->getGrupo()->getId() === 3110
				){
					$html .= '<li>';
					$html .= '<a href="/relatorioRankingSetenta" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-users"></span>';
					$html .= 'Ranking Setenta';
					$html .= '</a>';
					$html .= '</li>';
			}			

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial){
				$html .= '<li>';
				$html .= '<a href="/relatorioCelulasNaoRealizadas" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Células não realizadas no período';
				$html .= '</a>';
				$html .= '</li>';
			}

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao 
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial){

			$html .= '<li>';
			$html .= '<a href="/relatorioAlunos/1" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-graduation-cap"></span>';
			$html .= 'Alunos que ainda não foram a aula';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/relatorioAlunos/2" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-graduation-cap"></span>';
			$html .= 'Alunos que estão quase reprovando';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/relatorioAlunosNaSemana" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-graduation-cap"></span>';
			$html .= 'Quantidade de alunos que já tem presença na aula aberta';
			$html .= '</a>';
			$html .= '</li>';			

			$html .= '<li>';
			$html .= '<a href="/relatorioSetenta" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-trophy"></span>';
			$html .= 'Setenta';
			$html .= '</a>';
			$html .= '</li>';

				$html .= '<li>';
				$html .= '<a href="/relatorioNovo/4" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-list"></span>';
				$html .= 'Resumo';
				$html .= '</a>';
				$html .= '</li>';

			}

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::subEquipe){
				$html .= '<li>';
				$html .= '<a href="/relatorioCelulasDeEliteQuem" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-table"></span>';
				$html .= 'Quem foi célula de elite';
				$html .= '</a>';
				$html .= '</li>';			
			}

			$html .= '<li>';
			$html .= '<a href="/relatorioRegistro" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-calendar"></span>';
			$html .= 'Registro';
			$html .= '</a>';
			$html .= '</li>';			

			if($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
			$html .= '<li>';
			$html .= '<a href="/relatorioGeradorDeMeta" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-calendar"></span>';
			$html .= 'Gerador de Meta';
			$html .= '</a>';
			$html .= '</li>';			
			}		
			
			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::subEquipe){
				$html .= '<li>';
				$html .= '<a href="/relatorioQuantidadeDePessoasPorRevisao" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-table"></span>';
				$html .= 'Pessoas enviadas para o revisão';
				$html .= '</a>';
				$html .= '</li>';			
				}

			// if($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
			// 	$html .= '<li>';
			// 	$html .= '<a href="/relatorioConsultarOrdenacao" onClick="mostrarSplash();">';
			// 	$html .= '<span class="fa fa-search"></span>';
			// 	$html .= 'Consulta Ordenação';
			// 	$html .= '</a>';
			// 	$html .= '</li>';			
			// }

			$html .= '</ul>';

			$html .= '</li>';

			if($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
			&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial
				&& $this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao) {

			$html .= '<li>';
			$html .= '<a class="accordion-toggle" href="#">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= '<span class="sidebar-title">Cursos</span>';
			$html .= '<span class="caret"></span>';
			$html .= '</a>';

			$html .= '<ul class="nav sub-nav">';
			$html .= '<li>';
			$html .= '<a href="/cursoChamada" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-list"></span>';
			$html .= 'Chamada';
			$html .= '</a>';
			$html .= '</li>';
			$arrayOQueMostrarDosCursos = array();
			$arrayOQueMostrarDosCursos['reentrada'] = false;
			$arrayOQueMostrarDosCursos['turmas'] = false;
			$arrayOQueMostrarDosCursos['usuarios'] = false;
			$arrayOQueMostrarDosCursos['gerarCarterinhas'] = false;
			$arrayOQueMostrarDosCursos['gerarReposicoes'] = false;
			$arrayOQueMostrarDosCursos['gerarFaltas'] = false;
			$arrayOQueMostrarDosCursos['financeiroPorEquipe'] = false;			
			$arrayOQueMostrarDosCursos['lancarPresenca'] = false;
			$arrayOQueMostrarDosCursos['consultarMatricula'] = false;
			$arrayOQueMostrarDosCursos['listagem'] = false;
			$arrayOQueMostrarDosCursos['financeiroPorData'] = false;
			$arrayOQueMostrarDosCursos['turmasEncerradas'] = false;
			$arrayOQueMostrarDosCursos['formatura'] = false;
			if ($this->view->pessoa->getPessoaCursoAcessoAtivo()) {
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR) {
					$arrayOQueMostrarDosCursos['turmasEncerradas'] = true;
					$arrayOQueMostrarDosCursos['turmas'] = true;
					$arrayOQueMostrarDosCursos['usuarios'] = true;
					$arrayOQueMostrarDosCursos['gerarFaltas'] = true;
					$arrayOQueMostrarDosCursos['listagem'] = true;
					$arrayOQueMostrarDosCursos['financeiroPorData'] = true;
					$arrayOQueMostrarDosCursos['financeiroPorEquipe'] = true;
				}
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::SUPERVISOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::FACILITADOR) {
						$arrayOQueMostrarDosCursos['reentrada'] = true;
						$arrayOQueMostrarDosCursos['gerarCarterinhas'] = true;
						$arrayOQueMostrarDosCursos['gerarReposicoes'] = true;
						$arrayOQueMostrarDosCursos['consultarMatricula'] = true;
						$arrayOQueMostrarDosCursos['formatura'] = true;
					}
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::SUPERVISOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::AUXILIAR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::FACILITADOR) {
						$arrayOQueMostrarDosCursos['lancarPresenca'] = true;
					}
			} else {
				if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
					$arrayOQueMostrarDosCursos['formatura'] = true;
					$arrayOQueMostrarDosCursos['turmasEncerradas'] = true;
					$arrayOQueMostrarDosCursos['reentrada'] = true;
					$arrayOQueMostrarDosCursos['turmas'] = true;
					$arrayOQueMostrarDosCursos['usuarios'] = true;
					$arrayOQueMostrarDosCursos['gerarCarterinhas'] = true;
					$arrayOQueMostrarDosCursos['gerarReposicoes'] = true;
					$arrayOQueMostrarDosCursos['gerarFaltas'] = true;
					$arrayOQueMostrarDosCursos['financeiroPorEquipe'] = true;
					$arrayOQueMostrarDosCursos['lancarPresenca'] = true;
					$arrayOQueMostrarDosCursos['consultarMatricula'] = true;
					$arrayOQueMostrarDosCursos['listagem'] = true;
					$arrayOQueMostrarDosCursos['financeiroPorData'] = true;
				}
			}
			if ($arrayOQueMostrarDosCursos['listagem']) {
				$html .= '<li>';
				$html .= '<a href="/cursoListagem" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-user"></span>';
				$html .= 'Listagem de telefones';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['reentrada']) {
				$html .= '<li>';
				$html .= '<a href="/cursoReentrada" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-user"></span>';
				$html .= 'Reentrada de Aluno';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['consultarMatricula']) {
				$html .= '<li>';
				$html .= '<a href="/cursoMatricula" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-terminal"></span>';
				$html .= 'Consultar Matrícula';
				$html .= '</a>';
				$html .= '</li>';

				$html .= '<li>';
				$html .= '<a href="/cursoVerificarFinanceiro" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-terminal"></span>';
				$html .= 'Consultar Financeiro';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['turmas']) {
				$html .= '<li>';
				$html .= '<a href="/cursoListarTurma" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-list"></span>';
				$html .= 'Turmas';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['turmasEncerradas']) {
				$html .= '<li>';
				$html .= '<a href="/cursoTurmasEncerradas" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-list"></span>';
				$html .= 'Turmas Encerradas';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['formatura']) {
				$html .= '<li>';
				$html .= '<a href="/cursoFormatura" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-graduation-cap"></span>';
				$html .= 'Formatura';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['usuarios']) {
				$html .= '<li>';
				$html .= '<a href="/cursoUsuarios" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Usuários';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['gerarCarterinhas']) {
				$html .= '<li>';
				$html .= '<a href="/cursoSelecionarParaCarterinha" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-user"></span>';
				$html .= 'Gerar Carterinha';
				$html .= '</a>';
				$html .= '</li>';
			}

			$html .= '<li>';
			$html .= '<a href="/cursoSelecionarReposicoes" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-list"></span>';
			$html .= 'Gerar Reposições';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/cursoFinanceiroPorModulos" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-money"></span>';
			$html .= 'Financeiro Por Módulos';
			$html .= '</a>';
			$html .= '</li>';

			if ($arrayOQueMostrarDosCursos['gerarFaltas']) {
				$html .= '<li>';
				$html .= '<a href="/cursoGerarFaltas" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-money"></span>';
				$html .= 'Gerar Faltas';
				$html .= '</a>';
				$html .= '</li>';
			}			
			if ($arrayOQueMostrarDosCursos['lancarPresenca']) {
				$html .= '<li>';
				$html .= '<a href="/cursoLancarPresenca" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-pencil"></span>';
				$html .= 'Lançar Presença';
				$html .= '</a>';
				$html .= '</li>';

				$html .= '<li>';
				$html .= '<a href="/cursoLancarReposicao" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-pencil"></span>';
				$html .= 'Lançar Reposição';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['financeiroPorData']) {
				$html .= '<li>';
				$html .= '<a href="/cursoFinanceiroPorData" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-money"></span>';
				$html .= 'Financeiro por datas';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($arrayOQueMostrarDosCursos['financeiroPorEquipe']) {
				$html .= '<li>';
				$html .= '<a href="/cursoFinanceiroPorEquipe" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-money"></span>';
				$html .= 'Financeiro Equipes';
				$html .= '</a>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			$html .= '</li>';

		} // fim restrição de coordenadores

			$html .= '<li>';
			$html .= '<a class="accordion-toggle" href="#">';
			$html .= '<span class="fa fa-print"></span>';

			$html .= '<span class="sidebar-title">Imprimir</span>';
			$html .= '<span class="caret"></span>';
			$html .= '</a>';
			$html .= '<ul class="nav sub-nav">';

			$html .= '<li>';
			$html .= '<a href="/cadastroFichaRevisionistas" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= 'Fichas Revisionistas/Líderes';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/cadastroListagemRevisionistas" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= 'Listagem Revisionistas Ativos';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/cadastroListagemLideres" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= 'Listagem Líderes Ativos';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja || $this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao ||
					$this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao) {
				$html .= '<li>';
				$html .= '<a href="/cadastroSelecionarRevisaoCracha" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-terminal"></span>';
				$html .= 'Crachás do Revisão de Vidas';
				$html .= '</a>';
				$html .= '</li>';
			}

			$html .= '</ul>';
			$html .= '</li>';

		}
		if ($this->view->entidade->getEntidadeTipo()->getId() !== EntidadeTipo::subEquipe) {
			$html .= '<li class="sidebar-label pt20">Suporte</li>';
			$html .= '<li>';
			$html .= '<a href="https://circuitodavisao.zendesk.com/hc/pt-br" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-envelope"></span>';
			$html .= '<span class="sidebar-title">Suporte</span>';
			$html .= '</a>';
			$html .= '</li>';
		}
		if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao
		|| $this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
			$html .= '<li class="sidebar-label pt20">Ordenação</li>';
			$html .= '<li>';
				$html .= '<a href="/consultarOrdenacao" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-user"></span>';
			$html .= '<span class="sidebar-title">Consultar Ordenação</span>';
			$html .= '</a>';
			$html .= '</li>';
		}
	
		$html .= '</ul>';
		// End: Sidebar Menu
		// Start: Sidebar Collapse Button
		$html .= '<div class="sidebar-toggle-mini">';
		$html .= '<a href="#">';
		$html .= '<span class="fa fa-sign-out"></span>';
		$html .= '</a>';
		$html .= '</div>';
		// End: Sidebar Collapse Button

		$html .= '</div>';
		// End: Sidebar Left Content

		$html .= '</aside>';

		return $html;
	}

	static public function montaNomeLideres($grupoResponsavel) {
		$nomeLideres = '';
		if ($grupoResponsavel) {
			$pessoas = [];
			foreach ($grupoResponsavel as $gr) {
				$p = $gr->getPessoa();
				$pessoas[] = $p;
			}
			$contagem = 1;
			$totalPessoas = count($pessoas);
			foreach ($pessoas as $p) {
				if ($contagem == 2) {
					$nomeLideres .= '&nbsp;&&nbsp;';
				}
				if ($totalPessoas == 1) {
					$nomeLideres .= $p->getNomePrimeiroUltimo();
				} else {// duas pessoas
					$nomeLideres .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
				}
				$contagem++;
			}
		}
		return $nomeLideres;
	}

	static public function montaInformacaoEntidade($entidadeFilho) {
		$informacaoEntidade = '';
		if (!empty($entidadeFilho)) {
			$informacaoEntidade = '<small>' . $entidadeFilho->infoEntidade() . '</small>';
		}
		return $informacaoEntidade;
	}

	function getResponsabilidades() {
		return $this->responsabilidades;
	}

	/**
	 * Retorna a pessoa logada
	 * @return Pessoa
	 */
	function getPessoa() {
		return $this->pessoa;
	}

	function setResponsabilidades($responsabilidades) {
		$this->responsabilidades = $responsabilidades;
	}

	function setPessoa($pessoa) {
		$this->pessoa = $pessoa;
	}

}
