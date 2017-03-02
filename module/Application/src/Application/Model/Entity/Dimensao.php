<?php

namespace Application\Model\Entity;

/**
 * Nome: Dimensao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela dimensao
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="dimensao")
 */
class Dimensao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="FatoCiclo", inversedBy="dimensao")
     * @ORM\JoinColumn(name="fato_ciclo_id", referencedColumnName="id")
     */
    private $fatoCiclo;

    /**
     * @ORM\ManyToOne(targetEntity="DimensaoTipo", inversedBy="dimensao")
     * @ORM\JoinColumn(name="dimensao_tipo_id", referencedColumnName="id")
     */
    private $dimensaoTipo;

    /** @ORM\Column(type="integer") */
    protected $visitante;

    /** @ORM\Column(type="integer") */
    protected $consolidacao;

    /** @ORM\Column(type="integer") */
    protected $membro;

    /** @ORM\Column(type="integer") */
    protected $lider;

    /** @ORM\Column(type="integer") */
    protected $fato_ciclo_id;

    /** @ORM\Column(type="integer") */
    protected $dimensao_tipo_id;

    /**
     * Retorna a DimensaoTipo
     * @return DimensaoTipo
     */
    function getDimensaoTipo() {
        return $this->dimensaoTipo;
    }

    function getDimensao_tipo_id() {
        return $this->dimensao_tipo_id;
    }

    function setDimensaoTipo($dimensaoTipo) {
        $this->dimensaoTipo = $dimensaoTipo;
    }

    function setDimensao_tipo_id($dimensao_tipo_id) {
        $this->dimensao_tipo_id = $dimensao_tipo_id;
    }

    function getFatoCiclo() {
        return $this->fatoCiclo;
    }

    function getVisitante() {
        return $this->visitante;
    }

    function getConsolidacao() {
        return $this->consolidacao;
    }

    function getMembro() {
        return $this->membro;
    }

    function getFato_ciclo_id() {
        return $this->fato_ciclo_id;
    }

    function setFatoCiclo($fatoCiclo) {
        $this->fatoCiclo = $fatoCiclo;
    }

    function setVisitante($visitante) {
        $this->visitante = $visitante;
    }

    function setConsolidacao($consolidacao) {
        $this->consolidacao = $consolidacao;
    }

    function setMembro($membro) {
        $this->membro = $membro;
    }

    function setFato_ciclo_id($fato_ciclo_id) {
        $this->fato_ciclo_id = $fato_ciclo_id;
    }

    function getLider() {
        return $this->lider;
    }

    function setLider($lider) {
        $this->lider = $lider;
    }

}
