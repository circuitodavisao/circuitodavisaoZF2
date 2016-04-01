<?php

namespace Login\View\Helper;

/**
 * Nome: Message.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar mensagens alert bootstrap na tela
 */
use Zend\View\Helper\AbstractHelper;

class MensagemStatica extends AbstractHelper {

    protected $mensagem;
    protected $tipo;

    public function __construct() {
        
    }

    /**
     * Div com mensagem alert bootstrap
     * @param String $mensagem
     * @param int $tipo
     * @return html
     */
    public function __invoke($mensagem, $tipo) {
        $this->setMensagem($mensagem);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tipo = 'alert ';
        if ($this->getTipo()) {
            switch ($this->getTipo()) {
                case 1:
                    $tipo .= 'alert-warning';
                    $tipoIcone = 'warning';
                    break;
                case 2:
                    $tipo .= 'alert-primary';
                    $tipoIcone = 'comment';
                    break;
                case 3:
                    $tipo .= 'alert-success';
                    $tipoIcone = 'check';
                    break;
                case 4:
                    $tipo .= 'alert-danger';
                    $tipoIcone = 'times';
                    break;
            }

            $tipo .= ' pastel';
            $mensagem = $this->getMensagem();
            if ($mensagem) {
                $html .= '<div class="' . $tipo . '">';
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

}
