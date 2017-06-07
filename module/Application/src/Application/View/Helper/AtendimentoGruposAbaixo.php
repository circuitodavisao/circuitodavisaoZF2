<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Helper\FuncoesEntidade;
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
                $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
                if ($grupoResponsavel) {
                    $pessoas = array();
                    foreach ($grupoResponsavel as $gr) {
                        $p = $gr->getPessoa();
                        $pessoas[] = $p;
                    }
                    $informacaoEntidade = '';
                    $informacaoFoto = '';
                    $contagem = 1;
                    $tamanho = 45;
                    $totalPessoas = count($pessoas);

                    /* Coloca a foto da(s) pessoa(s) */
                    foreach ($pessoas as $p) {
                        if ($contagem == 2) {
                            $informacaoEntidade .= '&nbsp;&nbsp;';
                            $informacaoFoto .= '';
                        }
                        $informacaoFoto .= FuncoesEntidade::tagImgComFotoDaPessoa($p, $tamanho, '%');

                        if ($totalPessoas == 1) {
                            $informacaoEntidade .= $p->getNomePrimeiroUltimo();
                        } else {// duas pessoas
                            $informacaoEntidade .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                        }
                        $contagem++;
                    }

                    $numeroAtendimentos = 0;

                    if ($grupoFilho->getGrupoAtendimento()) {
                        foreach ($grupoFilho->getGrupoAtendimento() as $ga) {
                            $temAtendimentoNoMesEAno = $ga->verificaSeTemNesseMesEAno($this->view->mes, $this->view->ano);
                            if ($temAtendimentoNoMesEAno) {
                                $numeroAtendimentos++;
                            }
                        }
                    }

                    /* Linha de atendimento */
                    $html .= '<div class="row mt10">';

                    $html .= '<div class="col-md-3 col-xs-3">';
                    $html .= $informacaoFoto;
                    $html .= '</div>';

                    $html .= '<div class="col-md-9 col-xs-9">';
                    $html .= $this->montarBarraDeProgressoAtendimento($grupoFilho->getId(), $numeroAtendimentos, $informacaoEntidade, $this->view->abaSelecionada);
                    $html .= '</div>';

                    $html .= '</div>';
                    /* Fim Linha de atendimento */
                }
            }
        } else {
            $html .= '<div class="alert alert-warning">'
                    . '<i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados para lan&ccedil;ar atendimento!'
                    . '</div>';
        }
        return $html;
    }

    public function montarBarraDeProgressoAtendimento($idGrupo, $numeroAtendimentos, $informacaoEntidade, $abaSelecionada) {
        $html = '';

        /* Coluna 1 - Barra */
        $html .= '<div class="col-md-10 col-sm-10 col-xs-7">';

        $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
        $html .= $this->montarBarraDeProgresso($idGrupo, $numeroAtendimentos);
        $html .= '</div>';
        $html .= '<span style="padding-top: 0px;">' . $informacaoEntidade . '</span>';

        $html .= '</div>';
        /* Fim Coluna 1 */

        /* Coluna 2 - Botões */
        $html .= '<div class="col-md-2 col-sm-2 col-xs-5">';

        $html .= $this->botaoAtendimento($idGrupo, 1, $abaSelecionada);
        $html .= Constantes::$NBSP;
        $html .= $this->botaoAtendimento($idGrupo, 2, $abaSelecionada, $numeroAtendimentos);

        $html .= '</div>';
        /* Fim Coluna 2 */

        return $html;
    }

    public function montarBarraDeProgresso($idGrupo, $numeroAtendimentos) {
        $html = '';

        /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
        $valor = 10;
        $colorBar = "progress-bar-danger";
        $disabledMinus = 'disabled';
        if ($numeroAtendimentos === 1) {
            $valor = 50;
            $colorBar = "progress-bar-warning";
            $disabledMinus = '';
        }
        if ($numeroAtendimentos >= 2) {
            $valor = 100;
            $colorBar = "progress-bar-success";
            $disabledMinus = '';
        }

        $idDiv = 'progressBarAtendimento' . $idGrupo;
        $html .= '<div id="' . $idDiv . '" '
                . 'class="progress-bar ' . $colorBar . '" '
                . 'role="progressbar" '
                . 'aria-valuenow="' . $valor . '" '
                . 'aria-valuemin="0" '
                . 'aria-valuemax="5" '
                . 'style="width: ' . $valor . '%;">' .
                $numeroAtendimentos;
        $html .= '</div>';
        return $html;
    }

    public function botaoAtendimento($idGrupo, $tipoBotao = 1, $abaSelecionada, $numeroAtendimentos = 0) {
        $html = '';
        $tipoRemover = 2;

        $iconeDoBotao = 'plus';
        $tipoDoBotao = BotaoSimples::botaoPequenoImportante;
        $disabled = '';
        if ($tipoBotao === $tipoRemover) {
            $iconeDoBotao = 'minus';
            $tipoDoBotao = BotaoSimples::botaoPequenoMenosImportante;
            if ($numeroAtendimentos === 0) {
                $disabled = 'disabled';
            }
        }
        $stringIcone = '<i class="fa fa-' . $iconeDoBotao . '" aria-hidden="true"></i>';

        $idButton = 'id="botao' . $tipoBotao . '_' . $idGrupo . '"';
        $funcaoOnClick = $this->view->funcaoOnClick('mudarAtendimento(' . $idGrupo . ', ' . $tipoBotao . ', ' . $abaSelecionada . ')');
        $extra = $idButton . ' ' . $funcaoOnClick . ' ' . $disabled;

        $html .= $this->view->botaoSimples($stringIcone, $extra, $tipoDoBotao);
        return $html;
    }

}
