<?php

namespace Entidade\Entity;

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
class EventoFrequencia {

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

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $evento_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="string") */
    protected $frequencia;

    /** @ORM\Column(type="integer") */
    protected $ciclo;

    /**
     * Seta data e hora de criação
     */
    function setDataEHoraCriacao() {
        $timeNow = new DateTime();
        $this->setData_criacao($timeNow->format('Y-m-d'));
        $this->setHora_criacao($timeNow->format('H:s:i'));
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getEvento() {
        return $this->evento;
    }

    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getEvento_id() {
        return $this->evento_id;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
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

    function setId($id) {
        $this->id = $id;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
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

}
