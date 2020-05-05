<?php

namespace Application\Model\Entity;

/**
 * Nome: Turma.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Model\Entity\Situacao;

/**
 * @ORM\Entity
 * @ORM\Table(name="turma")
 */
class Turma extends CircuitoEntity {

	/**
	 * @ORM\OneToMany(targetEntity="TurmaPessoa", mappedBy="turma", fetch="EXTRA_LAZY")
	 */
	protected $turmaPessoa;

	/**
	 * @ORM\OneToMany(targetEntity="TurmaProfessor", mappedBy="turma", fetch="EXTRA_LAZY")
	 */
	protected $turmaProfessor;

	/**
	 * @ORM\OneToMany(targetEntity="TurmaAula", mappedBy="turma", fetch="EXTRA_LAZY")
	 */
	protected $turmaAula;

	/** @ORM\Column(type="integer") */
	protected $mes;

	/** @ORM\Column(type="integer") */
	protected $ano;

	/** @ORM\Column(type="string") */
	protected $observacao;

	/** @ORM\Column(type="integer") */
	protected $grupo_id;

	/** @ORM\Column(type="integer") */
	protected $curso_id;

	/**
	 * @ORM\ManyToOne(targetEntity="Curso", inversedBy="turma")
	 * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
	 */
	private $curso;

	/**
	 * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="turma")
	 * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
	 */
	private $grupo;

	public function getTurmaAulaAtiva() {
		$entidadeAtiva = null;
		foreach ($this->getTurmaAula() as $entidade) {
			if ($entidade->verificarSeEstaAtivo()) {
				$entidadeAtiva = $entidade;
				break;
			}
		}
		return $entidadeAtiva;
	}

	public function __construct() {
		$this->turmaPessoa = new ArrayCollection();
		$this->turmaAula = new ArrayCollection();
	}

	function getTurmaPessoa() {
		return $this->turmaPessoa;
	}

	function getData_revisao() {
		return $this->data_revisao;
	}

	function setTurmaPessoa($turmaPessoa) {
		$this->turmaPessoa = $turmaPessoa;
	}

	function setData_revisao($data_revisao) {
		$this->data_revisao = $data_revisao;
	}

	function getMes() {
		return $this->mes;
	}

	function getAno() {
		return $this->ano;
	}

	function getObservacao() {
		return $this->observacao;
	}

	function setMes($mes) {
		$this->mes = $mes;
	}

	function setAno($ano) {
		$this->ano = $ano;
	}

	function setObservacao($observacao) {
		$this->observacao = $observacao;
	}

	function setCurso($curso){
		$this->curso = $curso;
	}
	function getCurso(){
		return $this->curso;
	}
	function getGrupo() {
		return $this->grupo;
	}

	function setGrupo($grupo) {
		$this->grupo = $grupo;
	}

	function getGrupo_id() {
		return $this->grupo_id;
	}

	function getCurso_id() {
		return $this->curso_id;
	}

	function setGrupo_id($grupo_id) {
		$this->grupo_id = $grupo_id;
	}

	function setCurso_id($curso_id) {
		$this->curso_id = $curso_id;
	}

	function getTurmaAula() {
		return $this->turmaAula;
	}

	function setTurmaAula($turmaAula) {
		$this->turmaAula = $turmaAula;
	}

	function getTurmaProfessor() {
		return $this->turmaProfessor;
	}

	function setTurmaProfessor($i) {
		$this->turmaProfessor = $i;
	}

}
