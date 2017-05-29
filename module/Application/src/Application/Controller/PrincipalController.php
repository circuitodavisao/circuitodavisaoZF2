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
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);

        if ($cicloSelecionado > 1) {
            $cicloSelecionado--;
        } else {
            /* Mês Passado */
            if ($cicloSelecionado == 1) {
                if (date('n') == 1) {
                    $mesSelecionado = 12;
                    $anoSelecionado = date('Y') - 1;
                } else {
                    $mesSelecionado = date('n') - 1;
                    $anoSelecionado = date('Y');
                }
                $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
            }
        }

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioPessoal);
        $relatorioEquipe = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioEquipe);

        $periodoSelecionado = Funcoes::periodoCicloMesAno($cicloSelecionado, $mesSelecionado, $anoSelecionado);


        return new ViewModel(
                array(
            'periodo' => $periodoSelecionado,
            'relatorio' => $relatorio,
            'relatorioEquipe' => $relatorioEquipe,
        ));
    }

}
