<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\FatoLider;
use DateTime;
use Exception;

/**
 * Nome: FatoRankingCelulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ranking_celula
 */
class FatoRankingCelulaORM extends CircuitoORM {

	public function encontrarPorMesEAno($mes, $ano) {
		$dqlBase = "SELECT "
			. "frc "
			. "FROM  " . Constantes::$ENTITY_FATO_RANKING_CELULA . " frc "
			. "WHERE "
			. "frc.mes = ?1 "
			. "AND frc.ano = ?2 "
			. "ORDER BY frc.id ASC";
		try {
			$result = $this->getEntityManager()->createQuery($dqlBase)
				->setParameter(1, $mes)
				->setParameter(2, $ano)
				->getResult();

			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function encontrarPorIdGrupoIgreja($idGrupoIgreja, $mes, $ano) {
		$dqlBase = "SELECT "
			. "frc "
			. "FROM  " . Constantes::$ENTITY_FATO_RANKING_CELULA . " frc "
			. "WHERE "
			. "frc.grupo_id = ?1 "
			. "AND frc.mes = ?2 "
			. "AND frc.ano = ?3 "
			. "ORDER BY frc.id ASC";

		try {
			$result = $this->getEntityManager()->createQuery($dqlBase)
				->setParameter(1, $idGrupoIgreja)
				->setParameter(2, $mes)
				->setParameter(3, $ano)
				->getResult();

			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function encontrarPorIdGrupoEquipe($idGrupoEquipe, $mes, $ano) {
		$dqlBase = "SELECT "
			. "frc "
			. "FROM  " . Constantes::$ENTITY_FATO_RANKING_CELULA . " frc "
			. "WHERE "
			. "frc.grupo_equipe_id = ?1 "
			. "AND frc.mes = ?2 "
			. "AND frc.ano = ?3 "
			. "ORDER BY frc.id ASC";

		try {
			$result = $this->getEntityManager()->createQuery($dqlBase)
				->setParameter(1, $idGrupoEquipe)
				->setParameter(2, $mes)
				->setParameter(3, $ano)
				->getResult();

			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

}
