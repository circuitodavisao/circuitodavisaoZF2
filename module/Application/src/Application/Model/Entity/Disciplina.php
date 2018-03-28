<?php

namespace Application\Model\Entity;

/**
 * Nome: Disciplina.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela disciplina
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="disciplina")
 */
class Disciplina extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $curso_id;

    /** @ORM\Column(type="integer") */
    protected $posicao;

    /**
     * @ORM\OneToMany(targetEntity="Aula", mappedBy="disciplina")  
     */
    protected $aula;

    /**
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="disciplina")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */
    protected $curso;

    public function __construct() {
        
    }

    function getNome() {
        return $this->nome;
    }

    function getCurso_id() {
        return $this->curso_id;
    }

    function getPosicao() {
        return $this->posicao;
    }

    function getCurso() {
        return $this->curso;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCurso_id($curso_id) {
        $this->curso_id = $curso_id;
    }

    function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

    function setCurso($curso) {
        $this->curso = $curso;
    }

    function getAula() {
        return $this->aula;
    }

    function getAulaOrdenadasPorPosicao() {
        $arrayAulaOrdenadasPorPosicao = $this->getAula();
        if ($aulas = $this->getAula()) {
            $arrayAulaOrdenadasPorPosicao = array();
            foreach ($aulas as $aula) {
                $arrayAulaOrdenadasPorPosicao[] = $aula;
            }
            uksort($arrayAulaOrdenadasPorPosicao, function ($a, $b) use ($arrayAulaOrdenadasPorPosicao) {
                return ($arrayAulaOrdenadasPorPosicao[$a]->getPosicao() < $arrayAulaOrdenadasPorPosicao[$b]->getPosicao()) ? -1 : 1;
            });
        }
        return $arrayAulaOrdenadasPorPosicao;
    }

    function setAula($aula) {
        $this->aula = $aula;
    }

}
