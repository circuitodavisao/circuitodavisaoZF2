<?php

namespace Application\Model\ORM;

use Application\Model\Entity\CircuitoEntity;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: CircuitoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class CircuitoORM {

    private $_entityManager;
    private $_entity;

    /**
     * Construtor Sobrecarregado
     * @param EntityManager $entityManager
     * @param type $entity
     */
    public function __construct(EntityManager $entityManager = null, $entity = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        if (!is_null($entity)) {
            $this->_entity = $entity;
        }
    }

    /**
     * Localizar entidade por id
     * @param integer $id
     * @return CircuitoEntity
     * @throws Exception
     */
    public function encontrarPorId($id) {
        $idInteiro = (int) $id;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade) {
			$entidade = null;
        }
        return $entidade;
    }

    public function verificarSeExistePorId($id) {
        $idInteiro = (int) $id;

        $resposta = true;
        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade) {
            $resposta = false;
        }
        return $resposta;
    }

    /**
     * Buscar todos os registros da entidade
     * @return CircuitoEntity
     * @throws Exception
     */
    public function buscarTodosRegistrosEntidade($campoOrderBy = null, $sentidoOrderBy = null) {
        if ($campoOrderBy) {
            $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findBy(array(), array("$campoOrderBy" => "$sentidoOrderBy"));
        } else {
            $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        }
        if (!$entidades) {
            return false;
        }
        return $entidades;
    }

    /**
     * Atualiza a entidade no banco de dados
     * @param CircuitoEntity $entidade
     */
    public function persistir($entidade, $setarDataEHora = true) {
        try {
            if ($setarDataEHora) {
                $entidade->setDataEHoraDeCriacao();
            }
            $this->getEntityManager()->persist($entidade);
            $this->getEntityManager()->flush($entidade);
        } catch (Exception $exc) {
             error_log('mensagem de error: ' . $exc->getMessage());
        }
    }

    public function remover($entidade) {
        try {
            $this->getEntityManager()->remove($entidade);
            $this->getEntityManager()->flush($entidade);
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

    public function encontrarPorNomeETabela($nome, $tabela) {
        try {
            $dql = "SELECT e "
                    . "FROM  " . $tabela . " e "
                    . "WHERE "
                    . "e.nome LIKE ?1 ";

            $nomeAjustado = '%' . $nome . '%';
            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $nomeAjustado)
                    ->getResult();
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
