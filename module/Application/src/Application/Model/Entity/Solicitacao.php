<?php

namespace Application\Model\Entity;

/**
 * Nome: Solicitacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela solicitacao
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="solicitacao")
 */
class Solicitacao extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="solicitante")
     * @ORM\JoinColumn(name="solicitante_id", referencedColumnName="id")
     */
    private $solicitante;

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="receptor")
     * @ORM\JoinColumn(name="receptor_id", referencedColumnName="id")
     */
    private $receptor;

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

    /**
     * @ORM\OneToMany(targetEntity="SolicitacaoSituacao", mappedBy="solicitacao") 
     */
    protected $solicitacaoSituacao;

    public function __construct() {
        $this->solicitacaoSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="integer") */
    protected $objeto1;

    /** @ORM\Column(type="integer") */
    protected $objeto2;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $numero;

    /** @ORM\Column(type="integer") */
    protected $solicitante_id;

    /** @ORM\Column(type="integer") */
    protected $solicitacao_tipo_id;

    /** @ORM\Column(type="integer") */
    protected $receptor_id;
	
    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $motivo;

    function getSolicitacaoSituacaoAtiva() {
        $solicitacaoSituacaoAtiva = null;
        foreach ($this->getSolicitacaoSituacao() as $solicitacaoSituacao) {
            if ($solicitacaoSituacao->verificarSeEstaAtivo()) {
                $solicitacaoSituacaoAtiva = $solicitacaoSituacao;
                break;
            }
        }
        return $solicitacaoSituacaoAtiva;
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

    function getSolicitante_id() {
        return $this->solicitante_id;
    }

    function setSolicitante_id($solicitante_id) {
        $this->solicitante_id = $solicitante_id;
    }

    function getSolicitacaoSituacao() {
        return $this->solicitacaoSituacao;
    }

    function setSolicitacaoSituacao($solicitacaoSituacao) {
        $this->solicitacaoSituacao = $solicitacaoSituacao;
    }

	function setGrupo($grupo){
		$this->grupo = $grupo;
	}

	function getGrupo(){
		return $this->grupo;
	}

    function getNome() {
        return $this->nome;
    }

    function getNumero() {
        return $this->numero;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function getReceptor_id() {
        return $this->receptor_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function setReceptor_id($receptor_id) {
        $this->receptor_id = $receptor_id;
    }

	function setSolicitante($solicitante){
		$this->solicitante = $solicitante;
	}

	function getSolicitante(){
		return  $this->solicitante;
	}

	function setReceptor($receptor){
		$this->receptor = $receptor;
	}

	function getReceptor(){
		return $this->receptor;
	}
	function setMotivo($motivo){
		$this->motivo = $motivo;
	}
	function getMotivo(){
		return $this->motivo;
	}
}
