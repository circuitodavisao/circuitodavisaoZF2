<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoaVisto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa_financeiro
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa_financeiro")
 */
class TurmaPessoaFinanceiro extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaPessoa", inversedBy="turmaPessoaFinanceiro")
     * @ORM\JoinColumn(name="turma_pessoa_id", referencedColumnName="id")
     */
    private $turma_pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="turmaPessoaFinanceiro")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    private $disciplina;

    /** @ORM\Column(type="integer") */
    protected $turma_pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $disciplina_id;

    function getTurma_pessoa() {
        return $this->turma_pessoa;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function getTurma_pessoa_id() {
        return $this->turma_pessoa_id;
    }

    function getDisciplina_id() {
        return $this->disciplina_id;
    }

    function setTurma_pessoa($turma_pessoa) {
        $this->turma_pessoa = $turma_pessoa;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

    function setTurma_pessoa_id($turma_pessoa_id) {
        $this->turma_pessoa_id = $turma_pessoa_id;
    }

    function setDisciplina_id($disciplina_id) {
        $this->disciplina_id = $disciplina_id;
    }

}
