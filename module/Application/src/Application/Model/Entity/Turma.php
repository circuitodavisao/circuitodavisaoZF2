<?php

namespace Application\Model\Entity;

/**
 * Nome: Turma.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma")
 */
class Turma extends CircuitoEntity {

    /**
     * @ORM\OneToMany(targetEntity="TurmaAluno", mappedBy="pessoa") 
     */
    protected $turmaAluno;

    public function __construct() {
        $this->turmaAluno = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    function getTurmaAluno() {
        return $this->turmaAluno;
    }

    function getData_revisao() {
        return $this->data_revisao;
    }

    function setTurmaAluno($turmaAluno) {
        $this->turmaAluno = $turmaAluno;
    }

    function setData_revisao($data_revisao) {
        $this->data_revisao = $data_revisao;
    }

}
