<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
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
        $tipoEntidade = $this->view->entidadeTipo;
        $nomeDoGrupo = '';
        $stringNome = 'Nome';
        $stringNumero = 'Número';

        switch ($tipoEntidade) {
            case 1:
                $nomeDoGrupo = $stringNome . ' do Nacional';
                break;
            case 2:
                $nomeDoGrupo = $stringNome . ' da Região';
                break;
            case 3:
                $nomeDoGrupo = $stringNumero . ' da Coordenação';
                break;
            case 4:
                $nomeDoGrupo = $stringNome . ' da Igreja';
                break;
            case 5:
                $nomeDoGrupo = $stringNome . ' da Equipe';
                break;
            case 6:
                $nomeDoGrupo = $stringNumero . ' da Subequipe';
                break;
            case 7:
                $nomeDoGrupo = $stringNumero . ' da Subequipe';
                break;
            default:
                break;
        }

        /* Verificando o tipo de entidade */
        $mostrarBotao = Constantes::$CLASS_HIDDEN;
        $tipoDadosComplementar = 0;

        /* Numero da entidade abaixo */
        if ($tipoEntidade === 3 ||
                $tipoEntidade === 6 ||
                $tipoEntidade === 7) {
            /* Selecionar Numeracao */
            $html .= '<label class = "field-label">' . $this->view->translate(Constantes::$TRADUCAO_SELECIONE_O_NUMERO_DA_SUB_EQUIPE);
            $html .= '</label>';
            $html .= $this->view->formSelect($this->getForm()->get(Constantes::$FORM_NUMERACAO));
            $tipoDadosComplementar = 1;
        }
        /* Nome da entidade abaixo */
        if ($tipoEntidade === 1 ||
                $tipoEntidade === 2 ||
                $tipoEntidade === 4 ||
                $tipoEntidade === 5) {
            /* Nome Entidade */
            $html .= '<label class="field-label">' . $nomeDoGrupo . '</label>';
            $html .= $this->view->formInput($this->getForm()->get(Constantes::$FORM_NOME_ENTIDADE));

            $mostrarBotao = '';
            $tipoDadosComplementar = 2;
        }

        /* Fim HelperView dados complementares */
        $html .= '<div class="mt10">';

        $html .= '<div id="divInserirAlterarDadosComplementares" class="' . $mostrarBotao . '">';
        $html .= '<div id="divBotaoInserirSelectDadosComplementares">';
        $html .= $this->view->botaoLink(Constantes::$TRADUCAO_INSERIR, Constantes::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ')'));
        $html .= '</div>';

        $html .= '<div id = "divBotaoAlterarSelectDadosComplementares" class="hidden">';
        $html .= $this->view->botaoLink(Constantes::$TRADUCAO_ALTERAR, Constantes::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ')'));
        $html .= '</div>';
        $html .= '</div>';

        $html .= $this->view->botaoLink(Constantes::$TRADUCAO_VOLTAR, Constantes::$STRING_HASHTAG, 8, $this->view->funcaoOnClick('botaoVoltarDadosComplementares()'));

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
