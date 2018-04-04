<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa")
 */
class TurmaPessoa extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="turmaPessoa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Turma", inversedBy="turmaPessoa")
     * @ORM\JoinColumn(name="turma_id", referencedColumnName="id")
     */
    private $turma;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaFrequencia", mappedBy="turma_pessoa")
     */
    protected $turmaPessoaFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaSituacao", mappedBy="turma_pessoa")
     */
    protected $turmaPessoaSituacao;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaAula", mappedBy="turma_pessoa")
     */
    protected $turmaPessoaAula;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $turma_id;

    public function __construct() {
        $this->turmaPessoaFrequencia = new ArrayCollection();
        $this->turmaPessoaAula = new ArrayCollection();
        $this->turmaPessoaSituacao = new ArrayCollection();
    }

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

    function getMatricula() {
        return str_pad($this->getId(), 10, 0, STR_PAD_LEFT);
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

    function getTurmaPessoaFrequencia() {
        return $this->turmaPessoaFrequencia;
    }

    function setTurmaPessoaFrequencia($turmaPessoaFrequencia) {
        $this->turmaPessoaFrequencia = $turmaPessoaFrequencia;
    }

    function getTurmaPessoaAula() {
        return $this->turmaPessoaAula;
    }

    function setTurmaPessoaAula($turmaPessoaAula) {
        $this->turmaPessoaAula = $turmaPessoaAula;
    }

    function getTurmaPessoaSituacao() {
        return $this->turmaPessoaSituacao;
    }

    function setTurmaPessoaSituacao($turmaPessoaSituacao) {
        $this->turmaPessoaSituacao = $turmaPessoaSituacao;
    }

    public function getTurmaPessoaSituacaoAtiva() {
        $entidadeAtiva = null;
        foreach ($this->getTurmaPessoaSituacao() as $entidade) {
            if ($entidade->verificarSeEstaAtivo()) {
                $entidadeAtiva = $entidade;
                break;
            }
        }
        return $entidadeAtiva;
    }

}
