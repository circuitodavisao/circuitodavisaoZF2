<?php

namespace Application\View\Helper;

/**
 * Nome: Message.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar mensagens alert bootstrap na tela
 */
use Zend\View\Helper\AbstractHelper;

class MensagemStatica extends AbstractHelper {

    protected $mensagem;
    protected $tipo;
    protected $pastel;
    protected $hidden;
    protected $identificacao;

    public function __construct() {
        
    }

    /**
     * Div com mensagem alert bootstrap
     * 1 - warning
     * 2 - primary
     * 3 - success
     * 4 - danger
     * @param String $mensagem
     * @param int $tipo
     * @param int $pastel
     * @return html
     */
    public function __invoke($mensagem, $tipo, $pastel = 1, $hidden = 0, $identificacao = '') {
        $this->setMensagem($mensagem);
        $this->setTipo($tipo);
        $this->setPastel($pastel);
        $this->setHidden($hidden);
        $this->setIdentificacao($identificacao);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tipo = 'alert ';
        if ($this->getTipo()) {
            switch ($this->getTipo()) {
                case 1:/* Relatorio desatualizado */
                    $tipo .= 'alert-warning';
                    $tipoIcone = 'warning';
                    break;
                case 2:
                    $tipo .= 'alert-primary';
                    $tipoIcone = 'comment';
                    break;
                case 3:/* Relatorio atualizado */
                    $tipo .= 'alert-success';
                    $tipoIcone = 'check';
                    break;
                case 4:
                    $tipo .= 'alert-danger';
                    $tipoIcone = 'times';
                    break;
            }
            if ($this->getPastel() == 1) {
                $tipo .= ' pastel';
            } else {
                $tipo .= ' text-center';
            }

            $hidden = '';
            if ($this->getHidden() == 1) {
                $hidden = ' hidden';
            }
            $mensagem = $this->getMensagem();
            if ($mensagem) {
                $html .= '<div id="' . $this->getIdentificacao() . '" class="' . $tipo . $hidden . '">';
                $html .= '<i class="fa fa-' . $tipoIcone . ' pr10"></i>';
                $html .= $mensagem;
                $html .= '</div>';
            }
        }
        return $html;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getPastel() {
        return $this->pastel;
    }

    function setPastel($pastel) {
        $this->pastel = $pastel;
    }

    function getHidden() {
        return $this->hidden;
    }

    function setHidden($hidden) {
        $this->hidden = $hidden;
    }

    function getIdentificacao() {
        if (empty($this->identificacao)) {
            $this->identificacao = 'divMensagemEstatica';
        }
        return $this->identificacao;
    }

    function setIdentificacao($identificacao) {
        $this->identificacao = $identificacao;
    }

}
