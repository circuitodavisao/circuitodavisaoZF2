<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoRankingCelula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_ranking_celula 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_ranking_celula")
 */
class FatoRankingCelula extends CircuitoEntity {

	/** @ORM\Column(type="integer") */
	protected $grupo_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_equipe_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_evento_id;

	/** @ORM\Column(type="integer") */
	protected $valor;

	/** @ORM\Column(type="integer") */
	protected $p1;

	/** @ORM\Column(type="integer") */
	protected $p2;

	/** @ORM\Column(type="integer") */
	protected $p3;

	/** @ORM\Column(type="integer") */
	protected $p4;

	/** @ORM\Column(type="integer") */
	protected $p5;

	/** @ORM\Column(type="integer") */
	protected $p6;

	/** @ORM\Column(type="integer") */
	protected $mes;

	/** @ORM\Column(type="integer") */
	protected $ano;

	function getGrupo_id() {
		return $this->grupo_id;
	}

	function setGrupo_id($grupo_id) {
		$this->grupo_id = $grupo_id;
	}

	function getGrupo_evento_id(){
		return $this->grupo_evento_id;
	}

	function setGrupo_evento_id($grupo_evento_id){
		$this->grupo_evento_id = $grupo_evento_id;
	}

	function getValor(){
		return $this->valor;
	}

	function setValor($valor){
		$this->valor = $valor;
	}

	function getMes(){
		return $this->mes;
	}

	function setMes($mes){
		$this->mes = $mes;
	}

	function getAno(){
		return $this->ano;
	}

	function setAno($ano){
		$this->ano = $ano;
	}

	function getGrupo_equipe_id(){
		return $this->grupo_equipe_id;
	}

	function setGrupo_equipe_id($grupo_equipe_id){
		$this->grupo_equipe_id = $grupo_equipe_id;
	}

	function getP1(){
		return $this->p1;
	}

	function setP1($p1){
		if($p1 == ''){
			$p1 = 0;
		}
		$this->p1 = $p1;
	}


	function getP2(){
		return $this->p2;
	}

	function setP2($p2){
		if($p2 == ''){
			$p2 = 0;
		}
		$this->p2 = $p2;
	}


	function getP3(){
		return $this->p3;
	}

	function setP3($p3){
		if($p3 == ''){
			$p3 = 0;
		}
		$this->p3 = $p3;
	}


	function getP4(){
		return $this->p4;
	}

	function setP4($p4){
		if($p4 == ''){
			$p4 = 0;
		}
		$this->p4 = $p4;
	}


	function getP5(){
		return $this->p5;
	}

	function setP5($p5){
		if($p5 == ''){
			$p5 = 0;
		}
		$this->p5 = $p5;
	}


	function getP6(){
		return $this->p6;
	}

	function setP6($p6){
		if($p6 == ''){
			$p6 = 0;
		}
		$this->p6 = $p6;
	}

}
