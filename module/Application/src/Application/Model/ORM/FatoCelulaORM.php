<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\CircuitoEntity;
use Application\Model\Entity\FatoCelula;
use Application\Model\Entity\FatoCiclo;
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
    public function criarFatoCelula($fatoCiclo, $eventoCelulaId) {
        $fatoCelula = new FatoCelula();
        try {
            $fatoCelula->setFatoCiclo($fatoCiclo);
            $fatoCelula->setRealizada(0);
            $fatoCelula->setEvento_celula_id($eventoCelulaId);
            $this->persistir($fatoCelula);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }   

}
