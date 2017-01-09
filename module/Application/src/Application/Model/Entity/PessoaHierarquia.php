<?php

namespace Application\Model\Entity;

/**
 * Nome: PessoaHierarquia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa_hierarquia
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="pessoa_hierarquia")
 */
class PessoaHierarquia extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Hierarquia", inversedBy="pessoaHierarquia")
     * @ORM\JoinColumn(name="hierarquia_id", referencedColumnName="id")
     */
    private $hierarquia;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="pessoaHierarquia")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /** @ORM\Column(type="integer") */
    protected $hierarquia_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    function getHierarquia() {
        return $this->hierarquia;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getHierarquia_id() {
        return $this->hierarquia_id;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function setHierarquia($hierarquia) {
        $this->hierarquia = $hierarquia;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setHierarquia_id($hierarquia_id) {
        $this->hierarquia_id = $hierarquia_id;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

}
