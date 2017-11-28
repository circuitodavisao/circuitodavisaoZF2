<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemAulas.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe Helper responsável pela listagem de aulas.
 */
class ListagemAulas extends AbstractHelper {

    public function __construct() {

    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $aulas = $this->view->aulas;
        $aulasAtivas = array();
        foreach ($aulas as $aula) {
            if ($aula->verificarSeEstaAtivo()) {
                if ($aula->getDisciplina_id() == $this->view->idDisciplina) {
                    $aulasAtivas[] = $aula;
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($aulasAtivas) == 0) {
            $html .= $this->view->templateFormularioTopo('Aulas');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Aulas</div>';

            $html .= '</div>';
            $html .= '<div class="panel-footer">';
//            $html .= '<a href="/cadastroListarTurmaInativa">Aulas Inativas </a>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickVoltar = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_DISCIPLINA_LISTAR . '", '.$this->view->idCurso.')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_AULA_CADASTRAR . '", ' . $this->view->idDisciplina . ')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CADASTRAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            $html .= '</div>';
            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();
        } else {

            $html .= $this->view->templateFormularioTopo('Aulas');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';


            $html .= '<th class="text-center">';
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

            foreach ($aulasAtivas as $aula) {
                $html .= '<tr>';


                $stringNomeDaFuncaoOnClickInserir = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_FICHA_REVISAO . '", ' . $aula->getId() . ')';
                $stringNomeDaFuncaoOnClick = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_AULA_EDITAR . '", ' . $aula->getId() . ')';
                $stringNomeDaFuncaoOnClickExclusao = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_AULA_EXCLUSAO . '", ' . $aula->getId() . ')';
              

                $html .= '<td class="text-center">' . $aula->getPosicao() . '</td>';
                if (strlen($aula->getNome()) > 20) {
                    $nome = substr($aula->getNome(), 0, 15) . '..';
                } else {
                    $nome = $aula->getNome();
                }
                $html .= '<td class="text-center">' . $nome . '</td>';
                $html .= '<td class="text-center">';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 9, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));

                $html .= '</td>';
//                        }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>';
            /* Fim panel-body */

            $html .= '<div class="panel-footer">';
//            $html .= '<a href="/cadastroListarTurmaInativa">Aulas Inativas </a>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickVoltar = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_DISCIPLINA_LISTAR . '", '.$this->view->idCurso.')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCircuito("' .Constantes::$ROUTE_CURSO. Constantes::$PAGINA_AULA_CADASTRAR . '", ' . $this->view->idDisciplina . ')';
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
