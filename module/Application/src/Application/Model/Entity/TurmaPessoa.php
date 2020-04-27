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

    function getTurmaPessoaAulaPorAula($idAula) {
        $turmaPessoalAulaEncontrado = null;
        if ($this->getTurmaPessoaAula()->count() > 0) {
            foreach ($this->getTurmaPessoaAula() as $turmaPessoaAula) {
                if ($turmaPessoaAula->getAula()->getId() === $idAula) {
                    $turmaPessoalAulaEncontrado = $turmaPessoaAula;
                    break;
                }
            }
        }
        return $turmaPessoalAulaEncontrado;
    }

    function getTurmaPessoaVistoPorAula($idAula) {
        $turmaPessoalVistoEncontrado = null;
        if ($this->getTurmaPessoaVisto()->count() > 0) {
            foreach ($this->getTurmaPessoaVisto() as $turmaPessoaVisto) {
                if ($turmaPessoaVisto->getAula()->getId() === $idAula) {
                    $turmaPessoalVistoEncontrado = $turmaPessoaVisto;
                    break;
                }
            }
        }
        return $turmaPessoalVistoEncontrado;
    }

    function getTurmaPessoaFinanceiroPorDisciplina($idDisciplina) {
        $turmaPessoalFinanceiroEncontrado = null;
        if ($this->getTurmaPessoaFinanceiro()->count() > 0) {
            foreach ($this->getTurmaPessoaFinanceiro() as $turmaPessoaFinanceiro) {
                if ($turmaPessoaFinanceiro->getDisciplina()->getId() === $idDisciplina) {
                    $turmaPessoalFinanceiroEncontrado = $turmaPessoaFinanceiro;
                    break;
                }
            }
        }
        return $turmaPessoalFinanceiroEncontrado;
    }

    function getTurmaPessoaAvaliacaoPorDisciplina($idDisciplina) {
        $turmaPessoalAvaliacaoEncontrado = null;
        if ($this->getTurmaPessoaAvaliacao()->count() > 0) {
            foreach ($this->getTurmaPessoaAvaliacao() as $turmaPessoaAvaliacao) {
                if ($turmaPessoaAvaliacao->getDisciplina()->getId() === $idDisciplina) {
                    $turmaPessoalAvaliacaoEncontrado = $turmaPessoaAvaliacao;
                    break;
                }
            }
        }
        return $turmaPessoalAvaliacaoEncontrado;
    }

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
     * @ORM\OneToMany(targetEntity="TurmaPessoaFrequencia", mappedBy="turma_pessoa", fetch="EXTRA_LAZY"))
     */
    protected $turmaPessoaFrequencia;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaSituacao", mappedBy="turma_pessoa", fetch="EXTRA_LAZY")
     */
    protected $turmaPessoaSituacao;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaAula", mappedBy="turma_pessoa", fetch="EXTRA_LAZY"))
     */
    protected $turmaPessoaAula;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaVisto", mappedBy="turma_pessoa", fetch="EXTRA_LAZY"))
     */
    protected $turmaPessoaVisto;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaFinanceiro", mappedBy="turma_pessoa", fetch="EXTRA_LAZY"))
     */
    protected $turmaPessoaFinanceiro;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaAvaliacao", mappedBy="turma_pessoa", fetch="EXTRA_LAZY"))
     */
    protected $turmaPessoaAvaliacao;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $turma_id;

    /** @ORM\Column(type="integer") */
    protected $antigo_id;

    public function __construct() {
        $this->turmaPessoaFrequencia = new ArrayCollection();
        $this->turmaPessoaAula = new ArrayCollection();
        $this->turmaPessoaVisto = new ArrayCollection();
        $this->turmaPessoaFinanceiro = new ArrayCollection();
        $this->turmaPessoaAvaliacao = new ArrayCollection();
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

    function getTurmaPessoaVisto() {
        return $this->turmaPessoaVisto;
    }

    function setTurmaPessoaVisto($turmaPessoaVisto) {
        $this->turmaPessoaVisto = $turmaPessoaVisto;
    }

    function getTurmaPessoaFinanceiro() {
        return $this->turmaPessoaFinanceiro;
    }

    function setTurmaPessoaFinanceiro($turmaPessoaFinanceiro) {
        $this->turmaPessoaFinanceiro = $turmaPessoaFinanceiro;
    }

    function getTurmaPessoaAvaliacao() {
        return $this->turmaPessoaAvaliacao;
    }

    function setTurmaPessoaAvaliacao($turmaPessoaAvaliacao) {
        $this->turmaPessoaAvaliacao = $turmaPessoaAvaliacao;
    }

    function getAntigo_id() {
        return $this->antigo_id;
    }

    function setAntigo_id($antigo_id) {
        $this->antigo_id = $antigo_id;
    }

}
