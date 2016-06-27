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
                $p->setTransferido($gp->getTransferido());
                $pessoas[] = $p;
            }
        }

        /* Listagem dos eventos */
        $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Sem eventos cadastrados */
        if (count($eventos) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem eventos cadastrados!</div>';
        } else {
            foreach ($pessoas as $pessoa) {
                $html .= '<tr>';

                /* TIPO */
                $html .= '<td class="tdTipo">';

                /* Menu dropup Tipo */
                $html .= '<div class="btn-group btn-block dropdown">';
                $html .= '<span class="btn btn-dark btn-xs btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= $pessoa->getTipo();
                $html .= '<span class="sr-only"></span>';
                $html .= '</span>';

                $html .= '<ul class="dropdown-menu sobrepor-elementos">';
                $html .= '<span class="editable-container editable-inline">';
                $html .= '<div class="ml5 definicao-altura-30">';
                $html .= '<form class="form-inline editableform">';
                $html .= '<div class="control-group form-group">';
                $html .= '<div>';
                $html .= '<div class="editable-input">';
                $html .= '<button type="submit" class="btn btn-primary btn-sm" style="margin-left:5px;"><i class="fa fa-location-arrow"></i></button>';
                $html .= '<span class="editable-clear-x"></span>';
                $html .= '<button type="submit" class="btn btn-primary btn-sm" style="margin-left:5px;"><i class="fa fa-terminal"></i></button>';
                $html .= '<span class="editable-clear-x"></span>';
                $html .= '<button type="submit" class="btn btn-primary btn-sm" style="margin-left:5px;"><i class="fa fa-unlock-alt"></i></button>';
                $html .= '<span class="editable-clear-x"></span>';
                $html .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-left:5px;"><i class="fa fa-trash-o"></i></button>';
                $html .= '<span class="editable-clear-x"></span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</form>';
                $html .= '</div>';
                $html .= '</span>';
                $html .= '</ul>';
                /* Fim Menu dropup */

                $html .= '</td>';

                /* NOME */
                $html .= '<td class="tdNome text-left">&nbsp;';
                /* Menu dropup Nome */
                $html .= '<div class="btn-group dropdown">';

                $html .= '<a id="menudrop_' . $pessoa->getId() . '" class="tdNome text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= '<span id="span_nome_' . $pessoa->getId() . '">';
                /* Verificação se é transferencia */
                if ($pessoa->verificarSeFoiTransferido()) {
                    $html .= '<i class="fa fa-download"></i>';
                }
                $html .= $pessoa->getNomeListaDeLancamento();
                $html .= '</span>';
                $html .= '<span class="sr-only"></span>';
                $html .= '</a>';

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
                /* Fim Menu dropup */
                $html .= '</td>';
                foreach ($eventos as $ge) {
                    $valor = '';
                    $class = 'btn-default';
                    $classIco = 'fa-thumbs-down';
                    $evento = $ge->getEvento();
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

                    $html .= '<td ' . $style . ' class="text-center">';
                    $html .= '<div class="btn-group">';
                    if ($mostrar) {
                        $html .= '<button id="b_' . $idEventoFrequencia . '" type="button" class="btn ' . $class . ' btn-sm"'
                                . ' onclick=\'mudarFrequencia(';
                        $html .= "\"$idEventoFrequencia\", {$this->view->cicloSelecionado}, {$this->view->abaSelecionada}";
                        $html .= ');\'>';
                        $html .= '<i id="i_' . $idEventoFrequencia . '" class="fa ' . $classIco . '"></i>';
                        $html .= '</button>';
                    } else {/* Eventos futuro */
                        $html .= '<i class="fa fa-clock-o"></i>';
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
