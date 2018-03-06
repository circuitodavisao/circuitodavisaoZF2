<?php

namespace Application\Model\Entity;

/**
 * Nome: PessoaCursoAcesso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa_curso_acesso
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="pessoa_curso_acesso")
 */
class PessoaCursoAcesso extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="CursoAcesso", inversedBy="pessoaCursoAcesso")
     * @ORM\JoinColumn(name="curso_acesso_id", referencedColumnName="id")
     */
    private $cursoAcesso;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="pessoaCursoAcesso")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="pessoaCursoAcesso")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    function getCursoAcesso() {
        return $this->cursoAcesso;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function setCursoAcesso($cursoAcesso) {
        $this->cursoAcesso = $cursoAcesso;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

}
