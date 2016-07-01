<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoPessoa;
use Exception;

/**
 * Nome: GrupoPessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_pessoa
 */
class GrupoPessoaORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_GRUPO_PESSOA;
    }

    /**
     * Localizar entidade por $idGrupoPessoa
     * 
     * @param integer $idGrupoPessoa
     * @return GrupoPessoa
     * @throws Exception
     */
    public function encontrarPorIdGrupoPessoa($idGrupoPessoa) {
        $id = (int) $idGrupoPessoa;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a grupo_pessoa de id = {$id}");
        }
        return $entidade;
    }

    /**
     * Atualiza a grupo_pessoa no banco de dados
     * 
     * @param GrupoPessoa $grupoPessoa
     */
    public function persistirGrupoPessoa($grupoPessoa) {

        try {
            $this->getEntityManager()->flush($grupoPessoa);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
