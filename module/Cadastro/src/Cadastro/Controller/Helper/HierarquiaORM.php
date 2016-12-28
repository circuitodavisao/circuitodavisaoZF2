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
     * Localizar todos as hierarquias
     * @return Hieraquia[]
     * @throws Exception
     */
    public function encontrarTodas() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum grupo_pessoa_tipo");
        }
        return $entidades;
    }

    /**
     * Localizar todas as hierarquias
     * 
     * @param integer $idTipoEntidade
     * @return Hieraquia[]
     * @throws Exception
     */
    public function encontrarPorEntidadeTipo($idTipoEntidade) {
        $entidades = null;
//        switch ($idTipoEntidade) {
//            case 1:// Presidente cadastrado nacional
//                // Bispo
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 1);
//                break;
//            case 2:// Nacional cadastrado regiao
//                // Bispo
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 1);
//                break;
//            case 3:// Regiao cadastrado sub regiao
//                // Bispo
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 1);
//                // Pastor
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 2);
//                break;
//            case 4:// Sub Regiao cadastrado Coordenacao
//                // Bispo
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 1);
//                // Pastor
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 2);
//                // Missionario
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 3);
//                break;
//            case 5:// Coordenacao cadastrado Sub Coordenacao
//                // Bispo
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 1);
//                // Pastor
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 2);
//                // Missionario
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 3);
//                // Diacono
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 4);
//                // Obreiro
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 5);
//                break;
//            case 8:// Sub Equipe cadastrado Sub Equipe
//                // Obreiro
//                $entidades[] = $this->getEntityManager()->find($this->getEntity(), 6);
//                break;
//            default:
//                break;
//        }
//
//        if (!$entidades) {
//            throw new Exception("Não foi encontrado nenhuma hierarquia");
//        }
        return $entidades;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
