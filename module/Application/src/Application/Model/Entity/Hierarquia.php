<?php

namespace Application\Model\Entity;

/**
 * Nome: Hierarquia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela hierarquia 
 * 1 - BISPO
 * 2 - PASTOR
 * 3 - MISSIONARIO
 * 4 - DIACONO
 * 5 - OBREIRO
 * 6 - LIDER DE CELULA
 * 7 - LIDER EM TREINAMENTO
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="hierarquia")
 */
class Hierarquia extends CircuitoEntity {

    const BISPO = 1;
    const PASTOR = 2;
    const MISSIONARIO = 3;
    const DIACONO = 4;
    const OBREIRO = 5;
    const LIDER_DE_CELULA = 6;
    const LIDER_EM_TREINAMENTO = 7;

    /**
     * @ORM\OneToMany(targetEntity="PessoaHierarquia", mappedBy="hierarquia") 
     */
    protected $pessoaHierarquia;

    public function __construct() {
        $this->pessoaHierarquia = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $sigla;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getSigla() {
        return $this->sigla;
    }

    function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    function getPessoaHierarquia() {
        return $this->pessoaHierarquia;
    }

    function setPessoaHierarquia($pessoaHierarquia) {
        $this->pessoaHierarquia = $pessoaHierarquia;
    }

}
