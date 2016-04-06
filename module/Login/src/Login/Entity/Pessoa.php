<?php

namespace Login\Entity;

/**
 * Nome: Pessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa
 */

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Pessoa {

    /**
     * @ORM\OneToMany(targetEntity="PessoaPerfilAcesso", mappedBy="pessoa") 
     */
    protected $perfisDeAcesso;

    public function __construct() {
        $this->perfisDeAcesso = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $senha;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $status;

    /** @ORM\Column(type="string") */
    protected $data_nascimento;

    /** @ORM\Column(type="string") */
    protected $documento;

    /** @ORM\Column(type="string") */
    protected $token;

    /** @ORM\Column(type="string") */
    protected $token_data;

    /** @ORM\Column(type="string") */
    protected $token_hora;

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getStatus() {
        return $this->status;
    }

    function getData_nascimento() {
        return $this->data_nascimento;
    }

    function getDocumento() {
        return $this->documento;
    }

    public function verificarSeEstaAtivo() {
        $resposta = false;
        if ($this->status == 'A') {
            $resposta = true;
        }
        return $resposta;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setSenha($senha) {
        $this->senha = md5($senha);
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    function getToken() {
        return $this->token;
    }

    /**
     * Seta token e data para validacao
     * @param String $token
     */
    function setToken($token) {
        $this->token = $token;
        $timeNow = new DateTime();
        $this->setToken_data($timeNow->format('Y-m-d'));
        $this->setToken_hora($timeNow->format('H:s:i'));
    }

    function getToken_data() {
        return $this->token_data;
    }

    function getToken_data_ano() {
        return substr($this->token_data, 0, 4);
    }

    function getToken_data_mes() {
        return substr($this->token_data, 5, 2);
    }

    function getToken_data_dia() {
        return substr($this->token_data, 8, 2);
    }

    function setToken_data($token_data) {
        $this->token_data = $token_data;
    }

    function getToken_hora() {
        return $this->token_hora;
    }

    function getToken_hora_hora() {
        return substr($this->token_hora, 0, 2);
    }

    function getToken_hora_minutos() {
        return substr($this->token_hora, 3, 2);
    }

    function getToken_hora_segundos() {
        return substr($this->token_hora, 6, 2);
    }

    function setToken_hora($token_hora) {
        $this->token_hora = $token_hora;
    }

    function getPerfisDeAcesso() {
        return $this->perfisDeAcesso;
    }

    function setPerfisDeAcesso($perfisDeAcesso) {
        $this->perfisDeAcesso = $perfisDeAcesso;
    }

}
