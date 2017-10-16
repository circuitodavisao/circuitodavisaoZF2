<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_responsavel
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_pai_filho")
 */
class GrupoPaiFilho extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoPaiFilhoFilhos")
     * @ORM\JoinColumn(name="pai_id", referencedColumnName="id")
     */
    private $grupoPaiFilhoPai;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoPaiFilhoPai")
     * @ORM\JoinColumn(name="filho_id", referencedColumnName="id")
     */
    private $grupoPaiFilhoFilho;

    /** @ORM\Column(type="integer") */
    protected $pai_id;

    /** @ORM\Column(type="integer") */
    protected $filho_id;

    function getPai_id() {
        return $this->pai_id;
    }

    function getFilho_id() {
        return $this->filho_id;
    }

    function setPai_id($pai_id) {
        $this->pai_id = $pai_id;
    }

    function setFilho_id($filho_id) {
        $this->filho_id = $filho_id;
    }

    /**
     * Retorna o grupo pai
     * @return Grupo
     */
    function getGrupoPaiFilhoPai() {
        return $this->grupoPaiFilhoPai;
    }

    function setGrupoPaiFilhoPai($grupoPaiFilhoPai) {
        $this->grupoPaiFilhoPai = $grupoPaiFilhoPai;
    }

    /**
     * Retorna o grupo filho
     * @return Grupo
     */
    function getGrupoPaiFilhoFilho() {
        return $this->grupoPaiFilhoFilho;
    }

    function setGrupoPaiFilhoFilho($grupoPaiFilhoFilho) {
        $this->grupoPaiFilhoFilho = $grupoPaiFilhoFilho;
    }

}
