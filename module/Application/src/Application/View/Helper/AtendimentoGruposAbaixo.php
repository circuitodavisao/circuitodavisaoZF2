<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\Entidade;
use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AtendimentoGruposAbaixo.php
 * @author Luca Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar o numero de discipulos atendidos e o progresso do líder 
 */
class AtendimentoGruposAbaixo extends AbstractHelper {

    protected $tipo;
    protected $mes;
    protected $ano;

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
        $this->setMes($this->view->mes);
        $this->setAno($this->view->ano);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $mensagemAlertaSemDiscipulos = '<div class="alert alert-warning">'
                . '<i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Times cadastrados!'
                . '</div>';

        if (count($this->view->gruposAbaixo) > 0) {
            /* Ordenação */
            $discipulos = array();
            foreach ($this->view->gruposAbaixo as $gpFilho) {
                $discipulos[] = $gpFilho;
            }
            if ($this->getTipo() === 2) {
                $relatorio = array();
                foreach ($discipulos as $gpFilho) {
                    $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                    $relatorioAtendimento = Grupo::relatorioDeAtendimentosAbaixo($grupoFilho->getGrupoPaiFilhoFilhosAtivos(), $this->getMes(), $this->getAno());
                    $relatorio[$grupoFilho->getId()] = $relatorioAtendimento;
                }
                for ($i = 0; $i < count($discipulos); $i++) {
                    for ($j = 0; $j < count($discipulos); $j++) {
                        $discipulo1 = $discipulos[$i];
                        $discipulo2 = $discipulos[$j];
                        $relatorio1 = $relatorio[$discipulo1->getGrupoPaiFilhoFilho()->getId()];
                        $relatorio2 = $relatorio[$discipulo2->getGrupoPaiFilhoFilho()->getId()];
                        if ($relatorio1[0] > $relatorio2[0]) {
                            $discipulos[$i] = $discipulo2;
                            $discipulos[$j] = $discipulo1;
                        }
                    }
                }
            }

            foreach ($discipulos as $gpFilho) {

                $html .= '<hr/>';
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();              

                $html .= $this->montarLinhaDeAtendimento($grupoFilho);

                if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio) {
                    $todosFilhos = $grupoFilho->getGrupoPaiFilhoFilhosPorMesEAno($this->getMes(), $this->getAno());
                    if (count($todosFilhos)) {
                        $html .= '<div id="grupos144' . $grupoFilho->getId() . '" class="hidden bg-default">';
                        foreach ($todosFilhos as $gpFilho144) {
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
        } else {
            $html .= $mensagemAlertaSemDiscipulos;
        }
        return $html;
    }

    public function montarBarraDeProgressoAtendimento($grupo, $discipuloAbaixo = false) {
        $html = '';

        $tamanhoColuna1 = 'col-md-10 col-sm-9 col-xs-6';
        $tamanhoColuna2 = 'col-md-2 col-sm-3 col-xs-6';
        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio && !$discipuloAbaixo) {
            $tamanhoColuna1 = 'col-md-11 col-sm-10 col-xs-10';
            $tamanhoColuna2 = 'col-md-1 col-sm-2 col-xs-2" style="padding-left: 0px; padding-top: 20px; vertical-align: middle;';
        }
        if ($discipuloAbaixo) {
            $tamanhoColuna1 = 'col-md-10 col-sm-10 col-xs-10';
            $tamanhoColuna2 = '';
        }

        /* Row 1 */
        $html .= '<div class="row">';
        /* Coluna 1 - Barra */
        $html .= '<div class="' . $tamanhoColuna1 . '">';

        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoLancamento || $discipuloAbaixo) {
            $html .= '<div class="progress progress-bar-xl" style="margin-bottom: 0px;">';
            $html .= $this->montarBarraDeProgresso($grupo);
            $html .= '</div>';
        }
        if ($this->getTipo() === AtendimentoGruposAbaixo::tipoRelatorio && !$discipuloAbaixo) {
            $html .= $this->view->cabecalhoDeAtendimentos($grupo->getGrupoPaiFilhoFilhosPorMesEAno($this->getMes(), $this->getAno()));
        }

        $html .= $grupo->getNomeLideresAtivos();
        $entidadeTipoId = $grupo->getEntidadeAtiva()->getEntidadeTipo()->getId();
        if($entidadeTipoId === Entidade::REGIONAL){
            $html .= ' - REGIÃO: ' . $grupo->getEntidadeAtiva()->getNome();
        }
        if($entidadeTipoId === Entidade::COORDENACAO){
            $html .= ' - COORDENAÇÃO: ' . $grupo->getEntidadeAtiva()->getNumero();
        }
        if($entidadeTipoId === Entidade::IGREJA){
            $html .= ' - IGREJA: ' . $grupo->getEntidadeAtiva()->getNome();
        }

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
                $html .= Constantes::$NBSP;

                $idAuxiliar = $grupo->getId() . '_' . $this->view->mes . '_' . $this->view->ano;
                $funcao = $this->view->funcaoOnClick('mostrarSplash(); funcaoCircuito("lancamentoAtendimentoComentario","' . $idAuxiliar . '")');
                $html .= $this->view->botaoSimples('<i class="fa fa-comments"></i>', $funcao, BotaoSimples::botaoMuitoPequenoMenosImportante);
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
        $html .= '</div>';
        /* Fim Row 1 */

        $grupoAtendimentoComentarioAtivos = $grupo->getGrupoAtendimentoComentarioAtivos($this->getMes(), $this->getAno());
        if (count($grupoAtendimentoComentarioAtivos) > 0) {
            /* Row 2 */
            $html .= '<div class="row">';
            $html .= '<div class="panel">';
            $html .= '<div class="panel-body text-center" style="padding:1px;">';
            $html .= '<table class="table table-condensed">';
            $html .= '<thead><tr class="info"><th class="text-center">Coment&aacute;rios ' . count($grupoAtendimentoComentarioAtivos) . '</th>'
                    . '<th><span class="btn btn-primary btn-xs"><i class="fa fa-eye" onClick="$(\'.comentario_' . $grupo->getId() . '\').toggleClass(\'hidden\');"></i></button></th></tr></thead>';
            $html .= '<tbody>';
            foreach ($grupoAtendimentoComentarioAtivos as $grupoAtendimentoComentario) {
                $html .= '<tr class="hidden comentario_' . $grupo->getId() . '">';
                $html .= '<td>';
                $html .= '<span class="hidden-xs">' . $grupoAtendimentoComentario->getComentario() . '</span>';
                $html .= '<span class="hidden-sm hidden-md hidden-lg">';
                if (strlen($grupoAtendimentoComentario->getComentario()) > 20) {
                    $html .= substr($grupoAtendimentoComentario->getComentario(), 0, 20) . '...';
                } else {
                    $html .= $grupoAtendimentoComentario->getComentario();
                }
                $html .= '</span>';
                $html .= '</td>';
                if ($this->getTipo() === AtendimentoGruposAbaixo::tipoLancamento) {
                    $funcao = $this->view->funcaoOnClick('validarExclusaoComentario(' . $grupoAtendimentoComentario->getId() . ')');
                    $html .= '<td>';
                    $html .= $this->view->botaoSimples('<i class="fa fa-times"</i>', $funcao, BotaoSimples::botaoMuitoPequenoPerigoso);
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            /* Fim Row 2 */
        }


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
            $tipoDoBotao = BotaoSimples::botaoMuitoPequenoImportante;
            $disabled = '';
        }
        if ($tipoBotao === AtendimentoGruposAbaixo::tipoRemover) {
            $iconeDoBotao = 'minus';
            $tipoDoBotao = BotaoSimples::botaoMuitoPequenoMenosImportante;
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
            $funcaoOnClick = $this->view->funcaoOnClick('mudarAtendimento(' . $idGrupo . ', ' . $tipoBotao . ', ' . $this->view->mes . ', ' . $this->view->ano . ')');
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
			foreach ($grupo->getGrupoResponsavel() as $grupoResponsavel) {
if($grupoResponsavel->verificarSeEstaAtivo()){
				if ($quantidadeDeLideres === 2) {
					$html .= Constantes::$NBSP;
				}
				$pessoa = $grupoResponsavel->getPessoa();
				$html .= FuncoesEntidade::tagImgComFotoDaPessoa($pessoa, AtendimentoGruposAbaixo::tamanhoDaFoto, '%');
				$quantidadeDeLideres++;
}
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

    function getMes() {
        return $this->mes;
    }

    function getAno() {
        return $this->ano;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

}
