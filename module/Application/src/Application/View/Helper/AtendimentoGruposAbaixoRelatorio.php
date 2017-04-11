<?php

namespace Application\View\Helper;

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
        $html = '';
        if (!empty($this->view->gruposAbaixo)) {
            foreach ($this->view->gruposAbaixo as $gp12) {
                $grupo12 = $gp12->getGrupoPaiFilhoFilho();
                $grupos144 = $grupo12->getGrupoPaiFilhoFilhos();

                $entidadeFilho12 = $grupo12->getEntidadeAtiva();
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
                            $informacaoEntidade12 .= '&nbsp;&nbsp;';
                            $informacaoFoto12 .= '';
                        }
                        $imagem = '1.jpg';
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
                    $html .= '<div class="row">';
                    $html .= '<div class="panel-default">';

                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class= "panel">';
                    $html .= '<div class="panel-body center-block">';
                    $html .= '</br>';
                    $html .= '<div class="row mt10 text-center ">';
                    $html .= '<div class="col-md-3 col-xs-4" style="padding-right: 0px;">';
                    $html .= '<span class="" href="#" >';
                    $html .= $informacaoFoto12;
                    $html .= '</span>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-9 col-xs-8" style="padding-left: 2px; padding-right: 2px;">';
                    $html .= '<div class="row">';
                    $html .= '<div class="col-md-10 col-sm-10 col-xs-7" style="padding-top: 3px; padding-right: 4px;">';

                    $html .= '<span style="padding-top: 0px;">' . $informacaoEntidade12 . ' - ' . $nomeEntidade12 . ' </span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</br>';
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
                    }

                    /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
                    if ($progresso12 > 50 && $progresso12 < 80) {
                        $colorBarTotal12 = "alert-warning";
                    } else if ($progresso12 >= 80) {
                        $colorBarTotal12 = "alert-success";
                    } else {
                        $colorBarTotal12 = "alert-danger";
                    }
                    if (count($grupos144) > 0) {
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

                                $html .= '<div class="row mt10">';
                                $html .= '<div class="col-md-3 col-xs-4" style="padding-right: 0px;">';
                                $html .= '<span class="" href="#" >';
                                $html .= $informacaoFoto;
                                $html .= '</span>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-9 col-xs-8" style="padding-left: 2px; padding-right: 2px;">';
                                $html .= '<div class="row">';
                                $html .= '<div class="col-md-11 col-sm-11 col-xs-11" style="padding-top: 3px; padding-right: 4px;">';
                                $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
                                $html .= '<div id="progressBarAtendimento' . $grupoFilho->getId() . '" class="progress-bar ' . $colorBar . '" role="progressbar" aria-valuenow="' . $valueNow . '" aria-valuemin="0" aria-valuemax="5" style="width: ' . $valueNow . '%;">' . $labelProgressBar . '</div>';
                                $html .= '</div>';
                                $html .= '<span style="padding-top: 0px;">' . $informacaoEntidade . ' - ' . $nomeEntidade . ' </span>';
                                $html .= '</div>';

//                    $html .= '<div class="col-md-2 col-sm-2 col-xs-5" style="padding-left: 0px; padding-top: 3px; vertical-align: middle;">';
//                    $html .= '<button id="br_' . $grupoFilho->getId() . '" type="button" onclick="mudarAtendimento(' . $grupoFilho->getId() . ', 2);" class="btn btn-md btn-primary ' . $disabledMinus . '" style="padding-top: 0px; padding-bottom: 0px;">';
//                    $html .= '<i class="fa fa-minus" aria-hidden="true"></i>';
//                    $html .= '</button>';
//                    $html .= '&nbsp';
//                    $html .= '<button  id="bl_' . $grupoFilho->getId() . '" type="button" onclick="mudarAtendimento(' . $grupoFilho->getId() . ', 1);" class="btn btn-md btn-primary" style="padding-top: 0px; padding-bottom: 0px;">';
//                    $html .= '<i class="fa fa-plus" aria-hidden="true"></i>';
//                    $html .= '</button>';
//                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                        }
                        $html .= '</div>';
                        $html .= '<div class="alert '. $colorBarTotal12.'"><i class="fa fa-warning pr10" aria-hidden="true"></i>' . $totalGruposAtendidos . ' de ' . $totalGruposFilhos . ' foram Atendidos!!!</div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                }
            }
        } else {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!</div>';
        }
        return $html;
    }

}
