<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Dimensao;
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
     * @param \Application\Model\ORM\RepositorioORM $repositorioORM
     * @return type
     */
    public function encontrarPorNumeroIdentificador($numeroIdentificador, $ciclo, $mes, $ano, RepositorioORM $repositorioORM) {
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
            if (empty($resposta)) {
                $resposta = $this->criarFatoNoCiclo($numeroIdentificador, $ano, $mes, $ciclo, $repositorioORM);
            }
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar fato_ciclo por numeroIdentificador
     * @param string $numeroIdentificador
     * @param int $ciclo
     * @param int $mes
     * @param int $ano
     * @param int $tipoComparacao
     * @return array
     */
    public function montarRelatorioPorNumeroIdentificador($numeroIdentificador, $ciclo, $mes, $ano, $tipoComparacao) {
        $cicloInt = (int) $ciclo;
        $mesInt = (int) $mes;
        $anoInt = (int) $ano;
        $dimensaoTipoCulto = 2;
        $dimensaoTipoDomingo = 4;
        $dqlBase = "SELECT "
                . "SUM(d.lider) lideres, "
                . "SUM(d.visitante) visitantes, "
                . "SUM(d.consolidacao) consolidacoes, "
                . "SUM(d.membro) membros "
                . "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
                . "JOIN fc.dimensao d "
                . "WHERE "
                . "d.dimensaoTipo = #dimensaoTipo "
                . "AND fc.numero_identificador #tipoComparacao ?1 "
                . "AND fc.mes = ?2 "
                . "AND fc.ano = ?3 "
                . "AND fc.ciclo = ?4";
        try {
            if ($tipoComparacao == 1) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
            }
            if ($tipoComparacao == 2) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
                $numeroIdentificador .= '%';
            }
            for ($indice = $dimensaoTipoCulto; $indice <= $dimensaoTipoDomingo; $indice++) {
                $dqlAjustada = str_replace('#dimensaoTipo', $indice, $dqlAjustadaTipoComparacao);
                $result[$indice] = $this->getEntityManager()->createQuery($dqlAjustada)
                        ->setParameter(1, $numeroIdentificador)
                        ->setParameter(2, $mesInt)
                        ->setParameter(3, $anoInt)
                        ->setParameter(4, $cicloInt)
                        ->getResult();
            }
            return $result;
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
                $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
                if ($grupoSelecionado->getGrupoPaiFilhoPai()) {
                    $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                }
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === $tipoEquipe) {
                $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
                if ($grupoSelecionado->getGrupoPaiFilhoPai()) {
                    $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                }
            }
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === $tipoIgreja) {
                $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
            }

            return $numeroIdentificador;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Criar fato ciclo
     * @param type $numeroIdentificador
     * @param type $ano
     * @param type $mes
     * @param type $ciclo
     * @param \Application\Model\ORM\RepositorioORM $repositorioORM
     * @return type
     */
    public function criarFatoNoCiclo($numeroIdentificador, $ano, $mes, $ciclo, RepositorioORM $repositorioORM) {
        $fatoCiclo = new FatoCiclo();
        try {
            $fatoCiclo->setNumero_identificador($numeroIdentificador);
            $fatoCiclo->setAno($ano);
            $fatoCiclo->setMes($mes);
            $fatoCiclo->setCiclo($ciclo);
            $this->persistir($fatoCiclo);
            $dimensoes = $this->criarDimensoes($fatoCiclo, $repositorioORM);
            $fatoCicloPesquisa = $this->encontrarPorNumeroIdentificador($numeroIdentificador, $ciclo, $mes, $ano, $repositorioORM);
            $fatoCicloPesquisa->setDimensao($dimensoes);
            return $fatoCicloPesquisa;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Criar dimensões
     * @param type $fatoCiclo
     * @param RepositorioORM $repositorioORM
     * @return Dimensao
     */
    public function criarDimensoes($fatoCiclo, RepositorioORM $repositorioORM) {
        $dimensoes = null;
        try {
            for ($indiceDimensoes = 1; $indiceDimensoes <= 4; $indiceDimensoes++) {
                $dimensao = new Dimensao();
                $dimensao->setFatoCiclo($fatoCiclo);
                $dimensaoTipo = $repositorioORM->getDimensaoTipoORM()->encontrarPorId($indiceDimensoes);
                $dimensao->setDimensaoTipo($dimensaoTipo);
                $dimensao->setVisitante(0);
                $dimensao->setConsolidacao(0);
                $dimensao->setMembro(0);
                $dimensao->setLider(0);
                $repositorioORM->getDimensaoORM()->persistir($dimensao);
                $dimensoes[] = $dimensao;
            }
            return $dimensoes;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
