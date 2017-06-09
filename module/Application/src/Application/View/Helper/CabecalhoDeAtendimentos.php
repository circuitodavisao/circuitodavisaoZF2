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

    protected $gruposAbaixo;

    public function __construct() {
        
    }

    public function __invoke($gruposAbaixo) {
        $this->setGruposAbaixo($gruposAbaixo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $totalGruposFilhosAtivos = 0;
        $totalGruposAtendidos = 0;

        if ($this->getGruposAbaixo()) {
            foreach ($this->getGruposAbaixo() as $gpFilho) {
                $totalGruposAtendido = 0;
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                if ($grupoFilho->getResponsabilidadesAtivas()) {
                    foreach ($grupoFilho->getGrupoAtendimento() as $grupoAtendimento) {
                        if ($grupoAtendimento->verificaSeTemNesseMesEAno(
                                        $this->view->mes, $this->view->ano)) {
                            $totalGruposAtendido++;
                        }
                    }
                    if ($totalGruposAtendido >= 1) {
                        $totalGruposAtendidos++;
                    }

                    $totalGruposFilhosAtivos++;
                }
            }

            if ($totalGruposFilhosAtivos) {
                $progresso = ($totalGruposAtendidos / $totalGruposFilhosAtivos) * 100;
            } else {
                $progresso = 0;
            }

            /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
            $colorBarTotal = LancamentoController::retornaClassBarradeProgressoPeloValor($progresso);

            $valorBarraFormatada = 0;
            if ($progresso > 0) {
                $valorBarraFormatada = number_format($progresso, 2, '.', '');
            }
            $html .= '<span id="totalGruposAtendidos">' . $totalGruposAtendidos . ' </span> '
                    . $this->view->translate('of')
                    . ' <span id="totalGruposFilhos">' . $totalGruposFilhosAtivos . '</span> '
                    . '<span class="hidden-xs">' . $this->view->translate(Constantes::$TRADUCAO_SUBTITULO_CABECALHO_ATENDIMENTO) . '</span>';
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
        }
        return $html;
    }

    function getGruposAbaixo() {
        return $this->gruposAbaixo;
    }

    function setGruposAbaixo($gruposAbaixo) {
        $this->gruposAbaixo = $gruposAbaixo;
    }

}
