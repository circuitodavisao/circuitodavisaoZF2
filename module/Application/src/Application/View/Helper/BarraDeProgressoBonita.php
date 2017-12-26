<?php

namespace Application\View\Helper;

use Application\Controller\RelatorioController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BarraDeProgressoBonita.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar BarraDeProgressoBonita
 */
class BarraDeProgressoBonita extends AbstractHelper {

    private $margem;
    private $extra;
    private $label;
    private $corDaBarra;
    private $valorDaBarra;
    private $mostrarMeta;
    private $valorReal;
    private $valorDaMeta;

    public function __construct() {
        
    }

    public function __invoke($label, $corDaBarra, $valorDaBarra, $margem = 'm15', $mostrarMeta = false, $valorDaMeta = 0, $valorReal = 0, $extra = '') {
        $this->setMargem($margem);
        $this->setLabel($label);
        $this->setCorDaBarra($corDaBarra);
        $this->setValorDaBarra($valorDaBarra);
        $this->setMostrarMeta($mostrarMeta);
        $this->setValorDaMeta($valorDaMeta);
        $this->setValorReal($valorReal);
        $this->setExtra($extra);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="row ' . $this->getMargem() . '">';
        $html .= '<div class="col-xs-12" ' . $this->getExtra() . '>';
        $html .= '<h5 class="mb15 text-muted">' . $this->getLabel() . '</h5>';
        $html .= '<div class="progress progress-bar-sm">';
        $html .= '<div class="progress-bar progress-bar-' . $this->getCorDaBarra() . '" role="progressbar" aria-valuenow="' . $this->getValorDaBarra() . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $this->getValorDaBarra() . '%;">';
        $html .= '<span class="sr-only">' . RelatorioController::formataNumeroRelatorio($this->getValorDaBarra()) . '%</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<p>';
        $html .= '<b class="text-' . $this->getCorDaBarra() . '">' . RelatorioController::formataNumeroRelatorio($this->getValorDaBarra()) . '%</b>';
        if ($this->getMostrarMeta()) {
            $html .= '<span class="pull-right text-' . $this->getCorDaBarra() . '">' . RelatorioController::formataNumeroRelatorio($this->getValorReal()) . ' de ' . $this->getValorDaMeta() . '</span>';
        }
        $html .= '</p>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getCorDaBarra() {
        return $this->corDaBarra;
    }

    function getValorDaBarra() {
        return $this->valorDaBarra;
    }

    function getMostrarMeta() {
        return $this->mostrarMeta;
    }

    function getValorDaMeta() {
        return $this->valorDaMeta;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setCorDaBarra($corDaBarra) {
        $this->corDaBarra = $corDaBarra;
    }

    function setValorDaBarra($valorDaBarra) {
        $this->valorDaBarra = $valorDaBarra;
    }

    function setMostrarMeta($mostrarMeta) {
        $this->mostrarMeta = $mostrarMeta;
    }

    function setValorDaMeta($valorDaMeta) {
        $this->valorDaMeta = $valorDaMeta;
    }

    function getMargem() {
        return $this->margem;
    }

    function getExtra() {
        return $this->extra;
    }

    function setMargem($margem) {
        $this->margem = $margem;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

    function getValorReal() {
        return $this->valorReal;
    }

    function setValorReal($valorReal) {
        $this->valorReal = $valorReal;
    }

}
