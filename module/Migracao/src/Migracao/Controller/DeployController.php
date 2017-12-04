<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Application\Model\Entity\EventoTipo;
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
            $pessoas = array();
            if (intval($idPessoa)) {
                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoas[] = $pessoa;
            } else {
                $resposta = $repositorioORM->getPessoaORM()->encontrarPorNome($idPessoa);
                for ($indiceResposta = 0; $indiceResposta < count($resposta); $indiceResposta++) {
                    $pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($resposta[$indiceResposta]['id']);
                    $pessoas[] = $pessoa;
                }
            }

            $dados = array();
            foreach ($pessoas as $pessoa) {
                $dados['data_criacao'] = $pessoa->getData_criacaoStringPadraoBanco();
                $dados['id'] = $pessoa->getId();
                $dados['nome'] = $pessoa->getNome();
                $dados['documento'] = $pessoa->getDocumento();
                $dados['email'] = $pessoa->getEmail();
                $dados['senha'] = $pessoa->getSenha();
                if ($pessoa->getPessoaHierarquiaAtivo()) {
                    $dados['hierarquia'] = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
                }
                if ($grupoResponsaveis = $pessoa->getResponsabilidadesAtivas()) {
                    foreach ($grupoResponsaveis as $grupoResponsavel) {
                        foreach ($grupoResponsavel->getGrupo()->getEntidade() as $entidade) {
                            $dados['Entidade-' . $grupoResponsavel->getId() . ' Status'] = $entidade->verificarSeEstaAtivo();
                            $dados['Entidade-' . $grupoResponsavel->getId()] = $entidade->infoEntidade();
                        }
                        if ($grupoEventoCelula = $grupoResponsavel->getGrupo()->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula)) {
                            foreach ($grupoEventoCelula as $grupoEvento) {
                                $dados['Celula ' . $grupoEvento->getId() . ' Status'] = $grupoEvento->getEvento()->verificarSeEstaAtivo();
                                $dados['Celula ' . $grupoEvento->getId() . ' Hospedeiro'] = $grupoEvento->getEvento()->getEventoCelula()->getNome_hospedeiro();
                                $dados['Celula ' . $grupoEvento->getId() . ' Dia'] = $grupoEvento->getEvento()->getDia();
                                $dados['Celula ' . $grupoEvento->getId() . ' Hora'] = $grupoEvento->getEvento()->getHora();
                            }
                        }
                    }
                }
                $resultado[] = $dados;
            }
        }

        return new ViewModel(array('resultado' => $resultado,));
    }

}
