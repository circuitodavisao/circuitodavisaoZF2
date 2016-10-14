<?php

namespace Login\View\Helper;

/**
 * Nome: PerfilDropDown.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar o menu dropdown do cabeçalho
 */
use Login\Entity\Entidade;
use Zend\View\Helper\AbstractHelper;

class PerfilDropDown extends AbstractHelper {

    private $entidade;
    private $tipo;

    public function __construct() {
        
    }

    /**
     * Renderiza html do perfil em dropdown
     * Tipo:
     * 1 - <li>
     * 2 - <div> modal
     * @param Entidade $entidade
     * @param int $tipo
     * @return string
     */
    public function __invoke($entidade, $tipo) {
        $this->setEntidade($entidade);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $tipoEntidade = $this->getEntidade()->getTipo_id();
        $nomeEntidade = $this->getEntidade()->getEntidadeTipo()->getNome();
        $infoEntidade = $this->getEntidade()->infoEntidade();
        $html = '';
        if ($this->getTipo() == 1) {
            $html .= $this->htmlLi();
        }
        if ($this->getTipo() == 2) {
            /* Modal */
            $html .= '<div id="modal-' . $this->getEntidade()->getId() . '" class="popup-basic admin-form mfp-with-anim mfp-hide">';
            $html .= PerfilIcone::htmlPanel(2, $tipoEntidade, $nomeEntidade, $infoEntidade);
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Html com o li do perfil
     * @param type $tipo
     * @return string
     */
    private function htmlLi() {
        $html = '';
        $html .= '<li class="list-group-item">';

        $html .= '<a onclick=\'abrirModal("modal-' . $this->getEntidade()->getId() . '", ' . $this->getEntidade()->getId() . ',"perfilSelecionado");\' href="#modal-image" class="animated animated-short fadeInUp">';

        $html .= '<span class="fa fa-twitter"></span> ' . $this->getEntidade()->getEntidadeTipo()->getNome();
        $html .= ' - ' . $this->getEntidade()->infoEntidade();
        $html .= '</a>';

        $html .= '</li>';
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

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
