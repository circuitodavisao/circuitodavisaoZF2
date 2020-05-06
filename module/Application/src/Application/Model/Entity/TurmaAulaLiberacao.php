<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaAulaLiberacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_aula_liberacao
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_aula_liberacao")
 */
class TurmaAulaLiberacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="turmaAulaLiberacao")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="TurmaAula", inversedBy="turmaAulaLiberacao")
     * @ORM\JoinColumn(name="turma_aula_id", referencedColumnName="id")
     */
    private $turmaAula;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $turma_aula_id;

    /** @ORM\Column(type="string") */
    protected $chave;

	function getChave(){
		return $this->chave;
	}

	function setChave($i){
		$this->chave = $i;
	}

    function getPessoa() {
        return $this->pessoa;
    }

    function getTurmaAula() {
        return $this->turmaAula;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getTurma_aula_id() {
        return $this->turma_aula_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setTurma_aula_id($i) {
        $this->turma_aula_id = $i;
    }

    function setTurmaAula($turma) {
        $this->turmaAula = $turma;
    }

}
