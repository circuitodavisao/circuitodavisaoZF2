<?php

namespace Application\Model\Entity;

/**
 * Nome: RegistroAcao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela registro_acao 
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="registro_acao")
 */
class RegistroAcao extends CircuitoEntity {

    const LOGIN = 1;
	const LOGOUT = 2;
	const VER_CELULAS = 3;
	const CADASTROU_UMA_CELULA = 4;
	const ALTEROU_UMA_CELULA = 5;
	const VER_CULTOS = 6;
	const CADASTROU_UM_CULTO = 7;
	const ALTEROU_UM_CULTO = 8;
	const CADASTROU_UM_REVISIONISTA = 9;
	const VER_SOLICITACOES = 10;
	const CADASTROU_UMA_SOLICITACAO = 11;
	const VER_SECRETARIOS_DO_PD = 12;
	const CADASTROU_UM_SECRETARIO_DO_PD = 13;
	const LANCAR_ARREGIMENTACAO = 14;
	const ENVIOU_RELATORIO_= 15;
	const LANCAR_ATENDIMENTO = 16;
	const VER_PARCEIRO_DE_DEUS = 17;
	const LANCOU_PARCEIRO_DE_DEUS = 18;
	const ACEITOU_PARCEIRO_DE_DEUS = 19;
	const EXLUIU_PARCEIRO_DE_DEUS = 20;
	const VER_RELATORIO_MEMBRESIA = 21;
	const VER_RELATORIO_CELULA_REALIZADAS = 22;
	const VER_RELATORIO_CELULA_QUANTIDADE = 23;
	const VER_RELATORIO_CELULA_DE_ELITE = 24;
	const VER_RELATORIO_PARCEIRO_DE_DEUS = 25;
	const VER_RELATORIO_ATENDIMENTO = 26;
	const VER_RELATORIO_APROVEITAMENTO_DO_IV = 27;
	const VER_RELATORIO_ALUNOS_QUE_NAO_FORAM_A_AULA = 28;
	const VER_RELATORIO_ALUNOS_REPROVANDO = 29;
	const VER_RELATORIO_ALUNOS_NA_SEMANA = 30;
	const VER_RELATORIO_RANKING_CELULA_= 31;
	const VER_RELATORIO_SETENTA = 32;
	const VER_CHAMADA = 33;
	const VER_REPOSICOES = 34;
	const GEROU_REPOSICOES = 35;
	const VER_FICHAS_DO_REVISAO_DE_VIDAS = 36;
	const VER_LISTAGEM_DE_REVISIONISTAS_ATIVOS = 37;
	const VER_LISTAGEM_DE_LIDERES_ATIVOS = 38;
	const VER_RELATORIO_CELULAS_NAO_REALIZADAS = 39;

    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="registroAcao") 
     */
    protected $registro;

    public function __construct() {
        $this->$registro = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function getRegistro() {
        return $this->registro;
    }

    function getNome() {
        return $this->nome;
    }

    function setResgitro($registro) {
        $this->registro = $registro;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
