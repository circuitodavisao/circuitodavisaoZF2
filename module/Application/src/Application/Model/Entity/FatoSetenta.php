<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoSetenta.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_setenta 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_setenta")
 */
class FatoSetenta extends CircuitoEntity {

	/** @ORM\Column(type="integer") */
	protected $grupo_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_igreja_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_equipe_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_evento_id;

	/** @ORM\Column(type="string") */
	protected $setenta;

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
	protected $v1;

	/** @ORM\Column(type="integer") */
	protected $v2;

	/** @ORM\Column(type="integer") */
	protected $v3;

	/** @ORM\Column(type="integer") */
	protected $v4;

	/** @ORM\Column(type="integer") */
	protected $v5;

	/** @ORM\Column(type="integer") */
	protected $v6;

	/** @ORM\Column(type="decimal") */
	protected $pd1;

	/** @ORM\Column(type="decimal") */
	protected $pd2;

	/** @ORM\Column(type="decimal") */
	protected $pd3;

	/** @ORM\Column(type="decimal") */
	protected $pd4;

	/** @ORM\Column(type="decimal") */
	protected $pd5;

	/** @ORM\Column(type="decimal") */
	protected $pd6;

	/** @ORM\Column(type="string") */
	protected $e1;

	/** @ORM\Column(type="string") */
	protected $e2;

	/** @ORM\Column(type="string") */
	protected $e3;

	/** @ORM\Column(type="string") */
	protected $e4;

	/** @ORM\Column(type="string") */
	protected $e5;

	/** @ORM\Column(type="string") */
	protected $e6;

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

	function getGrupo_igreja_id() {
		return $this->grupo_igreja_id;
	}

	function setGrupo_igreja_id($grupo_igreja_id) {
		$this->grupo_igreja_id = $grupo_igreja_id;
	}

	function getGrupo_evento_id(){
		return $this->grupo_evento_id;
	}

	function setGrupo_evento_id($grupo_evento_id){
		$this->grupo_evento_id = $grupo_evento_id;
	}

	function getSetenta(){
		return $this->setenta;
	}

	function setSetenta($setenta){
		$this->setenta = $setenta;
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


	function getV1(){
		return $this->v1;
	}

	function setV1($v1){
		if($v1 == ''){
			$v1 = 0;
		}
		$this->v1 = $v1;
	}


	function getV2(){
		return $this->v2;
	}

	function setV2($v2){
		if($v2 == ''){
			$v2 = 0;
		}
		$this->v2 = $v2;
	}


	function getV3(){
		return $this->v3;
	}

	function setV3($v3){
		if($v3 == ''){
			$v3 = 0;
		}
		$this->v3 = $v3;
	}


	function getV4(){
		return $this->v4;
	}

	function setV4($v4){
		if($v4 == ''){
			$v4 = 0;
		}
		$this->v4 = $v4;
	}


	function getV5(){
		return $this->v5;
	}

	function setV5($v5){
		if($v5 == ''){
			$v5 = 0;
		}
		$this->v5 = $v5;
	}


	function getV6(){
		return $this->v6;
	}

	function setV6($v6){
		if($v6 == ''){
			$v6 = 0;
		}
		$this->v6 = $v6;
	}

	function getPd1(){
		return $this->pd1;
	}

	function setPd1($pd1){
		if($pd1 == ''){
			$pd1 = 0;
		}
		$this->pd1 = $pd1;
	}


	function getPd2(){
		return $this->pd2;
	}

	function setPd2($pd2){
		if($pd2 == ''){
			$pd2 = 0;
		}
		$this->pd2 = $pd2;
	}


	function getPd3(){
		return $this->pd3;
	}

	function setPd3($pd3){
		if($pd3 == ''){
			$pd3 = 0;
		}
		$this->pd3 = $pd3;
	}


	function getPd4(){
		return $this->pd4;
	}

	function setPd4($pd4){
		if($pd4 == ''){
			$pd4 = 0;
		}
		$this->pd4 = $pd4;
	}


	function getPd5(){
		return $this->pd5;
	}

	function setPd5($pd5){
		if($pd5 == ''){
			$pd5 = 0;
		}
		$this->pd5 = $pd5;
	}


	function getPd6(){
		return $this->pd6;
	}

	function setPd6($pd6){
		if($pd6 == ''){
			$pd6 = 0;
		}
		$this->pd6 = $pd6;
	}

	function getE1(){
		return $this->e1;
	}

	function setE1($e1){
		if($e1 == ''){
			$e1 = 0;
		}
		$this->e1 = $e1;
	}


	function getE2(){
		return $this->e2;
	}

	function setE2($e2){
		if($e2 == ''){
			$e2 = 0;
		}
		$this->e2 = $e2;
	}


	function getE3(){
		return $this->e3;
	}

	function setE3($e3){
		if($e3 == ''){
			$e3 = 0;
		}
		$this->e3 = $e3;
	}


	function getE4(){
		return $this->e4;
	}

	function setE4($e4){
		if($e4 == ''){
			$e4 = 0;
		}
		$this->e4 = $e4;
	}


	function getE5(){
		return $this->e5;
	}

	function setE5($e5){
		if($e5 == ''){
			$e5 = 0;
		}
		$this->e5 = $e5;
	}


	function getE6(){
		return $this->e6;
	}

	function setE6($e6){
		if($e6 == ''){
			$e6 = 0;
		}
		$this->e6 = $e6;
	}

}
