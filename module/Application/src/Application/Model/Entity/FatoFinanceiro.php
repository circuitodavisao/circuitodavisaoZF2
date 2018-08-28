<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoFinanceiro.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_financeiro
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_financeiro")
 */
class FatoFinanceiro extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="fatoFinanceiro")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

	/**
	 * @ORM\ManyToOne(targetEntity="FatoFinanceiroTipo", inversedBy="fatoFinanceiro")
	 * @ORM\JoinColumn(name="fato_financeiro_tipo_id", referencedColumnName="id")
	 */
	private $fatoFinanceiroTipo;

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="integer") */
	protected $pessoa_id;

	/** @ORM\Column(type="integer") */
	protected $fato_financeiro_tipo_id;

	/** @ORM\Column(type="decimal") */
	protected $valor;

	/** @ORM\Column(type="string") */
	protected $data;

	private $grupo;

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

	function setData($data){
		$this->data = $data;
	}
	
	function getData(){
		return $this->data;
	}

	function setGrupo($grupo){
		$this->grupo = $grupo;
	}

	function getGrupo(){
		return $this->grupo;
	}

	function setPessoa($pessoa){
		$this->pessoa = $pessoa;
	}

	function getPessoa(){
		return $this->pessoa;
	}

	function setPessoa_id($pessoa_id){
		$this->pessoa_id = $pessoa_id;
	}

	function getPessoa_id(){
		return $this->pessoa_id;
	}

	function setFato_financeiro_tipo_id($fato_financeiro_tipo_id){
		$this->fato_financeiro_tipo_id = $fato_financeiro_tipo_id;
	}

	function getFato_financeiro_tipo_id(){
		return $this->fato_financeiro_tipo_id;
	}

	function setFatoFinanceiroTipo($fatoFinanceiroTipo){
		$this->fatoFinanceiroTipo = $fatoFinanceiroTipo;
	}

	function getFatoFinanceiroTipo(){
		return $this->fatoFinanceiroTipo;
	}
}
