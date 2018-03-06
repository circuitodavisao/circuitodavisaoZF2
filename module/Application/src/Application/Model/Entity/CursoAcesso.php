<?php

namespace Application\Model\Entity;

/**
 * Nome: CursoAcesso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela curso_Acesso
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="curso_acesso")
 */
class CursoAcesso extends CircuitoEntity {

    const COORDENADOR = 1;
    const SUPERVISOR = 2;
    const AUXILIAR = 3;

    /**
     * @ORM\OneToMany(targetEntity="PessoaCursoAcesso", mappedBy="cursoAcesso") 
     */
    protected $pessoaCursoAcesso;

    public function __construct() {
        $this->pessoaCursoAcesso = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getEvento() {
        return $this->evento;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

}
