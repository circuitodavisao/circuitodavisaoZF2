<?php

namespace Login\View\Helper;

use Entidade\Entity\Pessoa;
use Login\Controller\Helper\Constantes;
use Zend\Session\Container;
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
        // Start: Header 
        $html .= '<header class="navbar navbar-fixed-top navbar-shadow bg-dark">';
        $html .= '<div class="navbar-branding">';
        $html .= '<a class="navbar-brand" href="#" style="padding-top: 22px;">';
        $html .= '<img src="' . Constantes::$IMAGEM_LOGO_BRANCA . '" title="' . $this->view->translate(Constantes::$TRADUCAO_NOME_APLICACAO) . '" class="img-responsive" style="max-width:100%;">';
        $html .= '</a>';
        $html .= '<span id="toggle_sidemenu_l" class="ad ad-lines"></span>';
        $html .= '</div>';
        $html .= '<ul class="nav navbar-nav navbar-right">';
        $html .= '<li class="dropdown menu-merge">';
        $html .= '<a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">';
        $html .= '<img src="/img/avatars/diego-kort.jpg" alt="' . $this->view->pessoa->getNomePrimeiroUltimo() . '" class="mw30 br64">';
        $html .= '<span class="pl15">' . $this->view->pessoa->getNomePrimeiroUltimo() . '</span>';
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
        // Start: Sidebar Menu
        $html .= '<ul class="nav sidebar-menu">';
        $html .= '<li class="sidebar-label pt20">Menu</li>';
        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= '<span class="sidebar-title">Cadastrar</span>';
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
        $html .= '<a href="/cadastroCultos">';
        $html .= '<span class="fa fa-users"></span>';
        $html .= 'Cultos';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a class="accordion-toggle" href="#">';
        $html .= '<span class="fa fa-pencil"></span>';
        $html .= '<span class="sidebar-title">Lançar</span>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';
        $html .= '<ul class="nav sub-nav">';
        $html .= '<li>';
        $html .= '<a href="/lancamento">';
        $html .= '<span class="fa fa-terminal"></span>';
        $html .= 'Arregimentação';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '</ul>';
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
