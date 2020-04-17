<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: TurmaPessoaAulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_pessoa_aula
 */
class TurmaPessoaAulaORM extends CircuitoORM {

	public function encontrarPorTurmaPessoaEAula($idTurmaPessoa, $idAula) {
		$resposta = null;
		$dql = "SELECT "
			. " tpa "
			. "FROM  " . Constantes::$ENTITY_TURMA_PESSOA_AULA . " tpa "
			. "WHERE "
			. "tpa.aula_id = ?2 "
			. "AND tpa.turma_pessoa_id = ?1 ";
		try {
			$resultado = $this->getEntityManager()->createQuery($dql)
				->setParameter(1,(int) $idTurmaPessoa)
				->setParameter(2,(int) $idAula)
				->getResult();

			if($resultado){
				$resposta = $resultado[0];
			}

			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

}
