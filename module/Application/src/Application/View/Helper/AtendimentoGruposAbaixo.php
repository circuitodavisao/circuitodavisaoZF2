<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AtendimentoGruposAbaixo.php
 * @author Luca Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar o numero de discipulos atendidos e o progresso do líder 
 */
class AtendimentoGruposAbaixo extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        if (!empty($this->view->gruposAbaixo)) {
            foreach ($this->view->gruposAbaixo as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $entidadeFilho = $grupoFilho->getEntidadeAtiva();
                $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
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
                            if ($ga->getData_criacaoMes() == $this->view->mes) {
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
                    $html .= '<div class="col-md-10 col-sm-10 col-xs-7" style="padding-top: 3px; padding-right: 4px;">';
                    $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
                    $html .= '<div id="progressBarAtendimento' . $grupoFilho->getId() . '" class="progress-bar ' . $colorBar . '" role="progressbar" aria-valuenow="' . $valueNow . '" aria-valuemin="0" aria-valuemax="5" style="width: ' . $valueNow . '%;">' . $labelProgressBar . '</div>';
                    $html .= '</div>';
                    $html .= '<span style="padding-top: 0px;">' . $informacaoEntidade . '</span>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 col-sm-2 col-xs-5" style="padding-left: 0px; padding-top: 3px; vertical-align: middle;">';
                    $html .= '<button id="br_' . $grupoFilho->getId() . '" type="button" onclick="mudarAtendimento(' . $grupoFilho->getId() . ', 2);" class="btn btn-md btn-primary ' . $disabledMinus . '" style="padding-top: 0px; padding-bottom: 0px;">';
                    $html .= '<i class="fa fa-minus" aria-hidden="true"></i>';
                    $html .= '</button>';
                    $html .= '&nbsp';
                    $html .= '<button  id="bl_' . $grupoFilho->getId() . '" type="button" onclick="mudarAtendimento(' . $grupoFilho->getId() . ', 1);" class="btn btn-md btn-primary" style="padding-top: 0px; padding-bottom: 0px;">';
                    $html .= '<i class="fa fa-plus" aria-hidden="true"></i>';
                    $html .= '</button>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }
        } else {
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!</div>';
        }
        return $html;
    }

}
