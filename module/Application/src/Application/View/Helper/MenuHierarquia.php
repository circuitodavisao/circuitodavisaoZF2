<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: MenuHierarquia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar menu hierarquia
 */
class MenuHierarquia extends AbstractHelper {

    protected $nomeLideres;
    protected $nomeEntidade;
    protected $tipo;

    public function __construct() {
        
    }

    /**
     * Monta os discipulos no menu lateral
     * Tipo 1 - Discipulo sem time abaixo
     * Tipo 2 - Discipulo com time abaixo
     * Tipo 3 - abre time abaixo
     * Tipo 4 - fecha time abaixo
     * Tipo 5 - 1 nivel de discipulos sem time
     * Tipo 6 - 1 nivel de discipulos com time
     * Tipo 7 - 1 nivel abre time
     * @param String $nomeLideres
     * @param String $nomeEntidade
     * @param int $tipo
     * @return String
     */
    public function __invoke($nomeLideres = '', $nomeEntidade = '', $tipo = 1) {
        $this->setNomeLideres($nomeLideres);
        $this->setNomeEntidade($nomeEntidade);
        $this->setTipo($tipo);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $class = '';
        $classUnbind = '';

        if ($this->getTipo() === 2 || $this->getTipo() === 6) {
            $class = "accordion-toggle";
        }
        if ($this->getTipo() === 3 || $this->getTipo() === 5) {
            $classUnbind = 'discipulo12';
        }
        if ($this->getTipo() === 6) {
            $classUnbind = 'discipuloComTime';
        } if ($this->getTipo() === 7 || $this->getTipo() === 8) {
            $classUnbind = 'discipulo144';
        }
        if ($this->getTipo() === 3 || $this->getTipo() === 7) {
            $html .= '<ul class="nav sub-nav">';
            $html .= '<li class="' . $classUnbind . '">';
            $html .= '<a href="#">';
            $html .= '&nbsp;&nbsp;';
            if ($this->getTipo() === 7) {
                $html .= '&nbsp;&nbsp;';
            }
            $html .= 'Ver&nbsp;' . $this->getNomeEntidade();
            $html .= '</a>';
            $html .= '</li>';
        }
        if ($this->getTipo() === 4) {
            $html .= '</li>';
            $html .= '</ul>';
        }

        if ($this->getTipo() !== 3 && $this->getTipo() !== 4 && $this->getTipo() !== 7) {
            $html .= '<li class="' . $classUnbind . '">';
            $html .= '<a class="' . $class . '" href="#">';

            if ($this->getTipo() === 5 || $this->getTipo() === 6 || $this->getTipo() === 8) {
                $html .= '&nbsp;&nbsp;';
            }
            if ($this->getTipo() === 8) {
                $html .= '&nbsp;&nbsp;';
            }
            $html .= $this->getNomeEntidade() . '<br />';

            if ($this->getTipo() === 5 || $this->getTipo() === 6 || $this->getTipo() === 8) {
                $html .= '&nbsp;&nbsp;';
            }
            if ($this->getTipo() === 8) {
                $html .= '&nbsp;&nbsp;';
            }
            $html .= $this->getNomeLideres();
            if ($this->getTipo() === 2 || $this->getTipo() === 6) {
                $html .= '<span class="caret"></span>';
            }
            $html .= '</a>';
            if ($this->getTipo() !== 2 && $this->getTipo() !== 6) {
                $html .= '</li>';
            }
        }
        return $html;
    }

    function getNomeLideres() {
        return $this->nomeLideres;
    }

    function getNomeEntidade() {
        return $this->nomeEntidade;
    }

    function setNomeLideres($nomeLideres) {
        $this->nomeLideres = $nomeLideres;
        return $this;
    }

    function setNomeEntidade($nomeEntidade) {
        $this->nomeEntidade = $nomeEntidade;
        return $this;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

}
