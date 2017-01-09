<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\ORM\CircuitoORM;
use Doctrine\Common\Collections\Criteria;
use Exception;

/**
 * Nome: GrupoPessoaTipoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_pessoa_tipo
 */
class GrupoPessoaTipoORM extends CircuitoORM {

    /**
     * Localizar todos os tipos
     * @return GrupoPessoaTipo[]
     * @throws Exception
     */
    public function encontrarTodos() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum grupo_pessoa_tipo");
        }
        return $entidades;
    }

    /**
     * Localizar os tipos de pessoa para lançamento de dados
     * @return GrupoPessoaTipo[]
     * @throws Exception
     */
    public function tipoDePessoaLancamento() {
        $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->in(Constantes::$ID, [1, 2, 3]));
        try {
            $entidades = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->matching($criteria);
            return $entidades;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
