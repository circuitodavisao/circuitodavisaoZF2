<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoEvento;
use Exception;

/**
 * Nome: GrupoEventoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_evento
 */
class GrupoEventoORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_GRUPO_EVENTO;
    }

    /**
     * Atualiza a grupo_evento no banco de dados
     * @param GrupoEvento $grupoEvento
     */
    public function persistirGrupoEvento($grupoEvento) {
        try {
            $this->getEntityManager()->persist($grupoEvento);
            $this->getEntityManager()->flush($grupoEvento);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
