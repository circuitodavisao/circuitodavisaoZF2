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
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemFichasParaRevisao extends AbstractHelper {

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
//        foreach ($grupo->getResponsabilidadesAtivas() as $gr) {
//            $p = $gr->getPessoa();
//            $p->setTipo('LP');
//            $pessoas[] = $p;
//        }
        if (count($frequencias) > 0) {
            foreach ($frequencias as $f) {

                $p = null;
                $pAux = null;
                $p = $f->getPessoa();
                $pAux = new Pessoa();
                $grupoPessoa = $p->getGrupoPessoaAtivo();
                if ($grupoPessoa != null) {
                    if($this->view->entidade->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA){
                        $idGrupoIgrejaDoRevisionista = $grupoPessoa->getGrupo()->getGrupoIgreja();
                        $idGrupoIgrejaLogado = $this->view->entidade->getGrupo()->getGrupoIgreja();
                        if (($idGrupoIgrejaDoRevisionista == $idGrupoIgrejaLogado) && ($f->getFrequencia() == 'N')) {
                            $pAux->setId($f->getId());
                            $pAux->setNome($p->getNome());
                            $pessoas[] = $pAux;
                        }
                    }else{
                        $idGrupoEquipeDoRevisionista = $grupoPessoa->getGrupo()->getGrupoEquipe(); 
                        $idGrupoEquipeLogado = $this->view->entidade->getGrupo()->getGrupoEquipe();
                        if (($idGrupoEquipeDoRevisionista == $idGrupoEquipeLogado) && ($f->getFrequencia() == 'N')) {
                            $pAux->setId($f->getId());
                            $pAux->setNome($p->getNome());
                            $pessoas[] = $pAux; 
                        }
                    }
                }
            }
        }

        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Pessoas Cadastradas!</div>';
        } else {

            $html .= $this->view->templateFormularioTopo('Fichas do Revisão de Vidas');
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
//                    }
            $html .= '<th class="text-center"></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($pessoas as $pessoa) {
                $html .= '<tr>';
 
                $html .= '<td class="text-center">' . $pessoa->getId() . '</td>';

                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $pessoa->getId() . ')';

                $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';

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
            /* Botões */
//                if ($tipoCelula) {
//                    if (count($this->getEventos()) < 2) {
//                        $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_CELULA . '", 0)';
//                        $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVA_CELULA), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
//                    } else {
//                        $html .= '<div class="alert alert-micro alert-warning">';
//                        $html .= '<i class="fa fa-warning pr10" aria-hidden="true"></i>';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_NUMERO_MAXIMO_CELULAS);
//                        $html .= '</div>';
//                    }
//                }
//                if ($tipoCulto) {
//                    $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_CULTO . '", 0)';
//                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_CULTO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
//                }
//                if ($tipoRevisao) {
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_FICHA_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
            $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
//                }

            /* Fim Botões */
            $html .= '</div>';
            /* Fim panel-footer */
            $html .= $this->view->templateFormularioRodape();
        }

        return $html;
    }

}
