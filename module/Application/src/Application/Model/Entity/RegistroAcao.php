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
	const ENVIOU_RELATORIO = 15;
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
	const ACEITAR_SOLICITACAO = 40;
	const RECUSAR_SOLICITACAO = 41;
	const CADASTROU_UM_LIDER = 42;
	const CADASTROU_UM_REVISAO_DE_VIDAS = 43;
	const CADASTROU_UM_DISCIPULADO = 44;
	const CADASTROU_UM_LIDER_AO_REVISAO_DE_VIDAS = 45;
	const VER_RELATORIO_DE_REGISTRO = 46;
	const REENTRADA_DE_ALUNO = 47;
	const CONSULTAR_MATRICULA = 48;
	const VER_TURMAS = 49;
	const CADASTROU_UMA_TURMA = 50;
	const ADICIONOU_ALUNOS_A_TURMA = 51;
	const REMOVEU_UMA_TURMA = 52;
	const ABRIU_UMA_AULA = 53;
	const REABRIU_UMA_TURMA = 54;
	const CADASTROU_UM_USUARIO_NO_INSTITUTO = 55;
	const INATIVOU_UM_USUARIO_NO_INSTITUTO = 56;
	const LANCOU_UMA_PRESENCA = 57;
	const LANCOU_UMA_REPOSICAO = 58;
	const ALTEROU_UMA_FREQUENCIA_DE_UM_ALUNO = 59;
	const ALTEROU_UM_VISTO_DE_UM_ALUNO = 60;
	const ALTEROU_UMA_AVALIACAO_DE_UM_ALUNO = 61;
	const ALTEROU_UM_FINANCEIRO_DE_UM_ALUNO = 62;
	const MUDOU_SITUACAO_DO_ALUNO_PARA_ATIVO = 63;
	const MUDOU_SITUACAO_DO_ALUNO_PARA_ESPECIAL = 64;
	const MUDOU_SITUACAO_DO_ALUNO_PARA_REPROVADO_POR_FALTAS = 65;
	const MUDOU_SITUACAO_DO_ALUNO_PARA_DESISTENTE = 66;
	const REMOVEU_UM_ALUNO_DA_TURMA = 67;
	const LANCOU_UMA_FICHA_NO_REVISAO_DE_VIDAS = 68;
	const REMOVEU_UMA_FICHA_DO_REVISAO_DE_VIDAS = 69;
	const CADASTROU_VISITANTE = 70;
	const CADASTROU_CONSOLIDACAO = 71;
	const CADASTROU_MEMBRO = 72;
	const LANCOU_VISITANTE = 73;
	const LANCOU_CONSOLIDACAO = 74;
	const LANCOU_MEMBRO = 75;
	const LANCOU_CULTO = 76;
	const LANCOU_CELULA = 77;
	const LANCAR_ATENDIMENTO_LIDER = 78;

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
