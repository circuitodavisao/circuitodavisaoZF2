<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;

/**
 * Nome: HierarquiaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity hierarquia
 */
class HierarquiaORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_HIERARQUIA;
    }

    /**
     * Localizar Hierarquia por $idHierarquia
     * 
     * @param integer $idHierarquia
     * @return Hierarquia
     * @throws Exception
     */
    public function encontrarPorIdHierarquia($idHierarquia) {
        $id = (int) $idHierarquia;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a hierarquia de id = {$id}");
        }
        return $entidade;
    }

    /**
     * Localizar todos os tipos
     * @return GrupoPessoaTipo[]
     * @throws Exception
     */
    public function encontrarTodos() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum grupo_pessoa_tipo");
        }
        return $entidades;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
