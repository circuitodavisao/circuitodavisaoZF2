<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\EntidadeTipo;
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

        $stringFoto = $this->view->pessoa->getFoto();
        if (empty($stringFoto)) {
            $stringFoto = 'placeholder.png';
        }
        // Start: Header 
        $html .= '<header class="navbar navbar-fixed-top">';
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
        /* Laço para mostrar as responsabilidades ativas */
        foreach ($this->view->responsabilidades as $responsabilidade) {
            /* Grupo da responsabilidades */
            $grupo = $responsabilidade->getGrupo();
            foreach ($grupo->getGrupoPaiFilhoPai() as $gpfPai) {
                $ativo = true;
                $entidadeSelecionada = $grupo->getEntidadeAtiva();
//                if (!$gpfPai->verificarSeEstaAtivo()) {
//                    $ativo = false;
//                    $entidadeSelecionada = $grupo->getEntidadeInativaPorDataInativacao($gpfPai->getData_inativacaoStringPadraoBanco());
//                }
                if ($gpfPai->verificarSeEstaAtivo()) {
                    $grupoPai = $gpfPai->getGrupoPaiFilhoPai();
//                    $html .= $this->view->perfilDropDown($entidadeSelecionada, 1, $ativo, $grupoPai);
                }
            }
        }
        $html .= '<li class="dropdown-footer">';
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

        // Start: Sidebar Header
        $html .= '<header class="sidebar-header">';

        // Sidebar Widget - Author 
        $html .= '<div class="sidebar-widget author-widget">';
        $html .= '<div class="media">';
        $html .= '<a class="media-left" href="#">';
        $html .= '<img src="/img/avatars/' . $stringFoto . '" class="img-responsive">';
        $html .= '</a>';
        $html .= '<div class="media-body">';
        $html .= '<div class="media-links">';
        $html .= '<a href="/preSaida" onClick="mostrarSplash();">Sair</a>';
        $html .= '</div>';
        $html .= '<div class="media-author">' . $this->view->pessoa->getNome() . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '</header>';

        $html .= '<ul class="nav sidebar-menu">';

        $html .= '<li class="sidebar-label pt20">Principal</li>';
        $html .= '<li>';
        $html .= '<a href="/principal" onClick="mostrarSplash();">';
        $html .= '<span class="fa fa-home"></span>';
        $html .= '<span class="sidebar-title">Principal</span>';
        $html .= '</a>';
        $html .= '</li>';


        if ($this->view->entidade->verificarSeEstaAtivo()) {
            /* Arvore */
            $html .= '<li class="sidebar-label pt20">Hierarquia</li>';

            /* Pegar pessoas abaixo */
            if ($this->view->discipulos) {
                $html .= '<li>';
                $html .= '<a class="accordion-toggle" href="#">';
                $html .= '<span class="fa fa-sitemap"></span>';
                $html .= '<span class="sidebar-title">Meu Time</span>';
                $html .= '<span class="caret"></span>';
                $html .= '</a>';
                $html .= '<ul class="nav sub-nav">';
                foreach ($this->view->discipulos as $gpFilho12) {
                    $grupoFilho = $gpFilho12->getGrupoPaiFilhoFilho();
                    $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                    $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();

                    $nomeLideres = Self::montaNomeLideres($grupoResponsavel);
                    $informacaoEntidade = Self::montaInformacaoEntidade($entidadeFilho);

                    $discipulos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos(0);
                    if (count($discipulos144) > 0) {
                        $html .= $this->view->menuHierarquia($nomeLideres, $informacaoEntidade, 2);
                        $html .= $this->view->menuHierarquia('', '', 3, $grupoFilho->getId());
                        foreach ($discipulos144 as $gpFilho144) {
                            $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                            $entidadeFilho144 = $grupoFilho144->getEntidadeAtiva();
                            $grupoResponsavel144 = $grupoFilho144->getResponsabilidadesAtivas();

                            $nomeLideres144 = Self::montaNomeLideres($grupoResponsavel144);
                            $informacaoEntidade144 = Self::montaInformacaoEntidade($entidadeFilho144);

                            $discipulos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos(0);
                            if (count($discipulos1728) > 0) {
                                $html .= $this->view->menuHierarquia($nomeLideres144, $informacaoEntidade144, 2);
                                $html .= $this->view->menuHierarquia('', '', 3, $grupoFilho144->getId());
                                foreach ($discipulos1728 as $gpFilho1728) {
                                    $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                    $entidadeFilho1728 = $grupoFilho1728->getEntidadeAtiva();
                                    $grupoResponsavel1728 = $grupoFilho1728->getResponsabilidadesAtivas();

                                    $nomeLideres1728 = Self::montaNomeLideres($grupoResponsavel1728);
                                    $informacaoEntidade1728 = Self::montaInformacaoEntidade($entidadeFilho1728);

                                    $discipulos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos(0);
                                    if (count($discipulos20736) > 0) {
                                        $html .= $this->view->menuHierarquia($nomeLideres1728, $informacaoEntidade1728, 2);
                                        $html .= $this->view->menuHierarquia('', '', 3, $grupoFilho1728->getId());
                                        foreach ($discipulos20736 as $gpFilho20736) {
                                            $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                            $entidadeFilho20736 = $grupoFilho20736->getEntidadeAtiva();
                                            $grupoResponsavel20736 = $grupoFilho20736->getResponsabilidadesAtivas();

                                            $nomeLideres20736 = Self::montaNomeLideres($grupoResponsavel20736);
                                            $informacaoEntidade20736 = Self::montaInformacaoEntidade($entidadeFilho20736);

                                            $html .= $this->view->menuHierarquia($nomeLideres20736, $informacaoEntidade20736, 1, $grupoFilho20736->getId());
                                        }
                                        $html .= $this->view->menuHierarquia('', '', 4);
                                    } else {
                                        $html .= $this->view->menuHierarquia($nomeLideres1728, $informacaoEntidade1728, 1, $grupoFilho1728->getId());
                                    }
                                }
                                $html .= $this->view->menuHierarquia('', '', 4);
                            } else {
                                $html .= $this->view->menuHierarquia($nomeLideres144, $informacaoEntidade144, 1, $grupoFilho144->getId());
                            }
                        }
                        $html .= $this->view->menuHierarquia('', '', 4);
                    } else {
                        $html .= $this->view->menuHierarquia($nomeLideres, $informacaoEntidade, 1, $grupoFilho->getId());
                    }
                }

                $html .= '</ul>';
                $html .= '</li>';
                $html .= '</ul>';
            } else {
                $html .= '<li>';
                $html .= '<a href="#">';
                $html .= '<span class="fa fa-user-times"></span>';
                $html .= '<span class="sidebar-title">Sem Time</span>';
                $html .= '</a>';
                $html .= '</li>';
            }

            $html .= '</li>';
        }

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
                $html .= '<a href="#" onClick="mostrarSplash();">';
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

            $html .= '<li>';
            $html .= '<a href="/cadastroRevisionistas" onClick="mostrarSplash();">';
            $html .= '<span class="fa fa-users"></span>';
            $html .= 'Revisionistas';
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
        $html .= '<a href="/lancamentoArregimentacao" onClick="mostrarSplash();">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Arregimentação';
        $html .= '</a>';
        $html .= '</li>';
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

        $html .= '</ul>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-bar-chart"></span>';
        $html .= '<span class="sidebar-title">Relatórios</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';

        $html .= '<ul class="nav sub-nav">';

        for ($indiceMenuRelatorio = 1; $indiceMenuRelatorio <= 8; $indiceMenuRelatorio++) {

            $label = '';
            switch ($indiceMenuRelatorio) {
                case 1:
                    $label = 'Membresia';
                    break;
                case 2:
                    $label = 'C&eacute;lulas Realizadas';
                    break;
                case 3:
                    $label = 'C&eacute;lulas Quantidade';
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
                    break;
            }
            $html .= '<li>';
            $html .= '<a href="/relatorio/' . $indiceMenuRelatorio . '" onClick="mostrarSplash();">';
            $html .= '<span class="fa fa-table"></span>';
            $html .= $label;
            $html .= '</a>';
            $html .= '</li>';
        }

        $html .= '<li>';
        $html .= '<a href="/relatorioPessoasFrequentes" onClick="mostrarSplash();">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Pessoas Frequentes';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a href="/relatorioAtendimento" onClick="mostrarSplash();">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Atendimento';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '</ul>';

        $html .= '</li>';
        if ($this->view->entidade->verificarSeEstaAtivo()) {

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
            $html .= '<span class="sidebar-title">Chamada</span>';
            $html .= '</a>';
            $html .= '</li>';

            if ($this->view->entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
                $html .= '<li>';
                $html .= '<a href="/cursoListarTurma" onClick="mostrarSplash();">';
                $html .= '<span class="fa fa-list"></span>';
                $html .= '<span class="sidebar-title">Turmas</span>';
                $html .= '</a>';
                $html .= '</li>';
                $html .= '<li>';
                $html .= '<a href="/cursoSelecionarParaCarterinha" onClick="mostrarSplash();">';
                $html .= '<span class="fa fa-users"></span>';
                $html .= '<span class="sidebar-title">Gerar Carterinha</span>';
                $html .= '</a>';
                $html .= '</li>';

                $html .= '<li>';

                $html .= '<li>';
                $html .= '<a class="accordion-toggle" href="#">';
                $html .= '<span class="fa fa-terminal"></span>';
                $html .= '<span class="sidebar-title">Lançar</span>';
                $html .= '<span class="caret"></span>';
                $html .= '</a>';

                $html .= '<ul class="nav sub-nav">';

                $html .= '<li>';
                $html .= '<a href="/cursoLancarPresenca" onClick="mostrarSplash();">';
                $html .= '<span class="sidebar-title">Presença</span>';
                $html .= '</a>';
                $html .= '</li>';
                $html .= '</ul>';

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
            $html .= 'Fichas Revisao';
            $html .= '</a>';
            $html .= '</li>';

            $html .= '<li>';
            $html .= '<a href="#">';
            $html .= '<span class="fa fa-terminal"></span>';
            $html .= 'Listagem Revisionistas(Manutenção)';
            $html .= '</a>';
            $html .= '</li>';

            $html .= '</ul>';
            $html .= '</li>';

            $html .= '<li>';
            $html .= '<a class="accordion-toggle" href="#">';
            $html .= '<span class="fa fa-cogs"></span>';
            $html .= '<span class="sidebar-title">Adm. (Manutenção)</span>';
            $html .= '<span class="caret"></span>';
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

        $html .= '<div id="modals">';
        /* Laço para mostrar a s responsabilidades ativas modal */
        foreach ($this->view->responsabilidades as $responsabilidade) {
            /* Grupo da responsabilidades */
            $grupo = $responsabilidade->getGrupo();
            foreach ($grupo->getGrupoPaiFilhoPai() as $gpfPai) {
                $ativo = true;
                $entidadeSelecionada = $grupo->getEntidadeAtiva();
                if (!$gpfPai->verificarSeEstaAtivo()) {
                    $ativo = false;
                    $entidadeSelecionada = $grupo->getEntidadeInativaPorDataInativacao($gpfPai->getData_inativacaoStringPadraoBanco());
                }
                $grupoPai = $gpfPai->getGrupoPaiFilhoPai();
//                $html .= $this->view->perfilDropDown($entidadeSelecionada, 2, $ativo, $grupoPai);
            }
        }
        $html .= '</div>';
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
