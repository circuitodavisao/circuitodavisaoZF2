<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoRanking.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_discipulado
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_discipulado")
 */
class FatoDiscipulado extends CircuitoEntity {

    /**
     * @ORM\OneToOne(targetEntity="Grupo")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

	/** @ORM\Column(type="integer") */
	protected $grupo_id;

	/** @ORM\Column(type="integer") */
	protected $grupo_evento_id;

	/** @ORM\Column(type="integer") */
	protected $pessoa_id;

	/** @ORM\Column(type="integer") */
	protected $mes;

    /** @ORM\Column(type="integer") */
    protected $ano;

    /** @ORM\Column(type="integer") */
    protected $lanche;

    /** @ORM\Column(type="integer") */
    protected $avisos;

    /** @ORM\Column(type="integer") */
    protected $administrativo;

    /** @ORM\Column(type="integer") */
    protected $oracao;

    /** @ORM\Column(type="integer") */
    protected $palavra;

    /** @ORM\Column(type="string") */
    protected $observacao;
 
    function getGrupo() {
        return $this->grupo;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

	function getGrupo_id(){
		return $this->grupo_id;
	}

	function setGrupo_id($grupo_id){
		$this->grupo_id = $grupo_id;
	}

	function getGrupo_evento_id(){
		return $this->grupo_evento_id;
	}

	function setGrupo_evento_id($grupo_evento_id){
		$this->grupo_evento_id = $grupo_evento_id;
	}

	function getPessoa_id(){
		return $this->pessoa_id;
	}

	function setPessoa_id($pessoa_id){
		$this->pessoa_id = $pessoa_id;
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

	function getLanche(){
		return $this->lanche;
	}

	function setLanche($lanche){
		$this->lanche = $lanche;
	}

	function getAvisos(){
		return $this->avisos;
	}

	function setAvisos($avisos){
		$this->avisos = $avisos;
	}

	function getAdministrativo(){
		return $this->administrativo;
	}

	function setAdministrativo($administrativo){
		$this->administrativo = $administrativo;
	}

	function getOracao(){
		return $this->oracao;
	}

	function setOracao($oracao){
		$this->oracao = $oracao;
	}

	function getPalavra(){
		return $this->palavra;
	}

	function setPalavra($palavra){
		$this->palavra = $palavra;
	}

	function getObservacao(){
		return $this->observacao;
	}

	function setObservacao($observacao){
		$this->observacao = $observacao;
	}

}
