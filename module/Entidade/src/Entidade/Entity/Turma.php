<?php

namespace Entidade\Entity;

/**
 * Nome: Turma.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma")
 */
class Turma {

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoa", mappedBy="pessoa") 
     */
    protected $turmaPessoa;

    public function __construct() {
        $this->turmaPessoa = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $data_revisao;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getId() {
        return $this->id;
    }

    function getData_revisao() {
        return $this->data_revisao;
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

    function setId($id) {
        $this->id = $id;
    }

    function setData_revisao($data_revisao) {
        $this->data_revisao = $data_revisao;
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

    function getTurmaPessoa() {
        return $this->turmaPessoa;
    }

    function setTurmaPessoa($turmaPessoa) {
        $this->turmaPessoa = $turmaPessoa;
    }

}
