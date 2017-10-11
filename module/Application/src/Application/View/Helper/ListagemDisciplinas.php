<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDisciplinas.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe Helper responsável pela listagem de disciplinas. 
 */
class ListagemDisciplinas extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $disciplinas = $this->view->disciplinas;
        $disciplinasAtivas = array();
        foreach ($disciplinas as $disciplina) {
            if ($disciplina->verificarSeEstaAtivo()) {
                if ($disciplina->getCurso_id() == $this->view->idCurso) {
                    $disciplinasAtivas[] = $disciplina;
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($disciplinasAtivas) == 0) {

            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Disciplinas</div>';

            $html .= '</div>';


            ;
        } else {

            $html .= $this->view->templateFormularioTopo('Disciplinas');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';


            $html .= '<th class="text-center hidden-xs">';
            $html .= 'Posição';
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= 'Nome';
            $html .= '</th>';

//                    }
            $html .= '<th class="text-center">Ações</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($disciplinasAtivas as $disciplina) {
                $html .= '<tr>';


                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $disciplina->getId() . ')';
                $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_DISCIPLINA_EDITAR . '", ' . $disciplina->getId() . ')';
                $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_DISCIPLINA_EXCLUSAO . '", ' . $disciplina->getId() . ')';
                $stringNomeDaFuncaoOnClickIncluirAlunos = 'funcaoCadastro("' . Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA . '",' . $disciplina->getId() . ')';

                $html .= '<td class="text-center">' . $disciplina->getPosicao() . '</td>';
                $html .= '<td class="text-center">' . $disciplina->getNome() . '</td>';

                $html .= '<td class="text-center">';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 9, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                $html .= $this->view->botaoLink('Incluir Aulas', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickIncluirAlunos));
                $html .= '</td>';
//                        }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>';
            /* Fim panel-body */

            $html .= '<div class="panel-footer">';
//            $html .= '<a href="/cadastroListarTurmaInativa">Disciplinas Inativas </a>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickVoltar = 'funcaoCadastro("' . Constantes::$PAGINA_CURSO_LISTAR . '", 0)';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_DISCIPLINA_CADASTRAR . '", ' . $this->view->idCurso . ')';
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
