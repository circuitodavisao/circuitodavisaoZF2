<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoPessoaTipo;
use Exception;

/**
 * Nome: GrupoPessoaTipoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_pessoa_tipo
 */
class GrupoPessoaTipoORM {

    private $_entityManager;
    private $_entity;

    /**
     * Construtor
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesLancamento::$ENTITY_GRUPO_PESSOA_TIPO;
    }

    /**
     * Localizar entidade por $idGrupoPessoaTipo
     * 
     * @param integer $idGrupoPessoaTipo
     * @return GrupoPessoaTipo
     * @throws Exception
     */
    public function encontrarPorIdGrupoPessoaTipo($idGrupoPessoaTipo) {
        $id = (int) $idGrupoPessoaTipo;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a grupo_pessoa_tipo de id = {$id}");
        }
        return $entidade;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
