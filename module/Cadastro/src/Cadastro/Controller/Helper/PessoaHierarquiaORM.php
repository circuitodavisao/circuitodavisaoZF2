<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\PessoaHierarquia;
use Exception;

/**
 * Nome: PessoaHierarquiaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa_hierarquia
 */
class PessoaHierarquiaORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_PESSOA_HIERARQUIA;
    }

    /**
     * Cria/Atualiza a pessoa_hierarquia no banco de dados
     * @param PessoaHierarquia $pessoaHierarquia
     */
    public function persistirPessoaHierarquia($pessoaHierarquia) {
        try {
            $pessoaHierarquia->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($pessoaHierarquia);
            $this->getEntityManager()->flush($pessoaHierarquia);
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
