<?php

namespace Application\Model\Entity;

/**
 * Nome: Evento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela Evento
 */
use Application\Controller\Helper\Constantes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/** @ORM\Entity */
class Evento extends CircuitoEntity implements InputFilterAwareInterface {

    protected $inputFilter;
    protected $idAntigo;

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

    /** @ORM\Column(type="integer") */
    protected $dia;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $hora;

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

    function getData_criacaoAno() {
        return explode('-', $this->getData_criacao())[0];
    }

    function getData_criacaoMes() {
        return explode('-', $this->getData_criacao())[1];
    }

    function getData_criacaoDia() {
        return explode('-', $this->getData_criacao())[2];
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
        if ($this->getEventoTipo()->getId() === 2) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Verifica se o evento é do tipo culto
     * @return boolean
     */
    function verificaSeECulto() {
        $resposta = false;
        if ($this->getEventoTipo()->getId() === 1) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Verifica se o evento é do tipo culto
     * @return boolean
     */
    function verificaSeERevisao() {
        $resposta = false;
        if ($this->getTipo_id() == 3) {
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

    public function getInputFilter() {
        
    }

    public static function getInputFilterEvento() {
        $inputFilter = new InputFilter();
        /* Dia da Semana */
        $inputFilter->add(array(
            Constantes::$VALIDACAO_NAME => Constantes::$FORM_DIA_DA_SEMANA,
            Constantes::$VALIDACAO_REQUIRED => true,
            Constantes::$VALIDACAO_VALIDATORS => array(
                array(
                    Constantes::$VALIDACAO_NAME => Constantes::$VALIDACAO_NOT_EMPTY,
                ),
            ),
        ));
        /* Hora */
        $inputFilter->add(array(
            Constantes::$VALIDACAO_NAME => Constantes::$FORM_HORA,
            Constantes::$VALIDACAO_REQUIRED => true,
            Constantes::$VALIDACAO_VALIDATORS => array(
                array(
                    Constantes::$VALIDACAO_NAME => Constantes::$VALIDACAO_NOT_EMPTY,
                ),
            ),
        ));
        /* Minutos */
        $inputFilter->add(array(
            Constantes::$VALIDACAO_NAME => Constantes::$FORM_MINUTOS,
            Constantes::$VALIDACAO_REQUIRED => true,
            Constantes::$VALIDACAO_VALIDATORS => array(
                array(
                    Constantes::$VALIDACAO_NAME => Constantes::$VALIDACAO_NOT_EMPTY,
                ),
            ),
        ));

        return $inputFilter;
    }

    public static function getInputFilterEventoCulto() {
        $inputFilter = Evento::getInputFilterEvento();
        /* Nome */
        $inputFilter->add(array(
            Constantes::$VALIDACAO_NAME => Constantes::$FORM_NOME,
            Constantes::$VALIDACAO_REQUIRED => true,
            Constantes::$VALIDACAO_VALIDATORS => array(
                array(
                    Constantes::$VALIDACAO_NAME => Constantes::$VALIDACAO_NOT_EMPTY,
                ),
            ),
        ));

        return $inputFilter;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @throws Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }

    function getNome() {
        return $this->nome;
    }

    function getNomeAjustado() {
        $nomeAjustado = substr($this->nome, 0, 8);
        if (strlen($this->nome) > 8) {
            $nomeAjustado .= '...';
        }
        return $nomeAjustado;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    public function exchangeArray($data) {
        $this->nome = (!empty($data[Constantes::$FORM_NOME]) ? strtoupper($data[Constantes::$FORM_NOME]) : null);
    }

    function getIdAntigo() {
        return $this->idAntigo;
    }

    function setIdAntigo($idAntigo) {
        $this->idAntigo = $idAntigo;
    }

}
