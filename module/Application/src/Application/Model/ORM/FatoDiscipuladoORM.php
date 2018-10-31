<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;
use DateTime;

class FatoDiscipuladoORM extends CircuitoORM {

	public function encontrarPorGrupoPessoaMesAno($idGrupo, $idPessoa, $mes, $ano) {
       try {
            $entidade = $this->getEntityManager()
                    ->getRepository($this->getEntity())
					->findOneBy(array(
						'grupo_id' => $idGrupo,
						'pessoa_id' => $idPessoa,
						'mes' => $mes,
						'ano' => $ano,
					));
			return $entidade;
		} catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
