<?php

namespace Application\Model\Entity;

/**
 * Nome: MetasOrdenacaoTipo.php
 * @author Ivan Tavares <ivanlsjt@gmail.com>
 * Descricao: Entidade anotada da tabela metas_ordenacao_tipo 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="metas_ordenacao_tipo ")
 */
class MetasOrdenacaoTipo extends CircuitoEntity {

     const obreiro = 1;
     const diacono = 2;
     const missionario = 3;
     const pastor = 4;
     const bispo = 5;


    /**
     * @ORM\OneToMany(targetEntity="GrupoMetasOrdenacao", mappedBy="metasOrdenacaoTipo") 
     */
    protected $grupoMetasOrdenacao;

    public function __construct() {
        $this->grupoMetasOrdenacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getGrupoMetasOrdenacao() {
        return $this->grupoMetasOrdenacao;
    }

    function getNome() {
        return $this->nome;
    }

    function setGrupoMetasOrdenacao($grupoMetasOrdenacao) {
        $this->grupoMetasOrdenacao = $grupoMetasOrdenacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
