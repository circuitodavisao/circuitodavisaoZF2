<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\RepositorioORM;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: PrincipalController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class PrincipalController extends CircuitoController {

    /**
     * Função padrão, traz a tela principal
     * GET /principal
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupo);

        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloAtual = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
        $cicloPassado = 0;

        if ($cicloAtual > 1) {
            $cicloPassado = $cicloAtual - 1;
        } else {
            $anoPesquisa = $anoSelecionado;
            $mesPesquisa = $mesSelecionado - 1;
            if ($mesSelecionado == 1) {
                $anoPesquisa = $anoSelecionado - 1;
                $mesPesquisa = 1;
            }
            $cicloPassado = Funcoes::totalCiclosMes($mesPesquisa, $anoPesquisa);
        }

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloPassado, $mesPesquisa, $anoPesquisa, $tipoRelatorioPessoal);
        $relatorioEquipe = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloPassado, $mesPesquisa, $anoPesquisa, $tipoRelatorioEquipe);
        $periodoSelecionado = Funcoes::periodoCicloMesAno($cicloPassado, $mesPesquisa, $anoPesquisa);

        echo "anoPesquisa$anoPesquisa mesPesquisa$mesPesquisa cicloPassado$cicloPassado";
        return new ViewModel(
                array(
            'periodo' => $periodoSelecionado,
            'relatorio' => $relatorio,
            'relatorioEquipe' => $relatorioEquipe,
        ));
    }

}
