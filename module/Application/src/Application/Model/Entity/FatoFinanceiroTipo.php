<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoFinanceiroTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: FatoFinanceiro anotada da tabela fato_financeiro_tipo 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_financeiro_tipo")
 */
class FatoFinanceiroTipo extends CircuitoEntity {

    const parceiroDeDeusIndividual = 1;
    const parceiroDeDeusCelula = 2;

    /**
     * @ORM\OneToMany(targetEntity="FatoFinanceiro", mappedBy="fatoFinanceiroTipo") 
     */
    protected $fatoFinanceiro;

    public function __construct() {
        $this->fatoFinanceiro = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getFatoFinanceiro() {
        return $this->fatoFinanceiro;
    }

    function getNome() {
        return $this->nome;
    }

    function setFatoFinanceiro($fatoFinanceiro) {
        $this->fatoFinanceiro = $fatoFinanceiro;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
