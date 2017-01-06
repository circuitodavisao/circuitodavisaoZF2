<?php

namespace Lancamento\Controller\Helper;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\Entidade;
use Exception;

/**
 * Nome: EntidadeORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class EntidadeORM extends CircuitoORM {

    /**
     * Localizar entidade por idEntidade
     * 
     * @param integer $idEntidade
     * @return Entidade
     * @throws Exception
     */
    public function encontrarPorIdEntidade($idEntidade) {
        $id = (int) $idEntidade;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a entidade de id = {$id}");
        }
        return $entidade;
    }

    /**
     * Atualiza a entidade no banco de dados
     * @param Entidade $entidade
     */
    public function persistirEntidade($entidade) {
        try {
            $entidade->setDataEHoraDeCriacao();
            $this->getEntityManager()->persist($entidade);
            $this->getEntityManager()->flush($entidade);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
