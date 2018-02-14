<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TurmaPessoaFrequenciaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_pessoa_frequencia
 */
class TurmaPessoaFrequenciaORM extends CircuitoORM {

    public function encontrarTodosAtivos() {
        $dql = "SELECT tpf.id "
                . "FROM  " . Constantes::$ENTITY_TURMA_PESSOA_FREQUENCIA . " tpf "
                . "WHERE tpf.data_inativacao IS NULL";
        try {
            $result = $this->getEntityManager()->createQuery($dql)
                    ->getResult();

            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
