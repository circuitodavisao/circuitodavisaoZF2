<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoCurso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_curso
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_curso")
 */
class FatoCurso extends CircuitoEntity {

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="integer") */
	protected $turma_pessoa_id;

	/** @ORM\Column(type="integer") */
	protected $turma_id;
	
	/** @ORM\Column(type="integer") */
	protected $situacao_id;

	function getNumero_identificador() {
		return $this->numero_identificador;
	}

	function setNumero_identificador($numero_identificador) {
		$this->numero_identificador = $numero_identificador;
	}

	function setTurma_pessoa_id($turma_pessoa_id){
		$this->turma_pessoa_id = $turma_pessoa_id;
	}

	function getTurma_pessoa_id(){
		return $this->turma_pessoa_id;
	}

	function setTurma_id($turma_id){
		$this->turma_id = $turma_id;
	}
	
	function getTurma_id(){
		return $this->turma_id;
	}

	function setSituacao_id($situacao_id){
		$this->situacao_id = $situacao_id;
	}
	
	public function getSituacao_id(){
		return $this->situacao_id;
	}
}
