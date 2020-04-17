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

	public function encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador, $tipoComparacao = 2) {
		$resposta = null;
		$dqlBase = "SELECT fc "
				. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc "
				. "WHERE "
				. "fc.numero_identificador #tipoComparacao ?1 "				
				. "AND fc.data_inativacao is null ";
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
				$resposta = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->getResult();
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
	public function encontrarFatoCursoPorTurmaPessoa($idTurmaPessoa) {
		$resposta = null;
		try {
			$dql = "SELECT fc "
				. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc "
				. "WHERE "
				. " fc.turma_pessoa_id = ?1 "
				. "AND fc.data_inativacao is null ";
				$resposta = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $idTurmaPessoa)
				->getResult();
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function encontrarUltimoFatoCursoPorTurmaPessoa($idTurmaPessoa) {
		$resposta = null;
		try {
			$dql = "SELECT fc "
				. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc "
				. "WHERE "
				. " fc.turma_pessoa_id = ?1 ";				
				$resposta = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $idTurmaPessoa)
				->getResult();
			
			$ultimoFatoCurso = null;
			foreach ($resposta as $fatoCurso) {				
				if($ultimoFatoCurso === null){
					$ultimoFatoCurso = $fatoCurso;
				}
				if($fatoCurso->getId() > $ultimoFatoCurso->getId()){
					$ultimoFatoCurso = $fatoCurso;
				}				
			} 

			return $ultimoFatoCurso;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function encontrarFatoCursoPorTurma($idTurma) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findBy(array(
					'turma_id' => $idTurma,
					'data_inativacao' => null,
				));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function encontrarFatoCursoPorSituacaoEParte($idSituacao, $qualParte) {
		$resposta = array();
		$dqlBase = "SELECT fc.id "
			. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc "
			. "ORDER BY fc.id DESC ";
		$totalDivisoes = 50;
		try {
			$totalDeGrupos = $this->getEntityManager()
				->createQuery($dqlBase)
				->setMaxResults(1)
				->getResult()[0]['id'];
			$fracaoParaMontar = intVal($totalDeGrupos)/intVal($totalDivisoes);
			$inicio = 1;
			if($qualParte > 1){
				$inicio = $fracaoParaMontar * ($qualParte - 1);
			}

			$dqlBase = "SELECT fc "
				. "FROM  " . Constantes::$ENTITY_FATO_CURSO . " fc ";
			$entidades = $this->getEntityManager()
				->createQuery($dqlBase)
				->setFirstResult($inicio)
				->setMaxResults(intVal($fracaoParaMontar))
				->getResult();

			foreach($entidades as $entidade){
				if(
					$entidade->getSituacao_id() === $idSituacao &&
					$entidade->getData_inativacao() === null
				){
					$resposta[] = $entidade;
				}
			}

			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
}
