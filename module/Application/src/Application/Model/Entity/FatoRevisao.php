<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoRevisao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_revisao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_revisao")
 */
class FatoRevisao extends CircuitoEntity {

	/** @ORM\Column(type="string") */
	protected $email_revisao;

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="integer") */
	protected $matricula;

	/** @ORM\Column(type="integer") */
	protected $evento_id;

	/** @ORM\Column(type="integer") */
	protected $idade;

	/** @ORM\Column(type="string") */
	protected $nome;

	/** @ORM\Column(type="string") */
	protected $nome_igreja;

	/** @ORM\Column(type="string") */
	protected $nome_equipe;

	/** @ORM\Column(type="string") */
	protected $data_nascimento;

	/** @ORM\Column(type="string") */
	protected $data_revisao;

	/** @ORM\Column(type="string") */
	protected $ativo;

	/** @ORM\Column(type="string") */
	protected $sexo;

	/** @ORM\Column(type="string") */
	protected $lideres;

	/** @ORM\Column(type="integer") */
	protected $tipo;

	/** @ORM\Column(type="string") */
	protected $entidade;

	/** @ORM\Column(type="string") */
	protected $hierarquia;

	function getNumero_identificador() {
		return $this->numero_identificador;
	}

	function setNumero_identificador($numero_identificador) {
		$this->numero_identificador = $numero_identificador;
	}

	function setMatricula($i) {
		$this->matricula = $i;
	}

	function setEvento_id($i) {
		$this->evento_id = $i;
	}

	function setIdade($i) {
		$this->idade = $i;
	}

	function setNome($i) {
		$this->nome = $i;
	}

	function setNome_equipe($i) {
		$this->nome_equipe = $i;
	}

	function setNome_igreja($i) {
		$this->nome_igreja = $i;
	}

	function setData_nascimento($i) {
		$this->data_nascimento = $i;
	}

	function setData_revisao($i) {
		$this->data_revisao = $i;
	}

	function setAtivo($i) {
		$this->ativo = $i;
	}

	function setSexo($i) {
		$this->sexo = $i;
	}

	function setLideres($i) {
		$this->lideres = $i;
	}

	function setTipo($i) {
		$this->tipo = $i;
	}

	function setEntidade($i) {
		$this->entidade = $i;
	}

	function setHierarquia($i) {
		$this->hierarquia = $i;
	}

	function getMatricula() {
		return $this->matricula;
	}

	function getEvento_id() {
		return $this->evento_id;
	}

	function getIdade() {
		return $this->idade;
	}

	function getNome() {
		return $this->nome;
	}

	function getNome_equipe() {
		return $this->nome_equipe;
	}

	function getNome_igreja() {
		return $this->nome_igreja;
	}

	function getData_nascimento() {
		return $this->data_nascimento;
	}

	function getData_revisao() {
		return $this->data_revisao;
	}

	function getAtivo() {
		return $this->ativo;
	}

	function getSexo() {
		return $this->sexo;
	}

	function getLideres() {
		return $this->lideres;
	}

	function getTipo() {
		return $this->tipo;
	}
	
	function getEntidade() {
		return $this->entidade;
	}

	function getHierarquia() {
		return $this->hierarquia;
	}

	function setEmail_revisao($i) {
		$this->email_revisao = $i;
	}

	function getEmail_revisao() {
		return $this->email_revisao;
	}

	public function __set($name,$value) {
		$functionname='set'.ucfirst($name);
		return $this->$functionname($value);
	}
	public function __get($name) {
		$functionname='get'.ucfirst($name);
		return $this->$functionname();
	}

}
