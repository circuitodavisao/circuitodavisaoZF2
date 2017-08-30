<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BotaoSimples.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar um botao
 */
class BotaoSimples extends AbstractHelper {

    private $label;
    private $extra;
    private $tipoBotao;
    private $posicao;

    const botaoImportante = 1;
    const botaoMenorImportancia = 2;
    const botaoSucesso = 3;
    const botaoPequenoImportante = 4;
    const botaoPequenoMenosImportante = 5;
    const botaoMuitoPequenoImportante = 6;
    const botaoMuitoPequenoMenosImportante = 7;
    const botaoPerigoso = 8;
    const posicaoADireita = 1;
    const posicaoAEsquerda = 2;
    const posicaoAoCentro = 3;

    public function __construct() {
        
    }

    public function __invoke($label, $extra = '', $tipoBotao = BotaoSimples::botaoImportante, $posicao = BotaoSimples::posicaoADireita) {
        $this->setLabel($label);
        $this->setExtra($extra);
        $this->setTipoBotao($tipoBotao);
        $this->setPosicao($posicao);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $classBotao = Constantes::$COR_BOTAO;
        $posicaoBotao = 'pull-right';
        if ($this->getTipoBotao() === BotaoSimples::botaoMenorImportancia) {
            $classBotao = 'default';
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoSucesso) {
            $classBotao = 'success';
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoPerigoso) {
            $classBotao = 'danger';
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoPequenoImportante) {
            $classBotao = 'sm btn-' . Constantes::$COR_BOTAO;
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoPequenoMenosImportante) {
            $classBotao = 'sm btn-default';
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoMuitoPequenoImportante) {
            $classBotao = 'xs btn-' . Constantes::$COR_BOTAO;
        }
        if ($this->getTipoBotao() === BotaoSimples::botaoMuitoPequenoMenosImportante) {
            $classBotao = 'xs btn-default';
        }
        if ($this->getPosicao() === BotaoSimples::posicaoAEsquerda) {
            $posicaoBotao = 'pull-left';
        }
        if ($this->getPosicao() === BotaoSimples::posicaoAoCentro) {
            $posicaoBotao = '';
        }

        $html .= '<button type="button" ' . $this->getExtra() . ' class="btn ladda-button btn-' . $classBotao . ' ' . $posicaoBotao . ' ml10" data-style="zoom-in">';
        $html .= '<span class="ladda-label">';
        $html .= $this->view->translate($this->getLabel());
        $html .= '</span>';
        $html .= '</button>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function getExtra() {
        return $this->extra;
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function setExtra($extra) {
        $this->extra = $extra;
        return $this;
    }

    function getTipoBotao() {
        return $this->tipoBotao;
    }

    function setTipoBotao($tipoBotao) {
        $this->tipoBotao = $tipoBotao;
        return $this;
    }

    function getPosicao() {
        return $this->posicao;
    }

    function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

}
