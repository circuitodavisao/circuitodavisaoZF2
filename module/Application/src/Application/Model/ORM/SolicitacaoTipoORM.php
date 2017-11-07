<?php

namespace Application\Model\ORM;

use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: SolicitacaoTipoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity hierarquia
 */
class SolicitacaoTipoORM extends CircuitoORM {

    /**
     * Localizar todos os tipos
     * @return SolicitacaoTipo[]
     * @throws Exception
     */
    public function encontrarTodos() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum tipo de solicitacao");
        }
        return $entidades;
    }

}
