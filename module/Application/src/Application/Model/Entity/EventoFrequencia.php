<?php

namespace Application\Model\Entity;

/**
 * Nome: EventoFrequencia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_frequencia
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="evento_frequencia")
 */
class EventoFrequencia extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="eventoFrequencia")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Evento", inversedBy="eventoFrequencia")
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $evento_id;

    /** @ORM\Column(type="string") */
    protected $frequencia;

    /** @ORM\Column(type="integer") */
    protected $ciclo;

    /** @ORM\Column(type="integer") */
    protected $mes;

    /** @ORM\Column(type="integer") */
    protected $ano;

    function getPessoa() {
        return $this->pessoa;
    }

    function getEvento() {
        return $this->evento;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getEvento_id() {
        return $this->evento_id;
    }

    function getFrequencia() {
        return $this->frequencia;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

    function setFrequencia($frequencia) {
        $this->frequencia = $frequencia;
    }

    function getCiclo() {
        return $this->ciclo;
    }

    function setCiclo($ciclo) {
        $this->ciclo = $ciclo;
    }

    function getMes() {
        return $this->mes;
    }

    function getAno() {
        return $this->ano;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

}
