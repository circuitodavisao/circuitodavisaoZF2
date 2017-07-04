<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Doctrine\Common\Collections\Criteria;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemFichasAtivasRevisao extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
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

                $p = $f->getPessoa();
                $grupoPessoa = $p->getGrupoPessoaAtivo();
                if ($grupoPessoa != null) {
                    /* Verifica se a pessoa logada é Líder de Igreja  */
                    if ($this->view->entidade->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                        $idGrupoIgrejaDoRevisionista = $grupoPessoa->getGrupo()->getGrupoIgreja();
                        $idGrupoIgrejaLogado = $this->view->entidade->getGrupo()->getGrupoIgreja();
                        if (($idGrupoIgrejaDoRevisionista == $idGrupoIgrejaLogado) && ($f->getFrequencia() == 'S')) {
                            $p->setId($f->getId());
                            $pessoas[] = $p;
                        }
                    } else {
                        $idGrupoEquipeDoRevisionista = $grupoPessoa->getGrupo()->getGrupoEquipe();
                        $idGrupoEquipeLogado = $this->view->entidade->getGrupo()->getGrupoEquipe();
                        if (($idGrupoEquipeDoRevisionista == $idGrupoEquipeLogado) && ($f->getFrequencia() == 'S')) {
                            $p->setId($f->getId());
                            $pessoas[] = $p;
                        }
                    }
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Fichas Ativas</div>';
        } else {

            $html .= $this->view->templateFormularioTopo('Fichas Ativas do Revisão de Vidas');
            $html .= '<div class="panel-body bg-light">';

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';


            $html .= '<th class="text-center">';
            $html .= $this->view->translate(Constantes::$TRADUCAO_MATRICULA);
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
            $html .= '</th>';
            $html .= '<th class="text-center">';
            $html .= $this->view->translate(Constantes::$TRADUCAO_ENTIDADE_REVISIONISTA);
            $html .= '</th>';
            /* Somente líderes de igreja e equipes podem remover pessoas ativas */
            if ($this->verificaSePodeRemoverPessoaAtiva()) {
                $html .= '<th class="text-center"></th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($pessoas as $pessoa) {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $pessoa->getId() . '</td>';
                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $pessoa->getId() . ')';
                $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';
                $html .= '<td class="text-center">' . $pessoa->getGrupoPessoaAtivo()->getGrupo()->getEntidadeAtiva()->infoEntidade() . '</td>';
                if ($this->verificaSePodeRemoverPessoaAtiva()) {
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . '  ' . $this->view->translate(Constantes::$TRADUCAO_REMOVER_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '</div>';
            /* Fim panel-body */
            $html .= '<div class="panel-footer text-right">';
            /* Botões */

            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_ATIVOS_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));


            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();
        }

        return $html;
    }
    /* Somente líderes de igreja e equipes podem remover pessoas ativas */
    private function verificaSePodeRemoverPessoaAtiva(){
        if ($this->view->entidade->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA ||
                        $this->view->entidade->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            return true;
        }else{
            return false;
        }
    }

}
