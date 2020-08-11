<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Pessoa;
use Application\Model\Entity\EventoTipo;
use Doctrine\Common\Collections\Criteria;
use Exception;
use DateTime;

/**
 * Nome: PessoaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity pessoa
 */
class PessoaORM extends CircuitoORM {
	
    public function getLideresPorSexo($sexo) {
        try {
            $dql = "SELECT p.nome, p.telefone "
                    . "FROM  " . Constantes::$ENTITY_PESSOA . " p "
                    . "WHERE "
                    . "p.sexo = ?1 AND p.documento IS NOT NULL ";
            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $sexo)
                    ->getResult();
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
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
     * Localizar pessoa por nome
     */
    public function encontrarPorNome($nome) {
        try {
            $dql = "SELECT p.id, p.nome, p.documento, p.email, p.senha "
                    . "FROM  " . Constantes::$ENTITY_PESSOA . " p "
                    . "WHERE "
                    . "p.nome LIKE ?1 ";

            $nomeAjustado = '%' . $nome . '%';
            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $nomeAjustado)
                    ->getResult();
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getMessage();
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

    public function verificarSeTemCPFCadastrado($CPF) {
        $resposta = false;
        try {
            $dql = "SELECT p.id, p.nome, p.documento, p.email, p.senha "
                    . "FROM  " . Constantes::$ENTITY_PESSOA . " p "
                    . "WHERE "
                    . "p.documento = ?1 ";

            $resultado = $this->getEntityManager()->createQuery($dql)
                    ->setParameter(1, $CPF)
                    ->getResult();
            if (count($resultado) > 0) {
                $resposta = true;
            }
            return $resposta;
        } catch (Exception $exc) {
            echo $exc->getMessage();
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
     * Atualiza a aluno com dados da busca do cpf
     * 
     * @param Pessoa $pessoa
     * @param array $post_data
     * @param int $tipo
     * @return Pessoa $pessoa
     */
    public function atualizarAlunoComDadosDaBuscaPorCPF($pessoa, $post_data, $tipo = 0) {
        try {
            $pessoa->setDocumento(intval($post_data[Constantes::$FORM_CPF . $tipo]));
            $pessoa->setNome($post_data[Constantes::$FORM_NOME . $tipo]);
            $pessoa->setEmail($post_data[Constantes::$FORM_EMAIL . $tipo]);
            $pessoa->setData_nascimento(Funcoes::mudarPadraoData($post_data[Constantes::$FORM_DATA_NASCIMENTO . $tipo], 0));
            $this->persistir($pessoa, false);

            return $pessoa;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Busca Pessoa Lider para o Revisao por Id  (Não retorna excesção caso não encontre)
     * @param idPessoa
     */
    public function encontrarPorIdPessoaParaRevisao($idPessoa) {
        $idInteiro = (int) $idPessoa;

        $entidade = $this->getEntityManager()->find($this->getEntity(), $idInteiro);
        if (!$entidade) {
            return false;
        }
        return $entidade;
    }

	public function verificarFrequenciaPorPessoaEventoEDia($idPessoa, $idEvento, $dia){
		$resposta = false;
		$dataFormatada = DateTime::createFromFormat('Y-m-d', $dia);
		$dataFormatada->setTime(0,0,0);
		$dqlBase = "SELECT "
			. "ef.id "
			. "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
			. "WHERE "
			. "ef.frequencia = 'S' AND "
			. "ef.pessoa_id = ?1 AND "
			. "ef.evento_id = ?2 AND "
			. "ef.dia = ?3 ";

		$resultado = $this->getEntityManager()->createQuery($dqlBase)
			->setParameter(1, (int) $idPessoa)
			->setParameter(2, (int) $idEvento)
			->setParameter(3, $dataFormatada)
			->getResult();

		if($resultado){
			$resposta = true;
		}
		return $resposta;
	}

	public function verificarSeFezReservaNoRevisaoDeVidas($idPessoa){
		$resposta = false;
		$dqlBase = "SELECT "
			. "ef.id "
			. "FROM  " . Constantes::$ENTITY_EVENTO_FREQUENCIA . " ef "
			. "JOIN ef.evento e "
			. "WHERE "
			. "ef.pessoa_id = ?1 AND "
			. "e.tipo_id = " . EventoTipo::tipoRevisao;

		$resultado = $this->getEntityManager()->createQuery($dqlBase)
			->setParameter(1, (int) $idPessoa)
			->getResult();

		if($resultado){
			$resposta = true;
		}
		return $resposta;
    }
    
      /**
     * Localizar pessoa por nome
     */
    public function encontrarPorDDDRegiaoBispoLucas() {                 
        try {
            $dql = "SELECT p.id, p.nome, p.telefone, p.data_nascimento FROM " . Constantes::$ENTITY_PESSOA . " p "
           . " WHERE " 
           . " (p.telefone > 61000000000  AND p.telefone < 61999999999 AND p.documento is not null) OR  "           
           . " (p.telefone > 87000000000  AND p.telefone < 87999999999 AND p.documento is not null) OR  "          
           . " (p.telefone > 81000000000  AND p.telefone < 81999999999 AND p.documento is not null) OR  "
           . " (p.telefone > 79000000000  AND p.telefone < 79999999999 AND p.documento is not null)";                 
                                          
            $resultado = $this->getEntityManager()->createQuery($dql)->getResult();
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

	public function pegarTodosSemEmailQueSaoLideres(){
		$dqlBase = "SELECT "
			. "p.id "
			. "FROM  " . Constantes::$ENTITY_PESSOA . " p "
			. "WHERE "
			. "p.email = 'atualize' AND p.documento IS NOT NULL";

		$resultado = $this->getEntityManager()->createQuery($dqlBase)
			->getResult();

		return $resultado;
	}

    public function encontrarVariosPorEmail($email) {
        try {
            $pessoas = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findBy(array(Constantes::$ENTITY_PESSOA_EMAIL => $email));
            return $pessoas;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

	public function eleitoresPorLocalidade($localidade){
		try {
			$dqlBase = "SELECT "
				. "p.nome, p.telefone, p.email, p.data_nascimento, p.sexo  "
				. "FROM  " . Constantes::$ENTITY_PESSOA . " p "
				. "WHERE "
				. "p.localidade_uf = ?1 AND "
				. "p.email IS NOT NULL AND p.email != 'atualize' AND p.email != '' AND "
				. "p.documento IS NOT NULL AND "
				. "p.telefone IS NOT NULL AND p.telefone > 999999999 AND p.telefone != 999999999 AND "
				. "p.data_nascimento IS NOT NULL "
				. "ORDER BY p.nome ASC";

			$resultado = $this->getEntityManager()
					 ->createQuery($dqlBase)
					 ->setParameter(1, $localidade)
					 ->getResult();

			return $resultado;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function localidadeUF(){
		try {
			$dqlBase = "SELECT "
				. " p.localidade_uf  "
				. "FROM  " . Constantes::$ENTITY_PESSOA . " p WHERE p.localidade_uf != '' AND p.localidade_uf != '/' GROUP BY p.localidade_uf";

			$resultado = $this->getEntityManager()->createQuery($dqlBase) ->getResult();

			return $resultado;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

}
