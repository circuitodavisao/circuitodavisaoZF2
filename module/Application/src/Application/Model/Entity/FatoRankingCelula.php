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

}
