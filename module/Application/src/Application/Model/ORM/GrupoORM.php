<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Grupo;
use DateTime;

/**
 * Nome: GrupoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo
 */
class GrupoORM extends CircuitoORM {

    /**
     * Seta o relatorio como enviado
     * @param Grupo $grupo
     */
    function setRelatorioEnviado($grupo) {
        $grupo->setEnvio('S');
        $timeNow = new DateTime();
        $grupo->setEnvio_data($timeNow->format('Y-m-d'));
        $grupo->setEnvio_hora($timeNow->format('H:s:i'));
        $this->persistir($grupo);
    }

    /**
     * Seta o status de envio para não
     * @param Grupo $grupo
     */
    function setRelatorioPendente($grupo) {
        $grupo->setEnvio('N');
        $this->persistir($grupo);
    }

}
