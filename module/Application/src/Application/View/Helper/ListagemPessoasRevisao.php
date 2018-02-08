<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Pessoa;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de pesoas ativas no revisão seleiconado
 */
class ListagemPessoasRevisao extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $pessoas = array();
        $frequencias = $this->view->evento->getEventoFrequencia();
        if (($frequencias)) {
            foreach ($frequencias as $frequencia) {
                if ($frequencia->getFrequencia() == 'S') {
                    $pessoas[] = $frequencia->getPessoa();
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {
            $html .= '<div class="panel-body bg-light">';
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Fichas Ativas</div>';
            $html .= '</div>';
        } else {
            $html .= '<div id="painelAlunos">';
            $html .= $this->view->templateFormularioTopo('Selecione os Alunos que não participarão da turma');
            $html .= '<div class="panel-menu">';
            $html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Digite o nome do Aluno">';
            $html .= '</div>';
            $html .= '<div class="panel-body bg-light">';
            $html .= '<table class="table footable" data-filter="#fooFilter" data-page-navigation=".pagination">';
            $html .= '<thead>';
            $html .= '<tr>';

            $html .= '<th class="text-center footable-sortable footable-sorted-desc">';
            $html .= $this->view->translate(Constantes::$TRADUCAO_MATRICULA);
            $html .= '</th>';
            $html .= '<th class="text-center footable-sortable">';
            $html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
            $html .= '</th>';
//                    }
            $html .= '<td class="text-center"></th>';
            $html .= '</td>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($pessoas as $pessoa) {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $pessoa->getId() . '</td>';
                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $pessoa->getId() . ')';
                $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';
                $html .= '<td class="text-center">';
                $html .= '<label class="option">
                              <input type="checkbox" name="alunos" id="' . $pessoa->getNome() . '" value="' . $pessoa->getId() . '">
                              <span class="checkbox"></span></label>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '<tfoot class="footer-menu">
                    <tr>
                      <td colspan="5">
                        <nav class="text-right">
                          <ul class="pagination hide-if-no-paging"></ul>
                        </nav>
                      </td>
                    </tr>
                  </tfoot>';
            $html .= '</table>';
            $html .= '</div>';
            /* Fim panel-body */
            $html .= '<div class="panel-footer text-right">';
            $stringNomeDaFuncaoOnClickVoltar = 'funcaoCircuito("' . Constantes::$ROUTE_CADASTRO . Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA . '", ' . $pessoa->getId() . ')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
            $stringNomeDaFuncaoOnClickProsseguir = 'mostrarResumo()';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CONFIRMACAO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickProsseguir));
            /* Fim Botões */
            $html .= '</div>';

            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();
            $html .= '</div>';
        }

        return $html;
    }

}
