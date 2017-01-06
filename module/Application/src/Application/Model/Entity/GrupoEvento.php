<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoEvento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_evento
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_evento")
 */
class GrupoEvento {

    /**
     * @ORM\ManyToOne(targetEntity="Evento", inversedBy="grupoEvento")
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoEvento")
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
    protected $evento_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;
    protected $novo;

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
     * Retorna o evento
     * @return Evento
     */
    function getEvento() {
        return $this->evento;
    }

    /**
     * Retorna o grupo
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

    function getEvento_id() {
        return $this->evento_id;
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

    function setEvento($evento) {
        $this->evento = $evento;
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

    function setEvento_id($evento_id) {
        $this->evento_id = $evento_id;
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

    function getData_criacaoAno() {
        return explode('-', $this->getData_criacao())[0];
    }

    function getData_criacaoMes() {
        return explode('-', $this->getData_criacao())[1];
    }

    function getData_criacaoDia() {
        return explode('-', $this->getData_criacao())[2];
    }

    function getNovo() {
        return $this->novo;
    }

    function setNovo($novo) {
        $this->novo = $novo;
    }

}
