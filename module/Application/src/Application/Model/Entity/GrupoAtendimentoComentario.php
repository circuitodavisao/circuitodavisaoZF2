<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoAtendimentoComentario.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_atendimento_comentario
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_atendimento_comentario")
 */
class GrupoAtendimentoComentario extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoAtendimentoComentario")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $comentario;

    /**
     * Retorna se tem atendimento nesse mes e ano
     * @param int $mes
     * @param int $ano
     * @return boolean
     */
    function verificaSeTemNesseMesEAno($mes, $ano) {
        $resposta = false;
        $mesComDuasCasas = str_pad($mes, 2, 0, STR_PAD_LEFT);
        if ($this->verificarSeEstaAtivo()) {
            if ($this->getData_criacaoMes() == $mesComDuasCasas &&
                    $this->getData_criacaoAno() == $ano) {
                $resposta = true;
            }
        }
        return $resposta;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function getComentario() {
        return $this->comentario;
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }

}
