<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Pessoa;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;

/**
 * Nome: DeployController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de deploy
 */
class DeployController extends CircuitoController {

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
     * GET /deploy
     */
    public function indexAction() {

        $token = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
        if ($token === 'c76ec8866438d1e6ddc90909b0debbe3') {
            $stringHashtag = '###';
            $gitUser = 'lpmagalhaes';
            $gitPassword = 'leonardo142857';

            $linkGit = 'github.com/circuitodavisao/circuitodavisaoZf2.git master';
            echo 'deploy automatico';
            echo $stringHashtag . 'Iniciando o deploy' . $stringHashtag . PHP_EOL;
            $comando = 'git pull https://' . $gitUser . ':' . $gitPassword . '@' . $linkGit;
            echo '<pre>';
            passthru($comando);
            echo '</pre>';
            echo $stringHashtag . 'Fim do deploy' . $stringHashtag . PHP_EOL;
        } else {
            echo "Sem token";
        }
    }

    public function verUsuarioAction() {
        $idPessoa = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
        echo "<pre>";
        if ($idPessoa) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            if (intval($idPessoa)) {
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
                echo "<br />Nome: " . $pessoa->getNome();
                echo "<br />Documento: " . $pessoa->getDocumento();
                echo "<br />Email: " . $pessoa->getEmail();
                echo "<br />Senha: " . $pessoa->getSenha();
            } else {
                $resposta = $repositorioORM->getPessoaORM()->encontrarPorNome($idPessoa);

                for ($indiceResposta = 0; $indiceResposta < count($resposta); $indiceResposta++) {
                    echo "<br />############################################################";
                    echo "<br />Id: " . $resposta[$indiceResposta]['id'];
                    echo "<br />Nome: " . $resposta[$indiceResposta]['nome'];
                    echo "<br />Documento: " . $resposta[$indiceResposta]['documento'];
                    echo "<br />Email: " . $resposta[$indiceResposta]['email'];
                    echo "<br />Senha: " . $resposta[$indiceResposta]['senha'];
                }
            }
        } else {
            echo "<br />Sem idPessoa";
        }
        echo "</pre>";
    }

}
