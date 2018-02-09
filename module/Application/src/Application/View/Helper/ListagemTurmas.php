<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

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
            if ($turma->verificarSeEstaAtivo()) {
                $turmasAtivas[] = $turma;
            }
        }

        /* Sem pessoas cadastrados */
        if (count($turmasAtivas) == 0) {
            $html .= $this->view->templateFormularioTopo('Turmas');
            $html .= '<div class="panel-body bg-light">';
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Turmas</div>';
            $html .= '</div>';
            $html .= '<div class="panel-footer">';
            $html .= '<span class="align-bottom">';
            $html .= '<a href="/cadastroListarTurmaInativa">Turmas Inativas </a>';
            $html .= '</span>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_CADASTRAR_TURMA . '", 0)';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CADASTRAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            $html .= '</div>';
            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();

            ;
        } else {

            $html .= $this->view->templateFormularioTopo('Turmas do IV');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';

            $html .= '<th class="text-center">';
            $html .= 'Id';
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= 'Mês';
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= 'Ano';
            $html .= '</th>';
            $html .= '<th class="text-center hidden-xs">';
            $html .= 'Observação';
            $html .= '</th>';
            $html .= '<th class="text-center hidden-xs">';
            $html .= 'Alunos';
            $html .= '</th>';
            if ($turma->getTurmaAulaAtiva()) {
                $html .= '<th class="text-center hidden-xs"></th>';
                $html .= '<th class="text-center hidden-xs">';
                $html .= 'Aula Aberta';
                $html .= '</th>';
            }
            $html .= '<th class="text-center"></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($turmasAtivas as $turma) {
                $stringNomeDaFuncaoOnClick = 'funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_EDITAR_TURMA . '", ' . $turma->getId() . ')';
                $stringNomeDaFuncaoOnClickExclusao = 'funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_EXCLUSAO_TURMA . '", ' . $turma->getId() . ')';
                $stringNomeDaFuncaoOnClickIncluirAlunos = 'funcaoCircuito("' . Constantes::$ROUTE_CADASTRO . Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA . '",' . $turma->getId() . ')';
                $stringNomeDaFuncaoOnClickAbrirAula = 'funcaoCircuito("' . Constantes::$ROUTE_CURSO . 'AbrirAula' . '",' . $turma->getId() . ')';

                $html .= '<tr>';
                $html .= '<td class="text-center">' . $turma->getId() . '</td>';
                $html .= '<td class="text-center">' . Funcoes::mesPorExtenso($turma->getMes(), 1) . '</td>';
                $html .= '<td class="text-center">' . $turma->getAno() . '</td>';
                $html .= '<td class="text-center hidden-xs">' . $turma->getObservacao() . '</td>';
                $html .= '<td class="text-center hidden-xs">' . count($turma->getTurmaPessoa()) . '</td>';
                $html .= '<td class="text-center">';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 9, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickIncluirAlunos));
                $html .= $this->view->botaoLink('Abrir Aula', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickAbrirAula));
                $html .= '</td>';

                if ($turma->getTurmaAulaAtiva()) {
                    $html .= '<td class="text-center hidden-xs">';
                    $html .= $turma->getTurmaAulaAtiva()->getAula()->getNome();
                    $html .= '</td>';
                }


                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>';
            /* Fim panel-body */

            $html .= '<div class="panel-footer">';
            $html .= '<span class="align-bottom">';
            $html .= '<a href="/cadastroListarTurmaInativa">Turmas Inativas </a>';
            $html .= '</span>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_CADASTRAR_TURMA . '", 0)';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CADASTRAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            $html .= '</div>';
            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();
        }

        return $html;
    }

}
