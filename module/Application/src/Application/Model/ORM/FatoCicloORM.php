<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\FatoCiclo;
use Application\Model\Entity\Grupo;
use Exception;

/**
 * Nome: FatoCicloORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ciclo
 */
class FatoCicloORM extends CircuitoORM {

    /**
     * Localizar fato_ciclo por numeroIdentificador
     * @param type $numeroIdentificador
     * @param type $ciclo
     * @param type $mes
     * @param type $ano
     * @return FatoCiclo
     */
    public function encontrarPorNumeroIdentificador($numeroIdentificador, $ciclo, $mes, $ano) {
        $cicloInt = (int) $ciclo;
        $mesInt = (int) $mes;
        $anoInt = (int) $ano;
        try {
            $resposta = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(
                    array(
                        Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador,
                        Constantes::$ENTITY_FATO_CICLO_MES => $mesInt,
                        Constantes::$ENTITY_FATO_CICLO_ANO => $anoInt,
                        Constantes::$ENTITY_FATO_CICLO_CICLO => $cicloInt,
            ));
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Montar numeroIdentificador
     * @param Grupo $grupo
     * @return string
     * @throws Exception
     */
    public function montarNumeroIdentificador($grupo) {
        $numeroIdentificador = null;
        $tamanho = 8;
        try {
            $tipoSubequipe = 7;
            $tipoEquipe = 6;
            $tipoIgreja = 5;
            $grupoSelecionado = $grupo;
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === $tipoSubequipe) {
                $numeroIdentificador .= str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT);
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === $tipoEquipe) {
                $numeroIdentificador .= str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT);
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === $tipoIgreja) {
                $numeroIdentificador .= str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT);
            }

            return $numeroIdentificador;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
