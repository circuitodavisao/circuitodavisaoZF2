<?php

namespace Application\Model\Entity;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\OneToMany(targetEntity="TurmaAulaLiberacao", mappedBy="turmaAula", fetch="EXTRA_LAZY")
     */
    protected $turmaAulaLiberacao;

	public function __construct() {
		$this->turmaAulaLiberacao = new ArrayCollection();
	}

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

    /** @ORM\Column(type="string") */
    protected $url1;
    /** @ORM\Column(type="string") */
    protected $url2;
    /** @ORM\Column(type="string") */
    protected $url3;
    /** @ORM\Column(type="string") */
    protected $url4;
    /** @ORM\Column(type="string") */
    protected $url5;
    /** @ORM\Column(type="string") */
    protected $url6;
    /** @ORM\Column(type="string") */
    protected $url7;

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

	function setUrl1($i){
		$this->url1 = $i;
	}
	function setUrl2($i){
		$this->url2 = $i;
	}
	function setUrl3($i){
		$this->url3 = $i;
	}
	function setUrl4($i){
		$this->url4 = $i;
	}
	function setUrl5($i){
		$this->url5 = $i;
	}
	function setUrl6($i){
		$this->url6 = $i;
	}
	function setUrl7($i){
		$this->url7 = $i;
	}
    function getUrl1() {
        return $this->url1;
    }
    function getUrl2() {
        return $this->url2;
    }
    function getUrl3() {
        return $this->url3;
    }
    function getUrl4() {
        return $this->url4;
    }
    function getUrl5() {
        return $this->url5;
    }
    function getUrl6() {
        return $this->url6;
    }
    function getUrl7() {
        return $this->url7;
    }

	function getTurmaAulaLiberacao(){
		return $this->turmaAulaLiberacao;
	}

	function setTurmaAulaLiberacao($i){
		$this->turmaAulaLiberacao = $i;
	}

}
