<?php

namespace Cadastro\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoPaiFilho;
use Exception;

/**
 * Nome: GrupoPaiFilhoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity GRUPO_PAI_FILHO
 */
class GrupoPaiFilhoORM {

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
        $this->_entity = ConstantesCadastro::$ENTIDADE_GRUPO_PAI_FILHO;
    }

    /**
     * Atualiza a grupo_pai_filho no banco de dados
     * @param GrupoPaiFilho $grupoPaiFilho
     */
    public function persistirGrupoResponsavel($grupoPaiFilho) {
        try {
            $grupoPaiFilho->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($grupoPaiFilho);
            $this->getEntityManager()->flush($grupoPaiFilho);
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
