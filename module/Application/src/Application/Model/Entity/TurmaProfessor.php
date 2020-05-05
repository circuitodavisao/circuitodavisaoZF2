<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaProfessor.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_professor
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_professor")
 */
class TurmaProfessor extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="turmaProfessor")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Turma", inversedBy="turmaProfessor")
     * @ORM\JoinColumn(name="turma_id", referencedColumnName="id")
     */
    private $turma;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $turma_id;

    /**
     * Retorna a pessoa da associação
     * @return Pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna a turma da associação
     * @return Turma
     */
    function getTurma() {
        return $this->turma;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getTurma_id() {
        return $this->turma_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setTurma_id($turma_id) {
        $this->turma_id = $turma_id;
    }

    function setTurma($turma) {
        $this->turma = $turma;
    }

}
