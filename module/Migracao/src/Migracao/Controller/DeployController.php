<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;

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
        $resultado = array();
        if ($idPessoa) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

            if (intval($idPessoa)) {
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
                $dados = array();
                $dados['id'] = $pessoa->getId();
                $dados['nome'] = $pessoa->getNome();
                $dados['documento'] = $pessoa->getDocumento();
                $dados['email'] = $pessoa->getEmail();
                $dados['senha'] = $pessoa->getSenha();
                $dados['hierarquia'] = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
                if ($grupoResponsaveis = $pessoa->getResponsabilidadesAtivas()) {
                    foreach ($grupoResponsaveis as $grupoResponsavel) {
                        $dados['GrupoResponsabilidadeGrupoId-' . $grupoResponsavel->getId()] = $grupoResponsavel->getGrupo()->getId();
                        foreach ($grupoResponsavel->getGrupo()->getEntidade() as $entidade) {
                            $dados['Entidade-' . $grupoResponsavel->getId()] = $entidade->getId();
                            $dados['EntidadeInfo-' . $grupoResponsavel->getId()] = $entidade->infoEntidade();
                        }
                    }
                }

                $resultado[] = $dados;
            } else {
                $resposta = $repositorioORM->getPessoaORM()->encontrarPorNome($idPessoa);
                for ($indiceResposta = 0; $indiceResposta < count($resposta); $indiceResposta++) {
                    $dados = array();
                    $dados['id'] = $resposta[$indiceResposta]['id'];
                    $dados['nome'] = $resposta[$indiceResposta]['nome'];
                    $dados['documento'] = $resposta[$indiceResposta]['documento'];
                    $dados['email'] = $resposta[$indiceResposta]['email'];
                    $dados['senha'] = $resposta[$indiceResposta]['senha'];
                    $resultado[] = $dados;
                }
            }
        }

        return new ViewModel(array('resultado' => $resultado,));
    }

}
