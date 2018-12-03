<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\CircuitoORM;
use DateTime;

class DimensaoORM extends CircuitoORM {

	public function dimensaoExcluirRelatorioSegunda() {
    $dateFormatada = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
    $dataEmString = $dateFormatada->format('Y-m-d');
    $dqlExcluirDimensoes = "DELETE from ".Constantes::$ENTITY_DIMENSAO." dim where dim.data_criacao = ?1";
    $result = $this->getEntityManager()->createQuery($dqlExcluirDimensoes)
    ->setParameter(1, $dataEmString)
    ->getResult();
	}

}
