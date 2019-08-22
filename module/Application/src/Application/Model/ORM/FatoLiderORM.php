<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\FatoLider;
use DateTime;
use Exception;

/**
 * Nome: FatoLiderORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_lider
 */
class FatoLiderORM extends CircuitoORM {

	/**
	 * Localizar fato_lider por numeroIdentificador
	 * @param String $numeroIdentificador
	 * @param integer $tipoComparacao
	 * @return String
	 */
	public function encontrarPorNumeroIdentificador($numeroIdentificador, $tipoComparacao, $periodo = 0, $inativo = false) {
		$dqlBase = "SELECT "
			. "SUM(fl.lideres) lideres "
			. "FROM  " . Constantes::$ENTITY_FATO_LIDER . " fl "
			. "WHERE "
			. "fl.numero_identificador #tipoComparacao ?1 "
			. "AND ((fl.data_criacao <= ?2 AND fl.data_inativacao IS NULL) OR (#inativo)) ";

		$resultadoPeriodo = Funcoes::montaPeriodo($periodo);
		$dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
		$dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

		$dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];
		$dqlBase = str_replace('#inativo', ' fl.data_criacao <= ?2 AND fl.data_inativacao >= \'' . $dataDoPeriodoFinal . '\' ', $dqlBase);
		try {
			if ($tipoComparacao == 1) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
			}
			if ($tipoComparacao == 2) {
				$dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
				$numeroIdentificador .= '%';
			}
			$result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dataDoPeriodoFormatada)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

	public function encontrarFatoLiderPorNumeroIdentificador($numeroIdentificador) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findOneBy(array(Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function encontrarMultiplosFatosLiderPorNumeroIdentificador($numeroIdentificador) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findBy(array(Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
	
	public function encontrarTodosFatosLideresAtivos(){
        $dqlBase = "SELECT fl "
                . "FROM  " . Constantes::$ENTITY_FATO_LIDER . " fl "                
                . "WHERE "                
                . "fl.data_inativacao is null ";                                
         try {
            $result = $this->getEntityManager()->createQuery($dqlBase)
                    ->getResult();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
	}

	public function encontrarFatoLiderPorNumeroGrupo($numeroIdentificador) {
		$dql = "SELECT fl "
			. "FROM  " . Constantes::$ENTITY_FATO_LIDER . " fl "
			. "WHERE "
			. "fl.numero_identificador LIKE ?1 ";

		try {
			$numeroIdentificador = '%'.$numeroIdentificador;
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroIdentificador)
				->getResult();
			return $result;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function encontrarVariosFatoLiderPorNumeroIdentificador($numeroIdentificador) {
		$resposta = null;
		try {
			$entidade = $this->getEntityManager()
				->getRepository($this->getEntity())
				->findBy(array(Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador));
			if ($entidade) {
				$resposta = $entidade;
			}
			return $resposta;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	/**
	 * Criar fato lider
	 * @param String $numeroIdentificador
	 * @param integer $quantidadeDeLideres
	 */
	public function criarFatoLider($numeroIdentificador, $quantidadeDeLideres, $dataCriacao = null) {
		$fatoLider = new FatoLider();
		$alterarDiaDeCriacao = true;
		if ($dataCriacao) {
			$fatoLider->setDataEHoraDeCriacao($dataCriacao);
			$alterarDiaDeCriacao = false;
		}
		try {
			$fatoLider->setNumero_identificador($numeroIdentificador);
			$fatoLider->setLideres($quantidadeDeLideres);
			$this->persistir($fatoLider, $alterarDiaDeCriacao);
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

}
