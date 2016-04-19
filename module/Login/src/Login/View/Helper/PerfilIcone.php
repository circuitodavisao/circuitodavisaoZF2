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
    protected $totalEntidades;

    public function __construct() {
        
    }

    /**
     * @param Entidade $entidade
     * @return html
     */
    public function __invoke($entidade, $totalEntidades) {
        $this->setEntidade($entidade);
        $this->setTotalEntidades($totalEntidades);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $tipoEntidade = $this->getEntidade()->getTipo_id();
        $nomeEntidade = $this->getEntidade()->getEntidadeTipo()->getNome();
        $infoEntidade = $this->getEntidade()->infoEntidade();

        /* Tamanho da coluna */
        $col = 4;
        switch ($this->getTotalEntidades()) {
            case 2:
                $col = 6;
                break;
            case 4:
                $col = 6;
                break;
            case 6:
                $col = 2;
                break;
        }

        $html = '';
        if ($this->getEntidade()->getEntidadeTipo()) {

            /* Div com tamanho das colunas */
            $html .= '<div id="" class="col-sm-4 col-md-' . $col . '">';

            /* Link com ativacao do modal */
            $html .= '<a onclick=\'abrirModal("modal-' . $this->getEntidade()->getId() . '", ' . $this->getEntidade()->getId() . ',"perfilSelecionado");\' href="#modal-image" data-effect="mfp-fullscale">';

            $html .= PerfilIcone::htmlPanel(1, $tipoEntidade, $nomeEntidade, $infoEntidade);

            /* FIM Link com ativacao do modal */
            $html .= '</a>';
            /* FIM Div com tamanho das colunas */
            $html .= '</div>';

            /* Modal */
            $html .= '<div id="modal-' . $this->getEntidade()->getId() . '" class="popup-basic admin-form mfp-with-anim mfp-hide">';

            $html .= PerfilIcone::htmlPanel(2, $tipoEntidade, $nomeEntidade, $infoEntidade);

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
    public static function corDoPanel($tipo) {
        $class = '';
        switch ($tipo) {
            //Presidencial
            case 1:
                $class = 'bg-system light';
                break;
            
            //Líder Regional
            case 2:
                $class = 'bg-alert light';
                break;
            
            //Líder Sub-Regional
            case 3:
                $class = 'bg-danger light';
                break;
            
            //Coordenador
            case 4:
                $class = 'bg-warning light';
                break;
            
            //Sub-Coordenador
            case 5:
                $class = 'bg-success light';
                break;
            
            //Líder de Igreja
            case 6:
                $class = 'bg-primary light';
                break;
            
            //Líder de Equipe
            case 7:
                $class = 'bg-dark';
                break;
            
            //Líder de Sub-Equipe
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
    public static function corDoFooter($tipo) {
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
                $classFooter = 'bg-light dark br-t br-light';
                break;
        }
        return $classFooter;
    }

    /**
     * Retorna a cor do texto na seleção de perfil
     * @param int $tipo
     * @return string
     */
    public static function corDoTexto($tipo) {
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
    public static function htmlPanel($tipo, $tipoId, $nomeEntidade, $infoEntidade) {
        $html = '';
        $corDoPanel = PerfilIcone::corDoPanel($tipoId);
        $corDoFooter = PerfilIcone::corDoFooter($tipoId);
        $corDoTexto = PerfilIcone::corDoTexto($tipoId);

        $hover = '';
        if ($tipo == 1) {
            $hover = 'bg-light-hover';
        }

        /* Div Panel */
        $html .= '<div class="panel panel-moldure ' . $hover . ' panel-tile ' . $corDoPanel . ' text-center br-a br-light">';
        /* Div Panel Body */
        $html .= '<div class="panel-body ' . $corDoPanel . '">';

        /* LOADER DO MODAL */
        if ($tipo == 2) {
            $html .= '<div>Carregando ';
            $html .= '<img src="' . Constantes::$LOADER_GIF . '"></i>';
            $html .= '</div>';
        }
        /* ICONE */
        $html .= '<i class="fa fa-twitter text-muted fs70 mt10"></i>';

        /* Info da entidade */
        $html .= '<h1 class="fs35 mbn ' . $corDoTexto . '">' . $nomeEntidade . '</h1>';
        /* FIM Info da entidade */

        /* Tipo da entidade */
        $html .= '<h6 class="' . $corDoTexto . '">' . $infoEntidade . '</h6>';
        /* FIM Tipo da entidade */

        /* FIM Div Panel Body */
        $html .= '</div>';

        /* Div Footer */
        $html .= '<div class="panel-footer ' . $corDoFooter . ' br-t br-light p12">';
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

    function getTotalEntidades() {
        return $this->totalEntidades;
    }

    function setTotalEntidades($totalEntidades) {
        $this->totalEntidades = $totalEntidades;
    }

}
