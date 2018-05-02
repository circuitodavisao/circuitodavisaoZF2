<?php

namespace Application\Model\Entity;

/**
 * Nome: EventoTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_tipo
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="evento_tipo")
 */
class EventoTipo extends CircuitoEntity {

    const tipoCelula = 1;
    const tipoCulto = 2;
    const tipoRevisao = 3;
    const tipoDiscipulado = 3;

    /**
     * @ORM\OneToMany(targetEntity="Evento", mappedBy="eventoTipo") 
     */
    protected $evento;

    public function __construct() {
        $this->evento = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    /**
     * Retorna o tipo do evento apenas com 3 digitos
     * @return string
     */
    function getNomeAjustado() {
        return substr($this->nome, 0, 3);
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getEvento() {
        return $this->evento;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

}
