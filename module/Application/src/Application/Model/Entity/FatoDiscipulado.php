<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoRanking.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_discipulado
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_discipulado")
 */
class FatoDiscipulado extends CircuitoEntity {

    /**
     * @ORM\OneToOne(targetEntity="Grupo")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="string") */
    protected $comecou_no_horario;

    /** @ORM\Column(type="string") */
    protected $terminou_no_horario;

    /** @ORM\Column(type="string") */
    protected $teve_lanche;

    /** @ORM\Column(type="string") */
    protected $teve_avisos;

    /** @ORM\Column(type="string") */
    protected $teve_palavra;

    function getGrupo() {
        return $this->grupo;
    }

    function getComecou_no_horario() {
        return $this->comecou_no_horario;
    }

    function getTerminou_no_horario() {
        return $this->terminou_no_horario;
    }

    function getTeve_lanche() {
        return $this->teve_lanche;
    }

    function getTeve_avisos() {
        return $this->teve_avisos;
    }

    function getTeve_palavra() {
        return $this->teve_palavra;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setComecou_no_horario($comecou_no_horario) {
        $this->comecou_no_horario = $comecou_no_horario;
    }

    function setTerminou_no_horario($terminou_no_horario) {
        $this->terminou_no_horario = $terminou_no_horario;
    }

    function setTeve_lanche($teve_lanche) {
        $this->teve_lanche = $teve_lanche;
    }

    function setTeve_avisos($teve_avisos) {
        $this->teve_avisos = $teve_avisos;
    }

    function setTeve_palavra($teve_palavra) {
        $this->teve_palavra = $teve_palavra;
    }

}
