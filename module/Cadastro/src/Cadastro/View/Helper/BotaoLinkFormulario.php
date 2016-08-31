<?php

namespace Cadastro\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoLinkFormulario.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao com link
 */
class BotaoLinkFormulario extends AbstractHelper {

    protected $link;
    protected $label;
    protected $extra;
    protected $icone;
    protected $tipo;

    public function __construct() {
        
    }

    public function __invoke($label, $link, $icone = 0, $extra = '') {
        $this->setLabel($label);
        $this->setLink($link);
        $this->setExtra($extra);
        $this->setIcone($icone);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $classCor = 'btn-info dark';
        $html = '';
        $html .= '<label for="" class="field-label">&nbsp;</label>';
        $html .= '<label for="" class="field">';
        $html .= '<a href="' . $this->getLink() . '" ' . $this->getExtra() . '>';
        $html .= '<button type="button" class="btn ladda-button ' . $classCor . '" data-style="zoom-in">';
        $html .= '<span class="ladda-label">';
        $html .= $this->view->translate($this->getLabel());
        if ($this->getIcone()) {
            $html .= '&nbsp;<i class="' . $this->getIcone() . '"></i>';
        }
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

}
