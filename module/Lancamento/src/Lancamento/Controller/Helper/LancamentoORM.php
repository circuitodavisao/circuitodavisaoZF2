<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Nome: LancamentoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ORM
 */
class LancamentoORM {

    private $_doctrineORMEntityManager;
    private $_entidadeORM;
    private $_grupoORM;
    private $_eventoORM;
    private $_eventoFrequenciaORM;
    private $_grupoPessoaORM;
    private $_grupoPessoaTipoORM;
    private $_grupoAtendimentoORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do Helper EntidadeORM
     * @return EntidadeORM
     */
    public function getEntidadeORM() {
        if (is_null($this->_entidadeORM)) {
            $this->_entidadeORM = new EntidadeORM($this->getDoctrineORMEntityManager());
        }
        return $this->_entidadeORM;
    }

    /**
     * Metodo public para obter a instancia do Helper EventoORM
     * @return EventoORM
     */
    public function getEventoORM() {
        if (is_null($this->_eventoORM)) {
            $this->_eventoORM = new EventoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_eventoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper EventoFrequenciaORM
     * @return EventoFrequenciaORM
     */
    public function getEventoFrequenciaORM() {
        if (is_null($this->_eventoFrequenciaORM)) {
            $this->_eventoFrequenciaORM = new EventoFrequenciaORM($this->getDoctrineORMEntityManager());
        }
        return $this->_eventoFrequenciaORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoORM
     * @return GrupoORM
     */
    public function getGrupoORM() {
        if (is_null($this->_grupoORM)) {
            $this->_grupoORM = new GrupoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoPessoaORM
     * @return GrupoPessoaORM
     */
    public function getGrupoPessoaORM() {
        if (is_null($this->_grupoPessoaORM)) {
            $this->_grupoPessoaORM = new GrupoPessoaORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoPessoaORM;
    }
    
    /**
     * Metodo public para obter a instancia do Helper GrupoAtendimentoORM
     * @return GrupoAtendimentoORM
     */
    public function getGrupoAtendimentoORM() {
        if (is_null($this->_grupoAtendimentoORM)) {
            $this->_grupoAtendimentoORM = new GrupoAtendimentoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoAtendimentoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoPessoaORM
     * @return GrupoPessoaTipoORM
     */
    public function getGrupoPessoaTipoORM() {
        if (is_null($this->_grupoPessoaTipoORM)) {
            $this->_grupoPessoaTipoORM = new GrupoPessoaTipoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoPessoaTipoORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    
}
