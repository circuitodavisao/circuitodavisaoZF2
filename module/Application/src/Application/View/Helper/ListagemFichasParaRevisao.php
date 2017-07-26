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

            /* Caso seja evento do tipo Célula */
//                    if ($tipoCelula) {
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(Constantes::$TRADUCAO_HORA);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_NOME_HOSPEDEIRO);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center visible-lg visible-md visible-sm">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_TELEFONE_HOSPEDEIRO);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center visible-lg visible-md visible-sm">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_LOGRADOURO);
//                        $html .= '</th>';
//                    }
//                    if ($tipoCulto) {
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(Constantes::$TRADUCAO_HORA);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center visible-lg visible-md visible-sm">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_NOME);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_EQUIPES);
//                        $html .= '</th>';
//                    }
//                    if ($tipoRevisao) {
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_DATA_SIMPLIFICADO);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_OBSERVACAO);
//                        $html .= '</th>';
//                        $html .= '<th class="text-center">';
//                        $html .= $this->view->translate(Constantes::$TRADUCAO_IGREJAS);
//                        $html .= '</th>';
//                    }
//                    if ($tipoRevisionistas) {
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
//                        $evento = $ge->getEvento();
//                        $diaDaSemanaAjustado = Funcoes::diaDaSemanaPorDia($evento->getDia());

                $html .= '<tr>';
//                        if ($tipoCelula) {
//
//                            $html .= '<td class="text-center">' . $this->view->translate($diaDaSemanaAjustado) . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
//                            $celula = $evento->getEventoCelula();
//                            $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_CELULA . '", ' . $celula->getId() . ')';
//                            $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_EXCLUSAO . '", ' . $evento->getId() . ')';
//
//                            $html .= '<td class="text-center">' . $celula->getNome_hospedeiroPrimeiroNome() . '</td>';
//                            $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getTelefone_hospedeiroFormatado() . '</td>';
//                            $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getLogradouro() . '&nbsp;' . $celula->getComplemento() . '</td>';
//                            $html .= '<td class="text-center">';
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
//                            $html .= '</td>';
//                        }
//                        if ($tipoCulto) {
//
//                            $html .= '<td class="text-center">' . $this->view->translate($diaDaSemanaAjustado) . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
//                            $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_CULTO . '", ' . $evento->getId() . ')';
//                            $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_EVENTO_EXCLUSAO . '", ' . $evento->getId() . ')';
//                            $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
//                            $texto = '';
//                            foreach ($grupoEventoAtivos as $gea) {
//                                if ($this->view->extra != $gea->getGrupo()->getId()) {
//                                    $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
//                                }
//                            }
//                            $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $evento->getNome() . '</span></td>';
//                            $html .= '<td class="text-center">' . $this->view->BotaoPopover(count($grupoEventoAtivos) - 1, $texto) . '</td>';
//                            $html .= '<td class="text-center">';
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
//                            $html .= '</td>';
//                        }
//                        if ($tipoRevisao) {
//
//                            $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';
//                            $stringNomeDaFuncaoOnClick = 'funcaoCadastro("' . Constantes::$PAGINA_CADASTRO_REVISAO . '", ' . $evento->getId() . ')';
//                            $stringNomeDaFuncaoOnClickExclusao = 'funcaoCadastro("' . Constantes::$PAGINA_CADASTRO_REVISAO . '", ' . $evento->getId() . ')';
//                            $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
//                            $texto = '';
//                            foreach ($grupoEventoAtivos as $gea) {
//                                if ($this->view->extra != $gea->getGrupo()->getId()) {
//                                    $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
//                                }
//                            }
//                            $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';
//                            $html .= '<td class="text-center">' . $this->view->BotaoPopover(count($grupoEventoAtivos) - 1, $texto) . '</td>';
//                            $html .= '<td class="text-center">';
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
//                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
//                            $html .= '</td>';
//                        }
//                        if ($tipoRevisionistas) {

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
