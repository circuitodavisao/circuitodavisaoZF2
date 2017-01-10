<?php

namespace Lancamento\View\Helper;

use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\FuncoesLancamento;
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
                    $tamanho = 45;
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
                } else if ($numeroAtendimentos >= 2) {
                    $valueNow = 100;
                    $labelProgressBar = "$numeroAtendimentos Atendimentos";
                    $colorBar = "progress-bar-success";
                } else {
                    $valueNow = 10;
                    $labelProgressBar = " 0 Atd.";
                    $colorBar = "progress-bar-danger";
                }
                
                $html .= '<div class="row mt10">';
                $html .=    '<div class="col-md-3 col-xs-5" style="padding-right: 0px;">';
                $html .=       '<span class="" href="#" >';
                $html .=        $informacaoFoto;
                $html .=        '</span>';
                $html .=    '</div>';    
                $html .=   '<div class="col-md-9 col-xs-7" style="padding-left: 2px; padding-right: 2px;">';
                $html .=        '<div class="row">';
                $html .=           '<div class="col-md-11 col-xs-9" style="padding-top: 3px; padding-right: 4px;">';
                $html .=               '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
                $html .=                   '<div id="divProgressBar" class="progress-bar '.$colorBar.'" role="progressbar" aria-valuenow="'.$valueNow.'" aria-valuemin="0" aria-valuemax="5" style="width: '.$valueNow.'%;">'.$labelProgressBar.'</div>';
                $html .=               '</div>';
                $html .=               '<span style="padding-top: 0px;"><?php echo $informacaoEntidade; ?></span>';
                $html .=           '</div>';

                $html .=            '<div class="col-md-1 col-xs-3" style="padding-left: 0px; padding-top: 3px; vertical-align: middle;">';
                $html .=                '<button type="button" onclick="funcaoLancamento(\'LancarAtendimento\',' . $grupoFilho->getId() .');" class="btn btn-xs btn-primary" style="padding-top: 0px; padding-bottom: 0px;">';
                $html .=                    '<i class="fa fa-plus" aria-hidden="true"></i>';
                $html .=               '</button>';
                $html .=            '</div>';                           
                $html .=        '</div>';  
                $html .=    '</div>';
                $html .= '</div>'; 

            }
        }
        return $html;
    }

}
