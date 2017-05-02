<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoCv.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_cv
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_cv")
 */
class GrupoCv {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Grupo")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $lider1;

    /** @ORM\Column(type="integer") */
    protected $lider2;

    /** @ORM\Column(type="string") */
    protected $numero_identificador;

    function getId() {
        return $this->id;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function getLider1() {
        return $this->lider1;
    }

    function getLider2() {
        return $this->lider2;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setLider1($lider1) {
        $this->lider1 = $lider1;
    }

    function setLider2($lider2) {
        $this->lider2 = $lider2;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function getNumero_identificador() {
        return $this->numero_identificador;
    }

    function setNumero_identificador($numero_identificador) {
        $this->numero_identificador = $numero_identificador;
    }

}
