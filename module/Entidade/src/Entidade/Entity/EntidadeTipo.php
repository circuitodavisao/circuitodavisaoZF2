<?php

namespace Entidade\Entity;

/**
 * Nome: EntidadeTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela entidade_tipo 
 * 1 - PRESIDENCIAL
 * 2 - REGIÃO
 * 3 - SUB REGIÃO
 * 4 - COORDENAÇÃO
 * 5 - SUB COORDENAÇÃO
 * 6 - IGREJA
 * 7 - EQUIPE
 * 8 - SUB EQUIPE
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="entidade_tipo")
 */
class EntidadeTipo {

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="entidadeTipo") 
     */
    protected $entidade;

    public function __construct() {
        $this->entidade = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getEntidade() {
        return $this->entidade;
    }

    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getNome() {
        return $this->nome;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

}
