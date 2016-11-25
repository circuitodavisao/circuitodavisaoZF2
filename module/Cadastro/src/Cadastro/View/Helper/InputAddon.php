<?php

namespace Cadastro\View\Helper;

use Login\Controller\Helper\Constantes;
use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputAddon.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um input com botao
 */
class InputAddon extends AbstractHelper {

    protected $form;
    protected $idInput;
    protected $funcao;
    protected $tipo;

    public function __construct() {
        
    }

    /**
     * Tipo 1 - Botao de Pesquisa
     * Tipo 2 - Botao de Pesquisa e Resposta
     * Tipo 3 - Resposta
     * @param Form $form
     * @param String $idInput
     * @param int $tipo
     * @param String $funcao
     * @return String
     */
    public function __invoke($form, $idInput, $tipo = 1, $funcao = '') {
        $this->setForm($form);
        $this->setIdInput($idInput);
        $this->setFuncao($funcao);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $icone = '';
        $input = $this->getForm()->get($this->getIdInput());
        if ($this->getTipo() == 1 || $this->getTipo() == 2) {
            $icone = 'fa fa-search';
        }

        $html .= '<div class="input-group input-group-lg mt10">';

        if ($this->getTipo() == 2 || $this->getTipo() == 3) {
            $html .= '<span class="input-group-addon">';
            $html .= '<span class="text-danger" id="span' . $this->getIdInput() . '"><i class="fa fa-times" aria-hidden="true"></i></span>';
            $html .= '</span>';
        }

        $html .= $this->view->formInput($input);
        $html .= '<span class="input-group-btn">';
        if ($this->getTipo() == 1 || $this->getTipo() == 2) {
            $html .= '<button class="btn btn-' . Constantes::$COR_BOTAO . '" type="button" ' . $this->view->funcaoOnClick($this->getFuncao()) . ' >';
            $html .= '<img id="loader' . $this->getIdInput() . '" class="hidden" src="' . Constantes::$LOADER_GIF . '" />';
            $html .= '&nbsp;<i class="' . $icone . '" aria-hidden="true"></i>';
            $html .= '</button>';
        }
        $html .= '</span>';

        $html .= '</div>';

        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function getIdInput() {
        return $this->idInput;
    }

    function getFuncao() {
        return $this->funcao;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setIdInput($idInput) {
        $this->idInput = $idInput;
    }

    function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
