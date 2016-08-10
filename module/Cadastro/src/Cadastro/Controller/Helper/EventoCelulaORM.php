<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;

/**
 * Nome: EventoCelulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity evento_celula
 */
class EventoCelulaORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_EVENTO_CELULA;
    }

    /**
     * Atualiza a evento_celula no banco de dados
     * @param EventoCelula $eventoCelula
     */
    public function persistirEventoCelula($eventoCelula) {
        try {
            $this->getEntityManager()->persist($eventoCelula);
            $this->getEntityManager()->flush($eventoCelula);
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
