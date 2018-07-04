<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoaVisto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa_visto
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa_visto")
 */
class TurmaPessoaVisto extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaPessoa", inversedBy="turmaPessoaVisto")
     * @ORM\JoinColumn(name="turma_pessoa_id", referencedColumnName="id")
     */
    private $turma_pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Aula", inversedBy="turmaPessoaVisto")
     * @ORM\JoinColumn(name="aula_id", referencedColumnName="id")
     */
    private $aula;

    /** @ORM\Column(type="integer") */
    protected $turma_pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $aula_id;

    function getTurma_pessoa() {
        return $this->turma_pessoa;
    }

    function getAula() {
        return $this->aula;
    }

    function getTurma_pessoa_id() {
        return $this->turma_pessoa_id;
    }

    function getAula_id() {
        return $this->aula_id;
    }

    function setTurma_pessoa($turma_pessoa) {
        $this->turma_pessoa = $turma_pessoa;
    }

    function setAula($aula) {
        $this->aula = $aula;
    }

    function setTurma_pessoa_id($turma_pessoa_id) {
        $this->turma_pessoa_id = $turma_pessoa_id;
    }

    function setAula_id($aula_id) {
        $this->aula_id = $aula_id;
    }

}
