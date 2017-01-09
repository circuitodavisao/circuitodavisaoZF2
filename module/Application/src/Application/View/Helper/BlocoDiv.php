<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: BlocoDiv.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar blocos div com id, class e conteudo
 */
class BlocoDiv extends AbstractHelper {

    protected $id;
    protected $class;
    protected $conteudo;

    public function __construct() {
        
    }

    public function __invoke($id, $class, $conteudo) {
        $this->setId($id);
        $this->setClass($class);
        $this->setConteudo($conteudo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div id="' . $this->getId() . '"  class="' . $this->getClass() . '">';
        $html .= $this->getConteudo();
        $html .= '</div>';
        return $html;
    }

    function getId() {
        return $this->id;
    }

    function getClass() {
        return $this->class;
    }

    function getConteudo() {
        return $this->conteudo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setClass($class) {
        $this->class = $class;
    }

    function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
    }

}
