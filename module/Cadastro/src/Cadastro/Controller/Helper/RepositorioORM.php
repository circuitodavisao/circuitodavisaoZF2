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
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
