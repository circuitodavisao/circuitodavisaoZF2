<?php

namespace Application\Model\Entity;

/**
 * Nome: Curso.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela curso
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="curso")
 */
class Curso extends CircuitoEntity {
    const INSTITUTO_DE_VENCEDORES = 2;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /**
     * @ORM\OneToMany(targetEntity="Disciplina", mappedBy="curso")  
     */
    protected $disciplina;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="curso")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\OneToMany(targetEntity="Turma", mappedBy="curso")
     */
    protected $turma;

    public function __construct() {
        $this->disciplina = new ArrayCollection();
        $this->turma = new ArrayCollection();
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

    function getTurma() {
        return $this->turma;
    }

    function setTurma($turma) {
        $this->turma = $turma;
    }

    function getNomeSigla() {
        $explodeNome = explode(" ", $this->getNome());
        $sigla = substr($explodeNome[0], 0, 1);
        if (count($explodeNome) > 1) {
            $sigla .= '.' . substr($explodeNome[(count($explodeNome) - 1)], 0, 1) . '.';
        }
        return $sigla;
    }

}
