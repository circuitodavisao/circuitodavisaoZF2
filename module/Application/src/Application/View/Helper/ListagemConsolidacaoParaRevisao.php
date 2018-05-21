<?php

namespace Application\View\Helper;

use Doctrine\Common\Collections\Criteria;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemConsolidacaoParaRevisao extends AbstractHelper {

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
        $grupo = $this->view->entidade->getGrupo();
//        foreach ($grupo->getResponsabilidadesAtivas() as $gr) {
//            $p = $gr->getPessoa();
//            $p->setTipo('LP');
//            $pessoas[] = $p;
//        }
        if (count($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado)) > 0) {
            foreach ($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado) as $gp) {

                /* Validação para visitantes inativados nesse mes transformados em consolidacoes */
                $adicionarVisitante = true;
                $grupoPessoaTipo = $gp->getGrupoPessoaTipo();
                if (!$gp->verificarSeEstaAtivo() && $grupoPessoaTipo->getId() == 1) {
                    $resposta = $this->view->repositorioORM->getGrupoPessoaORM()->encontrarPorIdPessoaAtivoETipo($gp->getPessoa_id(), null, 2); /* Consolidacao */
                    if (!empty($resposta)) {
                        $adicionarVisitante = false;
                    }
                }
                /* Fim validacao */

                $p = $gp->getPessoa();
                if (empty($gp->getNucleo_perfeito())) {
                    $p->setTipo($gp->getGrupoPessoaTipo()->getNomeSimplificado());
                } else {
                    $adicionar = false;
                }
                $p->setTransferido($gp->getTransferido(), $gp->getData_criacao(), $gp->getData_inativacao());
                $p->setIdGrupoPessoa($gp->getId());
                $p->setAtivo($gp->verificarSeEstaAtivo());
                if (!$p->getAtivo()) {
                    $p->setDataInativacao($gp->getData_inativacao());
                }
                $adicionar = true;
                /* Validacao de tranferencia */
                if ($p->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                    $adicionar = false;

                    /* Condição para data de cadastro */
                    $primeiroDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                    $ultimoDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);
                    $mesAtual = date('m'); /* Mes com zero */
                    $anoAtual = date('Y');

                    if ($p->getDataTransferidoAno() <= $anoAtual) {
                        if ($p->getDataTransferidoAno() == $anoAtual) {
                            if ($p->getDataTransferidoMes() <= $mesAtual) {
                                $adicionar = true;
                            }
                        } else {
                            $adicionar = true;
                        }
                    }
                }
                if (($p->getTipo() == 'CO' || $p->getTipo() == 'VI') && $gp->verificarSeEstaAtivo()) {
                    if ($adicionar && $adicionarVisitante && !$p->verificaSeParticipouDoRevisao()) {
                        $pessoasGrupo[] = $p;
                    }
                }
            }
        }

        /* Ordenacao de pessoas */
        $valores = array();
        foreach ($pessoasGrupo as $pg) {
            $valor = 0;
            switch ($pg->getTipo()) {
                case 'CO':
                    $valor = 4;
                    break;
                case 'LT':
                    $valor = 3;
                    break;
                case 'AL':
                    $valor = 2;
                    break;
                case 'VI':
                    $valor = 1;
                    break;
            }
            if (!$pg->getAtivo()) {
                $valor = -2;
                if (!$pg->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                    $valor = -1;
                }
            }
            $valores[$pg->getId()] = $valor;
        }
        $pA = array();
        $res = array();
        for ($i = 0; $i < count($pessoasGrupo); $i++) {
            for ($j = 0; $j < count($pessoasGrupo); $j++) {
                $pA[1] = $pessoasGrupo[$i];
                $pA[2] = $pessoasGrupo[$j];
                $res[1] = $valores[$pA[1]->getId()];
                $res[2] = $valores[$pA[2]->getId()];
                if ($res[1] > $res[2]) {
                    $pessoasGrupo[$i] = $pA[2];
                    $pessoasGrupo[$j] = $pA[1];
                }
            }
        }
        foreach ($pessoasGrupo as $pgA) {
            $pessoas[] = $pgA;
        }
        /* FIM Ordenacao de pessoas */



        /* Sem pessoas cadastrados */
        if (count($pessoas) == 0) {
            $html .= '<div class="alert alert-warning mt50"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Pessoas Cadastradas como visitante ou consolidação no lançamento de dados!</div>';
        } else {

            $html .= $this->view->templateFormularioTopo('Selecionar pessoa para o revisão');
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
            $html .= $this->view->translate(Constantes::$TRADUCAO_TIPO_REVISIONISTA);
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

                $html .= '<td class="text-center">' . $pessoa->getTipo() . '</td>';

                $stringNomeDaFuncaoOnClickInserir = 'funcaoCadastro("' . Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO . '", ' . $pessoa->getId() . ')';

                $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';

                $html .= '<td class="text-center">';

                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . '  ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
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
            $stringNomeDaFuncaoOnClickCadastro = 'funcaoCadastro("' . Constantes::$PAGINA_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
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
