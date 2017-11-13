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
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="solicitacao")
     * @ORM\JoinColumn(name="solicitante_id", referencedColumnName="id")
     */
    private $pessoa;

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
    protected $solicitante_id;

    /** @ORM\Column(type="integer") */
    protected $solicitacao_tipo_id;

    function getSolicitacaoTipo() {
        return $this->solicitacaoTipo;
    }

    function getObjeto1() {
        return $this->objeto1;
    }

    function getObjeto2() {
        return $this->objeto2;
    }

    function getSolicitacao_tipo_id() {
        return $this->solicitacao_tipo_id;
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

    function setSolicitacao_tipo_id($solicitacao_tipo_id) {
        $this->solicitacao_tipo_id = $solicitacao_tipo_id;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getSolicitante_id() {
        return $this->solicitante_id;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setSolicitante_id($solicitante_id) {
        $this->solicitante_id = $solicitante_id;
    }

}
