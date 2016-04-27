<?php

namespace Entidade\Entity;

/**
 * Nome: GrupoResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_responsavel
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_pai_filho")
 */
class GrupoPaiFilho {

    /**
     * @ORM\OneToMany(targetEntity="Grupo", mappedBy="pai") 
     */
    protected $pai;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="filhos") 
     * @ORM\JoinColumn(name="filho_id", referencedColumnName="id")
     */
    protected $filhos;

    public function __construct() {
        $this->pai = new ArrayCollection();
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

    /** @ORM\Column(type="integer") */
    protected $pai_id;

    /** @ORM\Column(type="integer") */
    protected $filho_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getPai() {
        return $this->pai;
    }

    function getFilhos() {
        return $this->filhos;
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

    function getPai_id() {
        return $this->pai_id;
    }

    function getFilho_id() {
        return $this->filho_id;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setPai($pai) {
        $this->pai = $pai;
    }

    function setFilhos($filhos) {
        $this->filhos = $filhos;
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

    function setPai_id($pai_id) {
        $this->pai_id = $pai_id;
    }

    function setFilho_id($filho_id) {
        $this->filho_id = $filho_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

}
