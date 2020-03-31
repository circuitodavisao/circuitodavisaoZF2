<?php

namespace Application\Model\Entity;

/**
 * Nome: Aula.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela aula
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="aula")
 */
class Aula extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $url;

    /** @ORM\Column(type="integer") */
    protected $disciplina_id;

    /** @ORM\Column(type="integer") */
    protected $posicao;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="aula")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    protected $disciplina;

    /**
     * @ORM\OneToMany(targetEntity="TurmaAula", mappedBy="aula")
     */
    protected $turmaAula;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaAula", mappedBy="aula")
     */
    protected $turmaPessoaAula;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaVisto", mappedBy="aula")
     */
    protected $turmaPessoaVisto;

    /**
     * @ORM\OneToMany(targetEntity="Pergunta", mappedBy="aula")  
     */
    protected $pergunta;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="aula")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    public function __construct() {
        $this->pergunta = new ArrayCollection();
        $this->turmaAula = new ArrayCollection();
        $this->turmaPessoaAula = new ArrayCollection();
        $this->turmaPessoaVisto = new ArrayCollection();
    }

	function getPessoa(){
		return $this->pessoa;
	}

	function getPessoa_id(){
		return $this->pessoa_id;
	}

	function setPessoa($i){
		$this->pessoa = $i;
	}

	function setPessoa_id($i){
		$this->pessoa_id = $i;
	}
	
    function getUrl() {
        return $this->url;
    }

    function getPergunta() {
        return $this->pergunta;
    }

	function setPergunta($i){
		$this->pergunta = $i;
	}

    function getNome() {
        return $this->nome;
    }

    function getDisciplina_id() {
        return $this->disciplina_id;
    }

    function getPosicao() {
        return $this->posicao;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDisciplina_id($disciplina_id) {
        $this->disciplina_id = $disciplina_id;
    }

    function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

    function getTurmaAula() {
        return $this->turmaAula;
    }

	function setUrl($i){
		$this->url = $i;
	}

    function setTurmaAula($turmaAula) {
        $this->turmaAula = $turmaAula;
    }

    function getTurmaPessoaAula() {
        return $this->turmaPessoaAula;
    }

    function setTurmaPessoaAula($turmaPessoaAula) {
        $this->turmaPessoaAula = $turmaPessoaAula;
    }

    function getTurmaPessoaVisto() {
        return $this->turmaPessoaVisto;
    }

    function setTurmaPessoaVisto($turmaPessoaVisto) {
        $this->turmaPessoaVisto = $turmaPessoaVisto;
    }

}
