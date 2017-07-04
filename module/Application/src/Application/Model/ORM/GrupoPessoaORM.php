<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\GrupoPessoa;
use Doctrine\Common\Collections\Criteria;
use Exception;

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
        $ultimaSemana = strtotime('-7 days');
        $dataUltimaSemana = date('Y-m-d', $ultimaSemana);
        $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->eq(Constantes::$ENTITY_PESSOA_DATA_CRIACAO, $dataUltimaSemana))
                ->andWhere(Criteria::expr()->eq(Constantes::$ENTITY_PESSOA_DATA_INATIVACAO, null))
        ;
        $grupoPessoas = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->matching($criteria);
        if (!empty($grupoPessoas)) {
            foreach ($grupoPessoas as $gp) {
                /* Recuperar o grupo pessoa ativo para saber o tipo */
                $grupoPessoaTipo = $gp->getGrupoPessoaTipo();
                /* Visitante */
                if ($gp->verificarSeEstaAtivo() && $grupoPessoaTipo->getId() == 1) {
                    /* Inativando o grupo pessoa de visitante */
                    $gp->setData_inativacao(date('Y-m-d'));
                    $gp->setHora_inativacao(date('H:s:i'));
                    $this->persistirGrupoPessoa($gp);

                    /* Criando um novo grupo pessoa de consolidação */
                    $grupoPessoaTipoConsolidacao = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorIdGrupoPessoaTipo(2);
                    $grupoPessoaConsolidacao = new GrupoPessoa();
                    $grupoPessoaConsolidacao->setPessoa($gp->getPessoa());
                    $grupoPessoaConsolidacao->setGrupo($gp->getGrupo());
                    $grupoPessoaConsolidacao->setGrupoPessoaTipo($grupoPessoaTipoConsolidacao);
                    $grupoPessoaConsolidacao->setData_criacao(date('Y-m-d'));
                    $grupoPessoaConsolidacao->setHora_criacao(date('H:s:i'));
                    $grupoPessoaConsolidacao->setNucleo_perfeito($gp->getNucleo_perfeito());
                    $this->persistirGrupoPessoa($grupoPessoaConsolidacao);
                }
            }
        } else {
//            echo " nao encontrou visitantes para transformar<br />";
        }
    }

}
