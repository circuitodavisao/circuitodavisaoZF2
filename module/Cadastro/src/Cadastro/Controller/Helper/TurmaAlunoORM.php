<?php

namespace Cadastro\Controller\Helper;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\TurmaAluno;
use Exception;

/**
 * Nome: TurmaAlunoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity turma_aluno
 */
class TurmaAlunoORM {

    private $_entityManager;
    private $_entity;

    public function __construct(EntityManager $entityManager = null) {
        if (!is_null($entityManager)) {
            $this->_entityManager = $entityManager;
        }
        $this->_entity = ConstantesCadastro::$ENTIDADE_TURMA_ALUNO;
    }

    /**
     * Localizar entidade por idTurmaAluno (matricula)
     * 
     * @param integer $idTurmaAluno
     * @return TurmaAluno
     * @throws Exception
     */
    public function encontrarPorIdTurmaAluno($idTurmaAluno) {
        $id = (int) $idTurmaAluno;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $id);
        if (!$entidade) {
            throw new Exception("Não foi encontrado a matricula de id = {$id}");
        }
        return $entidade;
    }

    /**
     * Atualiza a turma_aluno no banco de dados
     * @param TurmaAluno $turmaAluno
     */
    public function persistirTurmaAluno($turmaAluno) {
        try {
            $this->getEntityManager()->persist($turmaAluno);
            $this->getEntityManager()->flush($turmaAluno);
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

}
