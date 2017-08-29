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
        foreach($turmas as $turma){
            if($turma->verificarSeEstaAtivo()){
                $turmasAtivas[] = $turma;
            }
        }

        /* Sem pessoas cadastrados */
        if (count($turmasAtivas) == 0) {

            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Turmas</div>';

            $html .= '</div>';


            ;
        } else {
            
                $html .= $this->view->templateFormularioTopo('Turmas do IV');
                $html .= '<div class="panel-body bg-light">';

                $html .= '<table class="table">';
                $html .= '<thead>';
                $html .= '<tr>';

                $html .= '<th class="text-center">';
                $html .= 'ID';
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
//                    }
                $html .= '<th class="text-center"></th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                foreach ($turmasAtivas as $turma) { 
                    $html .= '<tr>';

                    $html .= '<td class="text-center">' . $turma->getId() . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $turma->getId() . ')';
                    $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_EDITAR_TURMA . '", ' . $turma->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_EXCLUSAO_TURMA . '", ' . $turma->getId() . ')';
                    $stringNomeDaFuncaoOnClickIncluirAlunos = 'funcaoCadastro("'.Constantes::$PAGINA_INCLUIR_ALUNOS_TURMA.','.$turma->getId().')';
                    
                    $html .= '<td class="text-center">' . Funcoes::mesPorExtenso($turma->getMes(),1) . '</td>';
                    $html .= '<td class="text-center">' . $turma->getAno() . '</td>';
                    $html .= '<td class="text-center hidden-xs">' . $turma->getObservacao() . '</td>';   

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickIncluirAlunos));
                    $html .= '</td>';
//                        }
                    $html .= '</tr>';
                }
                $html .= '</tbody>'; 
                $html .= '</table>';

                $html .= '</div>';
                /* Fim panel-body */
                
                $html .= '<div class="panel-footer">';
                $html .= '<a href="/cadastroListarTurmaInativa">Turmas Inativas </a>';
                $html .= '<div class="text-right">';
                $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_CADASTRAR_TURMA . '", 0)';
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
