<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Migracao\Controller\IndexController;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Doctrine\Common\Collections\Criteria;
use Exception;
use DateTime;

/**
 * Nome: GrupoPessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_pessoa
 */
class GrupoPessoaORM extends CircuitoORM {

    /**
     * Localizar entidade por $idPessoa, se $ativo e $tipo
     * 
     * @param int $idPessoa
     * @param String $ativo
     * @param int $tipo
     * @return type
     * @throws Exception
     */
    public function encontrarPorIdPessoaAtivoETipo($idPessoa, $ativo, $tipo) {
        $entidade = null;
        $idPessoaLimpo = (int) $idPessoa;
        $tipoLimpo = (int) $tipo;

        $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->eq(Constantes::$ENTITY_PESSOA_ID, $idPessoaLimpo))
                ->andWhere(Criteria::expr()->eq(Constantes::$ENTITY_DATA_INATIVACAO, $ativo))
                ->andWhere(Criteria::expr()->eq(Constantes::$ENTITY_TIPO_ID, $tipoLimpo))
        ;
        try {
            $grupoPessoas = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->matching($criteria);

            if (!empty($grupoPessoas)) {
                $entidade = $grupoPessoas[0];
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $entidade;
    }

    /**
     * A cada dia verifica quem foi cadastrado a uma semana e atualiza para consolidação
     * @param RepositorioORM $repositorioORM
     */
	public function alterarVisitanteParaConsolidacao(RepositorioORM $repositorioORM) {
		$dataParaInativar = IndexController::getDataParaInativacao();
		$grupoPessoas = $this->getEntityManager()
			->getRepository($this->getEntity())
			->findBy(array(
				Constantes::$ENTITY_PESSOA_DATA_INATIVACAO => null, 
					'tipo_id' => GrupoPessoaTipo::VISITANTE,
			));
		try {
			if ($grupoPessoas) {
				foreach ($grupoPessoas as $grupoPessoa) {
					$grupoPessoa->setDataEHoraDeInativacao($dataParaInativar);
					$repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa, $alterarDataDeCriacao = false);

					$grupoPessoaTipoConsolidacao = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorId(GrupoPessoaTipo::CONSOLIDACAO);
					$grupoPessoaConsolidacao = new GrupoPessoa();
					$grupoPessoaConsolidacao->setPessoa($grupoPessoa->getPessoa());
					$grupoPessoaConsolidacao->setGrupo($grupoPessoa->getGrupo());
					$grupoPessoaConsolidacao->setGrupoPessoaTipo($grupoPessoaTipoConsolidacao);
					$grupoPessoaConsolidacao->setNucleo_perfeito($grupoPessoa->getNucleo_perfeito());
					$repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoaConsolidacao);
				}
			} else {
				echo " nao encontrou visitantes para transformar<br />";
			}

		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	public function grupoPessoasAtivosNoPEriodo($idGrupo, $periodo) {
        $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
        $dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];
		$dql = "SELECT "
			. " gp "
			. "FROM  " . Constantes::$ENTITY_GRUPO_PESSOA . " gp "
                . "WHERE "
                . " gp.grupo_id = ?1 "
                . " AND gp.data_inativacao IS NULL "
                . " AND gp.data_criacao <= ?2 ";
		try {
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, (int) $idGrupo)
				->setParameter(2, $dataFinalFormatada)
				->getResult();
			return $result;
		} catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

	public function grupoPessoasInativosNoPEriodo($idGrupo, $periodo) {
        $resultadoPeriodo = Funcoes::montaPeriodo($periodo);
        $dataDoPeriodoFinal = $resultadoPeriodo[6] . '-' . $resultadoPeriodo[5] . '-' . $resultadoPeriodo[4];
        $dataDoPeriodoInicial = $resultadoPeriodo[3] . '-' . $resultadoPeriodo[2] . '-' . $resultadoPeriodo[1];
		$dql = "SELECT "
			. " gp "
			. "FROM  " . Constantes::$ENTITY_GRUPO_PESSOA . " gp "
                . "WHERE "
                . " gp.grupo_id = ?1 "
                . " AND gp.data_inativacao IS NOT NULL "
                . " AND gp.data_criacao <= ?2 "
                . " AND gp.data_inativacao >= ?3 ";
		try {
			$dataFinalFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoFinal);
			$dataInicialFormatada = DateTime::createFromFormat('Y-m-d', $dataDoPeriodoInicial);
			$result = $this->getEntityManager()->createQuery($dql)
				->setParameter(1, (int) $idGrupo)
				->setParameter(2, $dataFinalFormatada)
				->setParameter(3, $dataInicialFormatada)
				->getResult();
			return $result;
		} catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
