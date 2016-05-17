<?php

namespace Entidade\Entity;

/**
 * Nome: Evento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela Evento
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Evento {

    /**
     * @ORM\OneToOne(targetEntity="EventoCelula", mappedBy="evento")
     */
    private $eventoCelula;

    /**
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="evento") 
     */
    protected $grupoEvento;

    /**
     * @ORM\OneToMany(targetEntity="EventoFrequencia", mappedBy="evento") 
     */
    protected $eventoFrequencia;

    public function __construct() {
        $this->grupoEvento = new ArrayCollection();
        $this->eventoFrequencia = new ArrayCollection();
    }

    /**
     * @ORM\ManyToOne(targetEntity="EventoTipo", inversedBy="evento")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $eventoTipo;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="integer") */
    protected $dia;

    /** @ORM\Column(type="string") */
    protected $hora;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    /**
     * Retorna o tipo de evento
     * @return EventoTipo
     */
    function getEventoTipo() {
        return $this->eventoTipo;
    }

    function getDia() {
        return $this->dia;
    }

    function getHora() {
        return $this->hora;
    }

    function getHoraFormatoHoraMinuto() {
        return substr($this->hora, 0, 5);
    }

    function setEventoTipo($eventoTipo) {
        $this->eventoTipo = $eventoTipo;
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function getGrupoEvento() {
        return $this->grupoEvento;
    }

    function setGrupoEvento($grupoEvento) {
        $this->grupoEvento = $grupoEvento;
    }

    /**
     * Retorna o evento da célula
     * @return EventoCelula
     */
    function getEventoCelula() {
        return $this->eventoCelula;
    }

    function setEventoCelula($eventoCelula) {
        $this->eventoCelula = $eventoCelula;
    }

    /**
     * Retorna as frequnências do evento
     * @return EventoFrequencia
     */
    function getEventoFrequencia() {
        return $this->eventoFrequencia;
    }

    function setEventoFrequencia($eventoFrequencia) {
        $this->eventoFrequencia = $eventoFrequencia;
    }

}
