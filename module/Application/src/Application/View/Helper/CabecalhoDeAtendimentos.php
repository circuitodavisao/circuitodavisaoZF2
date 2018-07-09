<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\LancamentoController;
use Application\Model\Entity\Grupo;
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
            $relatorioAtendimento = Grupo::relatorioDeAtendimentosAbaixo(
                            $this->getGruposAbaixo(), $this->view->mes, $this->view->ano
            );

            /* percentagem da meta, sendo que a meta é 2 atendimentos por mes */
            $colorBarTotal = LancamentoController::retornaClassBarradeProgressoPeloValor($relatorioAtendimento[0]);

            $valorBarraFormatada = 0;
            if ($relatorioAtendimento[0] > 0) {
                $valorBarraFormatada = number_format($relatorioAtendimento[0], 2, '.', '');
            }
            $html .= '<span id="totalGruposAtendidos">' . $relatorioAtendimento[1] . ' </span> '
                    . $this->view->translate('of')
                    . ' <span id="totalGruposFilhos">' . $relatorioAtendimento[2] . '</span> '
                    . '<span class="hidden-xs"> time(s) atendido(s).</span>';
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
