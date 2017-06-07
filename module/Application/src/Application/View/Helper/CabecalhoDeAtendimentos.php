<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\LancamentoController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeAtendimentos.php
 * @author Luca Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar o numero de discipulos atendidos e o progresso do líder 
 */
class CabecalhoDeAtendimentos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $totalGruposFilhos = 0;
        $totalGruposAtendidos = 0;
        if ($this->view->gruposAbaixo) {
            foreach ($this->view->gruposAbaixo as $gpFilho) {
                $totalGruposAtendido = 0;
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
                if ($grupoResponsavel) {
                    $atendimentosDoGrupo = $grupoFilho->getGrupoAtendimento();
                    foreach ($atendimentosDoGrupo as $ga) {
                        if ($ga->verificarSeEstaAtivo()) {
                            if ($ga->getData_criacaoMes() == $this->view->mes) {
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

            if ($totalGruposFilhos) {
                $progresso = ($totalGruposAtendidos / $totalGruposFilhos) * 100;
            } else {
                $progresso = 0;
            }

            /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
            $colorBarTotal = LancamentoController::retornaClassBarradeProgressoPeloValor($progresso);

            $valorBarraFormatada = 0;
            if ($progresso > 0) {
                $valorBarraFormatada = number_format($progresso, 2, '.', '');
            }
            $html .= '<div class="row center-block text-center">';
            $html .= '<div class="section-divider mt30">';
            $html .= '<span>'
                    . '<span id="totalGruposAtendidos">' . $totalGruposAtendidos . ' </span> '
                    . $this->view->translate('of')
                    . ' <span id="totalGruposFilhos">' . $totalGruposFilhos . '</span> '
                    . $this->view->translate(Constantes::$TRADUCAO_SUBTITULO_CABECALHO_ATENDIMENTO)
                    . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12 col-xs-12">';
            $html .= '<div class="progress progress-bar-xl">';
            $html .= '<div '
                    . 'id="divProgressBar" '
                    . 'class="progress-bar ' . $colorBarTotal . '" '
                    . 'role="progressbar" '
                    . 'aria-valuenow="' . $valorBarraFormatada . '" '
                    . 'aria-valuemin="0" '
                    . 'aria-valuemax="100" '
                    . 'style="width: ' . $valorBarraFormatada . '%;">'
                    . $valorBarraFormatada . '%'
                    . '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

}
