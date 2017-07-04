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

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $periodo = -1;
        $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorioPessoal);
        $relatorioEquipe = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorioEquipe);


        $dados = array();
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $dados['periodo'] = $arrayPeriodo[0];
        $dados['relatorio'] = $relatorio;
        $dados['relatorioEquipe'] = $relatorioEquipe;

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhos();
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupoFilho);
                $tipoRelatorioSomado = 2;
                $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorioSomado);
                if ($relatorio['celulaQuantidade'] > 0) {
                    if ($relatorio['celulaRealizadas'] < $relatorio['celulaQuantidade']) {
                        $relatorioDiscipulos[$grupoFilho->getId()] = $relatorio;
                        $discipulos[] = $gpFilho;
                    }
                }
            }

            $dados['discipulos'] = $discipulos;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
        }

        return new ViewModel($dados);
    }

}
