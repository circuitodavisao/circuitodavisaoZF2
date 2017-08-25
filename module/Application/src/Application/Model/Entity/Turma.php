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
    
    /** @ORM\Column(type="integer") */
    protected $mes;
     
    /** @ORM\Column(type="integer") */
    protected $ano;
    
    /** @ORM\Column(type="string") */
    protected $observacao;
    
    /** @ORM\Column(type="integer") */
    protected $tipo_turma_id;
    
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

    function getMes() {
        return $this->mes;
    }

    function getAno() {
        return $this->ano;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    function getTipo_turma_id() {
        return $this->tipo_turma_id;
    }

    function setTipo_turma_id($tipo_turma_id) {
        $this->tipo_turma_id = $tipo_turma_id;
    }
}
