<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AtendimentoGruposAbaixo.php
 * @author Luca Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar o numero de discipulos atendidos e o progresso do líder 
 */
class AtendimentoGruposAbaixoRelatorio extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $htmlRaiz = '';

        if (!empty($this->view->gruposAbaixo)) {
            $arrayCompleto = array();
            $arrayIncompleto = array();
            $auxI = 0;
            $auxN = 0;
            foreach ($this->view->gruposAbaixo as $gp12) {

                $grupo12 = $gp12->getGrupoPaiFilhoFilho();
                $grupos144 = $grupo12->getGrupoPaiFilhoFilhos();
                $html[$grupo12->getId()] = '';
                $grupoResponsavel12 = $grupo12->getResponsabilidadesAtivas();
                $nomeEntidade12 = $grupo12->getEntidadeAtiva()->infoEntidade();
                if ($grupoResponsavel12) {
                    $pessoas12 = array();
                    foreach ($grupoResponsavel12 as $gr12) {
                        $p = $gr12->getPessoa();
                        $imagem = 'placeholder.png';
                        if (!empty($p->getFoto())) {
                            $imagem = $p->getFoto();
                        }
                        $pessoas12[] = $p;
                    }

                    $informacaoEntidade12 = '';
                    $informacaoFoto12 = '';
                    $contagem12 = 1;
                    $totalPessoas12 = count($pessoas12);

                    foreach ($pessoas12 as $p) {
                        if ($contagem12 == 2) {
                            $informacaoEntidade12 .= '&nbsp;&&nbsp;';
                            $informacaoFoto12 .= '';
                        }
                        $tamanho = 40;
                        if (!empty($p->getFoto())) {
                            $imagem = $p->getFoto();
                        }
                        $informacaoFoto12 .= '<img src="/img/avatars/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . '%"  height="' . $tamanho . '%" />&nbsp;';
                        if ($totalPessoas12 == 1) {
                            $informacaoEntidade12 .= $p->getNomePrimeiroUltimo();
                        } else {// duas pessoas
                            $informacaoEntidade12 .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                        }
                        $contagem12++;
                    }

                    $totalGruposAtendidos = 0;
                    $totalGruposFilhos = 0;
                    foreach ($grupos144 as $gpFilho) {
                        $totalGruposAtendido = 0;
                        $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                        $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                        $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
                        if ($grupoResponsavel) {
                            $atendimentosDoGrupo = $grupoFilho->getGrupoAtendimento();
                            foreach ($atendimentosDoGrupo as $ga) {
                                if ($ga->verificarSeEstaAtivo()) {
                                    $partes = explode("/", $ga->getDia());
                                    if ($partes[1] == $this->view->mes) {
                                        $totalGruposAtendido++;
                                    }
                                }
                            }
                            if ($totalGruposAtendido >= 1) {
                                $totalGruposAtendidos++;
                            }

                            $totalGruposFilhos++;
                        }
                    }

                    if (count($grupos144) > 0) {
                        $progresso12 = ($totalGruposAtendidos / $totalGruposFilhos) * 100;
                        $disabledPlus = '';
                    } else {
                        $progresso12 = 0;
                        $disabledPlus = 'disabled';
                    }

                    /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
                    if ($progresso12 > 50 && $progresso12 < 80) {
                        $colorBarTotal = "progress-bar-warning";
                    } else if ($progresso12 >= 80) {
                        $colorBarTotal = "progress-bar-success";
                    } else {
                        $colorBarTotal = "progress-bar-danger";
                    }

                    /* Inicio panel */
                    $html[$grupo12->getId()] .= '<div class="panel">';
                    $html[$grupo12->getId()] .= '<div class="panel-body">';

                    $html[$grupo12->getId()] .= '<div class="text-center mb10">';
                    $html[$grupo12->getId()] .= '<b>' . $nomeEntidade12 . '</b>';
                    $html[$grupo12->getId()] .= '</div>';

                    $html[$grupo12->getId()] .= '<div class="text-center mb10">';
                    $html[$grupo12->getId()] .= $informacaoEntidade12;
                    $html[$grupo12->getId()] .= '</div>';

                    $html[$grupo12->getId()] .= '<div class="col-md-3 col-xs-3">';
                    $html[$grupo12->getId()] .= $informacaoFoto12;
                    $html[$grupo12->getId()] .= '</div>';
                    $html[$grupo12->getId()] .= '<div class="col-md-9 col-xs-9">';
                    if (count($grupos144)) {
                        $html[$grupo12->getId()] .= '<div class="col-md-11 col-sm-10 col-xs-9">';
                        $html[$grupo12->getId()] .= '<span id="totalGruposAtendidos">' . $totalGruposAtendidos . ' </span> ' .
                                $this->view->translate('of') .
                                ' <span id="totalGruposFilhos">' . $totalGruposFilhos . '</span> ' .
                                $this->view->translate(Constantes::$TRADUCAO_SUBTITULO_CABECALHO_ATENDIMENTO);
                        $html[$grupo12->getId()] .= '<div class="progress progress-bar-xl">';
                        $html[$grupo12->getId()] .= '<div id="divProgressBar" class="progress-bar ' . $colorBarTotal . '" role="progressbar" aria-valuenow="' . number_format($progresso12, 2, '.', '') . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . number_format($progresso12, 2, '.', '') . '%;">' . number_format($progresso12, 2, '.', '') . '%</div>';
                        $html[$grupo12->getId()] .= '</div>';
                        $html[$grupo12->getId()] .= '</div>';

                        $html[$grupo12->getId()] .= '<div class="col-md-1 col-sm-2 col-xs-3" style="padding-left: 0px; padding-top: 20px; vertical-align: middle;">';
                        $html[$grupo12->getId()] .= '<div id="botaoMais' . $grupo12->getId() . '"> ';
                        $html[$grupo12->getId()] .= '<button  id="bl_' . $grupo12->getId() . '" type="button" onclick="abrir144(' . $grupo12->getId() . ');" class="btn btn-md btn-primary" ' . $disabledPlus . ' style="padding-top: 0px; padding-bottom: 0px;">';
                        $html[$grupo12->getId()] .= '<i class="fa fa-eye" aria-hidden="true"></i>';
                        $auxI = 0;
                        $html[$grupo12->getId()] .= '</button>';
                        $html[$grupo12->getId()] .= '</div>';
                        $html[$grupo12->getId()] .= '<div id="botaoMenos' . $grupo12->getId() . '" class="hidden"> ';
                        $html[$grupo12->getId()] .= '<button  id="bl_' . $grupo12->getId() . '" type="button" onclick="fechar144(' . $grupo12->getId() . ');" class="btn btn-md btn-primary" ' . $disabledPlus . ' style="padding-top: 0px; padding-bottom: 0px;">';
                        $html[$grupo12->getId()] .= '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
                        $html[$grupo12->getId()] .= '</button>';
                        $html[$grupo12->getId()] .= '</div>';
                        $html[$grupo12->getId()] .= '</div>';

                        $html[$grupo12->getId()] .= '<div id="grupos144' . $grupo12->getId() . '" class="hidden">';
                        foreach ($grupos144 as $gpFilho) {
                            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                            $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                            $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
                            $nomeEntidade = $grupoFilho->getEntidadeAtiva()->infoEntidade();
                            if ($grupoResponsavel) {
                                $pessoas = array();
                                foreach ($grupoResponsavel as $gr) {
                                    $p = $gr->getPessoa();
                                    $imagem = 'placeholder.png';
                                    if (!empty($p->getFoto())) {
                                        $imagem = $p->getFoto();
                                    }
                                    $pessoas[] = $p;
                                }

                                $informacaoEntidade = '';
                                $informacaoFoto = '';
                                $contagem = 1;
                                $totalPessoas = count($pessoas);

                                foreach ($pessoas as $p) {
                                    if ($contagem == 2) {
                                        $informacaoEntidade .= '&nbsp;&nbsp;';
                                        $informacaoFoto .= '';
                                    }
                                    $imagem = 'placeholder.png';
                                    $tamanho = 40;
                                    if (!empty($p->getFoto())) {
                                        $imagem = $p->getFoto();
                                    }
                                    $informacaoFoto .= '<img src="/img/avatars/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . '%"  height="' . $tamanho . '%" />&nbsp;';
                                    if ($totalPessoas == 1) {
                                        $informacaoEntidade .= $p->getNomePrimeiroUltimo();
                                    } else {// duas pessoas
                                        $informacaoEntidade .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                                    }
                                    $contagem++;
                                }
                                $totalGruposAtendidoIndividual = 0;
                                $atendimentosDoGrupoIndividual = $grupoFilho->getGrupoAtendimento();
                                foreach ($atendimentosDoGrupoIndividual as $ga) {
                                    if ($ga->verificarSeEstaAtivo()) {
                                        $partes = explode("/", $ga->getDia());
                                        echo "";
                                        if ($partes[1] == $this->view->mes) {
                                            $totalGruposAtendidoIndividual++;
                                        }
                                    }
                                }

                                $numeroAtendimentos = $totalGruposAtendidoIndividual;
                                /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
                                if ($numeroAtendimentos == 1) {
                                    $valueNow = 50;
                                    $labelProgressBar = "$numeroAtendimentos Atendimento";
                                    $colorBar = "progress-bar-warning";
                                    $disabledMinus = '';
                                } else if ($numeroAtendimentos >= 2) {
                                    $valueNow = 100;
                                    $labelProgressBar = "$numeroAtendimentos Atendimentos";
                                    $colorBar = "progress-bar-success";
                                    $disabledMinus = '';
                                } else {
                                    $valueNow = 10;
                                    $labelProgressBar = " 0 Atd.";
                                    $colorBar = "progress-bar-danger";
                                    $disabledMinus = 'disabled';
                                }

                                $html[$grupo12->getId()] .= '<div class="row mt10">';
                                $html[$grupo12->getId()] .= '<div class="col-md-3 col-xs-4" style="padding-right: 0px;">';
                                $html[$grupo12->getId()] .= '<span class="" href="#" >';
                                $html[$grupo12->getId()] .= $informacaoFoto;
                                $html[$grupo12->getId()] .= '</span>';
                                $html[$grupo12->getId()] .= '</div>';
                                $html[$grupo12->getId()] .= '<div class="col-md-9 col-xs-8" style="padding-left: 2px; padding-right: 2px;">';
                                $html[$grupo12->getId()] .= '<div class="row">';
                                $html[$grupo12->getId()] .= '<div class="col-md-11 col-sm-11 col-xs-11" style="padding-top: 3px; padding-right: 4px;">';
                                $html[$grupo12->getId()] .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
                                $html[$grupo12->getId()] .= '<div id="progressBarAtendimento' . $grupoFilho->getId() . '" class="progress-bar ' . $colorBar . '" role="progressbar" aria-valuenow="' . $valueNow . '" aria-valuemin="0" aria-valuemax="5" style="width: ' . $valueNow . '%;">' . $labelProgressBar . '</div>';
                                $html[$grupo12->getId()] .= '</div>';
                                $html[$grupo12->getId()] .= '<span style="padding-top: 0px;">' . $informacaoEntidade . ' - ' . $nomeEntidade . ' </span>';
                                $html[$grupo12->getId()] .= '</div>';
                                $html[$grupo12->getId()] .= '</div>';
                                $html[$grupo12->getId()] .= '</div>';
                                $html[$grupo12->getId()] .= '</div>';
                            }
                        }
                        $html[$grupo12->getId()] .= '</div>';
                    } else {
                        $html[$grupo12->getId()] .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!</div>';
                    }

                    $html[$grupo12->getId()] .= '</div>';

                    $html[$grupo12->getId()] .= '</div>';
                    $html[$grupo12->getId()] .= '</div>';
                    /* Fim panel */


                    if ($totalPessoas12 == 2) {
                        $ideal = 24;
                    } else {
                        $ideal = 12;
                    }
                    if ($totalGruposFilhos >= $ideal) {
                        $arrayCompleto[$auxI]['progresso'] = $progresso12;
                        $arrayCompleto[$auxI]['html'] = $html[$grupo12->getId()];
                        $auxI++;
                    } else {
                        $arrayIncompleto[$auxN]['progresso'] = $progresso12;
                        $arrayIncompleto[$auxN]['html'] = $html[$grupo12->getId()];
                        $auxN++;
                    }
                }
            }

            if (count($arrayCompleto) != 0) {
                for ($i = 0; $i < count($arrayCompleto); $i++) {
                    for ($j = 0; $j < count($arrayCompleto); $j++) {
                        if ($arrayCompleto[$i]['progresso'] > $arrayCompleto[$j]['progresso']) {
                            $aux = $arrayCompleto[$i];
                            $arrayCompleto[$i] = $arrayCompleto[$j];
                            $arrayCompleto[$j] = $aux;
                        }
                    }
                }
            }
            if (count($arrayIncompleto) != 0) {
                for ($i = 0; $i < count($arrayIncompleto); $i++) {
                    for ($j = 0; $j < count($arrayIncompleto); $j++) {
                        if ($arrayIncompleto[$i]['progresso'] > $arrayIncompleto[$j]['progresso']) {
                            $aux = $arrayIncompleto[$i];
                            $arrayIncompleto[$i] = $arrayIncompleto[$j];
                            $arrayIncompleto[$j] = $aux;
                        }
                    }
                }
            }

            if (count($arrayCompleto) != 0) {
                $corTime = 'default';
                $elemento = '<b>TIME COMPLETO</b></div>';
                for ($i = 0; $i < count($arrayCompleto); $i++) {
                    $elemento .= $arrayCompleto[$i]['html'];
                }
            } else {
                $corTime = 'danger';
                $elemento = '<b>SEM DISC&Iacute;PULOS COM TIME COMPLETO</b></div>';
            }

            $htmlRaiz .= '<div class="panel panel-' . $corTime . ' p10">';
            $htmlRaiz .= '<div class="panel-heading text-center">';
            $htmlRaiz .= $elemento;
            $htmlRaiz .= '</div>';

            if (count($arrayIncompleto) != 0) {
                $htmlRaiz .= '<div class="panel panel-warning p10">';
                $htmlRaiz .= '<div class="panel-heading text-center mb10">';
                $htmlRaiz .= '<b>TIME INCOMPLETO</b>';
                $htmlRaiz .= '</div>';
                for ($i = 0; $i < count($arrayIncompleto); $i++) {
                    $htmlRaiz .= $arrayIncompleto[$i]['html'];
                }
                $htmlRaiz .= '</div>';
            }
        } else {
            $htmlRaiz .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!</div>';
        }


        return $htmlRaiz;
    }

}
