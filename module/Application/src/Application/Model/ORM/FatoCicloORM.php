<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Dimensao;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\FatoCiclo;
use Application\Model\Entity\Grupo;
use DateTime;
use Exception;
use Zend\Session\Container;

/**
 * Nome: FatoCicloORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity fato_ciclo
 */
class FatoCicloORM extends CircuitoORM {

    public function encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dia, RepositorioORM $repositorioORM) {
        try {
            $resposta = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(
                    array(
                        Constantes::$ENTITY_FATO_CICLO_NUMERO_IDENTIFICADOR => $numeroIdentificador,
                        'data_criacao' => $dia,
            ));
            if (empty($resposta)) {
                $resposta = $this->criarFatoNoCiclo($numeroIdentificador, $dia, $repositorioORM);
            }
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar fato_ciclo por numeroIdentificador
     * @param type $numeroIdentificador
     * @param type $ciclo
     * @param type $mes
     * @param type $ano
     * @param RepositorioORM $repositorioORM
     * @return FatoCiclo
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
     * @param int $periodo
     * @param int $tipoComparacao
     * @return array
     */
    public function montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodo, $tipoComparacao) {
        $dimensaoTipoCelula = 1;
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
                . "AND fc.data_criacao = ?2 ";
        try {
            if ($tipoComparacao == 1) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
            }
            if ($tipoComparacao == 2) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
                $numeroIdentificador .= '%';
            }
            $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
            $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
            $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);

            for ($indice = $dimensaoTipoCelula; $indice <= $dimensaoTipoDomingo; $indice++) {
                $dqlAjustada = str_replace('#dimensaoTipo', $indice, $dqlAjustadaTipoComparacao);
                $result[$indice] = $this->getEntityManager()->createQuery($dqlAjustada)
                        ->setParameter(1, $numeroIdentificador)
                        ->setParameter(2, $dataDoPeriodoFormatada)
                        ->getResult();
            }
            return $result;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar fato_ciclo por numeroIdentificador
     * @param string $numeroIdentificador
     * @param int $periodo
     * @param int $tipoComparacao
     * @return array
     */
    public function montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodo, $tipoComparacao) {
        $dqlBase = "SELECT "
                . "COUNT(c.id) quantidade, "
                . "SUM(c.realizada) realizadas "
                . "FROM  " . Constantes::$ENTITY_FATO_CICLO . " fc "
                . "JOIN fc.fatoCelula c "
                . "WHERE "
                . "fc.numero_identificador #tipoComparacao ?1 "
                . "AND fc.data_criacao = ?2 ";
        try {
            if ($tipoComparacao == 1) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', '=', $dqlBase);
            }
            if ($tipoComparacao == 2) {
                $dqlAjustadaTipoComparacao = str_replace('#tipoComparacao', 'LIKE', $dqlBase);
                $numeroIdentificador .= '%';
            }
            $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
            $dataDoPeriodo = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
            $dataDoPeriodoFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodo);
            $result = $this->getEntityManager()->createQuery($dqlAjustadaTipoComparacao)
                    ->setParameter(1, $numeroIdentificador)
                    ->setParameter(2, $dataDoPeriodoFormatada)
                    ->getResult();


            return $result;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Montar numeroIdentificador
     */
    public function montarNumeroIdentificador(RepositorioORM $repositorioORM, $grupo = null) {
        $numeroIdentificador = null;
        $tamanho = 8;

        if ($grupo === null) {
            $sessao = new Container(Constantes::$NOME_APLICACAO);
            $idEntidadeAtual = $sessao->idEntidadeAtual;
            $entidadeSelecionada = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
            $grupoSelecionado = $entidadeSelecionada->getGrupo();
        } else {
            $grupoSelecionado = $grupo;
        }

        try {
            if ($grupoSelecionado->getEntidadeAtiva()) {
                while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                    $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
                    if ($grupoSelecionado->getGrupoPaiFilhoPai()) {
                        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                    }
                }
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                    $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
                    if ($grupoSelecionado->getGrupoPaiFilhoPai()) {
                        $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                    }
                }
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    $numeroIdentificador = str_pad($grupoSelecionado->getId(), $tamanho, 0, STR_PAD_LEFT) . $numeroIdentificador;
                }
            }

            return $numeroIdentificador;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Criar fato ciclo
     */
    public function criarFatoNoCiclo($numeroIdentificador, $dia, RepositorioORM $repositorioORM) {
        $fatoCiclo = new FatoCiclo();
        try {
            $fatoCiclo->setNumero_identificador($numeroIdentificador);
            $fatoCiclo->setDataEHoraDeCriacao();
            $fatoCiclo->setData_criacao($dia);
            $this->persistir($fatoCiclo, false);
            $dimensoes = $this->criarDimensoes($fatoCiclo, $repositorioORM);
            $fatoCicloPesquisa = $this->encontrarPorId($fatoCiclo->getId());
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
