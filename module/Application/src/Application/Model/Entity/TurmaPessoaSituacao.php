<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoaSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa_situacao
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa_situacao")
 */
class TurmaPessoaSituacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaPessoa", inversedBy="turmaPessoaSituacao")
     * @ORM\JoinColumn(name="turma_pessoa_id", referencedColumnName="id")
     */
    private $turma_pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="turmaPessoaSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;

    /** @ORM\Column(type="integer") */
    protected $turma_pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $situacao_id;

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

}
