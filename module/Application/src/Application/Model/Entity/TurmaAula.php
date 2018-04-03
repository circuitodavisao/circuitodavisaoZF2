<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaAula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_aula
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_aula")
 */
class TurmaAula extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Aula", inversedBy="turmaAula")
     * @ORM\JoinColumn(name="aula_id", referencedColumnName="id")
     */
    private $aula;

    /**
     * @ORM\ManyToOne(targetEntity="Turma", inversedBy="turmaAula")
     * @ORM\JoinColumn(name="turma_id", referencedColumnName="id")
     */
    private $turma;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="turmaAula")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /** @ORM\Column(type="integer") */
    protected $turma_id;

    /** @ORM\Column(type="integer") */
    protected $aula_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    function getAula() {
        return $this->aula;
    }

    function getTurma() {
        return $this->turma;
    }

    function getTurma_id() {
        return $this->turma_id;
    }

    function getAula_id() {
        return $this->aula_id;
    }

    function setAula($aula) {
        $this->aula = $aula;
    }

    function setTurma($turma) {
        $this->turma = $turma;
    }

    function setTurma_id($turma_id) {
        $this->turma_id = $turma_id;
    }

    function setAula_id($aula_id) {
        $this->aula_id = $aula_id;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

}
