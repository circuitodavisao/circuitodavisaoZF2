<?php

namespace Entidade\Entity;

/**
 * Nome: TurmaPessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa")
 */
class TurmaPessoa {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="turmaPessoa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Turma", inversedBy="turmaPessoa")
     * @ORM\JoinColumn(name="turma_id", referencedColumnName="id")
     */
    private $turma;

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
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $turma_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /**
     * Retorna a pessoa da associação
     * @return Pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna a turma da associação
     * @return Turma
     */
    function getTurma() {
        return $this->turma;
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

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getTurma_id() {
        return $this->turma_id;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
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

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setTurma_id($turma_id) {
        $this->turma_id = $turma_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    function setTurma($turma) {
        $this->turma = $turma;
    }

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

}
