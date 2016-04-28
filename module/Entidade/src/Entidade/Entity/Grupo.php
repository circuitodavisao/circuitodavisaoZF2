<?php

namespace Entidade\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Grupo {

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="grupo") 
     */
    protected $entidade;

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="grupo") 
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="grupo") 
     */
    protected $grupoEvento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupo") 
     */
    protected $grupoPessoa;

    public function __construct() {
        $this->entidade = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->grupoEvento = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
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

    /**
     * Recupera todas as entidades vinculadas aquele grupo
     * @return Entidade
     */
    function getEntidade() {
        return $this->entidade;
    }

    /**
     * Retorna a entidade ativa
     * @return Entidade
     */
    function getEntidadeAtiva() {
        $entidadeAtiva = null;
        foreach ($this->getEntidade() as $e) {
            if ($e->verificarSeEstaAtivo()) {
                $entidadeAtiva = $e;
                break;
            }
        }

        return $entidadeAtiva;
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

    /**
     * Retorna o grupo responsavel do grupo
     * @return GrupoResponsavel
     */
    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
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

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEvento() {
        return $this->grupoEvento;
    }

    function setGrupoEvento($grupoEvento) {
        $this->grupoEvento = $grupoEvento;
    }

    /**
     * Retorna o grupo pessoa
     * @return GrupoPessoa
     */
    function getGrupoPessoa() {
        return $this->grupoPessoa;
    }

    function setGrupoPessoa($grupoPessoa) {
        $this->grupoPessoa = $grupoPessoa;
    }

}
