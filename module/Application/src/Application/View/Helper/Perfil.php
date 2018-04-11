<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Pessoa;
use Application\Model\Helper\FuncoesEntidade;
use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: Perfil.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar o perfil da pessoa passada
 */
class Perfil extends AbstractHelper {

    private $formulario;
    private $pessoa;
    private $mostrarOpcoes;

    public function __construct() {
        
    }

    public function __invoke(Pessoa $pessoa, Form $formulario, $mostrarOpcoes = true) {
        $this->setPessoa($pessoa);
        $this->setFormulario($formulario);
        $this->setMostrarOpcoes($mostrarOpcoes);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $pessoa = $this->getPessoa();
        $imagem = FuncoesEntidade::nomeDaImagem($pessoa);

        $formulario = $this->getFormulario();
        $formulario->prepare();
        $formulario->setAttribute(Constantes::$ACTION, $this->view->url(Constantes::$ROUTE_LOGIN, array(Constantes::$ACTION => 'perfilSalvar')));

        $html .= $this->view->form()->openTag($formulario);
        $html .= '<div class="panel-body bg-light p25 mt30">';

        $html .= '<div class="row">';
        $html .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">';
        $html .= FuncoesEntidade::tagImgComFotoDaPessoa($pessoa, 256);
        $html .= '<div id="fotoCrop" class="img-container pv10 hidden">';
        $html .= '<img src="/img/fotos/' . $imagem . '">';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        if ($this->getMostrarOpcoes()) {
            $html .= '<div class="row mt10">';
            $html .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">';
            $html .= '<div class="btn-group">            ';
            $html .= '<label class="btn btn-primary btn-sm btn-upload" style="width: 136px;" for="inputImage" title="Upload image file">';
            $html .= '<input class="sr-only" id="inputImage" name="file" type="file" accept="image/*">';
            $html .= '<span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">';
            $html .= 'Subir Foto <span class="fa fa-upload"></span>';
            $html .= '</span>';
            $html .= '</label>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="alterarFoto row mt10 hidden">';
            $html .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">';
            $html .= '<div class = "btn-group">';
            $html .= '<button class = "btn btn-primary btn-sm" data-method = "zoom" data-option = "0.1" type = "button" title = "Zoom In">';
            $html .= '<span class = "docs-tooltip" data-toggle = "tooltip" title = "$().cropper(&quot;zoom&quot;, 0.1)">';
            $html .= '<span class = "fa fa-search-plus"></span>';
            $html .= '</span>';
            $html .= '</button>';
            $html .= '<button class = "btn btn-primary btn-sm" data-method = "zoom" data-option = "-0.1" type = "button" title = "Zoom Out">';
            $html .= '<span class = "docs-tooltip" data-toggle = "tooltip" title = "$().cropper(&quot;zoom&quot;, -0.1)">';
            $html .= '<span class = "fa fa-search-minus"></span>';
            $html .= '</span>';
            $html .= '</button>';
            $html .= '<button class = "btn btn-primary btn-sm" data-method = "rotate" data-option = "-45" type = "button" title = "Rotate Left">';
            $html .= '<span class = "docs-tooltip" data-toggle = "tooltip" title = "$().cropper(&quot;rotate&quot;, -45)">';
            $html .= '<span class = "fa fa-rotate-left"></span>';
            $html .= '</span>';
            $html .= '</button>';
            $html .= '<button class = "btn btn-primary btn-sm" data-method = "rotate" data-option = "45" type = "button" title = "Rotate Right">';
            $html .= '<span class = "docs-tooltip" data-toggle = "tooltip" title = "$().cropper(&quot;rotate&quot;, 45)">';
            $html .= '<span class = "fa fa-rotate-right"></span>';
            $html .= '</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="alterarFoto row mt10 mb10 hidden">';
            $html .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">';
            $html .= '<button class="btn btn-primary btn-sm" style="width: 136px;" data-method="getCroppedCanvas" type="button">';
            $html .= '<span class="docs-tooltip" data-toggle="tooltip" title="Salvar Foto">';
            $html .= 'Salvar Foto <span class="fa fa-save"></span>';
            $html .= '</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!$this->getMostrarOpcoes()) {
            $elementoDDD = $formulario->get(Constantes::$INPUT_DDD);
            $elementoTelefone = $formulario->get(Constantes::$INPUT_TELEFONE);

            $elementoDDD->setAttribute('disabled', true);
            $elementoTelefone->setAttribute('disabled', true);
        }

        $html .= $this->view->inputFormularioSimples(Constantes::$TRADUCAO_NOME, $formulario->get(Constantes::$INPUT_NOME), 12);
        $html .= $this->view->inputFormularioSimples(Constantes::$TRADUCAO_DDD, $formulario->get(Constantes::$INPUT_DDD), 3);
        $html .= $this->view->inputFormularioSimples(Constantes::$TRADUCAO_TELEFONE, $formulario->get(Constantes::$INPUT_TELEFONE), 9);
        $html .= $this->view->inputFormularioSimples(Constantes::$TRADUCAO_CPF, $formulario->get(Constantes::$INPUT_CPF), 12);
        $html .= $this->view->inputFormularioSimples(Constantes::$TRADUCAO_EMAIL, $formulario->get(Constantes::$INPUT_EMAIL), 8);
        $html .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center mt35">';
        $funcaoAlterarEmail = $this->view->funcaoOnClick('mostrarSplash(); funcaoCircuito("principalEmail", ' . $this->getPessoa()->getId() . ')');
        $html .= $this->view->botaoSimples(Constantes::$TRADUCAO_ALTERAR, $funcaoAlterarEmail);
        $html .= '</div>';
        $html .= '</div>';

        if ($this->getMostrarOpcoes()) {
            $html .= '<div class="panel-footer text-right">';
            $html .= $this->view->divMensagens();
            $html .= $this->view->formHidden($formulario->get(Constantes::$FORM_ID));
            $html .= $this->view->formHidden($formulario->get(Constantes::$INPUT_CSRF));
            $funcaoVerificarPerfil = $this->view->funcaoOnClick('validarPerfil(this.form)');
            $html .= $this->view->botaoLink($this->view->translate('Salvar Dados'), Constantes::$STRING_HASHTAG, 1, $funcaoVerificarPerfil);
            $html .= '</div>';
        }

        $html .= $this->view->form()->closeTag();
        return $html;
    }

    function getFormulario() {
        return $this->formulario;
    }

    function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function getMostrarOpcoes() {
        return $this->mostrarOpcoes;
    }

    function setMostrarOpcoes($mostrarOpcoes) {
        $this->mostrarOpcoes = $mostrarOpcoes;
    }

}
