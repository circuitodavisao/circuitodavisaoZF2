<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoCiclo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_ciclo 
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_ciclo")
 */
class FatoCiclo extends CircuitoEntity {

    /**
     * @ORM\OneToMany(targetEntity="Dimensao", mappedBy="fatoCiclo") 
     */
    protected $dimensao;

    /**
     * @ORM\OneToMany(targetEntity="FatoCelula", mappedBy="fatoCiclo") 
     */
    protected $fatoCelula;

    public function __construct() {
        $this->dimensao = new ArrayCollection();
        $this->fatoCelula = new ArrayCollection();
    }

    /** @ORM\Column(type="integer") */
    protected $numero_identificador;

    /** @ORM\Column(type="integer") */
    protected $mes;

    /** @ORM\Column(type="integer") */
    protected $ano;

    /** @ORM\Column(type="integer") */
    protected $ciclo;

    function getNumero_identificador() {
        return $this->numero_identificador;
    }

    function getMes() {
        return $this->mes;
    }

    function getAno() {
        return $this->ano;
    }

    function getCiclo() {
        return $this->ciclo;
    }

    function setNumero_identificador($numero_identificador) {
        $this->numero_identificador = $numero_identificador;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

    function setCiclo($ciclo) {
        $this->ciclo = $ciclo;
    }

    /**
     * Retorna a dimensão
     * @return Dimensao
     */
    function getDimensao() {
        return $this->dimensao;
    }

    function setDimensao($dimensao) {
        $this->dimensao = $dimensao;
    }

    /**
     * Retorna fato celula
     * @return FatoCelula
     */
    function getFatoCelula() {
        return $this->fatoCelula;
    }

    function setFatoCelula($fatoCelula) {
        $this->fatoCelula = $fatoCelula;
    }

}
