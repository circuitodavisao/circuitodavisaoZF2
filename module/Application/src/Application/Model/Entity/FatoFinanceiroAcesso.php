<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoFinanceiroAcesso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: FatoFinanceiro anotada da tabela fato_financeiro_acesso 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_financeiro_acesso")
 */
class FatoFinanceiroAcesso extends CircuitoEntity {

    const SECRETARIO_PARCEIRO_DE_DEUS = 1;

    /**
     * @ORM\OneToMany(targetEntity="PessoaFatoFinanceiroAcesso", mappedBy="fatoFinanceiroAcesso") 
     */
    protected $pessoaFatoFinanceiroAcesso;

    public function __construct() {
        $this->pessoaFatoFinanceiroAcesso = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getPessoaFatoFinanceiroAcesso() {
        return $this->pessoaFatoFinanceiroAcesso;
    }

    function getNome() {
        return $this->nome;
    }

    function setPessoaFatoFinanceiroAcesso($pessoaFatoFinanceiroAcesso) {
        $this->pessoaFatoFinanceiroAcesso = $pessoaFatoFinanceiroAcesso;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
