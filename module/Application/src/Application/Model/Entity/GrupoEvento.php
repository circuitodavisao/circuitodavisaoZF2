<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoEvento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_evento
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_evento")
 */
class GrupoEvento extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Evento", inversedBy="grupoEvento", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoEvento", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $evento_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;
    protected $novo;

    /**
     * Retorna o evento
     * @return Evento
     */
    function getEvento() {
        return $this->evento;
    }

    /**
     * Retorna o grupo
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    function getEvento_id() {
        return $this->evento_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function getNovo() {
        return $this->novo;
    }

    function setNovo($novo) {
        $this->novo = $novo;
    }

}
