<?php

namespace Entidade\Entity;

/**
 * Nome: Entidade.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela entidade
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="entidade")
 */
class Entidade {

    /**
     * @ORM\ManyToOne(targetEntity="EntidadeTipo", inversedBy="entidade")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $entidadeTipo;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="entidade")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

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

    /** @ORM\Column(type="integer") */
    protected $numero;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="integer") */
    protected $tipo_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

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

    public function infoEntidade() {
        $resposta = '';
        if ($this->getNome()) {
            $resposta = $this->getNome();
        }
        if ($this->getNumero()) {
            $resposta = $this->getNumero();
        }
        return $resposta;
    }

    /**
     * Retorna a entidade tipo da entidade
     * @return EntidadeTipo
     */
    function getEntidadeTipo() {
        return $this->entidadeTipo;
    }

    /**
     * Retorna o grupo da Entidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
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

    function getNumero() {
        return $this->numero;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function getTipo_id() {
        return $this->tipo_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setEntidadeTipo($entidadeTipo) {
        $this->entidadeTipo = $entidadeTipo;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
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

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    function setTipo_id($tipo_id) {
        $this->tipo_id = $tipo_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

}
