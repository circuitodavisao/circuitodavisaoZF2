<?php

namespace Application\Model\Entity;

/**
 * Nome: ResolucaoResponsabilidade.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela nova_responsabilidade
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="resolucao_responsabilidade")
 */
class ResolucaoResponsabilidade extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="TrocaResponsavel", inversedBy="novaResponsabilidade")
     * @ORM\JoinColumn(name="troca_responsavel_id", referencedColumnName="id")
     */
    private $trocaResponsavel;    

    /** @ORM\Column(type="integer") */
    protected $troca_responsavel_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="string") */
    protected $operacao;

    function getTrocaResponsavel() {
        return $this->trocaResponsavel;
    }        

    function getTroca_responsavel_id() {
        return $this->troca_responsavel_id;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getOperacao() {
        return $this->operacao;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setOperacao($operacao) {
        $this->operacao = $operacao;
    }

    function setTroca_responsavel_id($troca_responsavel_id) {
        $this->troca_responsavel_id = $troca_responsavel_id;
    }

    function setTrocaResponsavel($trocaResponsavel) {
        $this->trocaResponsavel = $trocaResponsavel;
    }
    
    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

}
