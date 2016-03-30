<?php

namespace Login\Controller\Helper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ORM\EntityManager;
use Exception;
use Login\Entity\Pessoa;
use Zend\XmlRpc\Value\Integer;

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
        $pessoa = $this->getEntityManager()
                ->getRepository(Constantes::$ENTITY_PESSOA)
                ->findOneBy(array(Constantes::$ENTITY_PESSOA_EMAIL => $email));
        return $pessoa;
    }

    /**
     * Localizar pessoa por cpf e data de nascimento
     * 
     * @param Integer $cpf
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
                ->getRepository(Constantes::$ENTITY_PESSOA)
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

    public function getEntityManager() {
        return $this->_entityManager;
    }

    public function getEntity() {
        return $this->_entity;
    }

}
