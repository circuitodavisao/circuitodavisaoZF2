<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoResponsavel;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\View\Model\ViewModel;

/**
 * Nome: IndexController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de migração
 */
class IndexController extends CircuitoController {

    private $conexao;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /
     */
    public function indexAction() {
        $view = new ViewModel();
        echo "abrindo a conexao";
        $this->abreConexao();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $queryIgrejas = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_igreja_ursula WHERE id = 1');
        while ($row = mysqli_fetch_array($queryIgrejas)) {
            $nomeIgreja = $row['nome'];
            $queryLider1 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_pessoa_ursula WHERE id = ' . $row['idResponsavel1']);
            if ($row['idResponsavel2']) {
                $queryLider2 = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_pessoa_ursula WHERE id = ' . $row['idResponsavel2']);
            } 

            $queryEquipes = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_equipe_ursula WHERE idIgreja = 1');
            while ($row = mysqli_fetch_array($queryEquipes)) {
                $nomeEquipe = $row['nome'];

                $querySubEquipes = mysqli_query($this->getConexao(), 'SELECT * FROM ursula_subequipe_ursula WHERE idEquipe = 1');
                while ($row = mysqli_fetch_array($querySubEquipes)) {
                    $numeroSubequipe = $row['numero'];
                }
            }
        }
//        while ($row = mysqli_fetch_array($queryigrejas)) {
//            $grupoIgreja = new Grupo();
////            $repositorioORM->getGrupoORM()->persistir($grupoIgreja);
//            $entidadeNova = new Entidade();
//            $entidadeNova->setEntidadeTipo(
//                    $repositorioORM->getEntidadeTipoORM()->encontrarPorId(6)
//            );
//            $entidadeNova->setGrupo($grupoIgreja);
//            $entidadeNova->setNome($row[1]);
////            $repositorioORM->getEntidadeORM()->persistir($entidadeNova);
//            
//            $grupoResposanvel = new GrupoResponsavel();
//            $grupoResposanvel->setGrupo($grupoIgreja);
//            $grupoResposanvel->setPessoa($repositorioORM->getPessoaORM()->encontrarPorId(1));
////            $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResposanvel);
//
//            echo '<br />igreja: ' . $row[1];
//        }


        return $view;
    }

    private function abreConexao() {
        try {
            if (empty($this->getConexao())) {
                $this->setConexao(mysqli_connect('167.114.118.195', 'circuito_visao2', 'Z#03SOye(hRN', 'circuito_visao', '3306'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    function getConexao() {
        return $this->conexao;
    }

    function setConexao($conexao) {
        $this->conexao = $conexao;
        return $this;
    }

}
