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
class ListagemDePessoasComEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = Funcoes::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($this->view->abaSelecionada);
        $pessoas = array();
        $pessoasGrupo = array();
        $grupo = $this->view->entidade->getGrupo();
        foreach ($grupo->getResponsabilidadesAtivas() as $gr) {
            $p = $gr->getPessoa();
            $p->setTipo('LP');
            $pessoas[] = $p;
        }
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
                    if ($gp->getNucleo_perfeito() == "C") {
                        $p->setTipo('CO');
                    }
                    if ($gp->getNucleo_perfeito() == "L") {
                        $p->setTipo('LT');
                    }
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
                if ($adicionar && $adicionarVisitante) {
                    $pessoasGrupo[] = $p;
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

        /* Listagem dos eventos */
        $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Sem eventos cadastrados */
        if (count($eventos) == 0) {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem eventos cadastrados!</div>';
        } else {
            foreach ($pessoas as $pessoa) {
                $classLinha = '';
                $corBotao = 'btn-dark';
                $corTextoTagsExtrasXs = ' class="hidden-lg" ';
                $corTextoTagsExtrasLg = ' class="hidden-xs hidden-sm hidden-md" ';
                $classLinha2 = '';
                if ($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo()) {
                    $classLinha = 'class="row-warning warning"';
                    $classLinha2 = 'footable-visible footable-first-column';
                    $corBotao = 'btn-warning disabled';
                    $base = ' text-warning" data-toggle="tooltip" data-placement="center" title data-original-title="Inativo"';
                    $corTextoTagsExtrasXs = 'class="hidden-lg' . $base;
                    $corTextoTagsExtrasLg = 'class="hidden-xs hidden-sm hidden-md' . $base;
                }
                if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                    $classLinha = 'class="row-dark default"';
                    $corBotao = 'btn-default';
                    $base = ' text-muted" data-toggle="tooltip" data-placement="center" title data-original-title="Transferido"';
                    $corTextoTagsExtrasXs = 'class="hidden-lg' . $base;
                    $corTextoTagsExtrasLg = 'class="hidden-xs hidden-sm hidden-md' . $base;
                }
                /* Reserva do revisão de vidas */
                if (!empty($pessoa->verificaSeRevisaoFoiCadastraddoNoMesEAno($mesSelecionado, $anoSelecionado))) {
                    $classLinha = 'class="row-success success"';
                    $classLinha2 = 'footable-visible footable-first-column';
                    $corBotao = 'btn-success';
                    $base = ' text-success" data-toggle="tooltip" data-placement="center" title data-original-title="Revisão de vidas"';
                    $corTextoTagsExtrasXs = 'class="hidden-lg' . $base;
                    $corTextoTagsExtrasLg = 'class="hidden-xs hidden-sm hidden-md' . $base;
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

                    if ($pessoa->getTipo() != 'AL') {
                        $html .= '<div class="control-group form-group">';

                        $html .= '<span class="input-group-btn">';
                        $html .= '<span onclick="funcaoPessoa(\'' . Constantes::$ROUTE_REMOVER_PESSOA . '\', ' . $pessoa->getIdGrupoPessoa() . ');" '
                                . 'class="btn ladda-button btn-sm" style="margin-left:5px;"><i class="fa fa-trash-o"></i></span>';
                        $html .= '</span>';

//                        /* Reserva do revisão de vidas */
//                        if (empty($pessoa->verificaSeRevisaoFoiCadastraddoNoMesEAno($mesSelecionado, $anoSelecionado))) {
//                            $html .= '<span class="input-group-btn">';
//                            $html .= '<span onclick="funcaoPessoa(\'' . Constantes::$ROUTE_CADASTRAR_PESSOA_REVISAO . '\', ' . $pessoa->getIdGrupoPessoa() . ');" '
//                                    . 'class="btn ladda-button btn-sm" style="margin-left:5px;"><i class="fa fa-send"></i></span>';
//                            $html .= '</span>';
//                        } else {
//                            $html .= '<span class="input-group-btn">';
//                            $html .= '<span onclick="funcaoPessoa(\'' . Constantes::$ROUTE_FICHA_REVISAO . '\', ' . $pessoa->getIdGrupoPessoa() . ');" '
//                                    . 'class="btn ladda-button btn-sm" style="margin-left:5px;"><i class="fa fa-file-text-o"></i></span>';
//                            $html .= '</span>';
//                        }

                        $html .= '</div>';
                    }

                    $html .= '</div>';
                    $html .= '</span>';
                    $html .= '</ul>';
                }

                /* Fim Menu dropup */

                $html .= '</td>';

                // AJUSTE 
                // 29/07/2016
                // Empura as colunas de eventos quando tem menos de 4 eventos
                $empuraColunas = '';
                if ($this->view->quantidadeDeEventosNoCiclo < 4) {
                    $empuraColunas = 'col-xs-10 col-sm-10 col-md-10';
                }
                // FIM AJUSTE

                /* NOME */
                $html .= '<td class="text-left ' . $empuraColunas . '">&nbsp;';
                /* Menu dropup Nome */
                $html .= '<div class="btn-group dropdown">';
                if (!($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo())) {
                    $html .= '<a id="menudrop_' . $pessoa->getId() . '" class="tdNome text-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                }
                /* nome */
                $html .= '<span id="span_nome_' . $pessoa->getId() . '" ' . $corTextoTagsExtrasXs . '>';
                $html .= $pessoa->getNomeListaDeLancamento($this->view->quantidadeDeEventosNoCiclo);
                $html .= '</span>';
                $html .= '<span ' . $corTextoTagsExtrasLg . '>';
                $html .= $pessoa->getNome();
                $html .= '</span>';
                /* fim nome */
                $html .= '</a>';
                if (!($pessoa->getTipo() != 'LP' && !$pessoa->getAtivo())) {
                    $html .= '<ul class="dropdown-menu sobrepor-elementos modal-edicao-nome">';
                    $html .= '<span class="editable-container editable-inline">';
                    $html .= '<div class="ml10 campo-edicao-nome">';
                    $html .= '<form class="form-inline editableform">';
                    $html .= '<div class="control-group form-group" style="width:140px;">';
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

                    /* Validacao para eventos 5 a 7 ou mais */
                    switch ($this->view->quantidadeDeEventosNoCiclo) {
                        case 1:
                            $style = 'style="width:100%;"';
                            break;
                        case 2:
                            $style = 'style="width:50%;"';
                            break;
                        case 3:
                            $style = 'style="width:33%;"';
                            break;
                        case 4:
                            $style = 'style="width:25%;"';
                            break;
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
                    $condicaoCicloAtual = ($this->view->abaSelecionada == 1 && $this->view->cicloSelecionado == Funcoes::cicloAtual($mesSelecionado, $anoSelecionado));
                    $condicaoCicloAnteriores = ($this->view->abaSelecionada == 1 && $this->view->cicloSelecionado < Funcoes::cicloAtual($mesSelecionado, $anoSelecionado));
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
                    $primeiroDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                    if ($pessoa->verificarSeFoiTransferido($mesSelecionado, $anoSelecionado)) {
                        $mostrar = false;
                        $icone = 2;
                        /* Condição para data de cadastro */

                        $ultimoDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);

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
                            $primeiroDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 1);
                            $ultimoDiaCiclo = Funcoes::periodoCicloMesAno($this->view->cicloSelecionado, $mesSelecionado, $anoSelecionado, '', 2);
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
                            if ($eventosFiltrados->count() === 1) {
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
                        $html .= "\"$idEventoFrequencia\", {$this->view->cicloSelecionado}, {$this->view->abaSelecionada}, {$this->view->grupo->getId()}";
                        $html .= ');\'>';
                        $html .= '<i id="i_' . $idEventoFrequencia . '" class="fa ' . $classIco . '"></i>';
                        $html .= '</button>';
                    } else {/* Eventos futuro */
                        $html .= '<button type="button" class="btn btn-sm disabled">';
                        if ($icone == 1) {
                            $html .= '<i class="fa fa-clock-o"></i>';
                        }
                        if ($icone == 2) {
                            $html .= '<i class="fa fa-random"></i>';
                        }
                        if ($icone == 3) {
                            $html .= '<i class="fa fa-ban"></i>';
                        }
                        $html .= '</button>';
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
