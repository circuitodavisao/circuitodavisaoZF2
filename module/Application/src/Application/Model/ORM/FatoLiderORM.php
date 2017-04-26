<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\FatoLider;
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
    public function encontrarPorNumeroIdentificador($numeroIdentificador, $tipoComparacao) {
        $dqlBase = "SELECT "
                . "SUM(fl.lideres) lideres "
                . "FROM  " . Constantes::$ENTITY_FATO_LIDER . " fl "
                . "WHERE "
                . " fl.numero_identificador #tipoComparacao ?1 "
                . "AND fl.data_inativacao IS NULL";
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
                    ->getResult();
//            echo "<pre>";
//            var_dump($result);
//            echo "</pre>";
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Criar fato lider
     * @param String $numeroIdentificador
     * @param integer $quantidadeDeLideres
     */
    public function criarFatoLider($numeroIdentificador, $quantidadeDeLideres) {
        $fatoLider = new FatoLider();
        try {
            $fatoLider->setNumero_identificador($numeroIdentificador);
            $fatoLider->setLideres($quantidadeDeLideres);
            $this->persistir($fatoLider);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
