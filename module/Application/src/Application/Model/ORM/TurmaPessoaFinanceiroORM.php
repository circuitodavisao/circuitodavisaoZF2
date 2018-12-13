<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\CircuitoORM;
use Exception;
use DateTime;

/**
 * Nome: TurmaPessoaFinanceiroORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_pessoa_frequencia
 */
class TurmaPessoaFinanceiroORM extends CircuitoORM {

	public function encontrarPorDatas($dataInicial, $dataFinal) {
		try {
			$dql = "SELECT tpf "
				. "FROM  " . Constantes::$ENTITY_TURMA_PESSOA_FINANCEIRO . " tpf "
				. "WHERE "
				. "tpf.data_criacao >= ?1 AND tpf.data_criacao <= ?2 order by tpf.data_criacao asc";

			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataInicial);
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataFinal);
			$resultados = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $dataInicialFormatada)
				->setParameter(2, $dataFinalFormatada)
				->getResult();
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		return $resultados;
	}
}
