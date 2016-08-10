<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;

/**
 * Nome: EventoTipoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity evento_tipo
 */
class EventoTipoORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_EVENTO_TIPO;
    }

    /**
     * Localizar evento tipo por idEventoTipo
     * 1 - Culto
     * 2 - Célula
     * @param integer $idEventoTipo
     * @return Evento
     * @throws Exception
     */
    public function encontrarPorIdEventoTipo($idEventoTipo) {
        $id = (int) $idEventoTipo;

        $evento = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$evento) {
            throw new Exception("Não foi encontrado ao evento tipo de id = {$id}");
        }
        return $evento;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
