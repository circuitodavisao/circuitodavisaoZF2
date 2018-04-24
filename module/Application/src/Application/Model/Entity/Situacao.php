<?php

namespace Application\Model\Entity;

/**
 * Nome: Situacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela situacao 
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="situacao")
 */
class Situacao extends CircuitoEntity {

    const ATIVO = 1;
    const PENDENTE_DE_ACEITACAO = 2;
    const ACEITO_AGENDADO = 3;
    const RECUSAO = 4;
    const CONCLUIDO = 5;
    const ESPECIAL = 6;

    /**
     * @ORM\OneToMany(targetEntity="TurmaPessoaSituacao", mappedBy="situacao") 
     */
    protected $turmaPessoaSituacao;

    /**
     * @ORM\OneToMany(targetEntity="SolicitacaoSituacao", mappedBy="situacao") 
     */
    protected $solicitacaoSituacao;

    public function __construct() {
        $this->turmaPessoaSituacao = new ArrayCollection();
        $this->solicitacaoSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getSolicitacaoSituacao() {
        return $this->solicitacaoSituacao;
    }

    function setSolicitacaoSituacao($solicitacaoSituacao) {
        $this->solicitacaoSituacao = $solicitacaoSituacao;
    }

    function getTurmaPessoaSituacao() {
        return $this->turmaPessoaSituacao;
    }

    function setTurmaPessoaSituacao($turmaPessoaSituacao) {
        $this->turmaPessoaSituacao = $turmaPessoaSituacao;
    }

}
