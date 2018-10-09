<?php

namespace Application\Model\Entity;

/**
 * Nome: PessoaFatoFinanceiroAcesso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa_fato_financeiro_acesso
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="pessoa_fato_financeiro_acesso")
 */
class PessoaFatoFinanceiroAcesso extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="FatoFinanceiroAcesso", inversedBy="pessoaFatoFinanceiroAcesso")
     * @ORM\JoinColumn(name="fato_financeiro_acesso_id", referencedColumnName="id")
     */
    private $fatoFinanceiroAcesso;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="pessoaFatoFinanceiroAcesso")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="pessoaFatoFinanceiroAcesso")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
	private $grupo;

	/** @ORM\Column(type="integer") */
    protected $fato_financeiro_acesso_id;

	/** @ORM\Column(type="integer") */
    protected $pessoa_id;

	/** @ORM\Column(type="integer") */
    protected $grupo_id;

	function setFato_financeiro_acesso_id($fato_financeiro_acesso_id){
		$this->fato_financeiro_acesso_id = $fato_financeiro_acesso_id;
	}

	function getFato_financeiro_acesso_id(){
		return $this->fato_financeiro_acesso_id;
	}

	function setPessoa_id($pessoa_id){
		$this->pessoa_id = $pessoa_id;
	}

	function getPessoa_id(){
		return $this->pessoa_id;
	}

	function setGrupo_id($grupo_id){
		$this->grupo_id = $grupo_id;
	}

	function getGrupo_id(){
		return $this->grupo_id;
	}

    function getFatoFinanceiroAcesso() {
        return $this->fatoFinanceiroAcesso;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function setFatoFinanceiroAcesso($fatoFinanceiroAcesso) {
        $this->fatoFinanceiroAcesso = $fatoFinanceiroAcesso;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

}
