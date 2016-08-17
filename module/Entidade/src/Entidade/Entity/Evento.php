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

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

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

    function getData_criacaoAno() {
        return explode('-', $this->getData_criacao())[0];
    }

    function getData_criacaoMes() {
        return explode('-', $this->getData_criacao())[1];
    }

    function getData_criacaoDia() {
        return explode('-', $this->getData_criacao())[2];
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

    /**
     * Retorna o dia sendo domingo dia 8 para ordenação correta
     * @return int
     */
    function getDiaAjustado() {
        $aux = $this->dia;
        if ($this->dia == 1) {
            $aux = 8;
        }
        return $aux;
    }

    function getHora() {
        return $this->hora;
    }

    function getHoraSemMinutosESegundos() {
        return substr($this->getHora(), 0, 2);
    }

    function getMinutosSemHorasESegundos() {
        return substr($this->getHora(), 3, 2);
    }

    function getHoraFormatoHoraMinuto() {
        $resposta = '';
        /* Se for hora em ponto hora mais 'H' */
        $hora = substr($this->hora, 0, 2);
        $minutos = substr($this->hora, 3, 2);
        if ((int) $minutos == 0) {
            $resposta = $hora . 'H';
        } else {
            $resposta = $hora . '.';
        }
        return $resposta;
    }

    /**
     * Retorna as horas com os minutos apenas
     * @return String
     */
    function getHoraFormatoHoraMinutoParaListagem() {
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

    /**
     * Retorna grupo evento
     * @return GrupoEvento
     */
    function getGrupoEvento() {
        return $this->grupoEvento;
    }

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEventoAtivos() {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $ge) {
            if ($ge->verificarSeEstaAtivo()) {
                $grupoEventos[] = $ge;
            }
        }
        return $grupoEventos;
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

    /**
     * Verifica se o evento é do tipo célula
     * @return boolean
     */
    function verificaSeECelula() {
        $resposta = false;
        if ($this->getTipo_id() == 2) {
            $resposta = true;
        }
        return $resposta;
    }

    function getTipo_id() {
        return $this->tipo_id;
    }

    function setTipo_id($tipo_id) {
        $this->tipo_id = $tipo_id;
    }

}
