<?php

namespace Entidade\Entity;

/**
 * Nome: AlunoSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela aluno_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="aluno_situacao")
 */
class AlunoSituacao {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaAluno", inversedBy="alunoSituacao")
     * @ORM\JoinColumn(name="turma_aluno_id", referencedColumnName="id")
     */
    private $turmaAluno;

    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="alunoSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;

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
    protected $situacao_id;

    /** @ORM\Column(type="integer") */
    protected $turma_aluno_id;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    function getSituacao() {
        return $this->situacao;
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

    function getSituacao_id() {
        return $this->situacao_id;
    }

    function getTurma_aluno_id() {
        return $this->turma_aluno_id;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
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

    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }

    function setTurma_aluno_id($turma_aluno_id) {
        $this->turma_aluno_id = $turma_aluno_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

}
