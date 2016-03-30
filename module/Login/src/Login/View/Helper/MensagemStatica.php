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
        if ($this->getTipo()) {
            switch ($this->getTipo()) {
                case 1:
                    $tipo = 'alert alert-warning';
                    break;
                case 2:
                    $tipo = 'alert alert-info';
                    break;
                case 3:
                    $tipo = 'alert alert-success';
                    break;
                case 4:
                    $tipo = 'alert alert-danger';
                    break;
            }

            $mensagem = $this->getMensagem();
            if ($mensagem) {
                $html .= '<div class="' . $tipo . '">';
//                $html .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
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
