<?php

namespace Lancamento\Controller\Helper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\GrupoPessoa;
use Exception;
use Login\Controller\Helper\Constantes;

/**
 * Nome: GrupoPessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity grupo_pessoa
 */
class GrupoPessoaORM {

    private $_entityManager;
    private $_entity;

    /**
     * Construtor
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesLancamento::$ENTITY_GRUPO_PESSOA;
    }

    /**
     * Localizar entidade por $idGrupoPessoa
     * 
     * @param integer $idGrupoPessoa
     * @return GrupoPessoa
     * @throws Exception
     */
    public function encontrarPorIdGrupoPessoa($idGrupoPessoa) {
        $id = (int) $idGrupoPessoa;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a grupo_pessoa de id = {$id}");
        }
        return $entidade;
    }

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
                ->andWhere(Criteria::expr()->eq(ConstantesLancamento::$ENTITY_PESSOA_ID, $idPessoaLimpo))
                ->andWhere(Criteria::expr()->eq(ConstantesLancamento::$ENTITY_DATA_INATIVACAO, $ativo))
                ->andWhere(Criteria::expr()->eq(ConstantesLancamento::$ENTITY_TIPO_ID, $tipoLimpo))
        ;
        $grupoPessoas = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->matching($criteria);
        if (!empty($grupoPessoas)) {
            $entidade = $grupoPessoas[0];
        }
        return $entidade;
    }

    /**
     * Atualiza a grupo_pessoa no banco de dados
     * 
     * @param GrupoPessoa $grupoPessoa
     */
    public function persistirGrupoPessoa($grupoPessoa) {
        try {
            $this->getEntityManager()->persist($grupoPessoa);
            $this->getEntityManager()->flush($grupoPessoa);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

    /**
     * A cada dia verifica quem foi cadastrado a uma semana e atualiza para consolidação
     * @param LancamentoORM $lancamentoORM
     */
    public function alterarVisitanteParaConsolidacao(LancamentoORM $lancamentoORM) {
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
            echo "encontrou visistantes na data de 7 dias atras<br />";
            foreach ($grupoPessoas as $gp) {
                /* Recuperar o grupo pessoa ativo para saber o tipo */
                $grupoPessoaTipo = $gp->getGrupoPessoaTipo();
                /* Visitante */
                if ($gp->verificarSeEstaAtivo() && $grupoPessoaTipo->getId() == 1) {
                    echo "alterando {$gp->getPessoa()->getNome()} <br />";
                    /* Inativando o grupo pessoa de visitante */
                    $gp->setData_inativacao(date('Y-m-d'));
                    $gp->setHora_inativacao(date('H:s:i'));
                    $this->persistirGrupoPessoa($gp);

                    /* Criando um novo grupo pessoa de consolidação */
                    $grupoPessoaTipoConsolidacao = $lancamentoORM->getGrupoPessoaTipoORM()->encontrarPorIdGrupoPessoaTipo(2);
                    $grupoPessoaConsolidacao = new GrupoPessoa();
                    $grupoPessoaConsolidacao->setPessoa($gp->getPessoa());
                    $grupoPessoaConsolidacao->setGrupo($gp->getGrupo());
                    $grupoPessoaConsolidacao->setGrupoPessoaTipo($grupoPessoaTipoConsolidacao);
                    $grupoPessoaConsolidacao->setData_criacao(date('Y-m-d'));
                    $grupoPessoaConsolidacao->setHora_criacao(date('H:s:i'));
                    $grupoPessoaConsolidacao->setNucleo_perfeito($gp->getNucleo_perfeito());
                    $this->persistirGrupoPessoa($grupoPessoaConsolidacao);
                    echo "alterado !!!<br/>";
                }
            }
        } else {
            echo " nao encontrou visitantes para transformar<br />";
        }
    }

}
