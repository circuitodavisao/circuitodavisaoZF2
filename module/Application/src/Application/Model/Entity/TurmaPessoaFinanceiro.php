<?php

namespace Application\Model\Entity;

/**
 * Nome: TurmaPessoaVisto.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela turma_pessoa_financeiro
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="turma_pessoa_financeiro")
 */
class TurmaPessoaFinanceiro extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TurmaPessoa", inversedBy="turmaPessoaFinanceiro")
     * @ORM\JoinColumn(name="turma_pessoa_id", referencedColumnName="id")
     */
    private $turma_pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="turmaPessoaFinanceiro")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    private $disciplina;

    /** @ORM\Column(type="integer") */
    protected $turma_pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $disciplina_id;

    /** @ORM\Column(type="integer") */
    protected $mes1;

    /** @ORM\Column(type="integer") */
    protected $ano1;

    /** @ORM\Column(type="string") */
    protected $valor1;

     /** @ORM\Column(type="integer") */
     protected $mes2;

     /** @ORM\Column(type="integer") */
     protected $ano2;

    /** @ORM\Column(type="string") */
    protected $valor2;

     /** @ORM\Column(type="integer") */
     protected $mes3;

     /** @ORM\Column(type="integer") */
     protected $ano3;

    /** @ORM\Column(type="string") */
    protected $valor3;

    function getTurma_pessoa() {
        return $this->turma_pessoa;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function getTurma_pessoa_id() {
        return $this->turma_pessoa_id;
    }

    function getDisciplina_id() {
        return $this->disciplina_id;
    }

    function getMes1() {
        return $this->mes1;
    }
    function getAno1() {
        return $this->ano1;
    }
    function getValor1() {
        return $this->valor1;
    }

    function getMes2() {
        return $this->mes2;
    }
    function getAno2() {
        return $this->ano2;
    }
    function getValor2() {
        return $this->valor2;
    }

    function getMes3() {
        return $this->mes3;
    }
    function getAno3() {
        return $this->ano3;
    }
    function getValor3() {
        return $this->valor3;
    }

    function setMes1($mes1) {
        $this->mes1 = $mes1;
    }

    function setAno1($ano1) {
        $this->ano1 = $ano1;
    }

    function setValor1($valor1) {
        $this->valor1 = $valor1;
    }

    function setMes2($mes2) {
        $this->mes2 = $mes2;
    }

    function setAno2($ano2) {
        $this->ano2 = $ano2;
    }

    function setValor2($valor2) {
        $this->valor2 = $valor2;
    }

    function setMes3($mes3) {
        $this->mes3 = $mes3;
    }

    function setAno3($ano3) {
        $this->ano3 = $ano3;
    }

    function setValor3($valor3) {
        $this->valor3 = $valor3;
    }

    function setTurma_pessoa($turma_pessoa) {
        $this->turma_pessoa = $turma_pessoa;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

    function setTurma_pessoa_id($turma_pessoa_id) {
        $this->turma_pessoa_id = $turma_pessoa_id;
    }

    function setDisciplina_id($disciplina_id) {
        $this->disciplina_id = $disciplina_id;
    }

}
