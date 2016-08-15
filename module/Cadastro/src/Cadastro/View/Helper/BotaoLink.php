<?php

namespace Cadastro\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoLink.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao com link
 */
class BotaoLink extends AbstractHelper {

    protected $link;
    protected $label;
    protected $extra;
    protected $icone;
    protected $tipo;

    public function __construct() {
        
    }

    public function __invoke($label, $link, $icone = '', $extra = '', $tipo = 0) {
        $this->setLabel($label);
        $this->setLink($link);
        $this->setExtra($extra);
        $this->setIcone($icone);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $classCor = 'btn-info dark';
        $html = '';
        $html .= '<label for="" class="field-label">&nbsp;</label>';
        $html .= '<label for="" class="field">';
        $html .= '<a href="' . $this->getLink() . '" ' . $this->getExtra() . '>';
        $html .= '<button type="button" class="btn ladda-button ' . $classCor . '" data-style="zoom-in" style="width:100%;">';
        $html .= '<span class="ladda-label">';
        if ($this->getTipo() != 1) {
            $html .= '<span class=" hidden-md hidden-lg">';
        }
        $html .= $this->view->translate($this->getLabel());
        if ($this->getTipo() != 1) {
            $html .= '</span>';
        }
        $html .= '&nbsp;<i class="' . $this->getIcone() . '"></i>';
        $html .= '</span>';
        $html .= '</button>';
        $html .= '</a>';
        return $html;
    }

    function getLink() {
        return $this->link;
    }

    function setLink($link) {
        $this->link = $link;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getExtra() {
        return $this->extra;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

    function getIcone() {
        return $this->icone;
    }

    function setIcone($icone) {
        $this->icone = $icone;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
