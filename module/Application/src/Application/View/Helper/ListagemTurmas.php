<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de pesoas ativas no revisão seleiconado 
 */
class ListagemTurmas extends AbstractHelper {

    private $amostragem;

    public function __construct() {
        
    }

    public function __invoke($amostragem = null) {
        $this->setAmostragem($amostragem);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $turmas = $this->view->turmas;
        

        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {

            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Turmas</div>';

            $html .= '</div>';


            ;
        } else {
            if ($this->getAmostragem() == null) {
                $html .= $this->view->templateFormularioTopo('Fichas do Revisão de Vidas');
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
//                    }
                $html .= '<th class="text-center"></th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                foreach ($turmas as $turma) {
                    $html .= '<tr>';

                    $html .= '<td class="text-center">' . $turma->getId() . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $turma->getId() . ')';

                    $html .= '<td class="text-center">' . $turma->getMes() . '</td>';
                    $html .= '<td class="text-center">' . $turma->getAno() . '</td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . '  ' . $this->view->translate(Constantes::$TRADUCAO_VER_FICHA_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
//                        }
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';

                $html .= '</div>';
                /* Fim panel-body */
                $html .= '<div class="panel-footer text-right">';

                $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));

                /* Fim Botões */
                $html .= '</div>';
                /* Fim panel-footer */
                $html .= $this->view->templateFormularioRodape();
            } else {
                
                $html .= '<div class="panel-body bg-light">';

                $html .= '<table class="table">';
                $html .= '<thead>';
                $html .= '<tr>';

                $html .= '<th class="text-center" colspan=2>';
                $html .= $this->view->translate(Constantes::$TRADUCAO_FICHAS_ATIVAS_LABEL);
                $html .= '</th>';
//                    }
                $html .= '<th class="text-center"></th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';

                foreach ($pessoas as $pessoa) {
                    $html .= '<tr>';

                    $html .= '<td class="text-center">' . $pessoa->getId() . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_REMOVER_REVISIONISTA_ATIVO . '", ' . $pessoa->getId() . ')';

                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_MINUS . '  ' . $this->view->translate(Constantes::$TRADUCAO_REMOVER_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
//                        }
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';

                $html .= '</div>';
                /* Fim panel-body */

            }
        }

        return $html;
    }

    function getAmostragem() {
        return $this->amostragem;
    }

    function setAmostragem($amostragem) {
        $this->amostragem = $amostragem;
    }

}
