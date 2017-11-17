<?php

namespace Application\Model\Entity;

/**
 * Nome: SolicitacaoSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela solicitacao_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="solicitacao_situacao")
 */
class SolicitacaoSituacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Solicitacao", inversedBy="solicitacaSituacao")
     * @ORM\JoinColumn(name="solicitacao_id", referencedColumnName="id")
     */
    private $solicitacao;

    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="solicitacaSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;

    /** @ORM\Column(type="integer") */
    protected $solicitacao_id;

    /** @ORM\Column(type="integer") */
    protected $situacao_id;

    /** @ORM\Column(type="string") */
    protected $extra;

    function getSolicitacao() {
        return $this->solicitacao;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function getSolicitacao_id() {
        return $this->solicitacao_id;
    }

    function getSituacao_id() {
        return $this->situacao_id;
    }

    function getExtra() {
        return $this->extra;
    }

    function setSolicitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function setSolicitacao_id($solicitacao_id) {
        $this->solicitacao_id = $solicitacao_id;
    }

    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

}
