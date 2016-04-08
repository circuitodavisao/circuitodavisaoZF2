<?php

namespace Login\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Grupo {

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="grupo") 
     */
    protected $entidade;

    /**
     * @ORM\OneToMany(targetEntity="Pessoa", mappedBy="grupo") 
     */
    protected $grupoResponsavel;

//    /**
//     * @ORM\ManyToOne(targetEntity="GrupoPaiFilho", mappedBy="grupoPai") 
//     * @ORM\JoinColumn(name="pai_id", referencedColumnName="id")
//     */
    protected $grupoPai;
    
//    /**
//     * @ORM\OneToMany(targetEntity="Grupo", mappedBy="gruposFilho") 
//     */
    protected $gruposFilho;

    public function __construct() {
        $this->entidade = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->gruposFilho = new ArrayCollection();
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
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getEntidade() {
        return $this->entidade;
    }

    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
    }

    function getGrupoPai() {
        return $this->grupoPai;
    }

    function getGruposFilho() {
        return $this->gruposFilho;
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

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    function setGrupoPai($grupoPai) {
        $this->grupoPai = $grupoPai;
    }

    function setGruposFilho($gruposFilho) {
        $this->gruposFilho = $gruposFilho;
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

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

}
