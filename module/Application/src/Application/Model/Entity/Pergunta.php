<?php

namespace Application\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="pergunta")
 */
class Pergunta extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $pergunta;

    /** @ORM\Column(type="string") */
    protected $r1;

    /** @ORM\Column(type="string") */
    protected $r2;

    /** @ORM\Column(type="string") */
    protected $r3;

    /** @ORM\Column(type="string") */
    protected $r4;

    /** @ORM\Column(type="integer") */
    protected $aula_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $certa;

    /**
     * @ORM\ManyToOne(targetEntity="Aula", inversedBy="pergunta")
     * @ORM\JoinColumn(name="aula_id", referencedColumnName="id")
     */
    protected $aula;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="pergunta")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;

    function getPergunta() {
        return $this->pergunta;
    }

    function getR1() {
        return $this->r1;
    }

    function getR2() {
        return $this->r2;
    }

    function getR3() {
        return $this->r3;
    }

    function getR4() {
        return $this->r4;
    }

    function getAula_id() {
        return $this->aula_id;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getCerta() {
        return $this->certa;
    }

    function getAula() {
        return $this->aula;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setPergunta($i) {
        $this->pergunta = $i;
    }

    function setR1($i) {
        $this->r1 = $i;
    }

    function setR2($i) {
        $this->r2 = $i;
    }

    function setR3($i) {
        $this->r3 = $i;
    }

    function setR4($i) {
        $this->r4 = $i;
    }

    function setAula_id($i) {
        $this->aula_id = $i;
    }

    function setPessoa_id($i) {
        $this->pessoa_id = $i;
    }

    function setCerta($i) {
        $this->certa = $i;
    }

    function setAula($i) {
        $this->aula = $i;
    }

    function setPessoa($i) {
        $this->pessoa = $i;
    }

}
