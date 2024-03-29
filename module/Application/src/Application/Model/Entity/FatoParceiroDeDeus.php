<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoParceiroDeDeus.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_parceiro_de_deus
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_parceiro_de_deus")
 */
class FatoParceiroDeDeus extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="fatoParceiroDeDeus")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

	/** @ORM\Column(type="string") */
	protected $numero_identificador;

	/** @ORM\Column(type="integer") */
	protected $evento_id;

	/** @ORM\Column(type="integer") */
	protected $pessoa_id;

	/** @ORM\Column(type="decimal") */
	protected $individual;

	/** @ORM\Column(type="decimal") */
	protected $celula;

	/** @ORM\Column(type="string") */
	protected $data;

	private $grupo;

	function getNumero_identificador() {
		return $this->numero_identificador;
	}

	function setNumero_identificador($numero_identificador) {
		$this->numero_identificador = $numero_identificador;
	}

	function setEvento_id($evento_id){
		$this->evento_id = $evento_id;
	}
	function getEvento_id(){
		return $this->evento_id;
	}
	function setIndividual($individual){
		$this->individual = $individual;
	}
	function getIndividual(){
		return $this->individual;
	}
	function setCelula($celula){
		$this->celula = $celula;
	}
	function getCelula(){
		return $this->celula;
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
}
