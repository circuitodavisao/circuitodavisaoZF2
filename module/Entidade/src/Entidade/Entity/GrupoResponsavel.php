<?php

namespace Entidade\Entity;

/**
 * Nome: GrupoResponsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_responsavel
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_responsavel")
 */
class GrupoResponsavel {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="grupoResponsavel")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoResponsavel")
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

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

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
     * Verificar se a responsabilidade foi cadastrada nesse mês
     * @return boolean
     */
    public function verificarSeFoiCadastradoNesseMes() {
        $resposta = false;
        if ($this->getData_criacaoMes() == date('n') && $this->getData_criacaoAno() == date('Y')) {
            $resposta = true;
        }
        return $resposta;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna o grupo da responsabilidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    /**
     * Identificação da responsabilidade
     * @return int
     */
    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_criacaoMes() {
        return explode('-', $this->data_criacao)[1];
    }

    function getData_criacaoAno() {
        return explode('-', $this->data_criacao)[0];
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
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

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

}
