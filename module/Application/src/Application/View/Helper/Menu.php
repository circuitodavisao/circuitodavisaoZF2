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

		// Start: Header
		$html .= '<header class="navbar navbar-fixed-top navbar-shadow">';
		$html .= '<div class="navbar-branding">';
		$html .= '<a class="navbar-brand" href="#" style="padding-top: 22px;">';
		$html .= '<img src="' . Constantes::$IMAGEM_LOGO_PEQUENA . '" title="' .
			$this->view->translate(Constantes::$TRADUCAO_NOME_APLICACAO) . '" class="img-responsive" style="max-width:100%;">';
		$html .= '</a>';
		$html .= '<span id="toggle_sidemenu_l" class="ad ad-lines"></span>';
		$html .= '</div>';
		$html .= '<ul class="nav navbar-nav navbar-right">';
		$html .= '<li class="dropdown menu-merge">';
		$html .= '<a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">';
		$html .= '<span class="caret caret-tp"></span>';
		$html .= '</a>';

		$html .= '<ul class="dropdown-menu list-group dropdown-persist w250" role="menu">';
		$html .= '<li class="dropdown-footer">';

		$html .= '<a href="' . $this->view->url(Constantes::$ROUTE_LOGIN) . 'perfil' . '" class="">';
		$html .= '<span class="fa fa-user pr5"></span>' . $this->view->translate('Perfil') . '</a>';

		$html .= '<a href="' . $this->view->url(Constantes::$ROUTE_LOGIN) . Constantes::$URL_PRE_SAIDA . '" class="">';
		$html .= '<span class="fa fa-power-off pr5"></span>' . $this->view->translate(Constantes::$TRADUCAO_SAIR) . '</a>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '</li>';
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
		$html .= '<a href="/principal" onClick="mostrarSplash();">';
		$html .= '<span class="fa fa-home"></span>';
		$html .= '<span class="sidebar-title">Principal</span>';
		$html .= '</a>';
		$html .= '</li>';

		/* Start: Sidebar Menu */
		$html .= '<ul class="nav sidebar-menu">';
		$html .= '<li class="sidebar-label pt20">Menu</li>';

		/* Menu Cadastro */
		if ($this->view->entidade->verificarSeEstaAtivo()) {
			$html .= '<li>';
			$html .= '<a class="accordion-toggle" href="#">';
			$html .= '<span class="fa fa-terminal"></span>';
			$html .= '<span class="sidebar-title">Cadastro</span>';
			$html .= '<span class="caret"></span>';
			$html .= '</a>';

			$html .= '<ul class="nav sub-nav">';

			$html .= '<li>';
			$html .= '<a href="/cadastroCelulas" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Minha Célula';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$html .= '<li>';
				$html .= '<a href="/cadastroCultos" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Cultos(Manutenção)';
				$html .= '</a>';
				$html .= '</li>';
			}

			$html .= '<li>';
			$html .= '<a href="/cadastroGrupo" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Time';
			$html .= '</a>';
			$html .= '</li>';

			//            $html .= '<li>';
			//            $html .= '<a href="/cadastroDiscipulados" onClick="mostrarSplash();">';
			//            $html .= '<span class="fa fa-users"></span>';
			//            $html .= 'Discipulados';
			//            $html .= '</a>';
			//            $html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/cadastroRevisionistas" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Revisionistas';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/cadastroLideresRevisao" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-user"></span>';
			$html .= 'Trabalhar no Revis&atilde;o de Vidas';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
				$this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
					$html .= '<li>';
					$html .= '<a href="/cadastroSolicitacoes" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-users"></span>';
					$html .= 'Solicita&ccedil;&otilde;es';
					$html .= '</a>';
					$html .= '</li>';
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
		$html .= '<li>';
		$html .= '<a href="#" onClick="mostrarSplash(); funcaoCircuito(\'lancamentoArregimentacao\', 0);">';
		$html .= '<span class="fa fa-terminal"></span>';
		$html .= 'Arregimentação';
		$html .= '</a>';
		$html .= '</li>';
		if ($this->view->entidade->verificarSeEstaAtivo()) {
			$html .= '<li>';
			$html .= '<a href="/lancamentoAtendimento" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Atendimento';
			$html .= '</a>';
			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
				$html .= '<li>';
				$html .= '<a href="/cadastroAtivarFichas" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-users"></span>';
				$html .= 'Fichas Revisão de Vidas';
				$html .= '</a>';
				$html .= '</li>';
			}
			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
				$this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe) {
					$html .= '<li>';
					$html .= '<a href="/lancamentoParceiroDeDeusExtrato" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-money"></span>';
					$html .= 'Parceiro de Deus';
					$html .= '</a>';
					$html .= '</li>';
				}
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
				switch ($indiceMenuRelatorio) {
				case 1:
					$label = 'Membresia';
					$mostrar = true;
					break;
				case 2:
					$label = 'C&eacute;lulas Realizadas';
					$mostrar = true;
					break;
				case 3:
					$label = 'C&eacute;lulas Quantidade';
					$mostrar = true;
					break;
				case 4:
					$label = 'Culto da Semana';
					break;
				case 5:
					$label = 'Arena';
					break;
				case 6:
					$label = 'Domingo';
					break;
				case 7:
					$label = 'C&eacute;lula/Arena';
					break;
				case 8:
					$label = 'C&eacute;lulas de Elite';
					$mostrar = false;
					break;
				case 9:
					$label = 'Parceiro de Deus Consolidado';
					$mostrar = true;
					break;
				}
				if ($mostrar) {
					$html .= '<li>';
					$html .= '<a href="/relatorio/' . $indiceMenuRelatorio . '" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-table"></span>';
					$html .= $label;
					$html .= '</a>';
					$html .= '</li>';
				}
			}
//			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
//				$html .= '<li>';
//				$html .= '<a href="/relatorioExclusaoCelulas" onClick="mostrarSplash();">';
//				$html .= '<span class="fa fa-table"></span>';
//				$html .= 'Células Excluídas';
//				$html .= '</a>';
//				$html .= '</li>';
//			}
//			$html .= '<li>';
//			$html .= '<a href="/relatorioPessoasFrequentes" onClick="mostrarSplash();">';
//			$html .= '<span class="fa fa-users"></span>';
//			$html .= 'Pessoas Frequentes';
//			$html .= '</a>';
//			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/relatorioAtendimento" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Atendimento';
			$html .= '</a>';
			$html .= '</li>';

//			$html .= '<li>';
//			$html .= '<a href="/relatorioDiscipulado" onClick="mostrarSplash();">';
//			$html .= '<span class="fa fa-users"></span>';
//			$html .= 'Discipulado';
//			$html .= '</a>';
//			$html .= '</li>';

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
				$this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe) {

					$html .= '<li>';
					$html .= '<a href="/relatorioInstituto" onClick="mostrarSplash();">';
					$html .= '<span class="fa fa-users"></span>';
					$html .= 'Aproveitamento Instituto de Vencedores';
					$html .= '</a>';
					$html .= '</li>';
				}

			$html .= '<li>';
			$html .= '<a href="/relatorioRankingCelula" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Ranking Célula';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '<li>';
			$html .= '<a href="/relatorioCelulasNaoRealizadas" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-users"></span>';
			$html .= 'Células não realizadas no período';
			$html .= '</a>';
			$html .= '</li>';

			$html .= '</ul>';

			$html .= '</li>';
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
			$arrayOQueMostrarDosCursos['lancarPresenca'] = false;
			$arrayOQueMostrarDosCursos['consultarMatricula'] = false;
			$arrayOQueMostrarDosCursos['listagem'] = false;
			if ($this->view->pessoa->getPessoaCursoAcessoAtivo()) {
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR) {
					$arrayOQueMostrarDosCursos['turmas'] = true;
					$arrayOQueMostrarDosCursos['usuarios'] = true;
					$arrayOQueMostrarDosCursos['gerarFaltas'] = true;
					$arrayOQueMostrarDosCursos['listagem'] = true;
				}
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::SUPERVISOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::FACILITADOR) {
						$arrayOQueMostrarDosCursos['reentrada'] = true;
						$arrayOQueMostrarDosCursos['gerarCarterinhas'] = true;
						$arrayOQueMostrarDosCursos['gerarReposicoes'] = true;
						$arrayOQueMostrarDosCursos['consultarMatricula'] = true;
					}
				if ($this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::COORDENADOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::SUPERVISOR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::AUXILIAR ||
					$this->view->pessoa->getPessoaCursoAcessoAtivo()->getCursoAcesso()->getId() === CursoAcesso::FACILITADOR) {
						$arrayOQueMostrarDosCursos['lancarPresenca'] = true;
					}
			} else {
				if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
					$arrayOQueMostrarDosCursos['reentrada'] = true;
					$arrayOQueMostrarDosCursos['turmas'] = true;
					$arrayOQueMostrarDosCursos['usuarios'] = true;
					$arrayOQueMostrarDosCursos['gerarCarterinhas'] = true;
					$arrayOQueMostrarDosCursos['gerarReposicoes'] = true;
					$arrayOQueMostrarDosCursos['gerarFaltas'] = true;
					$arrayOQueMostrarDosCursos['lancarPresenca'] = true;
					$arrayOQueMostrarDosCursos['listagem'] = true;
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
			}
			if ($arrayOQueMostrarDosCursos['turmas']) {
				$html .= '<li>';
				$html .= '<a href="/cursoListarTurma" onClick="mostrarSplash();">';
				$html .= '<span class="fa fa-list"></span>';
				$html .= 'Turmas';
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
			$html .= '</ul>';
			$html .= '</li>';

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
			$html .= 'Fichas Revisão de Vidas';
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

			if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
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
		if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja || $this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe) {
			$html .= '<li class="sidebar-label pt20">Suporte</li>';
			$html .= '<li>';
			$html .= '<a href="/principalSuporte" onClick="mostrarSplash();">';
			$html .= '<span class="fa fa-envelope"></span>';
			$html .= '<span class="sidebar-title">Suporte</span>';
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
