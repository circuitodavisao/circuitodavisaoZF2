<?php

namespace Cadastro\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoResponsavel;
use Exception;

/**
 * Nome: GrupoResponsavelORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity GRUPO_RESPONSAVEL
 */
class GrupoResponsavelORM {

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
        $this->_entity = ConstantesCadastro::$ENTIDADE_GRUPO_RESPONSAVEL;
    }

    /**
     * Atualiza a grupo_responsavel no banco de dados
     * @param GrupoResponsavel $grupoResponsavel
     */
    public function persistirGrupoResponsavel($grupoResponsavel) {
        try {
            $grupoResponsavel->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($grupoResponsavel);
            $this->getEntityManager()->flush($grupoResponsavel);
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
