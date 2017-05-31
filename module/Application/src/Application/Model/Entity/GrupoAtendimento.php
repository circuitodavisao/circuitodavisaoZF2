<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoAtendimento.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_atendimento
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_atendimento")
 */
class GrupoAtendimento extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoAtendimento")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    function getGrupo() {
        return $this->grupo;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

}
