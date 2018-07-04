<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TurmaPessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_pessoa
 */
class TurmaPessoaORM extends CircuitoORM {

    public function encontrarPorIdAntigo($idAntigo) {
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_TURMA_PESSOA_ID_ANTIGO => $idAntigo));
            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
