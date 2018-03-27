<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\CircuitoEntity;
use Application\Model\Entity\EventoFrequencia;
use Exception;

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

}
