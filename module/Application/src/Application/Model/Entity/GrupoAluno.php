<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoFilho.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_filho
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_aluno")
 */
class GrupoAluno extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaAluno", inversedBy="grupoAluno")
     * @ORM\JoinColumn(name="turma_aluno_id", referencedColumnName="id")
     */
    private $turmaAluno;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoAluno")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $turma_aluno_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function getTurma_aluno_id() {
        return $this->turma_aluno_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setTurma_aluno_id($turma_aluno_id) {
        $this->turma_aluno_id = $turma_aluno_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

}
