<?php

namespace Cadastro\View\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Form\ConstantesForm;
use Login\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: GrupoDadosComplementares.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os dados complementares do cadastro de grupo
 */
class GrupoDadosComplementares extends AbstractHelper {

    protected $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tipoNumero = 1;
        $tipoNome = 2;
        $tipoEntidade = $this->view->tipoEntidade;
        $nomeDoGrupo = '';
        $stringNome = 'Nome';
        $stringNumero = 'Número';
        $tipoDadosComplementar = 0;

        switch ($tipoEntidade) {
            case 1:// Presidencial
                $nomeDoGrupo = $stringNome . ' do Nacional';
                break;
            case 2:// Nacional
                $nomeDoGrupo = $stringNome . ' da Região';
                break;
            case 3:// Regiao
                $nomeDoGrupo = $stringNumero . ' da Sub Região';
                break;
            case 4:// Sub Regiao
                $nomeDoGrupo = $stringNumero . ' da Coordenação';
                break;
            case 5:// Coordenacao
                $nomeDoGrupo = $stringNumero . ' da Sub Coordenação';
                break;
            case 6:// Sub Coordenacao
                $nomeDoGrupo = $stringNome . ' da Igreja';
                break;
            case 7:// Igreja
                $nomeDoGrupo = $stringNome . ' da Equipe';
                break;
            case 8:// Equipe
                $nomeDoGrupo = $stringNumero . ' da Sub Equipe';
                break;
            default:
                break;
        }

        /* Verificando o tipo de entidade */
        $mostrarBotao = Constantes::$CLASS_HIDDEN;

        /* Numero da entidade abaixo */
        if ($tipoEntidade === 7 || // Equipe
                $tipoEntidade === 8) {// Sub equipe
            /* Selecionar Numeracao */
            $html .= '<label class = "field-label">' . $this->view->translate(ConstantesCadastro::$TRADUCAO_SELECIONE_O_NUMERO_DA_SUB_EQUIPE);
            $html .= '</label>';
            $html .= $this->view->formSelect($this->getForm()->get(ConstantesForm::$FORM_NUMERACAO));
            $tipoDadosComplementar = 1;
        }
        /* Nome da entidade abaixo */
        if ($tipoEntidade === 1 || // Presidente
                $tipoEntidade === 2 || // Regiao
                $tipoEntidade === 4 || // Coordenacao
                $tipoEntidade === 5) { // Sub Coordenacao
            /* Nome Entidade */
            $html .= '<label class="field-label">' . $nomeDoGrupo . '</label>';
            $html .= $this->view->formInput($this->getForm()->get(ConstantesForm::$FORM_NOME_ENTIDADE));

            $mostrarBotao = '';
            $tipoDadosComplementar = 2;
        }



        /* Fim HelperView dados complementares */
        $html .= '<div class="mt10">';

        $html .= '<div id="divInserirAlterarDadosComplementares" class="' . $mostrarBotao . '">';
        $html .= '<div id="divBotaoInserirSelectDadosComplementares">';
        $html .= $this->view->botaoLink(ConstantesCadastro::$TRADUCAO_INSERIR, ConstantesForm::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ')'));
        $html .= '</div>';

        $html .= '<div id = "divBotaoAlterarSelectDadosComplementares" class="hidden">';
        $html .= $this->view->botaoLink(ConstantesCadastro::$TRADUCAO_ALTERAR, ConstantesForm::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ')'));
        $html .= '</div>';
        $html .= '</div>';

        $html .= $this->view->botaoLink(Constantes::$TRADUCAO_VOLTAR, ConstantesForm::$STRING_HASHTAG, 8, $this->view->funcaoOnClick('botaoVoltarDadosComplementares()'));

        $html .= '</div>

        

        ';

        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
    }

}
