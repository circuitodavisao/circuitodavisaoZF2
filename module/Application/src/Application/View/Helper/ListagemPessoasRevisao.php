<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Pessoa;
use Doctrine\Common\Collections\Criteria;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de pesoas ativas no revisão seleiconado
 */
class ListagemPessoasRevisao extends AbstractHelper {

    private $amostragem;

    public function __construct() {

    }

    public function __invoke($amostragem = null) {
        $this->setAmostragem($amostragem);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = date("m");
        $anoSelecionado = date("Y");
        $pessoas = array();
        $pessoasGrupo = array();
        $frequencias = $this->view->evento->getEventoFrequencia();
        if (count($frequencias) > 0) {
            foreach ($frequencias as $f) {
                $p = null;
                $pAux = null;
                $p = $f->getPessoa();
                $pAux = new Pessoa();
                $grupoPessoa = $p->getGrupoPessoaAtivo();
                if ($grupoPessoa != null) {
                        $idGrupoIgrejaDoRevisionista = $grupoPessoa->getGrupo()->getGrupoIgreja();
                        $idGrupoIgrejaLogado = $this->view->entidade->getGrupo()->getGrupoIgreja();
                        if (($idGrupoIgrejaDoRevisionista == $idGrupoIgrejaLogado) && ($f->getFrequencia() == 'S')) {
                            $pAux->setId($f->getId());
                            $pAux->setNome($p->getNome());
                            $pessoas[] = $pAux;
                        }
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {

            $html .= '<div class="panel-body bg-light">';

            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Fichas Ativas</div>';

            $html .= '</div>';


            ;
        } else {
            if ($this->getAmostragem() == null) {
                $html .= '<div id="painelAlunos">';
                $html .= $this->view->templateFormularioTopo('Selecione os Alunos que não participarão da turma');
                $html .= '<div class="panel-menu">';
                $html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Digite o nome do Aluno">';
                $html .= '</div>';
                $html .= '<div class="panel-body bg-light">';
                $html .= '<form method="POST" name="formulario" action="cursoRetirarAlunos" id="formulario">';
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
                              <input type="checkbox" name="alunos" id="'.$pessoa->getNome().'" value="'.$pessoa->getId().'">
                              <span class="checkbox"></span></label>';
                    $html .= '</td>';
//                        }
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
                $html .= '</form>';
                $html .= '</div>';
                /* Fim panel-body */
                $html .= '<div class="panel-footer text-right">';

                $stringNomeDaFuncaoOnClickVoltar = 'funcaoCircuito("' .Constantes::$ROUTE_CADASTRO.Constantes::$PAGINA_LISTAGEM_REVISAO_TURMA. '", ' . $pessoa->getId() . ')';
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
                $stringNomeDaFuncaoOnClickProsseguir = 'mostrarResumo()';
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_CONFIRMACAO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickProsseguir));


                /* Fim Botões */
                $html .= '</div>';

                /* Fim panel-footer */
                $html .= $this->view->templateFormularioRodape();
                $html .= '</div>';
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
