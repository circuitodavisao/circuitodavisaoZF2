<?php

namespace Application\Model\Entity;

/**
 * Nome: Solicitacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela solicitacao
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="solicitacao")
 */
class Solicitacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="solicitacao")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /**
     * @ORM\ManyToOne(targetEntity="SolicitacaoTipo", inversedBy="solicitacao")
     * @ORM\JoinColumn(name="solicitacao_tipo_id", referencedColumnName="id")
     */
    private $solicitacaoTipo;

    /** @ORM\Column(type="integer") */
    protected $objeto1;

    /** @ORM\Column(type="integer") */
    protected $objeto2;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $solicitacao_tipo_id;

    function getGrupo() {
        return $this->grupo;
    }

    function getSolicitacaoTipo() {
        return $this->solicitacaoTipo;
    }

    function getObjeto1() {
        return $this->objeto1;
    }

    function getObjeto2() {
        return $this->objeto2;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getSolicitacao_tipo_id() {
        return $this->solicitacao_tipo_id;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setSolicitacaoTipo($solicitacaoTipo) {
        $this->solicitacaoTipo = $solicitacaoTipo;
    }

    function setObjeto1($objeto1) {
        $this->objeto1 = $objeto1;
    }

    function setObjeto2($objeto2) {
        $this->objeto2 = $objeto2;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setSolicitacao_tipo_id($solicitacao_tipo_id) {
        $this->solicitacao_tipo_id = $solicitacao_tipo_id;
    }

}
