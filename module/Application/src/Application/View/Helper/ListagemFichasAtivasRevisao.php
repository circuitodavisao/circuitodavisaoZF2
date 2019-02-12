<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

class ListagemFichasAtivasRevisao extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="panel-body bg-light">';
        $html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Filtro">';
        $html .= '<table class="table table-condensed table-hover bg-light mt15 footable" data-filter="#fooFilter">';
        $html .= '<tbody>';
        if ($listaRevisionistas = $this->view->listas['revisionistas']) {
            $html .= '<tr class="dark">';
            $html .= '<th>Inscrição</th>';
            $html .= '<th>Revisionista</th>';
            $html .= '<th>Idade</th>';
            $html .= '<th>Time</th>';
            $html .= '<th>Remover</th>';
            $html .= '</tr>';
            foreach ($listaRevisionistas as $lista) {
                $html .= '<tr>';
                $html .= '<td>' . $lista->getId() . '</td>';
                $html .= '<td>' . $lista->getPessoa()->getNome() . '</td>';
                $html .= '<td>' . $lista->getPessoa()->getIdade() . '</td>';
                $html .= '<td>' . $lista->getPessoa()->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade() . '</td>';
                $html .= '<td>';
                $funcaoOnClick = $this->view->funcaoOnClick('mostrarSplash(); funcaoCadastro("' . Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO . '", ' . $lista->getId() . ')');
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_REMOVER_REVISIONISTA), Constantes::$STRING_HASHTAG, 0, $funcaoOnClick);
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        if ($listaLideres = $this->view->listas['lideres']) {
            $html .= '<tr class="dark">';
            $html .= '<th>Inscrição</th>';
            $html .= '<th>Líder</th>';
            $html .= '<th>Time</th>';
            $html .= '<th>Remover</th>';
            $html .= '</tr>';
            foreach ($listaLideres as $lista) {
                $html .= '<tr>';
                $html .= '<td>' . $lista->getId() . '</td>';
                $html .= '<td>' . $lista->getPessoa()->getNome() . '</td>';
                $html .= '<td>' . $lista->getPessoa()->getResponsabilidadesAtivas()[0]->getGrupo()->getEntidadeAtiva()->infoEntidade() . '</td>';
                $html .= '<td>';
                $funcaoOnClick = $this->view->funcaoOnClick('mostrarSplash(); funcaoCadastro("' . Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO . '", ' . $lista->getId() . ')');
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_REMOVER_REVISIONISTA), Constantes::$STRING_HASHTAG, 0, $funcaoOnClick);
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        return $html;
    }

}
