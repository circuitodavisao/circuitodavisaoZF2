<?php

namespace Application\Model\ORM;

use Application\Model\Entity\FatoCelula;
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
     */
    public function criarFatoCelula($fatoCiclo) {
        $fatoCelula = new FatoCelula();
        try {
            $fatoCelula->setFatoCiclo($fatoCiclo);
            $fatoCelula->setRealizada(0);
            $this->persistir($fatoCelula);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
