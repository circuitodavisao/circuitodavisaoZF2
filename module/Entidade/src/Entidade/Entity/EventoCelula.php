<?php

namespace Entidade\Entity;

/**
 * Nome: EventoCelula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela evento_celula
 */
use Doctrine\ORM\Mapping as ORM;

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
    protected $cidade;

    /** @ORM\Column(type="string") */
    protected $bairro;

    /** @ORM\Column(type="integer") */
    protected $cep;

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

    function getEvento_id() {
        return $this->evento_id;
    }

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCep() {
        return $this->cep;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

}
