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

    /**
     * @ORM\OneToMany(targetEntity="PessoaHierarquia", mappedBy="hierarquia") 
     */
    protected $pessoaHierarquia;

    public function __construct() {
        $this->pessoaHierarquia = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getEntidade() {
        return $this->entidade;
    }

    function getNome() {
        return $this->nome;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
