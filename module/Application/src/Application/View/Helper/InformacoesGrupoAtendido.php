<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InformacoesGrupoAtendido.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar as informacoes de atendimento do grupo selecionado.
 */
class InformacoesGrupoAtendido extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $grupoResponsavel = $this->view->grupo->getResponsabilidadesAtivas();
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
            $infoFoto = '';
            $contagem = 1;
            $totalPessoas = count($pessoas);

            foreach ($pessoas as $p) {
                if ($contagem == 2) {
                    $informacaoEntidade .= '&nbsp;&&nbsp;';
                    $htmlFoto .= '';
                }
                $imagem = 'placeholder.png';
                $tamanho = 45;
                if (!empty($p->getFoto())) {
                    $imagem = $p->getFoto();
                }
                $infoFoto .= '<img src="/img/avatars/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . '%"  height="' . $tamanho . '%" />&nbsp;';
                if ($totalPessoas == 1) {
                    $informacaoEntidade .= $p->getNomePrimeiroUltimo();
                } else {// duas pessoas
                    $informacaoEntidade .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                }
                $contagem++;
            }
        }

        $qtdAtendimento = $this->view->numeroAtendimentos;
        if ($qtdAtendimento == 1) {
            $valueNow = 50;
            $labelProgressBar = "$qtdAtendimento Atendimento";
            $colorBar = "progress-bar-warning";
        } else if ($qtdAtendimento >= 2) {
            $valueNow = 100;
            $labelProgressBar = "$qtdAtendimento Atendimentos";
            $colorBar = "progress-bar-success";
        } else {
            $valueNow = 10;
            $labelProgressBar = " 0 Atd.";
            $colorBar = "progress-bar-danger";
        }


        $html .= '<div class="row mt10">';
        $html .= '<div class="col-md-3 col-xs-5" style="padding-left: 0px; padding-right: 0px;" >';
        $html .= '<span class="" href="#" >';
        $html .= $infoFoto;
        $html .= '</span>';
        $html .= '</div>';
        $html .= '<div class="col-md-9 col-xs-7" style="padding-left: 2px; padding-right: 2px;">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12 col-xs-12" style="padding-top: 3px;">';

        $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
        $html .= '<div id="divProgressBar" class="progress-bar ' . $colorBar . '" role="progressbar" aria-valuenow="' . $valueNow . '" aria-valuemin="0" aria-valuemax="5" style="width: ' . $valueNow . '%;">' . $labelProgressBar . '</div>';
        $html .= '</div>';
        $html .= '<span style="padding-top: 0px;">' . $informacaoEntidade . '</span>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
