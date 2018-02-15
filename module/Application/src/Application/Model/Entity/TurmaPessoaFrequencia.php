<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoaFrequencia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa_frequencia
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa_frequencia")
 */
class TurmaPessoaFrequencia extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaPessoa", inversedBy="turmaPessoaFrequencia")
     * @ORM\JoinColumn(name="turma_pessoa_id", referencedColumnName="id")
     */
    private $turma_pessoa;

    /** @ORM\Column(type="integer") */
    protected $turma_pessoa_id;

    /** @ORM\Column(type="datetime", name="data") */
    protected $data;

    /** @ORM\Column(type="string") */
    protected $hora;

    function getTurma_pessoa() {
        return $this->turma_pessoa;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function getTurma_pessoa_id() {
        return $this->turma_pessoa_id;
    }

    function getSituacao_id() {
        return $this->situacao_id;
    }

    function setTurma_pessoa($turma_pessoa) {
        $this->turma_pessoa = $turma_pessoa;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function setTurma_pessoa_id($turma_pessoa_id) {
        $this->turma_pessoa_id = $turma_pessoa_id;
    }

    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }

    function getData() {
        return $this->data;
    }

    function getHora() {
        return $this->hora;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

}
