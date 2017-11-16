<?php

namespace Application\Model\Entity;

/**
 * Nome: EventoFrequencia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_frequencia
 */
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

    /** @ORM\Column(type="datetime", name="dia") */
    protected $dia;

    function getDiaStringPadraoBanco() {
        if ($this->getDia()) {
            return $this->getDia()->format('Y-m-d');
        }
    }

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

    function getDia() {
        return $this->dia;
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

}
