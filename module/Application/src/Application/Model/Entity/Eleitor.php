<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="eleitor")
 */
class Eleitor extends CircuitoEntity {

	/** @ORM\Column(type="string") */
	protected $nome;

	/** @ORM\Column(type="integer") */
	protected $telefone;

	/** @ORM\Column(type="string") */
	protected $igreja;

	/** @ORM\Column(type="string") */
	protected $equipe;

	/** @ORM\Column(type="integer") */
	protected $situacao;

	/** @ORM\Column(type="integer") */
	protected $lista;
	
	public function setNome($nome){
		$this->nome = $nome;
	}	

	public function getNome(){
		return $this->nome;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}	

	public function getTelefone(){
		return $this->telefone;
	}

	public function setIgreja($igreja){
		$this->igreja = $igreja;
	}	

	public function getIgreja(){
		return $this->igreja;
	}

	public function setEquipe($equipe){
		$this->equipe = $equipe;
	}	

	public function getEquipe(){
		return $this->equipe;
	}

	public function setSituacao($situacao){
		$this->situacao = $situacao;
	}

	public function getSituacao(){
		return $this->situacao;
	}

	public function setLista($lista){
		$this->lista = $lista;
	}

	public function getLista(){
		return $this->lista;
	}
}
