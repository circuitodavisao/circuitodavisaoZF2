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
		$label = '';
		switch($this->lista){
		case 1: $label = 'AGENDA DELMASSO'; break;
		case 2: $label = 'CV NOVO'; break;
		case 3: $label = 'CV ANTIGO'; break;
		case 4: $label = 'RUA'; break;
		case 5: $label = 'FIEL'; break;
		case 6: $label = 'EPLEPSIA'; break;
		case 7: $label = 'GUARA'; break;
		case 8: $label = 'TESTE INTERNO'; break;
		case 9: $label = '2014'; break;
		case 10: $label = 'AUDIENCIA'; break;
		case 11: $label = 'PEDOFILIA'; break;
		case 12: $label = 'TAXISTA'; break;
		case 13: $label = 'GABINETE'; break;
		case 14: $label = 'OUTROS'; break;
		}
		return $label;
	}
}
