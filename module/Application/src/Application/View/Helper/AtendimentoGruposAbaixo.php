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

    protected $tipo;

    const tipoLancamento = 1;
    const tipoRelatorio = 2;
    const tamanhoDaFoto = 45;
    const tipoLancar = 1;
    const tipoRemover = 2;
    const tipoRelatorioVer = 3;
    const tipoRelatorioEsconder = 4;

    public function __construct() {
        
    }

    public function __invoke($tipo = 1) {
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $mensagemAlertaSemDiscipulos = '<div class="alert alert-warning">'
                . '<i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Discipulos cadastrados!'
                . '</div>';

        if (count($this->view->gruposAbaixo) > 0) {
            foreach ($this->view->gruposAbaixo as $gpFilho) {

                $html .= '<hr/>';
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                if ($grupoFilho->getResponsabilidadesAtivas()) {

                    $html .= $this->montarLinhaDeAtendimento($grupoFilho);

                    if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio) {

                        if (count($grupoFilho->getGrupoPaiFilhoFilhos())) {
                            $html .= '<div id="grupos144' . $grupoFilho->getId() . '" class="hidden bg-default">';
                            foreach ($grupoFilho->getGrupoPaiFilhoFilhos() as $gpFilho144) {
                                $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                                if ($grupoFilho144->getResponsabilidadesAtivas()) {
                                    $ehDiscipuloAbaixo = true;
                                    $html .= $this->montarLinhaDeAtendimento($grupoFilho144, $ehDiscipuloAbaixo);
                                }
                            }
                            $html .= '</div>';
                        } else {
                            $html .= $mensagemAlertaSemDiscipulos;
                        }
                    }
                }
            }
        } else {
            $html .= $mensagemAlertaSemDiscipulos;
        }
        return $html;
    }

    public function montarBarraDeProgressoAtendimento($grupo, $discipuloAbaixo = false) {
        $html = '';

        $tamanhoColuna1 = 'col-md-10 col-sm-10 col-xs-7';
        $tamanhoColuna2 = 'col-md-2 col-sm-2 col-xs-5';
        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio && !$discipuloAbaixo) {
            $tamanhoColuna1 = 'col-md-10 col-sm-10 col-xs-10';
            $tamanhoColuna2 = 'col-md-2 col-sm-2 col-xs-2" style="padding-left: 0px; padding-top: 20px; vertical-align: middle;';
        }
        if ($discipuloAbaixo) {
            $tamanhoColuna1 = 'col-md-10 col-sm-10 col-xs-10';
            $tamanhoColuna2 = '';
        }

        /* Coluna 1 - Barra */
        $html .= '<div class="' . $tamanhoColuna1 . '">';

        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoLancamento || $discipuloAbaixo) {
            $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
            $html .= $this->montarBarraDeProgresso($grupo);
            $html .= '</div>';
        }
        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio && !$discipuloAbaixo) {
            $html .= $this->view->cabecalhoDeAtendimentos($grupo->getGrupoPaiFilhoFilhos());
        }

        $html .= $grupo->getNomeLideresAtivos();

        $html .= '</div>';
        /* Fim Coluna 1 */

        /* Coluna 2 - Botões */
        if (!$discipuloAbaixo) {
            $numeroAtendimentos = $this->numeroDeAtendimentos($grupo);

            $html .= '<div class="' . $tamanhoColuna2 . '">';
            if ($this->getTipo() === AtendimentoGruposAbaixo::tipoLancamento) {
                $html .= $this->botaoAtendimento($grupo->getId(), 1);
                $html .= Constantes::$NBSP;
                $html .= $this->botaoAtendimento($grupo->getId(), 2, $numeroAtendimentos);
            }
            if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio) {
                $html .= '<div id="divBotaoVer' . $grupo->getId() . '">';
                $html .= $this->botaoAtendimento($grupo->getId(), 3);
                $html .= '</div>';
                $html .= '<div id="divBotaoEsconder' . $grupo->getId() . '" class="hidden">';
                $html .= $this->botaoAtendimento($grupo->getId(), 4);
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        /* Fim Coluna 2 */

        return $html;
    }

    public function montarBarraDeProgresso($grupo) {
        $html = '';
        $numeroAtendimentos = $this->numeroDeAtendimentos($grupo);

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

        $idDiv = 'progressBarAtendimento' . $grupo->getId();
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

    public function numeroDeAtendimentos($grupo) {
        $numeroAtendimentos = 0;
        if ($grupo->getGrupoAtendimento()) {
            foreach ($grupo->getGrupoAtendimento() as $grupoAtendimento) {
                if ($grupoAtendimento->verificaSeTemNesseMesEAno($this->view->mes, $this->view->ano)) {
                    $numeroAtendimentos++;
                }
            }
        }
        return $numeroAtendimentos;
    }

    public function botaoAtendimento($idGrupo, $tipoBotao = 1, $numeroAtendimentos = 0) {
        $html = '';
        $disabled = '';
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoLancar) {
            $iconeDoBotao = 'plus';
            $tipoDoBotao = BotaoSimples::botaoPequenoImportante;
            $disabled = '';
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRemover) {
            $iconeDoBotao = 'minus';
            $tipoDoBotao = BotaoSimples::botaoPequenoMenosImportante;
            if ($numeroAtendimentos === 0) {
                $disabled = 'disabled';
            }
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRelatorioVer) {
            $iconeDoBotao = 'eye';
            $tipoDoBotao = BotaoSimples::botaoMuitoPequenoImportante;
            $disabled = '';
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRelatorioEsconder) {
            $iconeDoBotao = 'eye-slash';
            $tipoDoBotao = BotaoSimples::botaoMuitoPequenoMenosImportante;
            $disabled = '';
        }
        $stringIcone = '<i class="fa fa-' . $iconeDoBotao . '" aria-hidden="true"></i>';

        if ($tipoBotao === AtendimentoGruposAbaixo::tipoLancar || $tipoBotao === AtendimentoGruposAbaixo::tipoRemover) {
            $idButton = 'id="botao' . $tipoBotao . '_' . $idGrupo . '"';
            $funcaoOnClick = $this->view->funcaoOnClick('mudarAtendimento(' . $idGrupo . ', ' . $tipoBotao . ', ' . $this->view->abaSelecionada . ')');
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRelatorioVer) {
            $idButton = 'id="botaoVer' . $idGrupo . '"';
            $funcaoOnClick = $this->view->funcaoOnClick('abrir144(' . $idGrupo . ')');
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRelatorioEsconder) {
            $idButton = 'id="botaoEsconder' . $idGrupo . '"';
            $funcaoOnClick = $this->view->funcaoOnClick('fechar144(' . $idGrupo . ')');
        }

        $extra = $idButton . ' ' . $funcaoOnClick . ' ' . $disabled;

        $html .= $this->view->botaoSimples($stringIcone, $extra, $tipoDoBotao);
        return $html;
    }

    public function montarLinhaDeAtendimento($grupo, $discipuloAbaixo = false) {
        $html = '';

        $html .= '<div class="row mt10">';

        $html .= '<div class="col-md-3 hidden-xs">';
        if (!$discipuloAbaixo) {
            $quantidadeDeLideres = 1;
            foreach ($grupo->getResponsabilidadesAtivas() as $grupoResponsavel) {
                if ($quantidadeDeLideres === 2) {
                    $html .= Constantes::$NBSP;
                }
                $pessoa = $grupoResponsavel->getPessoa();
                $html .= FuncoesEntidade::tagImgComFotoDaPessoa($pessoa, AtendimentoGruposAbaixo::tamanhoDaFoto, '%');
                $quantidadeDeLideres++;
            }
        }
        $html .= '</div>';

        $html .= '<div class="col-md-9 col-xs-12">';
        $html .= $this->montarBarraDeProgressoAtendimento($grupo, $discipuloAbaixo);
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
