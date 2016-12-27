<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoAtendimento;
use Exception;

/**
 * Nome: GrupoAtendimentoORM.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo atendimento.
 */
class GrupoAtendimentoORM {

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
        $this->_entity = ConstantesLancamento::$ENTITY_GRUPO_ATENDIMENTO;
    }

    /**
     * Atualiza o grupo no banco de dados
     * 
     * 
     * @param GrupoAtendimento $grupoAtendimento
     */
    public function persistirGrupoAtendimento($grupoAtendimento) {
        try {
            if (empty($grupoAtendimento->getId())) {
                $this->getEntityManager()->persist($grupoAtendimento);
                $this->getEntityManager()->flush($grupoAtendimento);
            }else{
                $this->getEntityManager()->merge($grupoAtendimento);
                $this->getEntityManager()->flush();
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Localizar entidade por $idAtendimento
     * 
     * @param integer $idAtendimento
     * @return GrupoAtendimento
     * @throws Exception
     */
    public function encontrarPorIdAtendimento($idAtendimento) {
        $id = (int) $idAtendimento;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado atendimento de id = {$id}");
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
