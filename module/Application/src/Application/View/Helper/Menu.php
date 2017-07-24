<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Entidade\Entity\Pessoa;
use Zend\View\Helper\AbstractHelper;

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
        $nomeEntidade = $this->view->entidade->getEntidadeTipo()->getNome();
        $infoEntidade = $this->view->entidade->infoEntidade();
        $html .= '<img src="/img/avatars/' . $stringFoto . '" alt="' . $this->view->pessoa->getNomePrimeiroUltimo() . '" class="mw30 br64">' .
                Constantes::$NBSP . $nomeEntidade . Constantes::$NBSP . '-' . Constantes::$NBSP . $infoEntidade;
        $html .= '<span class="pl5">- ' . $this->view->pessoa->getNomePrimeiroUltimo() . '</span>';
        $html .= '<span class="caret caret-tp"></span>';
        $html .= '</a>';

        $html .= '<ul class="dropdown-menu list-group dropdown-persist w250" role="menu">';
        /* Laço para mostrar as responsabilidades ativas */
        if (count($this->view->responsabilidades) > 1) {
            foreach ($this->view->responsabilidades as $responsabilidade) {
                /* Grupo da responsabilidades */
                $grupo = $responsabilidade->getGrupo();
                /* Entidades do grupo */
                $entidades = $grupo->getEntidade();
                foreach ($entidades as $entidade) {
                    if ($entidade->verificarSeEstaAtivo()) {
                        $html .= $this->view->perfilDropDown($entidade, 1);
                    }
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
        $html .= '<a href="/preSaida">Sair</a>';
        $html .= '</div>';
        $html .= '<div class="media-author">' . $this->view->pessoa->getNome() . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '</header>';

        $html .= '<ul class="nav sidebar-menu">';

        $html .= '<li class="sidebar-label pt20">Principal</li>';
        $html .= '<li>';
        $html .= '<a href="/principal">';
        $html .= '<span class="fa fa-home"></span>';
        $html .= '<span class="sidebar-title">Principal</span>';
        $html .= '</a>';
        $html .= '</li>';

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
            foreach ($this->view->discipulos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
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
                $informacaoEntidade = '';
                if (!empty($entidadeFilho)) {
                    $informacaoEntidade = '<small>' . $entidadeFilho->infoEntidade() . '</small>';
                }
                $html .= $this->view->menuHierarquia($nomeLideres, $informacaoEntidade, 2);
                $html .= $this->view->menuHierarquia('', '', 3);
                foreach ($grupoFilho->getGrupoPaiFilhoFilhos() as $gpFilho1) {
                    $grupoFilho = $gpFilho1->getGrupoPaiFilhoFilho();
                    $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                    $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
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
                    $informacaoEntidade = '';
                    if (!empty($entidadeFilho)) {
                        $informacaoEntidade = $entidadeFilho->getEntidadeTipo()->getNome() . ' <small>' . $entidadeFilho->infoEntidade() . '</small>';
                    }
                    $html .= $this->view->menuHierarquia($nomeLideres, $informacaoEntidade);
                    $grupoFilho->getGrupoPaiFilhoFilhos();
                }
                $html .= $this->view->menuHierarquia('', '', 4);
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

        /* Start: Sidebar Menu */
        $html .= '<ul class="nav sidebar-menu">';
        $html .= '<li class="sidebar-label pt20">Menu</li>';

        /* Menu Cadastro */
        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= '<span class="sidebar-title">Cadastro</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';

        $html .= '<ul class="nav sub-nav">';

        $html .= '<li>';
        $html .= '<a href="/cadastroCelulas">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Células';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a href="#">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Cultos (Manutenção)';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a href="#">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Time (Manutenção)';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '</ul>';

        $html .= '</li>';
        /* Fim Menu Cadastro */

        /* Menu Lançamento */
        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-pencil"></span>';
        $html .= '<span class="sidebar-title">Lançar</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';
        $html .= '<ul class="nav sub-nav">';
        $html .= '<li>';
        $html .= '<a href="/lancamentoArregimentacao">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Arregimentação';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a href="/lancamentoAtendimento">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Atendimento';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-bar-chart"></span>';
        $html .= '<span class="sidebar-title">Relatórios</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';
        $html .= '<ul class="nav sub-nav">';
        $html .= '<li>';
        $html .= '<a href="/relatorio/1">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Membresia';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a href="/relatorio/2">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Células Realizadas';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a href="/relatorio/3">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Células Quantidade';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a href="/relatorioAtendimento">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Atendimento';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</li>';

        $html .= '<li class="sidebar-label pt20">Chamada I.V.</li>';
        $html .= '<li>';
        $html .= '<a href="/instituto">';
        $html .= '<span class="fa fa-list"></span>';
        $html .= '<span class="sidebar-title">Chamada I.V.</span>';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-print"></span>';


        $html .= '<span class="sidebar-title">Imprimir(Manutenção)</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';
        $html .= '<ul class="nav sub-nav">';

        $html .= '<li>';
        $html .= '<a href="#">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Fichas Revisao';
        $html .= '</a>';
        $html .= '</li>';

        $html .= '<li>';
        $html .= '<a href="#">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Listagem Revisionistas';
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
            /* Entidades do grupo */

            $entidades = $grupo->getEntidade();
            foreach ($entidades as $entidade) {
                if ($entidade->verificarSeEstaAtivo()) {
                    echo $this->view->perfilDropDown($entidade, 2);
                }
            }
        }
        $html .= '</div>';
        return $html;
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
