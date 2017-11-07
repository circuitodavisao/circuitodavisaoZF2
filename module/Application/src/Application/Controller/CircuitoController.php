<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Nome: CircuitoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle com propriedade do ORM
 */
class CircuitoController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $repositorio;

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Mostrar as mensagens de erro
     * @param type $mensagens
     */
    public function direcionaErroDeCadastro($mensagens) {
        echo "ERRO: Cadastro invalido!<br /><br />########################<br />";
        foreach ($mensagens as $l => $value) {
            echo "key? $l<br >";
            foreach ($value as $key => $value) {
                echo "$key => $value <br />";
            }
        }
    }

    /**
     * Retona a entidade Logada
     */
    public static function getEntidadeLogada($repositorioORM, $sessao) {
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        return $entidade;
    }

    /**
     * Verifica a sessao
     */
    public static function verificandoSessao($sessao, $abstractActionController) {
        if (empty($sessao->idPessoa)) {
            return $abstractActionController->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
                        Constantes::$TIPO => 4,
                        Constantes::$MENSAGEM => 'Sua sessão expirou!',
            ));
        } else {
            return true;
        }
    }

    /**
     * Verifica a sessao
     */
    public static function direcionandoAoLogin($abstractActionController) {
        return $abstractActionController->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                    Constantes::$ACTION => Constantes::$ACTION_INDEX,
                    Constantes::$TIPO => 4,
                    Constantes::$MENSAGEM => 'Sua sessão expirou!',
        ));
    }

    public function getRepositorio() {
        if (empty($this->repositorio)) {
            $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
        }
        return $this->repositorio;
    }

}
