<?php

namespace Application\Model\Entity;

/**
 * Nome: DimensaoTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela dimensao_tipo 
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="dimensao_tipo")
 */
class DimensaoTipo extends CircuitoEntity {

    const CELULA = 1;
    const CULTO = 2;
    const ARENA = 3;
    const DOMINGO = 4;

    /**
     * @ORM\OneToMany(targetEntity="Dimensao", mappedBy="dimensaoTipo") 
     */
    protected $dimensao;

    public function __construct() {
        $this->dimensao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getDimensao() {
        return $this->dimensao;
    }

    function setDimensao($dimensao) {
        $this->dimensao = $dimensao;
    }

}
