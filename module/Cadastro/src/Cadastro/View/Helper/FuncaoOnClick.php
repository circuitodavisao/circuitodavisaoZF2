<?php

namespace Cadastro\View\Helper;

use Cadastro\Form\ConstantesForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: FuncaoOnClick.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar a função onClick no elemento
 */
class FuncaoOnClick extends AbstractHelper {

    protected $funcao;

    public function __construct() {
        
    }

    public function __invoke($funcao) {
        $this->setFuncao($funcao);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= ConstantesForm::$FORM_ONCLICK;
        $html .= '=\'';
        $html .= $this->getFuncao();
        $html .= ';\'';
        return $html;
    }

    function getFuncao() {
        return $this->funcao;
    }

    function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

}
