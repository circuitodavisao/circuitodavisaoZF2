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
class ListagemTurmasInativas extends AbstractHelper {


    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $turmas = $this->view->turmas;
        $turmasInativas = array();
        foreach($turmas as $turma){
            if($turma->verificarSeEstaAtivo() == false){
                $turmasInativas[] = $turma; 
            }
        }

        /* Sem pessoas cadastrados */
        if (count($turmasInativas) == 0) { 

            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Turmas</div>';

            $html .= '</div>';


            ;
        } else {
            
                $html .= $this->view->templateFormularioTopo('Turmas INATIVADAS'); 
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
                $html .= '<th class="text-center">';
                $html .= 'Data Inativação';
                $html .= '</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                foreach ($turmasInativas as $turma) { 
                    $html .= '<tr>';

                    $html .= '<td class="text-center">' . $turma->getId() . '</td>';

                   
                    
                    $html .= '<td class="text-center">' . Funcoes::mesPorExtenso($turma->getMes(),1) . '</td>';
                    $html .= '<td class="text-center">' . $turma->getAno() . '</td>';
                    $html .= '<td class="text-center hidden-xs">' . $turma->getObservacao() . '</td>';   

                    $html .= '<td class="text-center">'.$turma->getData_inativacaoStringPadraoBrasil().'</td>';
//                        }
                    $html .= '</tr>';
                }
                $html .= '</tbody>'; 
                $html .= '</table>';

                $html .= '</div>';
                /* Fim panel-body */
                $html .= '<div class="panel-footer text-right">';

                $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_LISTAR_TURMA . '", 0)'; 
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));

                /* Fim Botões */
                $html .= '</div>';
                /* Fim panel-footer */
                $html .= $this->view->templateFormularioRodape();   
            
        }

        return $html;
    }

}
