<?php

namespace Application\Model\Entity;

/**
 * Nome: EntidadeTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela entidade_tipo 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="entidade_tipo")
 */
class EntidadeTipo extends CircuitoEntity {

    const presidencial = 1;
    const nacional = 2;
    const regiao = 3;
    const coordenacao = 4;
    const igreja = 5;
    const equipe = 6;
    const subEquipe = 7;

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
