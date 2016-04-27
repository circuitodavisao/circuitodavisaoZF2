<?php

namespace Entidade\Entity;

/**
 * Nome: EventoCelula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_celula
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity 
 * @ORM\Table(name="evento_celula")
 */
class EventoCelula {

    /**
     * @ORM\OneToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $nome_hospedeiro;

    /** @ORM\Column(type="integer") */
    protected $telefone_hospedeiro;

    /** @ORM\Column(type="string") */
    protected $logradouro;

    /** @ORM\Column(type="string") */
    protected $complemento;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="integer") */
    protected $evento_id;

    function getEvento() {
        return $this->evento;
    }

    function getId() {
        return $this->id;
    }

    function getNome_hospedeiro() {
        return $this->nome_hospedeiro;
    }

    function getTelefone_hospedeiro() {
        return $this->telefone_hospedeiro;
    }

    function getLogradouro() {
        return $this->logradouro;
    }

    function getComplemento() {
        return $this->complemento;
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

    function setEvento($evento) {
        $this->evento = $evento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome_hospedeiro($nome_hospedeiro) {
        $this->nome_hospedeiro = $nome_hospedeiro;
    }

    function setTelefone_hospedeiro($telefone_hospedeiro) {
        $this->telefone_hospedeiro = $telefone_hospedeiro;
    }

    function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
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

    function getEvento_id() {
        return $this->evento_id;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

}
