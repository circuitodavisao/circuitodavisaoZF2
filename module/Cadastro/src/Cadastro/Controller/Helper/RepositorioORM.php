<?php

namespace Cadastro\Controller\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Nome: RepositorioORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ao repositorio ORM
 */
class RepositorioORM {

    private $_doctrineORMEntityManager;
    private $_eventoCelulaORM;
    private $_eventoTipoORM;
    private $_grupoEventoORM;
    private $_hierarquiaORM;
    private $_turmaAlunoORM;
    private $_pessoaHieraquiaORM;
    private $_entidadeTipoORM;
    private $_grupoResponsavelORM;
    private $_grupoPaiFilhoORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do Helper EventoTipoORM
     * @return EventoTipoORM
     */
    public function getEventoTipoORM() {
        if (is_null($this->_eventoTipoORM)) {
            $this->_eventoTipoORM = new EventoTipoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_eventoTipoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper EventoCelulaORM
     * @return EventoCelulaORM
     */
    public function getEventoCelulaORM() {
        if (is_null($this->_eventoCelulaORM)) {
            $this->_eventoCelulaORM = new EventoCelulaORM($this->getDoctrineORMEntityManager());
        }
        return $this->_eventoCelulaORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoEventoORM
     * @return GrupoEventoORM
     */
    public function getGrupoEventoORM() {
        if (is_null($this->_grupoEventoORM)) {
            $this->_grupoEventoORM = new GrupoEventoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoEventoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper HierarquiaORM
     * @return HierarquiaORM
     */
    public function getHierarquiaORM() {
        if (is_null($this->_hierarquiaORM)) {
            $this->_hierarquiaORM = new HierarquiaORM($this->getDoctrineORMEntityManager());
        }
        return $this->_hierarquiaORM;
    }

    /**
     * Metodo public para obter a instancia do Helper TurmaAlunoORM
     * @return TurmaAlunoORM
     */
    public function getTurmaAlunoORM() {
        if (is_null($this->_turmaAlunoORM)) {
            $this->_turmaAlunoORM = new TurmaAlunoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_turmaAlunoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper PessoaHierarquiaORM
     * @return PessoaHierarquiaORM
     */
    public function getPessoaHierarquiaORM() {
        if (is_null($this->_pessoaHieraquiaORM)) {
            $this->_pessoaHieraquiaORM = new PessoaHierarquiaORM($this->getDoctrineORMEntityManager());
        }
        return $this->_pessoaHieraquiaORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoResponsavelORM
     * @return GrupoResponsavelORM
     */
    public function getGrupoResponsavelORM() {
        if (is_null($this->_grupoResponsavelORM)) {
            $this->_grupoResponsavelORM = new GrupoResponsavelORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoResponsavelORM;
    }

    /**
     * Metodo public para obter a instancia do Helper EntidadeTipoORM
     * @return EntidadeTipoORM
     */
    public function getEntidadeTipoORM() {
        if (is_null($this->_entidadeTipoORM)) {
            $this->_entidadeTipoORM = new EntidadeTipoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_entidadeTipoORM;
    }

    /**
     * Metodo public para obter a instancia do Helper GrupoPaiFilhoORM
     * @return GrupoPaiFilhoORM
     */
    public function getGrupoPaiFilhoORM() {
        if (is_null($this->_grupoPaiFilhoORM)) {
            $this->_grupoPaiFilhoORM = new GrupoPaiFilhoORM($this->getDoctrineORMEntityManager());
        }
        return $this->_grupoPaiFilhoORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
