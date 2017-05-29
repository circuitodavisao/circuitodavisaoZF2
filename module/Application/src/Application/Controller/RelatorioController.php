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

    const dimensaoTipoCelula = 1;
    const dimensaoTipoCulto = 2;
    const dimensaoTipoArena = 3;
    const dimensaoTipoDomingo = 4;

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
        $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioPessoal);
        $periodoSelecionado = Funcoes::periodoCicloMesAno($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        $dados = array(
            'relatorio' => $relatorio,
            'periodoSelecionado' => $periodoSelecionado,
            'abaSelecionada' => $abaSelecionada,
        );

        $discipulos = $grupo->getGrupoPaiFilhoFilhos();
        if ($discipulos) {
            $relatorioDiscipulos = array();
            foreach ($discipulos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupoFilho);
                $tipoRelatorioSomado = 2;
                $relatorioDiscipulos[$grupoFilho->getId()] = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorioSomado);
            }
            $discipulosOrdenadoPorRelatorio = RelatorioController::ordenacaoDiscipulos($discipulos, $relatorioDiscipulos);
            $dados['discipulosOrdenadoPorRelatorio'] = $discipulosOrdenadoPorRelatorio;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
        }

        return new ViewModel($dados);
    }

    public static function montaRelatorio($repositorioORM, $numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorio) {
        /* Membresia */
        $relatorioMembresia = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorio);
        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio);
        $quantidadeLideres = $fatoLider[0]['lideres'];
        foreach ($relatorioMembresia as $key => $value) {
            $soma[$key] = 0;
            foreach ($value as $campo) {
                foreach ($campo as $keyCampo => $valorCampo) {
                    $soma[$key] += $valorCampo;
                }
            }
        }
        $relatorio['membresiaCulto'] = $soma[RelatorioController::dimensaoTipoCulto];
        $relatorio['membresiaArena'] = $soma[RelatorioController::dimensaoTipoArena];
        $relatorio['membresiaDomingo'] = $soma[RelatorioController::dimensaoTipoDomingo];
        $relatorio['membresiaMeta'] = Constantes::$META_LIDER * $quantidadeLideres;
        $relatorio['membresia'] = RelatorioController::calculaMembresia(
                        $soma[RelatorioController::dimensaoTipoCulto], $soma[RelatorioController::dimensaoTipoArena], $soma[RelatorioController::dimensaoTipoDomingo]);
        $relatorio['membresiaPerformance'] = $relatorio['membresia'] / $relatorio['membresiaMeta'] * 100;
        $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance']);
        $relatorio['quantidadeLideres'] = $quantidadeLideres;

        /* Célula */
        $relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $cicloSelecionado, $mesSelecionado, $anoSelecionado, $tipoRelatorio);

        $quantidadeCelulas = $relatorioCelula[0]['quantidade'];
        $quantidadeCelulasRealizadas = 0;
        if ($relatorioCelula[0]['realizadas']) {
            $quantidadeCelulasRealizadas = $relatorioCelula[0]['realizadas'];
        }

        $performanceCelulasRealizadas = 0;
        if ($quantidadeCelulas) {
            $performanceCelulasRealizadas = $quantidadeCelulasRealizadas / $quantidadeCelulas * 100;
        }
        $performanceCelula = 0;
        if ($soma[RelatorioController::dimensaoTipoCelula]) {
            $performanceCelula = $soma[RelatorioController::dimensaoTipoCelula] / $relatorio['membresiaMeta'] * 100;
        }
        $relatorio['celula'] = $soma[RelatorioController::dimensaoTipoCelula];
        $relatorio['celulaPerformance'] = $performanceCelula;
        $relatorio['celulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaPerformance']);
        $relatorio['celulaQuantidade'] = $quantidadeCelulas;
        $relatorio['celulaRealizadas'] = $quantidadeCelulasRealizadas;
        $relatorio['celulaRealizadasPerformance'] = $performanceCelulasRealizadas;
        $relatorio['celulaRealizadasPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaRealizadasPerformance']);

        return $relatorio;
    }

    /**
     * Calcula a membresia
     * @param integer $valorCulto
     * @param integer $valorArena
     * @param integer $valorDomingo
     * @return integer
     */
    public static function calculaMembresia($valorCulto, $valorArena, $valorDomingo) {
        return $valorCulto / 3 + $valorArena / 2 + $valorDomingo;
    }

    public static function formataNumeroRelatorio($valor) {
        return number_format($valor, 2, ',', '.');
    }

    public static function corDaLinhaPelaPerformance($valor) {
        $class = '';
        if ($valor < 70) {
            $class = 'danger';
        }
        if ($valor > 70 && $valor <= 85) {
            $class = 'warning';
        }
        if ($valor > 85 && $valor <= 100) {
            $class = 'success';
        }

        return $class;
    }

    public static function ordenacaoDiscipulos($discipulos, $relatorio) {
        /* Ordenacao */
        $tamanhoArray = count($discipulos);
        /* Ordenando membresia */
        $discipulosComRelatorio[1] = $discipulos;
        for ($i = 0; $i < $tamanhoArray; $i++) {
            for ($j = 0; $j < $tamanhoArray; $j++) {

                $discipulo1 = $discipulosComRelatorio[1][$i];
                $grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
                $percentual1 = $relatorio[$grupoFilho1->getId()]['membresiaPerformance'];

                $discipulo2 = $discipulosComRelatorio[1][$j];
                $grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
                $percentual2 = $relatorio[$grupoFilho2->getId()]['membresiaPerformance'];

                if ($percentual1 > $percentual2) {
                    $discipulosComRelatorio[1][$i] = $discipulo2;
                    $discipulosComRelatorio[1][$j] = $discipulo1;
                }
            }
        }

        $discipulosComRelatorio[2] = $discipulos;
        for ($i = 0; $i < $tamanhoArray; $i++) {
            for ($j = 0; $j < $tamanhoArray; $j++) {

                $discipulo1 = $discipulosComRelatorio[2][$i];
                $grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
                $percentual1 = $relatorio[$grupoFilho1->getId()]['celulaPerformance'];

                $discipulo2 = $discipulosComRelatorio[2][$j];
                $grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
                $percentual2 = $relatorio[$grupoFilho2->getId()]['celulaPerformance'];

                if ($percentual1 > $percentual2) {
                    $discipulosComRelatorio[2][$i] = $discipulo2;
                    $discipulosComRelatorio[2][$j] = $discipulo1;
                }
            }
        }
        /* Fim ordenação */

        return $discipulosComRelatorio;
    }

//    public function testeAction() {
//        try {
//            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
//            $setarDataEHora = false;
//
//            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail('falecomleonardopereira@gmail.com');
//
//            /* Inativando */
//            $grupoResponsavels = $pessoa->getGrupoResponsavel();
//            $gruposAtual = null;
//            foreach ($grupoResponsavels as $gr) {
//                $gr->setDataEHoraDeInativacao();
//                $repositorioORM->getGrupoResponsavelORM()->persistir($gr, $setarDataEHora);
//                $gruposAtual = $gr->getGrupo();
//            }
//            $gruposAtual->setDataEHoraDeInativacao();
//            $repositorioORM->getGrupoORM()->persistir($gruposAtual, $setarDataEHora);
//
//            $gpf = $gruposAtual->getGrupoPaiFilhoPai();
//            $gpf->setDataEHoraDeInativacao();
//            $repositorioORM->getGrupoPaiFilhoORM()->persistir($gpf, $setarDataEHora);
//
//            $entidade = $gruposAtual->getEntidadeAtiva();
//            $entidade->setNome('TRANSFERIDA - ' . $entidade->getNome());
//            $repositorioORM->getEntidadeORM()->persistir($entidade, $setarDataEHora);
//
//            /* Cadastrando */
//            $grupoNovo = new Grupo();
//            $repositorioORM->getGrupoORM()->persistir($grupoNovo);
//
//            $novaEntidade = new Entidade();
//            $novaEntidade->setGrupo($grupoNovo);
//            $novaEntidade->setNome('NOVO GRUPO');
//            $novaEntidade->setEntidadeTipo($repositorioORM->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
//            $repositorioORM->getEntidadeORM()->persistir($novaEntidade);
//
//            $pessoaPai = $repositorioORM->getPessoaORM()->encontrarPorEmail('rsilverio2012@hotmail.com');
//            $grPai = $pessoaPai->getGrupoResponsavel()[0];
//            $grupoPai = $grPai->getGrupo();
//
//            $grupoPF = new GrupoPaiFilho();
//            $grupoPF->setGrupoPaiFilhoFilho($grupoNovo);
//            $grupoPF->setGrupoPaiFilhoPai($grupoPai);
//            $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPF);
//
//            $grupoResponsavelNovo = new GrupoResponsavel();
//            $grupoResponsavelNovo->setGrupo($grupoNovo);
//            $grupoResponsavelNovo->setPessoa($pessoa);
//            $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);
//
//            $gpessoas = $gruposAtual->getGrupoPessoa();
//            foreach ($gpessoas as $gp) {
//                $grupoPessoa = new GrupoPessoa();
//                $grupoPessoa->setGrupo($grupoNovo);
//                $grupoPessoa->setPessoa($gp->getPessoa());
//                $grupoPessoa->setGrupoPessoaTipo($gp->getGrupoPessoaTipo());
//                $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);
//            }
//            $geventos = $gruposAtual->getGrupoEventoAtivosPorTipo(GrupoEvento::CELULA);
//            foreach ($geventos as $ge) {
//                $grupoEvento = new GrupoEvento();
//                $grupoEvento->setGrupo($grupoNovo);
//                $grupoEvento->setEvento($ge->getEvento());
//                $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
//            }
//        } catch (Exception $exc) {
//            echo $exc->getMessage();
//        }
//    }
}
