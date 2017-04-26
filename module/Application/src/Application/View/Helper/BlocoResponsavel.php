<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BlocoResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar o bloco do responsavel pelo tipo
 */
class BlocoResponsavel extends AbstractHelper {

    protected $tipo;

    public function __construct() {
        
    }

    public function __invoke($tipo) {
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $funcaoJS = str_replace('#tipo', $this->getTipo(), Constantes::$FUNCAO_JS_ABRIR_TELAS_DE_ALUNO);
        $funcaoJS = str_replace('#entidadeTipo', $this->view->entidadeTipo, $funcaoJS);
        $textoFuncaoJS = $this->view->funcaoOnClick($funcaoJS);

        $html .= '<div id="blocoResponsavel' . $this->getTipo() . '" class="well col-xs-12 btn btn-default text-left" ' . $textoFuncaoJS . '>';

        $textoInsiraOResponsavel = '';
        $html .= '<address>';
        switch ($this->getTipo()) {
            case 1:
                $textoInsiraOResponsavel = $this->view->translate(Constantes::$TRADUCAO_INSIRA_OS_DADOS . Constantes::$TRADUCAO_HOMEM);
                break;
            case 2:
                $textoInsiraOResponsavel = $this->view->translate(Constantes::$TRADUCAO_INSIRA_OS_DADOS . Constantes::$TRADUCAO_MULHER);
                break;
            default:
                $textoInsiraOResponsavel = $this->view->translate(Constantes::$TRADUCAO_INSIRA_OS_DADOS . Constantes::$TRADUCAO_RESPONSAVEL);
                break;
        }
        $html .= '<span id="spanInsiraOsDadosDoResponsavel">' . $textoInsiraOResponsavel . '</span>';
        if ($this->view->entidadeTipo !== 1 && $this->view->entidadeTipo !== 2 && $this->view->entidadeTipo !== 3 && $this->view->entidadeTipo !== 4) {
            $html .= '<span class="quebraDeLinhaDeSpan" id="spanMatricula' . $this->getTipo() . '"></span>';
        }

        $html .= '<span class="quebraDeLinhaDeSpan" id="spanCPF' . $this->getTipo() . '"></span>';
        $html .= '<span class="quebraDeLinhaDeSpan hidden-xs" id="spanNome' . $this->getTipo() . '"></span>';
        $html .= '<span class="quebraDeLinhaDeSpan quebraDeLinhaDeSpan hidden-xs" id="spanEmail' . $this->getTipo() . '"></span>';
        $html .= '<span class="quebraDeLinhaDeSpan visible-xs" id="spanNome' . $this->getTipo() . 'xs"></span>';
        $html .= '<span class="quebraDeLinhaDeSpan visible-xs" id="spanEmail' . $this->getTipo() . 'xs"></span>';
        $html .= '<span class="quebraDeLinhaDeSpan" id="spanHierarquia' . $this->getTipo() . '"></span>';
        $html .= '</address>';

        /* Botao Inserir Responsavel */
        $contadoDeDiv = 0;
        $id[$contadoDeDiv] = 'divBotaoInserirResponsavel' . $this->getTipo();
        $class[$contadoDeDiv] = '';
        $conteudo[$contadoDeDiv] = $this->view->botaoLink(
                Constantes::$TRADUCAO_INSERIR, Constantes::$STRING_HASHTAG, 5, $this->view->funcaoOnClick('abrirTelaDeAlunos(' . $this->getTipo() . ',' . $this->view->entidadeTipo . ')'));
        $html .= $this->view->blocoDiv($id[$contadoDeDiv], $class[$contadoDeDiv], $conteudo[$contadoDeDiv]);

        /* Botao Limpar Responsavel */
        $contadoDeDiv++;
        $id[$contadoDeDiv] = 'divBotaoLimparResponsavel' . $this->getTipo();
        $class[$contadoDeDiv] = Constantes::$FORM_HIDDEN;
        $conteudo[$contadoDeDiv] = $this->view->botaoLink(
                Constantes::$TRADUCAO_LIMPAR, Constantes::$STRING_HASHTAG, 6, $this->view->funcaoOnClick('limparDadosPessoaSelecionada(' . $this->getTipo() . ')'));
        $html .= $this->view->blocoDiv($id[$contadoDeDiv], $class[$contadoDeDiv], $conteudo[$contadoDeDiv]);

        $html .= '<div id="divCheckDadosResponsavelInseridos' . $this->getTipo() . '" class="checkDadosInseridos hidden">';
        $html .= '<span class="glyphicon glyphicon-ok text-success"></span>';
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
