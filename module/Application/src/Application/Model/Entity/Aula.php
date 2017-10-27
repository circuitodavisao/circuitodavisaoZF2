<?php

namespace Application\Model\Entity;

/**
 * Nome: Aula.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela aula
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="aula")
 */
class Aula extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $disciplina_id;

    /** @ORM\Column(type="integer") */
    protected $posicao;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="aula")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    protected $disciplina;

    public function __construct() {
        
    }

    function getNome() {
        return $this->nome;
    }

    function getDisciplina_id() {
        return $this->disciplina_id;
    }

    function getPosicao() {
        return $this->posicao;
    }

    function getDisciplina() {
        return $this->disciplina;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDisciplina_id($disciplina_id) {
        $this->disciplina_id = $disciplina_id;
    }

    function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

    function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }

}
