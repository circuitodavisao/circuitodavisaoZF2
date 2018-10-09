<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoFinanceiroSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_financeiro_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_financeiro_situacao")
 */
class FatoFinanceiroSituacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="FatoFinanceiro", inversedBy="fatoFinanceiroSituacao")
     * @ORM\JoinColumn(name="fato_financeiro_id", referencedColumnName="id")
     */
    private $fatoFinanceiro;

    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="fatoFinanceiroSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="fatoFinanceiroSituacao")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /** @ORM\Column(type="integer") */
    protected $fato_financeiro_id;

    /** @ORM\Column(type="integer") */
    protected $situacao_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    function getPessoa() {
        return $this->pessoa;
    }

	function setPessoa($pessoa){
		$this->pessoa = $pessoa;
	}

	function setPessoa_id($pessoa_id){
		$this->pessoa_id = $pessoa_id;
	}

	function getPessoa_id(){
		return $this->pessoa_id;
	}

    function getFatoFinanceiro() {
        return $this->fatoFinanceiro;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function getFato_financeiro_id() {
        return $this->fato_financeiro_id;
    }

    function getSituacao_id() {
        return $this->situacao_id;
    }

    function setFatoFinanceiro($fatoFinanceiro) {
        $this->fatoFinanceiro = $fatoFinanceiro;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function setFato_financeiro_id($fato_financeiro_id) {
        $this->fato_financeiro_id = $fato_financeiro_id;
    }

    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }
}
