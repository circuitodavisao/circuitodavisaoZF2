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

	public function turmaPessoaFinanceiroPorIgrejaMesEAno($idIgreja, $mes, $ano) {
		$dql = "SELECT "
			. "tpf.id, tpf.turma_pessoa_id, tpf.disciplina_id, tpf.mes1, tpf.ano1, tpf.valor1, tpf.mes2, tpf.ano2, tpf.valor2, tpf.mes3, tpf.ano3, tpf.valor3 "
			. "FROM  " . Constantes::$ENTITY_TURMA_PESSOA_FINANCEIRO . " tpf "
			. "JOIN tpf.turma_pessoa tp "
			. "JOIN tp.turma t "
			. "WHERE "
			. "t.grupo_id = ?1 "
			. "AND (tpf.mes1 = ?2 OR tpf.mes2 = ?2 OR tpf.mes3 = ?2) "
			. "AND (tpf.ano1 = ?3 OR tpf.ano2 = ?3 OR tpf.ano3 = ?3) "
			. "AND (tpf.valor1 = 'S' OR tpf.valor2 = 'S' OR tpf.valor3 = 'S') "
			. "AND tpf.data_inativacao is null ";
		try {			
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $idIgreja)
				->setParameter(2, $mes)
				->setParameter(3, $ano)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
}


