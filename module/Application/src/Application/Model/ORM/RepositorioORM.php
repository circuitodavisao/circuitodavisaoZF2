<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Doctrine\ORM\EntityManager;

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
    private $_eventoORM;
    private $_eventoCelulaORM;
    private $_grupoEventoORM;
    private $_eventoTipoORM;
    private $_hierarquiaORM;
    private $_turmaAlunoORM;
    private $_pessoaHierarquiaORM;
    private $_grupoResponsavelORM;
    private $_grupoPaiFilhoORM;
    private $_grupoAtendimentoORM;
    private $_eventoFrequenciaORM;

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
     * Metodo public para obter a instancia do GrupoPessoaORM
     * @return CircuitoORM
     */
    public function getGrupoResponsavelORM() {
        if (is_null($this->_grupoResponsavelORM)) {
            $this->_grupoResponsavelORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_RESPONSAVEL);
        }
        return $this->_grupoResponsavelORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoPessoaORM
     * @return CircuitoORM
     */
    public function getGrupoPaiFilhoORM() {
        if (is_null($this->_grupoPaiFilhoORM)) {
            $this->_grupoPaiFilhoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_PAI_FILHO);
        }
        return $this->_grupoPaiFilhoORM;
    }

    /**
     * Metodo public para obter a instancia do GrupoORM
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
     * Metodo public para obter a instancia do GrupoEventoORM
     * @return CircuitoORM
     */
    public function getGrupoEventoORM() {
        if (is_null($this->_grupoEventoORM)) {
            $this->_grupoEventoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_GRUPO_EVENTO);
        }
        return $this->_grupoEventoORM;
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
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getTurmaAlunoORM() {
        if (is_null($this->_turmaAlunoORM)) {
            $this->_turmaAlunoORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_TURMA_ALUNO);
        }
        return $this->_turmaAlunoORM;
    }

    /**
     * Metodo public para obter a instancia do EventoTipoORM
     * @return CircuitoORM
     */
    public function getEventoFrequenciaORM() {
        if (is_null($this->_eventoFrequenciaORM)) {
            $this->_eventoFrequenciaORM = new CircuitoORM($this->getDoctrineORMEntityManager(), Constantes::$ENTITY_EVENTO_FREQUENCIA);
        }
        return $this->_eventoFrequenciaORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
