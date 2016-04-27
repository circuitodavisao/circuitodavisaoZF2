<?php

namespace Login\Controller\Helper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Exception;
use Entidade\Entity\Pessoa;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class PessoaORM {

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
        $this->_entity = Constantes::$ENTITY_PESSOA;
    }

    /**
     * Localizar pessoa por idPessoa
     * 
     * @param integer $idPessoa
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorIdPessoa($idPessoa) {
        $id = (int) $idPessoa;

        $pessoa = $this->getEntityManager()->find($this->getEntity(), $idPessoa);
        if (!$pessoa) {
            throw new Exception("Não foi encontrado a pessoa de id = {$id}");
        }
        return $pessoa;
    }

    /**
     * Localizar pessoa por email
     * 
     * @param String $email
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorEmail($email) {
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_EMAIL => $email));
            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar pessoa por token
     * 
     * @param String $token
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorToken($token) {
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_TOKEN => $token));
            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Localizar pessoa por cpf e data de nascimento
     * 
     * @param int $cpfUltimosDigitos
     * @param String $dataNascimento
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorCPFEDataNascimento($cpfUltimosDigitos, $dataNascimento) {

        $criteria = new Criteria();
        $criteria->andWhere(
                $criteria->expr()->eq(
                        Constantes::$ENTITY_PESSOA_DATA_NASCIMENTO, $dataNascimento
                )
        );
        $pessoas = $this->getEntityManager()
                ->getRepository($this->getEntity())
                ->matching($criteria);

        $pessoa = null;
        foreach ($pessoas as $p) {
            $cpfTratado = substr(str_pad($p->getDocumento(), 11, 0, STR_PAD_LEFT), 9, 2);
            if ($cpfTratado == $cpfUltimosDigitos) {
                $pessoa = $p;
            }
        }
        return $pessoa;
    }

    /**
     * Atualiza a pessoa no banco de ados
     * 
     * @param Pessoa $pessoa
     */
    public function persistirPessoa($pessoa) {

        try {
            $this->getEntityManager()->flush($pessoa);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
