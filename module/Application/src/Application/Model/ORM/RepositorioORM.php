<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: RepositorioORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ao repositorio ORM
 */
class RepositorioORM {

    private $_doctrineORMEntityManager;
    private $_pessoaORM;
    private $_entidadeORM;
    private $_entidadeTipoORM;
    private $_grupoORM;
    private $_grupoPessoaORM;
    private $_grupoPessoaTipoORM;
    private $_grupoMetasOrdenacaoORM;
    private $_metasOrdenacaoTipoORM;
    private $_metasOrdenacaoCriterioORM;
    private $_eventoORM;    
    private $_eventoCelulaORM;
    private $_grupoEventoORM;
    private $_eventoTipoORM;
    private $_hierarquiaORM;
    private $_turmaPessoaORM;
    private $_turmaPessoaFrequenciaORM;
    private $_turmaPessoaAulaORM;
    private $_turmaPessoaVistoORM;
    private $_turmaPessoaFinanceiroORM;
    private $_turmaPessoaAvaliacaoORM;
    private $_turmaAulaORM;
    private $_pessoaHierarquiaORM;
    private $_grupoResponsavelORM;
    private $_grupoPaiFilhoORM;
    private $_grupoAtendimentoORM;
    private $_grupoAtendimentoComentarioORM;
    private $_eventoFrequenciaORM;
    private $_fatoCicloORM;
    private $_fatoCelulaORM;
    private $_fatoLiderORM;
    private $_dimensaoORM;
    private $_dimensaoTipoORM;
    private $_grupoCvORM;
    private $_turmaORM;
    private $_solicitacaoORM;
    private $_solicitacaoTipoORM;
    private $_cursoORM;
    private $_disciplinaORM;
    private $_aulaORM;
    private $_solicitacaoSituacaoORM;
    private $_situacaoORM;
    private $_fatoRankingORM;
    private $_cursoAcessoORM;
    private $_pessoaCursoAcessoORM;
    private $_profissaoORM;
    private $_resolucaoResponsabilidadeORM;
    private $_trocaResponsavelORM;
	private $_fatoFinanceiro;
	private $_fatoFinanceiroTipo;
	private $_fatoRankingCelulaORM;
	private $_fatoCursoORM;
	private $_fatoFinanceiroSituacaoORM;
	private $_pessoaFatoFinanceiroAcessoORM;
	private $_fatoFinanceiroAcessoORM;
	private $_eleitorORM;
	private $_fatoSetentaORM;
	private $_registroORM;
	private $_registroAcaoORM;
	private $_fatoDiscipuladoORM;
	private $_fatoCelulaDiscipuladoORM;
	private $_fatoMensalORM;
	private $_envioORM;
	private $_fatoRevisaoORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do PessoaORM
     * @return PessoaORM
     */
    public function getPessoaORM() {
        if (is_null($this->_pessoaORM)) {
            $this->_pessoaORM = new PessoaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PESSOA);
        }
        return $this->_pessoaORM;
    }

    /**
     * Metodo public para obter a instancia do EntidadeORM
     * @return CircuitoORM
     */
    public function getEntidadeORM() {
        if (is_null($this->_entidadeORM)) {
            $this->_entidadeORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ENTIDADE);
        }
        return $this->_entidadeORM;
    }

    /**
     * Metodo public para obter a instancia do EntidadeTipoORM
     * @return CircuitoORM
     */
    public function getEntidadeTipoORM() {
        if (is_null($this->_entidadeTipoORM)) {
            $this->_entidadeTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ENTIDADE_TIPO);
        }
        return $this->_entidadeTipoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaTipoORM
     * @return GrupoPessoaTipoORM
     */
    public function getGrupoPessoaTipoORM() {
        if (is_null($this->_grupoPessoaTipoORM)) {
            $this->_grupoPessoaTipoORM = new GrupoPessoaTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PESSOA_TIPO);
        }
        return $this->_grupoPessoaTipoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaORM
     * @return GrupoPessoaORM
     */
    public function getGrupoPessoaORM() {
        if (is_null($this->_grupoPessoaORM)) {
            $this->_grupoPessoaORM = new GrupoPessoaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PESSOA);
        }
        return $this->_grupoPessoaORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getGrupoResponsavelORM() {
        if (is_null($this->_grupoResponsavelORM)) {
            $this->_grupoResponsavelORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_RESPONSAVEL);
        }
        return $this->_grupoResponsavelORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getGrupoPaiFilhoORM() {
        if (is_null($this->_grupoPaiFilhoORM)) {
            $this->_grupoPaiFilhoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PAI_FILHO);
        }
        return $this->_grupoPaiFilhoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return GrupoORM
     */
    public function getGrupoORM() {
        if (is_null($this->_grupoORM)) {
            $this->_grupoORM = new GrupoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO);
        }
        return $this->_grupoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoORM
     * @return CircuitoORM
     */
    public function getEventoORM() {
        if (is_null($this->_eventoORM)) {
            $this->_eventoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO);
        }
        return $this->_eventoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoCelulaORM
     * @return CircuitoORM
     */
    public function getEventoCelulaORM() {
        if (is_null($this->_eventoCelulaORM)) {
            $this->_eventoCelulaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_CELULA);
        }
        return $this->_eventoCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoAtendimentoORM
     * @return CircuitoORM
     */
    public function getGrupoAtendimentoORM() {
        if (is_null($this->_grupoAtendimentoORM)) {
            $this->_grupoAtendimentoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_ATENDIMENTO);
        }
        return $this->_grupoAtendimentoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoAtendimentoComentarioORM
     * @return CircuitoORM
     */
    public function getGrupoAtendimentoComentarioORM() {
        if (is_null($this->_grupoAtendimentoComentarioORM)) {
            $this->_grupoAtendimentoComentarioORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_ATENDIMENTO_COMENTARIO);
        }
        return $this->_grupoAtendimentoComentarioORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getEventoTipoORM() {
        if (is_null($this->_eventoTipoORM)) {
            $this->_eventoTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_TIPO);
        }
        return $this->_eventoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do HierarquiaORM
     * @return HierarquiaORM
     */
    public function getHierarquiaORM() {
        if (is_null($this->_hierarquiaORM)) {
            $this->_hierarquiaORM = new HierarquiaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_HIERAQUIA);
        }
        return $this->_hierarquiaORM;
    }

    /**
     * Metodo public para obter a instancia do PessoaHierarquiaORM
     * @return CircuitoORM
     */
    public function getPessoaHierarquiaORM() {
        if (is_null($this->_pessoaHierarquiaORM)) {
            $this->_pessoaHierarquiaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PESSOA_HIERAQUIA);
        }
        return $this->_pessoaHierarquiaORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaPessoaORM
     * @return CircuitoORM
     */
    public function getTurmaPessoaORM() {
        if (is_null($this->_turmaPessoaORM)) {
            $this->_turmaPessoaORM = new TurmaPessoaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA);
        }
        return $this->_turmaPessoaORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaPessoaSituacaoORM
     * @return CircuitoORM
     */
    public function getTurmaPessoaSituacaoORM() {
        if (is_null($this->_turmaPessoaSituacaoORM)) {
            $this->_turmaPessoaSituacaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_SITUACAO);
        }
        return $this->_turmaPessoaSituacaoORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaPessoaFrequenciaORM
     * @return CircuitoORM
     */
    public function getTurmaPessoaFrequenciaORM() {
        if (is_null($this->_turmaPessoaFrequenciaORM)) {
            $this->_turmaPessoaFrequenciaORM = new TurmaPessoaFrequenciaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_FREQUENCIA);
        }
        return $this->_turmaPessoaFrequenciaORM;
    }

    /**fatoCicloExcluirRelatorioSegunda
     * Metodo public para obter a instancia do TurmaPessoaAulaORM
     * @return TurmaPessoaAulaORM
     */
    public function getTurmaPessoaAulaORM() {
        if (is_null($this->_turmaPessoaAulaORM)) {
            $this->_turmaPessoaAulaORM = new TurmaPessoaAulaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_AULA);
        }
        return $this->_turmaPessoaAulaORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaPessoaVistoORM
     * @return CircuitoORM
     */
    public function getTurmaPessoaVistoORM() {
        if (is_null($this->_turmaPessoaVistoORM)) {
            $this->_turmaPessoaVistoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_VISTO);
        }
        return $this->_turmaPessoaVistoORM;
    }
   
    /**
     * Metodo public para obter a instancia do Profissao
     * @return CircuitoORM
     */
    public function getProfissaoORM() {
        if (is_null($this->_profissaoORM)) {
            $this->_profissaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PROFISSAO);
        }
        return $this->_profissaoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoMetasOrdenacao
     * @return CircuitoORM
     */
    public function getGrupoMetasOrdenacaoORM() {
        if (is_null($this->_grupoMetasOrdenacaoORM)) {
            $this->_grupoMetasOrdenacaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_METAS_ORDENACAO);
        }
        return $this->_grupoMetasOrdenacaoORM;
    }

    /**
     * Metodo public para obter a instancia do MetasOrdenacaoTipo
     * @return CircuitoORM
     */
    public function getMetasOrdenacaoTipoORM() {
        if (is_null($this->_metasOrdenacaoTipoORM)) {
            $this->_metasOrdenacaoTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_METAS_ORDENACAO_TIPO);
        }
        return $this->_metasOrdenacaoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do MetasOrdenacaoCriterio
     * @return CircuitoORM
     */
    public function getMetasOrdenacaoCriterioORM() {
        if (is_null($this->_metasOrdenacaoCriterioORM)) {
            $this->_metasOrdenacaoCriterioORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_METAS_ORDENACAO_CRITERIO);
        }
        return $this->_metasOrdenacaoCriterioORM;
    }

     /**
     * Metodo public para obter a instancia do TrocaResponsavel
     * @return TrocaResponsavelORM
     */
    public function getTrocaResponsavelORM() {
        if (is_null($this->_trocaResponsavelORM)) {
            $this->_trocaResponsavelORM = new TrocaResponsavelORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TROCA_RESPONSAVEL);
        }
        return $this->_trocaResponsavelORM;
    }

    /**
     * Metodo public para obter a instancia do NovaResponsabilidade
     * @return ResolucaoResponsabilidadeORM
     */
    public function getResolucaoResponsabilidadeORM() {
        if (is_null($this->_resolucaoResponsabilidadeORM)) {
            $this->_resolucaoResponsabilidadeORM = new ResolucaoResponsabilidadeORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_RESOLUCAO_RESPONSABILIDADE);
        }
        return $this->_resolucaoResponsabilidadeORM;
    }
    
    /**
     * Metodo public para obter a instancia do TurmaPessoaFinanceiroORM
     * @return TurmaPessoaFinanceiroORM
     */
    public function getTurmaPessoaFinanceiroORM() {
        if (is_null($this->_turmaPessoaFinanceiroORM)) {
            $this->_turmaPessoaFinanceiroORM = new TurmaPessoaFinanceiroORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_FINANCEIRO);
        }
        return $this->_turmaPessoaFinanceiroORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaPessoaAvaliacaoORM
     * @return CircuitoORM
     */
    public function getTurmaPessoaAvaliacaoORM() {
        if (is_null($this->_turmaPessoaAvaliacaoORM)) {
            $this->_turmaPessoaAvaliacaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_PESSOA_AVALIACAO);
        }
        return $this->_turmaPessoaAvaliacaoORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaAulaORM
     * @return CircuitoORM
     */
    public function getTurmaAulaORM() {
        if (is_null($this->_turmaAulaORM)) {
            $this->_turmaAulaORM = new TurmaAulaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_AULA);
        }
        return $this->_turmaAulaORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getEventoFrequenciaORM() {
        if (is_null($this->_eventoFrequenciaORM)) {
            $this->_eventoFrequenciaORM = new EventoFrequenciaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_FREQUENCIA);
        }
        return $this->_eventoFrequenciaORM;
    }

    /**
     * Metodo public para obter a instancia do FatoCicloORM
     * @return FatoCicloORM
     */
    public function getFatoCicloORM() {
        if (is_null($this->_fatoCicloORM)) {
            $this->_fatoCicloORM = new FatoCicloORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CICLO);
        }
        return $this->_fatoCicloORM;
    }

    public function getFatoMensalORM() {
        if (is_null($this->_fatoMensalORM)) {
            $this->_fatoMensalORM = new FatoMensalORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_MENSAL);
        }
        return $this->_fatoMensalORM;
    }

    /**
     * Metodo public para obter a instancia do FatoCelulaORM
     * @return FatoCelulaORM
     */
    public function getFatoCelulaORM() {
        if (is_null($this->_fatoCelulaORM)) {
            $this->_fatoCelulaORM = new FatoCelulaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CELULA);
        }
        return $this->_fatoCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do FatoLiderORM
     * @return FatoLiderORM
     */
    public function getFatoLiderORM() {
        if (is_null($this->_fatoLiderORM)) {
            $this->_fatoLiderORM = new FatoLiderORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_LIDER);
        }
        return $this->_fatoLiderORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return DimensaoORM
     */
    public function getDimensaoORM() {
        if (is_null($this->_dimensaoORM)) {
            $this->_dimensaoORM = new DimensaoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DIMENSAO);
        }
        return $this->_dimensaoORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return CircuitoORM
     */
    public function getDimensaoTipoORM() {
        if (is_null($this->_dimensaoTipoORM)) {
            $this->_dimensaoTipoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DIMENSAO_TIPO);
        }
        return $this->_dimensaoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do DimensaoTipoORM
     * @return GrupoCvORM
     */
    public function getGrupoCvORM() {
        if (is_null($this->_grupoCvORM)) {
            $this->_grupoCvORM = new GrupoCvORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_CV);
        }
        return $this->_grupoCvORM;
    }

    /**
     * Metodo public para obter a instancia do CursoORM
     * @return CircuitoORM
     */
    public function getCursoORM() {
        if (is_null($this->_cursoORM)) {
            $this->_cursoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_CURSO);
        }
        return $this->_cursoORM;
    }

    /**
     * Metodo public para obter a instancia do DisciplinaORM
     * @return DisciplinaORM
     */
    public function getDisciplinaORM() {
        if (is_null($this->_disciplinaORM)) {
            $this->_disciplinaORM = new DisciplinaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_DISCIPLINA);
        }
        return $this->_disciplinaORM;
    }

    /**
     * Metodo public para obter a instancia do AulaORM
     * @return CircuitoORM
     */
    public function getAulaORM() {
        if (is_null($this->_aulaORM)) {
            $this->_aulaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_AULA);
        }
        return $this->_aulaORM;
    }

    /**
     * Metodo public para obter a instancia do TurmaORM
     * @return TurmaORM
     */
    public function getTurmaORM() {
        if (is_null($this->_turmaORM)) {
            $this->_turmaORM = new TurmaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA);
        }
        return $this->_turmaORM;
    }

    /**
     * Metodo public para obter a instancia do SolicitacaoORM
     * @return SolicitacaoORM
     */
    public function getSolicitacaoORM() {
        if (is_null($this->_solicitacaoORM)) {
            $this->_solicitacaoORM = new SolicitacaoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO);
        }
        return $this->_solicitacaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSolicitacaoTipoORM() {
        if (is_null($this->_solicitacaoTipoORM)) {
            $this->_solicitacaoTipoORM = new SolicitacaoTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO_TIPO);
        }
        return $this->_solicitacaoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSolicitacaoSituacaoORM() {
        if (is_null($this->_solicitacaoSituacaoORM)) {
            $this->_solicitacaoSituacaoORM = new SolicitacaoSituacaoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SOLICITACAO_SITUACAO);
        }
        return $this->_solicitacaoSituacaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getSituacaoORM() {
        if (is_null($this->_situacaoORM)) {
            $this->_situacaoORM = new SolicitacaoTipoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_SITUACAO);
        }
        return $this->_situacaoORM;
    }

    /**
     * Metodo public para obter a instancia do FatoRankingORM
     * @return CircuitoORM
     */
    public function getFatoRankingORM() {
        if (is_null($this->_fatoRankingORM)) {
            $this->_fatoRankingORM = new FatoRankingORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_RANKING);
        }
        return $this->_fatoRankingORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getCursoAcessoORM() {
        if (is_null($this->_cursoAcessoORM)) {
            $this->_cursoAcessoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_CURSO_ACESSO);
        }
        return $this->_cursoAcessoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getPessoaCursoAcessoORM() {
        if (is_null($this->_pessoaCursoAcessoORM)) {
            $this->_pessoaCursoAcessoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PESSOA_CURSO_ACESSO);
        }
        return $this->_pessoaCursoAcessoORM;
    }

    /**
     * Metodo public para obter a instancia do FatoFinanceiro
     * @return CircuitoORM
     */
    public function getFatoFinanceiroORM() {
        if (is_null($this->_fatoFinanceiro)) {
			$this->_fatoFinanceiro = new FatoFinanceiroORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_FINANCEIRO);
        }
        return $this->_fatoFinanceiro;
    }

     /**
     * Metodo public para obter a instancia do FatoFinanceiroTipo
     * @return CircuitoORM
     */
    public function getFatoFinanceiroTipoORM() {
        if (is_null($this->_fatoFinanceiroTipo)) {
			$this->_fatoFinanceiroTipo = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_FINANCEIRO_TIPO);
        }
        return $this->_fatoFinanceiroTipo;
    }

     /**
     * Metodo public para obter a instancia do FatoFinanceiroSituacao
     * @return CircuitoORM
     */
    public function getFatoFinanceiroSituacaoORM() {
        if (is_null($this->_fatoFinanceiroSituacaoORM)) {
			$this->_fatoFinanceiroSituacaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_FINANCEIRO_SITUACAO);
        }
        return $this->_fatoFinanceiroSituacaoORM;
    }

	/**
	 * Metodo public para obter a instancia do PessoaFatoFinanceiroAcessoORM
     * @return CircuitoORM
     */
    public function getPessoaFatoFinanceiroAcessoORM() {
        if (is_null($this->_pessoaFatoFinanceiroAcessoORM)) {
			$this->_pessoaFatoFinanceiroAcessoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_PESSOA_FATO_FINANCEIRO_ACESSO);
        }
        return $this->_pessoaFatoFinanceiroAcessoORM;
    }

	/**
	 * Metodo public para obter a instancia do FatoFinanceiroAcessoORM
     * @return CircuitoORM
     */
    public function getFatoFinanceiroAcessoORM() {
        if (is_null($this->_fatoFinanceiroAcessoORM)) {
			$this->_fatoFinanceiroAcessoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_FINANCEIRO_ACESSO);
        }
        return $this->_fatoFinanceiroAcessoORM;
    }

    /**
     * Metodo public para obter a instancia do FatoRankingCelula
     * @return FatoRankingCelulaORM
     */
    public function getFatoRankingCelulaORM() {
        if (is_null($this->_fatoRankingCelulaORM)) {
			$this->_fatoRankingCelulaORM = new FatoRankingCelulaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_RANKING_CELULA);
        }
        return $this->_fatoRankingCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do FatoCurso
     * @return FatoCursoORM
     */
    public function getFatoCursoORM() {
        if (is_null($this->_fatoCursoORM)) {
			$this->_fatoCursoORM = new FatoCursoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CURSO);
        }
        return $this->_fatoCursoORM;
    }

    public function getEleitorORM() {
        if (is_null($this->_eleitorORM)) {
			$this->_eleitorORM = new EleitorORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ELEITOR);
        }
        return $this->_eleitorORM;
    }

    /**
     * Metodo public para obter a instancia do FatoSetentaORM
     * @return CircuitoORM
     */
    public function getFatoSetentaORM() {
        if (is_null($this->_fatoSetentaORM)) {
			$this->_fatoSetentaORM = new FatoSetentaORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_SETENTA);
        }
        return $this->_fatoSetentaORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getRegistroORM() {
        if (is_null($this->_registroORM)) {
			$this->_registroORM = new RegistroORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_REGISTRO);
        }
        return $this->_registroORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return CircuitoORM
     */
    public function getRegistroAcaoORM() {
        if (is_null($this->_registroAcaoORM)) {
			$this->_registroAcaoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_REGISTRO_ACAO);
        }
        return $this->_registroAcaoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return FatoDiscipuladoORM
     */
    public function getFatoDiscipuladoORM() {
        if (is_null($this->_fatoDiscipuladoORM)) {
			$this->_fatoDiscipuladoORM = new FatoDiscipuladoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_DISCIPULADO);
        }
        return $this->_fatoDiscipuladoORM;
    }

    /**
     * Metodo public para obter a instancia do CircuitoORM
     * @return FatoCelulaDiscipuladoORM
     */
    public function getFatoCelulaDiscipuladoORM() {
        if (is_null($this->_fatoCelulaDiscipuladoORM)) {
			$this->_fatoCelulaDiscipuladoORM = new FatoCelulaDiscipuladoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_CELULA_DISCIPULADO);
        }
        return $this->_fatoCelulaDiscipuladoORM;
    }

    public function getGrupoEventoORM() {
        if (is_null($this->_grupoEventoORM)) {
			$this->_grupoEventoORM = new GrupoEventoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_EVENTO);
        }
        return $this->_grupoEventoORM;
    }

    public function getEnvioORM() {
        if (is_null($this->_envioORM)) {
			$this->_envioORM = new EnvioORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_ENVIO);
        }
        return $this->_envioORM;
    }

    public function getFatoRevisaoORM() {
        if (is_null($this->_fatoRevisaoORM)) {
			$this->_fatoRevisaoORM = new FatoRevisaoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_FATO_REVISAO);
        }
        return $this->_fatoRevisaoORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Iniciar transação
     */
    public function iniciarTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->beginTransaction();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Fechar transação
     */
    public function fecharTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->commit();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Desfazer transação
     */
    public function desfazerTransacao() {
        try {
            $this->getDoctrineORMEntityManager()->rollback();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
