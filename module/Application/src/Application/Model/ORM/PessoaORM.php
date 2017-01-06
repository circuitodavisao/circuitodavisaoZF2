<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Pessoa;
use Cadastro\Form\ConstantesForm;
use Doctrine\Common\Collections\Criteria;
use Exception;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class PessoaORM extends CircuitoORM {

    /**
     * Localizar pessoa por idPessoa
     * 
     * @param integer $idPessoa
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorIdPessoa($idPessoa) {
        $id = (int) $idPessoa;

        $pessoa = $this->getEntityManager()->find($this->getEntity(), $id);
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
     * Localizar pessoa por CPF
     * 
     * @param String $CPF
     * @return Pessoa
     * @throws Exception
     */
    public function encontrarPorCPF($CPF) {
        $resposta = null;
        try {
            $pessoa = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(Constantes::$ENTITY_PESSOA_DOCUMENTO => $CPF));
            if ($pessoa) {
                $resposta = $pessoa;
            }
            return $resposta;
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
     * Atualiza a pessoa no banco de dados
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

    /**
     * Criar a pessoa no banco de dados
     * 
     * @param Pessoa $pessoa
     */
    public function persistirPessoaNova($pessoa) {

        try {
            $this->getEntityManager()->persist($pessoa);
            $this->getEntityManager()->flush($pessoa);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Atualiza a aluno com dados da busca do cpf
     * 
     * @param Pessoa $pessoa
     * @param array $post_data
     * @param int $tipo
     * @return Pessoa $pessoa
     */
    public function atualizarAlunoComDadosDaBuscaPorCPF($pessoa, $post_data, $tipo = 0) {
        try {
            $pessoa->setDocumento(intval($post_data[ConstantesForm::$FORM_CPF . $tipo]));
            $pessoa->setNome($post_data[ConstantesForm::$FORM_NOME . $tipo]);
            $pessoa->setEmail($post_data[ConstantesForm::$FORM_EMAIL . $tipo]);
            $pessoa->setData_nascimento(Funcoes::mudarPadraoData($post_data[ConstantesForm::$FORM_DATA_NASCIMENTO . $tipo], 0));
            $this->persistirPessoaNova($pessoa);

            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
