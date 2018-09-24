<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\FatoCurso;
use DateTime;
use Exception;

/**
 * Nome: FatoCursoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_curso
 */
class FatoCursoORM extends CircuitoORM {

	public function encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador) {
		$resposta = null;
		try {
			$dql = "SELECT fc "
				. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc "
				. "WHERE "
				. " fc.numero_identificador LIKE ?1 "
				. "AND fc.data_inativacao is null ";
				$resposta = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroIdentificador . '%')
				->getResult();
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
	public function encontrarFatoCursoPorTurmaPessoa($idTurmaPessoa) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findBy(array('turma_pessoa_id' => $idTurmaPessoa));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
	public function encontrarFatoCursoPorTurma($idTurma) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findBy(array('turma_id' => $idTurma));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
}
