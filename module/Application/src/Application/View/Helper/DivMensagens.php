<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: DivMensagens.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o span com mensagens de erro e sucesso
 */
class DivMensagens extends AbstractHelper {

    private $label;
    private $tipo;
    private $mostrar;

    public function __construct() {
        
    }

    /**
     * Monta uma div com as mensagens estaticas
     * Tipo 1: success
     * Tipo 2: danger
     * @param string $label
     * @param int $tipo
     * @param boolean $mostrar
     * @return string
     */
    public function __invoke($label = '', $tipo = 2, $mostrar = false) {
        $this->setLabel($label);
        $this->setTipo($tipo);
        $this->setMostrar($mostrar);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $corAlert = 'danger';
        if ($this->getTipo() === 1) {
            $corAlert = 'success';
        }
        $hidden = 'hidden';
        if ($this->getMostrar()) {
            $hidden = '';
        }
        $html .= '<div id="divMensagens" class="alert alert-' . $corAlert . ' ' . $hidden . ' p15" role="alert">';
        $html .= $this->view->translate($this->getLabel());
        $html .= '</div>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

    function getMostrar() {
        return $this->mostrar;
    }

    function setMostrar($mostrar) {
        $this->mostrar = $mostrar;
        return $this;
    }

}
