<?php

namespace Application\Model\Entity;

/**
 * Nome: MetasOrdenacaoCriterio.php
 * @author Ivan Tavares <ivanlsjt@gmail.com>
 * Descricao: Entidade anotada da tabela metas_ordenacao_criterio
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="metas_ordenacao_criterio ")
 */
class MetasOrdenacaoCriterio extends CircuitoEntity {

     const lideres = 1;
     const membresia = 2;
     const pessoasEmCelula = 3;
     const parceiroDeDeus = 4;
     const igrejas = 5;
    

    /**
     * @ORM\OneToMany(targetEntity="GrupoMetasOrdenacao", mappedBy="metasOrdenacaoCriterio") 
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
