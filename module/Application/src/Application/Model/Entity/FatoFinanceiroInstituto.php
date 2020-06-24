<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_financeiro_instituto")
 */
class FatoFinanceiroInstituto extends CircuitoEntity {

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="integer") */
	protected $turma_pessoa_id;

	/** @ORM\Column(type="decimal") */
	protected $valor;

	/** @ORM\Column(type="integer") */
	protected $mes;

	/** @ORM\Column(type="integer") */
	protected $ano;

	function getNumero_identificador() {
		return $this->numero_identificador;
	}

	function setNumero_identificador($numero_identificador) {
		$this->numero_identificador = $numero_identificador;
	}

	function setValor($valor){
		$this->valor = $valor;
	}

	function getValor(){
		return $this->valor;
	}

	function setTurma_pessoa_id($i){
		$this->turma_pessoa_id = $i;
	}

	function getTurma_pessoa_id(){
		return $this->turma_pessoa_id;
	}

	function setMes($i){
		$this->mes = $i;
	}
	function setAno($i){
		$this->ano = $i;
	}

	function getMes(){
		return $this->mes;
	}
	function getAno(){
		return $this->ano;
	}
}
