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
     * @param RepositorioORM $repositorioORM
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
                $meta = 6;
                $resposta = $this->criarFatoNoCiclo($numeroIdentificador, $ano, $mes, $ciclo, $meta, $repositorioORM);
            }


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

    /**
     * Criar fato ciclo 
     * @param type $numeroIdentificador
     * @param type $ano
     * @param type $mes
     * @param type $ciclo
     * @param type $meta
     * @param RepositorioORM $repositorioORM
     * @return FatoCiclo
     */
    public function criarFatoNoCiclo($numeroIdentificador, $ano, $mes, $ciclo, $meta, RepositorioORM $repositorioORM) {
        $fatoCiclo = new FatoCiclo();
        try {
            $fatoCiclo->setNumero_identificador($numeroIdentificador);
            $fatoCiclo->setAno($ano);
            $fatoCiclo->setMes($mes);
            $fatoCiclo->setCiclo($ciclo);
            $fatoCiclo->setMeta($meta);
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
     * @param \Application\Model\ORM\RepositorioORM $repositorioORM
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
