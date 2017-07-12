<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: SpanDadosValidados.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para span com dados validado
 */
class SpanDadosValidados extends AbstractHelper {

    protected $id;
    protected $icone;
    protected $visivel;

    public function __construct() {
        
    }

    /**
     * 
     * @param string $id
     * @param string $icone
     * @param int $visivel
     * @return type
     */
    public function __invoke($id, $icone, $visivel = 1) {
        $this->setId($id);
        $this->setIcone($icone);
        $this->setVisivel($visivel);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $stringDiv = 'div';
        $stringPanResponsavel = 'panResponsavel';
        $idBase = $stringDiv . 'S' . $stringPanResponsavel;
        $idComposto = $idBase . $this->getId();

        $class = 'quebraDeLinhaDeSpan';
        if ($this->getVisivel() === 0) {
            $class .= ' ' . Constantes::$FORM_HIDDEN;
        }
        $conteudo = '';
        $conteudo .= '<i class="fa fa-' . $this->getIcone() . '" aria-hidden="true"></i>&nbsp;';
        $conteudo .= '<span id="' . 's' . $stringPanResponsavel . $this->getId() . '"></span>';

        return $this->view->blocoDiv($idComposto, $class, $conteudo);
    }

    function getIcone() {
        return $this->icone;
    }

    function getId() {
        return $this->id;
    }

    function setIcone($icone) {
        $this->icone = $icone;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getVisivel() {
        return $this->visivel;
    }

    function setVisivel($visivel) {
        $this->visivel = $visivel;
    }

}
