<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: RelatorioController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class RelatorioController extends CircuitoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela principal
     * GET /relatorio
     */
    public function indexAction() {
        
    }

    /**
     * Função padrão, traz a tela principal
     * GET /relatorioMembresia
     */
    public function membresiaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupo);
        /* Aba selecionada e ciclo */
        $abaSelecionada = $this->params()->fromRoute(Constantes::$ID);
        if (empty($abaSelecionada)) {
            $abaSelecionada = 1;
        }
        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);

        if ($abaSelecionada == 2) {
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
        }
        $tipoRelatorioPessoal = 1;
        $relatorio = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioPessoal);
        $discipulos = $grupo->getGrupoPaiFilhoFilhos();
        $periodoSelecionado = Funcoes::periodoCicloMesAno($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorioPessoal);
        echo "<pre>";
        var_dump($fatoLider);
        echo "</pre>";
        return new ViewModel(
                array(
            'relatorio' => $relatorio,
            'periodoSelecionado' => $periodoSelecionado,
            'discipulos' => $discipulos,
            'repositorioORM' => $repositorioORM,
            'abaSelecionada' => $abaSelecionada
                )
        );
    }

    /**
     * Função padrão, traz a tela principal
     * GET /relatorioGerar
     */
    public function gerarAction() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $mesSelecionado = date('n');
        $anoSelecionado = date('Y');
        $cicloSelecionado = Funcoes::cicloAtual($mesSelecionado, $anoSelecionado);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $tipoCelular = 2;

        $grupos = $repositorioORM->getGrupoORM()->encontrarTodos();
        echo "<br />Pegou grupos: " . count($grupos);
        foreach ($grupos as $grupo) {
            $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupo);
            echo "<br />$numeroIdentificador";

            $quantidadeLideres = 0;
            if ($grupo->getGrupoEventoAtivosPorTipo($tipoCelular)) {
                $quantidadeLideres = count($grupo->getResponsabilidadesAtivas());
            }
            $repositorioORM->getFatoCicloORM()->encontrarPorNumeroIdentificador(
                    $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $repositorioORM, $quantidadeLideres);
        }

        return new ViewModel();
    }

}
