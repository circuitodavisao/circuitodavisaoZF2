<?php

namespace Application\Model\Entity;

/**
 * Nome: AlunoSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela aluno_situacao
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="aluno_situacao")
 */
class AlunoSituacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaAluno", inversedBy="alunoSituacao")
     * @ORM\JoinColumn(name="turma_aluno_id", referencedColumnName="id")
     */
    private $turmaAluno;

    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="alunoSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;

    /** @ORM\Column(type="integer") */
    protected $situacao_id;

    /** @ORM\Column(type="integer") */
    protected $turma_aluno_id;

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function getSituacao_id() {
        return $this->situacao_id;
    }

    function getTurma_aluno_id() {
        return $this->turma_aluno_id;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }

    function setTurma_aluno_id($turma_aluno_id) {
        $this->turma_aluno_id = $turma_aluno_id;
    }

}
