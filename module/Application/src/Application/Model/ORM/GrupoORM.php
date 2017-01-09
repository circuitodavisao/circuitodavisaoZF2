<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Grupo;

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
        $this->persistir($grupo);
    }

}
