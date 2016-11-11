<?php

namespace Entidade\Entity;

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
class PessoaHierarquia {

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

    /** @ORM\Column(type="integer") */
    protected $hierarquia_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /**
     * Verificar se a data de inativação está nula
     * @return boolean
     */
    public function verificarSeEstaAtivo() {
        $resposta = false;
        if (is_null($this->getData_inativacao())) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Seta data e hora de criação
     */
    function setDataEHoraDeCriacao() {
        $timeNow = new DateTime();
        $this->setData_criacao($timeNow->format('Y-m-d'));
        $this->setHora_criacao($timeNow->format('H:s:i'));
    }

    function getHierarquia() {
        return $this->hierarquia;
    }

    function getPessoa() {
        return $this->pessoa;
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

    function setHierarquia_id($hierarquia_id) {
        $this->hierarquia_id = $hierarquia_id;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

}
