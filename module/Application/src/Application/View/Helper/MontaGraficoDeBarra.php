<?php

namespace Application\View\Helper;

use Application\Controller\RelatorioController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: MontaGraficoDeBarra.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar MontaGraficoDeBarra
 */
class MontaGraficoDeBarra extends AbstractHelper {

    private $relatorio;
    private $tipoRelatorio;

    const TIPO_RELATORIO_MEMBRESIA = 1;
    const TIPO_RELATORIO_CELULA = 2;

    public function __construct() {
        
    }

    public function __invoke($relatorio, $tipoRelatorio) {
        $this->setRelatorio($relatorio);
        $this->setTipoRelatorio($tipoRelatorio);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_MEMBRESIA) {
            $nomeGrafico = 'Membresia';
        }
        if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_CELULA) {
            $nomeGrafico = 'Celula';
        }

        $html .= '<input type="hidden" id="totalFatoRanking' . $nomeGrafico . '" value = "' . count($this->getRelatorio()) . '" />';
        foreach ($this->getRelatorio() as $key => $fatoRanking) {
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_MEMBRESIA) {
                $valor = $fatoRanking->getMembresia();
            }
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_CELULA) {
                $valor = $fatoRanking->getCelula();
            }

            if ($valor == 0) {
                $valor = 1;
            }
            echo '<input type="hidden" id="valorRanking' . $nomeGrafico . $key . '" value="' . $valor . '" />';
        }

        $totalRanking = count($this->getRelatorio());
        $colunaDeSelecao = 2;
        if ($totalRanking === 3) {
            $colunaDeSelecao = 1;
        }
        if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_MEMBRESIA) {
            $meuRanking = $this->view->grupo->getFatoRanking()->getRanking_membresia();
        }
        if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_CELULA) {
            $meuRanking = $this->view->grupo->getFatoRanking()->getRanking_celula();
        }

        $html .= '<input type="hidden" id="meuRanking' . $nomeGrafico . '" value="' . $meuRanking . '" />';
        
        $html .= '<table class="table">';
        $html .= '<tbody>';
        if ($meuRanking == 1 || $meuRanking == 2) {
            if ($meuRanking == 1) {
                $colunaDeSelecao = 4;
            }
            if ($meuRanking == 2) {
                $colunaDeSelecao = 3;
            }
        }
        $corSelecao = 'dark';
        $html .= '<tr>';
        for ($indiceRanking = 0; $indiceRanking < $totalRanking; $indiceRanking++) {
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_MEMBRESIA) {
                $ranking = $this->getRelatorio()[$indiceRanking]->getRanking_membresia();
            }
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_CELULA) {
                $ranking = $this->getRelatorio()[$indiceRanking]->getRanking_celula();
            }
            $class = '';
            if ($indiceRanking === $colunaDeSelecao) {
                $class = 'class="bg-' . $corSelecao . '"';
            }
            $html .= '<td ' . $class . ' width="20%">';
            $html .= '<h1>#' . $ranking . '</h1>';
            $html .= '</td>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        for ($indiceRanking = 0; $indiceRanking < $totalRanking; $indiceRanking++) {
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_MEMBRESIA) {
                $valor = $this->getRelatorio()[$indiceRanking]->getMembresia();
            }
            if ($this->getTipoRelatorio() === MontaGraficoDeBarra::TIPO_RELATORIO_CELULA) {
                $valor = $this->getRelatorio()[$indiceRanking]->getCelula();
            }
            $class = '';
            if ($indiceRanking === $colunaDeSelecao) {
                $class = 'class="bg-' . $corSelecao . '"';
            }
            $html .= '<td ' . $class . '>';
            $html .= '<div class="row mt20"><img src="/img/avatars/placeholder.png" class="mw60 img-circle"></div>';
            $html .= '<div class="row"><div id="div' . $nomeGrafico . $indiceRanking . '"></div></div>';
            $html .= '<div class="row">' . RelatorioController::formataNumeroRelatorio($valor) . '</div>';
            $html .= '</td>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        for ($indiceRanking = 0; $indiceRanking < $totalRanking; $indiceRanking++) {
            $grupo = $this->getRelatorio()[$indiceRanking]->getGrupo();
            $class = '';
            if ($indiceRanking === $colunaDeSelecao) {
                $class = 'class="bg-' . $corSelecao . '"';
            }
            $html .= '<td ' . $class . '>';
            $html .= $grupo->getNomeLideresAtivos();
            $html .= '</td>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        for ($indiceRanking = 0; $indiceRanking < $totalRanking; $indiceRanking++) {
            $grupo = $this->getRelatorio()[$indiceRanking]->getGrupo();
            $class = '';
            if ($indiceRanking === $colunaDeSelecao) {
                $class = 'class="bg-' . $corSelecao . '"';
            }
            $html .= '<td ' . $class . '>';
            $html .= $grupo->getEntidadeAtiva()->infoEntidade();
            $html .= '</td>';
        }
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    function getRelatorio() {
        return $this->relatorio;
    }

    function setRelatorio($relatorio) {
        $this->relatorio = $relatorio;
    }

    function getTipoRelatorio() {
        return $this->tipoRelatorio;
    }

    function setTipoRelatorio($tipoRelatorio) {
        $this->tipoRelatorio = $tipoRelatorio;
    }

}
