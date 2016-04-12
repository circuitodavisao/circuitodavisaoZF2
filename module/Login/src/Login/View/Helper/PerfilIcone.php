<?php

namespace Login\View\Helper;

/**
 * Nome: PerfilIcone.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os icones do selecionar perfil
 */
use Login\Controller\Helper\Constantes;
use Login\Entity\Entidade;
use Zend\View\Helper\AbstractHelper;

class PerfilIcone extends AbstractHelper {

    protected $entidade;

    public function __construct() {
        
    }

    /**
     * @param Entidade $entidade
     * @return html
     */
    public function __invoke($entidade) {
        $this->setEntidade($entidade);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        if ($this->getEntidade()->getEntidadeTipo()) {

            /* Div com tamanho das colunas */
            $html .= '<div id="" class="col-xs-12 col-md-4 col-sm-4 col-md-2">';

            /* Link com ativacao do modal */
            $html .= '<a onclick=\'abrirModal("modal-' . $this->getEntidade()->getId() . '", ' . $this->getEntidade()->getId() . ',"perfilSelecionado");\' href="#modal-image" data-effect="mfp-fullscale">';

            $html .= $this->htmlPanel(1);

            /* FIM Link com ativacao do modal */
            $html .= '</a>';
            /* FIM Div com tamanho das colunas */
            $html .= '</div>';

            /* Modal */
            $html .= '<div id="modal-' . $this->getEntidade()->getId() . '" class="popup-basic admin-form mfp-with-anim mfp-hide">';

            $html .= $this->htmlPanel(2);

            /* FIM Modal */
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Retorna o objeto entidade
     * @return Entidade
     */
    function getEntidade() {
        return $this->entidade;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    /**
     * Retorna a cor do panel da seleção de perfil
     * @param int $tipo
     * @return string
     */
    private function corDoPanel($tipo) {
        $class = '';
        switch ($tipo) {
            case 1:
                $class = 'bg-system';
                break;
            case 2:
                $class = 'bg-alert';
                break;
            case 3:
                $class = 'bg-danger';
                break;
            case 4:
                $class = 'bg-warning';
                break;
            case 5:
                $class = 'bg-success';
                break;
            case 6:
                $class = 'bg-primary';
                break;
            case 7:
                $class = 'bg-dark';
                break;
            case 8:
                $class = 'bg-light';
                break;
        }
        return $class;
    }

    /**
     * Retorna a cor do footer do panel da seleção de perfil
     * @param int $tipo
     * @return string
     */
    private function corDoFooter($tipo) {
        $classFooter = '';
        switch ($tipo) {
            case 1:
                $classFooter = 'bg-system br-n';
                break;
            case 2:
                $classFooter = 'bg-alert br-n';
                break;
            case 3:
                $classFooter = 'bg-danger br-n';
                break;
            case 4:
                $classFooter = 'bg-warning br-n';
                break;
            case 5:
                $classFooter = 'bg-success br-n';
                break;
            case 6:
                $classFooter = 'bg-primary br-n';
                break;
            case 7:
                $classFooter = 'bg-dark light br-t br-white';
                break;
            case 8:
                $classFooter = 'bg-dark light br-t br-white';
                break;
        }
        return $classFooter;
    }

    /**
     * Retorna a cor do texto na seleção de perfil
     * @param int $tipo
     * @return string
     */
    private function corDoTexto($tipo) {
        $classTexto = 'text-white';
        if ($tipo == 7 || $tipo == 8) {
            $classTexto = 'text-dark';
        }
        return $classTexto;
    }

    /**
     * Html com o panel do perfil
     * @param type $tipo
     * @return string
     */
    private function htmlPanel($tipo) {
        $html = '';
        $corDoPanel = $this->corDoPanel($this->getEntidade()->getTipo_id());
        $corDoFooter = $this->corDoFooter($this->getEntidade()->getTipo_id());
        $corDoTexto = $this->corDoTexto($this->getEntidade()->getTipo_id());

        /* Div Panel */
        $html .= '<div class="panel panel-tile ' . $corDoPanel . ' text-center br-a">';
        /* Div Panel Body */
        $html .= '<div class="panel-body animation-switcher">';

        /* LODAR DO MODAL */
        if ($tipo == 2) {
            $html .= '<div>Carregando ';
            $html .= '<img src="' . Constantes::$LOADER_GIF . '"></i>';
            $html .= '</div>';
        }
        /* ICONE */
        $html .= '<i class="fa fa-twitter text-muted fs70 mt10"></i>';

        /* Info da entidade */
        $html .= '<h1 class="fs35 mbn ' . $corDoTexto . '">' . $this->getEntidade()->getEntidadeTipo()->getNome() . '</h1>';
        /* FIM Info da entidade */

        /* Tipo da entidade */
        $html .= '<h6 class="' . $corDoTexto . '">' . $this->getEntidade()->infoEntidade() . '</h6>';
        /* FIM Tipo da entidade */

        /* FIM Div Panel Body */
        $html .= '</div>';

        /* Div Footer */
        $html .= '<div class="panel-footer ' . $corDoFooter . ' p12">';
        /* Dados Estaticos */
        $html .= '<span class="fs11 text-white">';
        $html .= '<i class="fa fa-clock-o"></i> ÚLTIMO LOGIN';
        $html .= '<b>2 DIAS ATRÁS</b>';
        $html .= '</span>';

        /* FIM Div Footer */
        $html .= '</div>';
        /* FIM Div Panel */
        $html .= '</div>';
        return $html;
    }

}
