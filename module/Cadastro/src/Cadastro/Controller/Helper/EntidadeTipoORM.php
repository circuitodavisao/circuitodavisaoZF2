<?php

namespace Cadastro\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\EntidadeTipo;
use Exception;

/**
 * Nome: EntidadeTipoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity entidade_tipo
 */
class EntidadeTipoORM {

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
        $this->_entity = ConstantesCadastro::$ENTIDADE_ENTIDADE_TIPO;
    }

    /**
     * Localizar entidade por idEntidadeTipo
     * 1 - PRESIDENCIAL
     * 2 - REGIÃO
     * 3 - SUB REGIÃO
     * 4 - COORDENAÇÃO
     * 5 - SUB COORDENAÇÃO
     * 6 - IGREJA
     * 7 - EQUIPE
     * 8 - SUB EQUIPE
     * @param integer $idEntidadeTipo
     * @return EntidadeTipo
     * @throws Exception
     */
    public function encontrarPorIdEntidade($idEntidadeTipo) {
        $id = (int) $idEntidadeTipo;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a entidade tipo de id = {$id}");
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
