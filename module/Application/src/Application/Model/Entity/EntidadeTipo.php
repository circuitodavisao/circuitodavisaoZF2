<?php

namespace Application\Model\Entity;

/**
 * Nome: EntidadeTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela entidade_tipo 
 * 1 - PRESIDENCIAL* 1 - PRESIDENCIAL
 * 2 - NACIONAL
 * 3 - REGIÃO
 * 4 - SUB REGIÃO
 * 5 - COORDENAÇÃO
 * 6 - SUB COORDENAÇÃO
 * 7 - IGREJA
 * 8 - EQUIPE
 * 9 - SUB EQUIPE
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="entidade_tipo")
 */
class EntidadeTipo extends CircuitoEntity {

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="entidadeTipo") 
     */
    protected $entidade;

    public function __construct() {
        $this->entidade = new ArrayCollection();
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
