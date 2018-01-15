<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoRanking.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_ranking 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_ranking")
 */
class FatoRanking extends CircuitoEntity {

    /**
     * @ORM\OneToOne(targetEntity="Grupo")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $ranking_membresia;

    /** @ORM\Column(type="integer") */
    protected $ranking_celula;

    /** @ORM\Column(type="float") */
    protected $membresia;

    /** @ORM\Column(type="float") */
    protected $culto;

    /** @ORM\Column(type="float") */
    protected $arena;

    /** @ORM\Column(type="float") */
    protected $domingo;

    /** @ORM\Column(type="float") */
    protected $celula;

    function getGrupo() {
        return $this->grupo;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function getRanking_membresia() {
        return $this->ranking_membresia;
    }

    function getRanking_celula() {
        return $this->ranking_celula;
    }

    function getMembresia() {
        return $this->membresia;
    }

    function getCulto() {
        return $this->culto;
    }

    function getArena() {
        return $this->arena;
    }

    function getDomingo() {
        return $this->domingo;
    }

    function getCelula() {
        return $this->celula;
    }

    function setRanking_membresia($ranking_membresia) {
        $this->ranking_membresia = $ranking_membresia;
    }

    function setRanking_celula($ranking_celula) {
        $this->ranking_celula = $ranking_celula;
    }

    function setMembresia($membresia) {
        $this->membresia = $membresia;
    }

    function setCulto($culto) {
        $this->culto = $culto;
    }

    function setArena($arena) {
        $this->arena = $arena;
    }

    function setDomingo($domingo) {
        $this->domingo = $domingo;
    }

    function setCelula($celula) {
        $this->celula = $celula;
    }

}
