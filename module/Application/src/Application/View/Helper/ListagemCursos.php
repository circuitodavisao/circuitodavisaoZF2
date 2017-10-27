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
class ListagemCursos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $cursos = $this->view->cursos;
        $cursosAtivos = array();
        foreach ($cursos as $curso) {
            if ($curso->verificarSeEstaAtivo()) {
                $cursosAtivos[] = $curso;
            }
        }

        /* Sem pessoas cadastrados */
        if (count($cursosAtivos) == 0) {
            $html .= $this->view->templateFormularioTopo('Cursos');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Cursos</div>';

            $html .= '</div>';
            $html .= '<div class="panel-footer">';
//                $html .= '<a href="/cadastroListarTurmaInativa">Turmas Inativas </a>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_CURSO_CADASTRAR . '", 0)';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CADASTRAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            $html .= '</div>';
            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();

            ;
        } else {

            $html .= $this->view->templateFormularioTopo('Cursos');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';

            $html .= '<th class="text-center">';
            $html .= 'ID';
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= 'Data de Criação';
            $html .= '</th>';
            $html .= '<th class="text-center hidden-xs">';
            $html .= 'Criado Por';
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= 'Nome';
            $html .= '</th>';

//                    }
            $html .= '<th class="text-center">Ações</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($cursosAtivos as $curso) {
                $html .= '<tr>';

                $html .= '<td class="text-center">' . $curso->getId() . '</td>';

                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $curso->getId() . ')';
                $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_CURSO_EDITAR . '", ' . $curso->getId() . ')';
                $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_CURSO_EXCLUSAO . '", ' . $curso->getId() . ')';
                $stringNomeDaFuncaoOnClickIncluirDisciplinas = 'funcaoCadastro("' . Constantes::$PAGINA_DISCIPLINA_LISTAR . '",' . $curso->getId() . ')';

                $html .= '<td class="text-center">' . $curso->getData_criacaoStringPadraoBrasil() . '</td>';
                if (strlen($curso->getPessoa()->getNomePrimeiroUltimo()) <= 12) {
                    $nomePessoaQueCriou = $curso->getPessoa()->getNomePrimeiroUltimo();
                } else {
                    $nomePessoaQueCriou = $curso->getPessoa()->getNomePrimeiroPrimeiraSiglaUltimo();
                }
                $html .= '<td class="text-center hidden-xs">' . $nomePessoaQueCriou . '</td>';
                $html .= '<td class="text-center">' . $curso->getNome() . '</td>';

                $html .= '<td class="text-center">';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 9, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                $html .= $this->view->botaoLink('Incluir Disciplinas', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickIncluirDisciplinas));
                $html .= '</td>';
//                        }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>';
            /* Fim panel-body */

            $html .= '<div class="panel-footer">';
//                $html .= '<a href="/cadastroListarTurmaInativa">Turmas Inativas </a>';
            $html .= '<div class="text-right">';
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_CURSO_CADASTRAR . '", 0)';
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
