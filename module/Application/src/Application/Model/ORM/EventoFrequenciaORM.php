<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\CircuitoEntity;
use Application\Model\Entity\EventoFrequencia;
use Exception;
use DateTime;

/**
 * Nome: EventoFrequencia.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe com acesso doctrine a entity evento_frequencia
 */
class EventoFrequenciaORM extends CircuitoORM {

    /**
     * Busca Evento_Frequencia do Revisao por Id  (Não retorna excesção caso não encontre)
     * @param idEventoFrequencia
     */
    public function encontrarPorIdEventoFrequencia($idEventoFrequencia) {  
        $idInteiro = (int) $idEventoFrequencia;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade || ($entidade->getEvento()->getTipo_id() != Constantes::$ID_TIPO_REVISAO) ) {
            return false;
        }
        return $entidade;
    }   

	public function quantidadeFrequenciasPorEventoEDia($idEvento, $dia){
		$dataFormatada = DateTime::createFromFormat('Y-m-d', $dia);
		$dataFormatada->setTime(0,0,0);
		$dqlBase = "SELECT "
			. "count(ef.id) as quantidade "
			. "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
			. "WHERE "
			. "ef.frequencia = 'S' AND "
			. "ef.evento_id = ?1 AND "
			. "ef.dia = ?2 ";

		$resultado = $this->getEntityManager()->createQuery($dqlBase)
			->setParameter(1, (int) $idEvento)
			->setParameter(2, $dataFormatada)
			->getResult();

		return $resultado[0]['quantidade'];
	}

}
