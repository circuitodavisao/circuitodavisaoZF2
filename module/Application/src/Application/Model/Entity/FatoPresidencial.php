<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_presidencial")
 */
class FatoPresidencial extends CircuitoEntity {

    /** @ORM\Column(type="integer") */
    protected $lideres;

    /** @ORM\Column(type="integer") */
    protected $celulas;

    /** @ORM\Column(type="integer") */
    protected $discipulados;

    /** @ORM\Column(type="integer") */
    protected $regioes;

    /** @ORM\Column(type="integer") */
    protected $coordenacoes;

    /** @ORM\Column(type="integer") */
    protected $igrejas;

    /** @ORM\Column(type="integer") */
    protected $parceiro;

    function getLideres() {
        return $this->lideres;
    }

    function getCelulas() {
        return $this->celulas;
    }

    function getDiscipulados() {
        return $this->discipulados;
    }

    function getRegioes() {
        return $this->regioes;
    }

    function getCoordenacoes() {
        return $this->coordenacoes;
    }

    function getIgrejas() {
        return $this->igrejas;
    }

    function getParceiro() {
        return $this->parceiro;
    }

    function setLideres($lideres) {
        $this->lideres = $lideres;
    }

    function setCelulas($i) {
        $this->celulas = $i;
    }

    function setDiscipulados($i) {
        $this->discipulados = $i;
    }

    function setRegioes($i) {
        $this->return = $i;
    }

    function setCoordenacoes($i) {
        $this->coordenacoes = $i;
    }

    function setIgrejas($i) {
        $this->igrejas = $i;
    }

    function setParceiro($i) {
        $this->parceiro = $i;
    }

}
