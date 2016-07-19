<?php

namespace Lancamento\View\Helper;

use Doctrine\Common\Collections\Criteria;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemDePessoasComEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($this->view->abaSelecionada);
        $pessoas = array();
        $grupo = $this->view->entidade->getGrupo();
        foreach ($grupo->getResponsabilidadesAtivas() as $gr) {
            $p = $gr->getPessoa();
            $p->setTipo('LP');
            $pessoas[] = $p;
        }
        if (count($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado)) > 0) {
            foreach ($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado) as $gp) {
                $p = $gp->getPessoa();
                $p->setTipo($gp->getGrupoPessoaTipo()->getNomeSimplificado());
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
                    $primeiroDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                    $ultimoDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);
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
                if ($adicionar == true) {
                    $pessoas[] = $p;
                }
            }
        }

        /* Listagem dos eventos */
        $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Sem eventos cadastrados */
        if (count($eventos) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem eventos cadastrados!</div>';
        } else {
            foreach ($pessoas as $pessoa) {
                $classLinha = '';
                $corBotao = 'btn-dark';
                $corTextoTagsExtras = '';
                $classLinha2 = '';
                if ($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo()) {
                    $classLinha = 'class="row-warning warning"';
                    $classLinha2 = 'footable-visible footable-first-column';
                    $corBotao = 'btn-warning disabled';
                    $corTextoTagsExtras = 'class="text-warning" data-toggle="tooltip" data-placement="center" title data-original-title="Inativo"';
                }
                if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                    $classLinha = 'class="row-dark default"';
                    $corBotao = 'btn-default';
                    $corTextoTagsExtras = 'class="text-muted" data-toggle="tooltip" data-placement="center" title data-original-title="Transferido"';
                }
                if ($pessoa->getTipo() == 'RE') {
                    $classLinha = 'class="row-success success"';
                    $classLinha2 = 'footable-visible footable-first-column';
                    $corBotao = 'btn-success';
                    $corTextoTagsExtras = 'class="text-success" data-toggle="tooltip" data-placement="center" title data-original-title="Revisão de vidas"';
                }
                $html .= '<tr id="tr_' . $pessoa->getIdGrupoPessoa() . '" ' . $classLinha . '>';

                /* TIPO */
                $html .= '<td class="tdTipo ' . $classLinha2 . '">';
                /* Menu dropup Tipo */
                $html .= '<div class="btn-group btn-block dropdown">';
                $html .= '<span class="btn ' . $corBotao . ' btn-xs btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= $pessoa->getTipo();
                $html .= '<span class="sr-only"></span>';
                $html .= '</span>';
                if ($pessoa->getTipo() != 'LP' && $this->view->abaSelecionada == 1 && $pessoa->getAtivo()) {
                    $html .= '<ul class="dropdown-menu sobrepor-elementos">';
                    $html .= '<span class="editable-container editable-inline">';
                    $html .= '<div class="ml5 definicao-altura-30">';
                    $html .= '<form class="form-inline editableform">';
                    $html .= '<div class="control-group form-group">';
                    if ($pessoa->getTipo() != 'AL') {
                        $html .= '<div>';
                        $html .= '<span class="input-group-btn">';
                        $html .= '<span onclick="removerPessoa(' . $pessoa->getIdGrupoPessoa() . ')" class="btn ladda-button btn-sm" style="margin-left:5px;"><i class="fa fa-trash-o"></i></span>';
                        $html .= '<span class="editable-clear-x"></span>';
                        $html .= '</span>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</form>';
                    $html .= '</div>';
                    $html .= '</span>';
                    $html .= '</ul>';
                }

                /* Fim Menu dropup */

                $html .= '</td>';

                /* NOME */
                $html .= '<td class="tdNome text-left">&nbsp;';
                /* Menu dropup Nome */
                $html .= '<div class="btn-group dropdown">';
                if (!($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo())) {
                    $html .= '<a id="menudrop_' . $pessoa->getId() . '" class="tdNome text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                }
                $html .= '<span id="span_nome_' . $pessoa->getId() . '" ' . $corTextoTagsExtras . '>';
                /* Verificação se é transferencia */
                $html .= $pessoa->getNomeListaDeLancamento();
                $html .= '</span>';
                $html .= '</a>';
                if (!($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo())) {
                    $html .= '<ul class="dropdown-menu sobrepor-elementos modal-edicao-nome">';
                    $html .= '<span class="editable-container editable-inline">';
                    $html .= '<div class="ml10 campo-edicao-nome">';
                    $html .= '<form class="form-inline editableform">';
                    $html .= '<div class="control-group form-group">';
                    $html .= '<div>';
                    $html .= '<div class="input-group">';
                    $html .= '<input type="text" class="form-control" id="nome_' . $pessoa->getId() . '" value="' . $pessoa->getNome() . '" />';
                    $html .= '<span class="input-group-btn">';
                    $html .= '<span onclick="alterarNome(' . $pessoa->getId() . ')" class="btn ladda-button btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-check"></i></span></span>';
                    $html .= '</span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';

                    $html .= '</div>';
                }
                /* Fim Menu dropup */
                $html .= '</td>';
                foreach ($eventos as $ge) {
                    $valor = '';
                    $class = 'btn-default';
                    $classIco = 'fa-thumbs-down';
                    $evento = $ge->getEvento();

                    /* Validacao para poucos eventos 5 a 7 ou mais */
                    switch ($this->view->quantidadeDeEventosNoCiclo) {
                        case 5:
                            $style = 'style="width:20%;"';
                            break;
                        case 6:
                            $style = 'style="width:18%;"';
                            break;
                        case 7:
                            $style = 'style="width:15%;"';
                            break;
                        case 8:
                            $style = 'style="width:12%;"';
                            break;
                        default:
                            $style = 'style="width:20%;"';
                            break;
                    }

                    /* Condições mes anteiror, mes atual e ciclos */
                    $condicaoMesAnterior = ($this->view->abaSelecionada == 2);
                    $condicaoCicloAtual = ($this->view->abaSelecionada == 1 && $this->view->cicloSelecionado == FuncoesLancamento::cicloAtual($mesSelecionado, $anoSelecionado));
                    $condicaoCicloAnteriores = ($this->view->abaSelecionada == 1 && $this->view->cicloSelecionado < FuncoesLancamento::cicloAtual($mesSelecionado, $anoSelecionado));
                    $diaDaSemana = date('N');
                    if ($diaDaSemana == 7) {
                        $diaDaSemana = 8;
                    } else {
                        $diaDaSemana++;
                    }
                    $condicaoDiaSemana = ($condicaoCicloAtual && $evento->getDiaAjustado() <= $diaDaSemana);

                    /* Validação */
                    $mostrar = false;
                    /* Validando abas */
                    if ($condicaoMesAnterior) {
                        $mostrar = true;
                    }
                    if ($condicaoCicloAnteriores) {
                        $mostrar = true;
                    } else {
                        if ($condicaoDiaSemana) {
                            $mostrar = true;
                        }
                    }

                    /* Validação de transferencias */
                    $icone = 1;
                    $primeiroDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                    if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                        $mostrar = false;
                        $icone = 2;
                        /* Condição para data de cadastro */

                        $ultimoDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);

                        /* cadastrado nesse mes com dia anteiror o ciclo */
                        if ($condicaoDiaSemana || $condicaoCicloAnteriores || $condicaoMesAnterior) {
                            if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado, 1)) {
                                $mostrar = true;
                                if ($condicaoMesAnterior) {
                                    if (!$pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado, 2)) {
                                        $mostrar = true;
                                    }
                                }
                            } else {
                                if ($pessoa->getDataTransferidoDia() <= $primeiroDiaCiclo) {
                                    $mostrar = true;
                                } else {
                                    if ($pessoa->getDataTransferidoDia() <= $ultimoDiaCiclo) {
                                        /* Verificar dia da semana da transferencia */
                                        $diaDaSemana = date('N', mktime(0, 0, 0, $pessoa->getDataTransferidoMes(), $pessoa->getDataTransferidoDia(), $pessoa->getDataTransferidoAno()));
                                        if ($diaDaSemana == 1) {
                                            $diaDaSemana = 8;
                                        } else {
                                            $diaDaSemana++;
                                        }
                                        if ($diaDaSemana >= $evento->getDiaAjustado()) {
                                            $mostrar = true;
                                        }
                                    }
                                    if ($pessoa->getDataTransferidoDia() > $ultimoDiaCiclo) {
                                        $mostrar = true;
                                    }
                                }
                            }
                        }
                    }
                    if (!$condicaoDiaSemana && !$condicaoMesAnterior) {
                        $icone = 1;
                    }
                    if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado, 2)) {
                        $icone = 2;
                    } else {
                        /* Verificando inativado */
                        if (!empty($pessoa->getDataInativacao())) {
                            /* Data Inativacao */
                            $primeiroDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                            $ultimoDiaCiclo = FuncoesLancamento::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);
                            if ($pessoa->getDataInativacaoDia() > $ultimoDiaCiclo) {
                                $mostrar = true;
                            }
                            if ($pessoa->getDataInativacaoDia() < $primeiroDiaCiclo && $pessoa->getDataInativacaoMes() == $mesSelecionado && $pessoa->getDataInativacaoAno() == $anoSelecionado) {
                                $icone = 3;
                                $mostrar = false;
                            }
                        }
                    }

                    $html .= '<td ' . $style . ' class="text-center">';
                    $html .= '<div class="btn-group">';
                    if ($mostrar) {
                        $eventoFrequencia = $evento->getEventoFrequencia();
                        $idEventoFrequencia = 'ico_' . $pessoa->getId() . '_' . $evento->getId();
                        if (count($eventoFrequencia) > 0) {
                            $criteria = Criteria::create()
                                    ->andWhere(Criteria::expr()->eq("pessoa_id", $pessoa->getId()))
                                    ->andWhere(Criteria::expr()->eq("ano", $anoSelecionado))
                                    ->andWhere(Criteria::expr()->eq("mes", $mesSelecionado))
                                    ->andWhere(Criteria::expr()->eq("ciclo", $this->view->cicloSelecionado))
                            ;
                            $eventosFiltrados = $eventoFrequencia->matching($criteria);
                            if ($eventosFiltrados->count() == 1) {
                                $valor = $eventosFiltrados->first()->getFrequencia();
                                if ($valor == 'S') {
                                    $class = 'btn-success';
                                    $classIco = 'fa-thumbs-up';
                                } else {
                                    $class = 'btn-default';
                                    $classIco = 'fa-thumbs-down';
                                }
                            }
                        }
                        $html .= '<button id="b_' . $idEventoFrequencia . '" type="button" class="btn ' . $class . ' btn-sm"'
                                . ' onclick=\'mudarFrequencia(';
                        $html .= "\"$idEventoFrequencia\", {$this->view->cicloSelecionado}, {$this->view->abaSelecionada}";
                        $html .= ');\'>';
                        $html .= '<i id="i_' . $idEventoFrequencia . '" class="fa ' . $classIco . '"></i>';
                        $html .= '</button>';
                    } else {/* Eventos futuro */
                        if ($icone == 1) {
                            $html .= '<i class="fa fa-clock-o"></i>';
                        }
                        if ($icone == 2) {
                            $html .= '<i class="fa fa-random"></i>';
                        }
                        if ($icone == 3) {
                            $html .= '<i class="fa fa-ban"></i>';
                        }
                    }
                    $html .= '</div>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
        }
        return $html;
    }

}
