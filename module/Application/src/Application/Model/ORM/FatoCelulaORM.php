<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\FatoCelula;
use Application\Model\Entity\FatoCiclo;
use DateTime;
use Exception;

/**
 * Nome: FatoCelulaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_celula
 */
class FatoCelulaORM extends CircuitoORM {

    /**
     * Cria o fato celula
     * @param FatoCiclo $fatoCiclo
     * @param integer $eventoCelulaId
     */
    public function criarFatoCelula($fatoCiclo, $eventoCelulaId, $estrategica = null) {
        $fatoCelula = new FatoCelula();
        try {
            $fatoCelula->setFatoCiclo($fatoCiclo);
            $fatoCelula->setRealizada(0);
            $fatoCelula->setEvento_celula_id($eventoCelulaId);
			if($estrategica){
				$fatoCelula->setEstrategica('S');
			}
            $this->persistir($fatoCelula);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Localizar entidade por evento_celula_id
     * @param integer $id
     * @return FatoCelula
     * @throws Exception
     */
    public function encontrarPorEventoCelulaId($id) {
        $idInteiro = (int) $id;
        $entidade = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->findOneBy(array(Constantes::$ENTITY_EVENTO_CELULA_ID => $idInteiro));
        if (!$entidade) {
            throw new Exception("Não foi encontrado a entidade de id = {$idInteiro}");
        }
        return $entidade;
    }

    /**
     * Localizar entidade por evento_celula_id
     * @param integer $id
     * @return FatoCelula
     * @throws Exception
     */
    public function encontrarPorEventoCelulaIdEFatoCiclo($idEventoCelula, $idFatoCiclo) {
        $idEventoCelulaInt = (int) $idEventoCelula;
        $idFatoCicloInt = (int) $idFatoCiclo;
        $entidade = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->findOneBy(array(
            Constantes::$ENTITY_EVENTO_CELULA_ID => $idEventoCelulaInt,
            'fato_ciclo_id' => $idFatoCicloInt
        ));
        if (!$entidade) {
            throw new Exception("Não foi encontrado a entidade de idEventoCelula = {$idEventoCelula} e idFatoCiclo = {$idFatoCiclo}");
        }
        return $entidade;
    }

	public function encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dia) {
		try {
			$dql = "SELECT fc.numero_identificador, c.evento_celula_id "
				. "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
				. "JOIN fc.fatoCelula c "
				. "WHERE "
				. "fc.numero_identificador LIKE ?1 AND fc.data_criacao = ?2 AND c.realizada = 0 ";

			$numeroIdentificador .= '%';
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, $numeroIdentificador)
				->setParameter(2, $dia)
				->getResult();


			return $result;
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
	}

  public function fatoCelulaExcluirRelatorioSegunda() {
    $dateFormatada = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
    $dataEmString = $dateFormatada->format('Y-m-d');
    $dqlExcluirFatoCelula = "DELETE FROM ".Constantes::$ENTITY_FATO_CELULA." fcel WHERE fcel.data_criacao = ?1";
    $result = $this->getEntityManager()->createQuery($dqlExcluirFatoCelula)
    ->setParameter(1, $dataEmString)
    ->getResult();
	}
}
