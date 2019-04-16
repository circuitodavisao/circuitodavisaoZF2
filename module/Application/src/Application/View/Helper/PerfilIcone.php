<?php

namespace Application\View\Helper;

/**
 * Nome: PerfilIcone.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os icones do selecionar perfil
 */
use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Zend\View\Helper\AbstractHelper;

class PerfilIcone extends AbstractHelper {

    protected $entidade;
    protected $totalEntidades;
    protected $grupoResponsavelAtivo;
    protected $grupoPai;

    public function __construct() {
        
    }

    /**
     * @param Entidade $entidade
     * @return html
     */
    public function __invoke($entidade, $totalEntidades, $grupoResponsavelAtivo = true, $grupoPai = null) {
        $this->setEntidade($entidade);
        $this->setTotalEntidades($totalEntidades);
        $this->setGrupoResponsavelAtivo($grupoResponsavelAtivo);
        if ($grupoPai) {
            $this->setGrupoPai($grupoPai);
        }
        return $this->renderHtml();
    }

    public function renderHtml() {
        $tipoEntidade = $this->getEntidade()->getTipo_id();
        $nomeEntidade = $this->getEntidade()->getEntidadeTipo()->getNome();
        $infoEntidade = $this->getEntidade()->infoEntidade();
		if($this->getEntidade()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
			$infoEntidade .= ' ' . $this->getEntidade()->getNome();
		}

        $estaAtivo = true;
        if (!$this->getEntidade()->verificarSeEstaAtivo()) {
            $estaAtivo = false;
        }

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
            $idPai = 0;
            if ($this->getGrupoPai()) {
                $idPai = $this->getGrupoPai()->getId();
            }
            $idComposto = $this->getEntidade()->getId() . '_' . $idPai;
            $html .= '<a onclick=\'abrirModal("modal-' . $this->getEntidade()->getId() . '", "' . $idComposto . '", "perfilSelecionado");\' href="#modal-image" data-effect="mfp-fullscale" class="pageload-link">';

            $html .= PerfilIcone::htmlPanel(1, $tipoEntidade, $nomeEntidade, $infoEntidade, $this->getGrupoResponsavelAtivo(), $this->getGrupoPai(), $estaAtivo);

            /* FIM Link com ativacao do modal */
            $html .= '</a>';
            /* FIM Div com tamanho das colunas */
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
            case 1:
                $class = 'bg-system light';
                break;
            case 2:
                $class = 'bg-alert light';
                break;
            case 3:
                $class = 'bg-system light';
                break;
            case 4:
                $class = 'bg-warning light';
                break;
            case 5:
                $class = 'bg-success light';
                break;
            case 6:
                $class = 'bg-primary light';
                break;
            case 7:
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
                $classFooter = 'bg-system br-n';
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
    public static function htmlPanel($tipo, $tipoId, $nomeEntidade, $infoEntidade, $grupoResponsavelAtivo = true, $grupoPai = null, $estaAtivo = true) {
        $html = '';
        $corDoPanel = PerfilIcone::corDoPanel($tipoId);
        $corDoFooter = PerfilIcone::corDoFooter($tipoId);
        $corDoTexto = PerfilIcone::corDoTexto($tipoId);

        if(!$estaAtivo){
            $corDoPanel = 'bg-danger';
            $corDoFooter = 'bg-danger';
        }
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
        if (!$grupoResponsavelAtivo) {
            $faIcon = 'times';
        } else {
            $faIcon = 'users';
        }
        /* Pegando dados do lider */
        if ($grupoPai) {
            $grupoResponsabilidades = $grupoPai->getResponsabilidadesAtivas();
            $nomeLideres = Menu::montaNomeLideres($grupoResponsabilidades);
            $html .= '<h3>Discípulo de: ' . $nomeLideres . '</h3>';
        }

        $html .= '<i class="fa fa-' . $faIcon . ' text-muted fs40 mt10"></i>';

        /* Info da entidade */
        $html .= '<h1 class="fs35-responsiva mbn">';
        if (!$grupoResponsavelAtivo) {
            $html .= '(INATIVADA) ';
        }
        $html .= $nomeEntidade . '</h1>';
        /* FIM Info da entidade */

        /* Tipo da entidade */
        $html .= '<h6 class="' . $corDoTexto . '">' . $infoEntidade . '</h6>';
        /* FIM Tipo da entidade */

        /* FIM Div Panel Body */
        $html .= '</div>';

//        /* Div Footer */
//        $html .= '<div class="panel-footer ' . $corDoFooter . ' br-t br-light p12">';
//        /* Dados Estaticos */
//        $html .= '<span class="fs11 ' . $corDoTexto . '">';
//        $html .= '<i class="fa fa-clock-o"></i> ÚLTIMO LOGIN';
//        $html .= '<b>2 DIAS ATRÁS</b>';
//        $html .= '</span>';
//
//        /* FIM Div Footer */
//        $html .= '</div>';
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

    function getGrupoResponsavelAtivo() {
        return $this->grupoResponsavelAtivo;
    }

    function setGrupoResponsavelAtivo($grupoResponsavelAtivo) {
        $this->grupoResponsavelAtivo = $grupoResponsavelAtivo;
    }

    function getGrupoPai() {
        return $this->grupoPai;
    }

    function setGrupoPai($grupoPai) {
        $this->grupoPai = $grupoPai;
    }

    function getEstaAtivo() {
        return $this->estaAtivo;
    }

    function setEstaAtivo($estaAtivo) {
        $this->estaAtivo = $estaAtivo;
    }

}
