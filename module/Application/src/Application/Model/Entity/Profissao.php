<?php

namespace Application\Model\Entity;

/**
 * Nome: Profissao.php
 * @author Ivan Leite de São José Tavares <ivanlsjt@gmail.com>
 * Descricao: Entidade anotada da tabela profissao  
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="profissao")
 */
class Profissao extends CircuitoEntity {
        
    /**
     * @ORM\OneToMany(targetEntity="Pessoa", mappedBy="profissao") 
     */
    protected $pessoa;
   
    /** @ORM\Column(type="string") */
    protected $nome;
       
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }    

    function getPessoa() {
        return $this->pessoa;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }    

}
