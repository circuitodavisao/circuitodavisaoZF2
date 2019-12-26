<?php

namespace Application\Model\Entity;

/**
 * Nome: SolicitacaoTipo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela solicitacao_tipo 
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="solicitacao_tipo")
 */
class SolicitacaoTipo extends CircuitoEntity {

    const TRANSFERIR_LIDER_NA_PROPRIA_EQUIPE = 1;
    const TRANSFERIR_LIDER_PARA_OUTRA_EQUIPE = 2;
    const UNIR_CASAL = 3;
    const SEPARAR = 4;
    const TROCAR_RESPONSABILIDADES = 5;
    const REMOVER_LIDER = 6;
    const REMOVER_CELULA = 7;
	const TRANSFERIR_ALUNO = 8;
	const SUBIR_LIDER = 9;
	const REMOVER_IGREJA = 10;
    const TRANSFERIR_IGREJA = 11;
    const ADICIONAR_RESPONSABILIDADE_SECRETARIO = 12;
    const REMOVER_RESPONSABILIDADE_SECRETARIO = 13;
    const ABRIR_IGREJA_COM_EQUIPE_COMPLETA = 14;
    const ABRIR_EQUIPE_COM_LIDER_DA_IGREJA = 15;

    /**
     * @ORM\OneToMany(targetEntity="Solicitacao", mappedBy="solicitacaoTipo") 
     */
    protected $solicitacao;

    public function __construct() {
        $this->solicitacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getSolicitacao() {
        return $this->solicitacao;
    }

    function getNome() {
        return $this->nome;
    }

    function setSolicitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
