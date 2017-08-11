<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Grupo;
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
    const stringRelatorio = 'relatorio';
    const stringPeriodoSelecionado = 'periodoSelecionado';

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
     * GET /relatorio[/tipoRelatorio][/abaSelecionada]
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupo);
        $periodo = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);

        $tipoRelatorioPessoal = 1;
        $relatorio = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorioPessoal);

        $tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');

        $dados = array(
            RelatorioController::stringRelatorio => $relatorio,
            'tipoRelatorio' => $tipoRelatorio,
            'periodo' => $periodo,
        );

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhos();
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($grupoFilho);
                $tipoRelatorioSomado = 2;
                $relatorioDiscipulos[$grupoFilho->getId()] = RelatorioController::montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorioSomado);
            }

            $discipulosOrdenado = RelatorioController::ordenacaoDiscipulos($grupoPaiFilhoFilhos, $relatorioDiscipulos, $tipoRelatorio);

            $dados['discipulosOrdenado'] = $discipulosOrdenado;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
        }

        return new ViewModel($dados);
    }

    public function atendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();



        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }

        /* Aba selecionada e ciclo */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        if (empty($parametro)) {
            $abaSelecionada = 1;
        } else {
            $abaSelecionada = $parametro;
        }
        $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);

        $discipulos = RelatorioController::ordenacaoDiscipulosAtendimento($gruposAbaixo, $mesSelecionado, $anoSelecionado);

        $view = new ViewModel(array(
            Constantes::$GRUPOS_ABAIXO => $discipulos,
            Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            Constantes::$ABA_SELECIONADA => $abaSelecionada,
            Constantes::$MES => $mesSelecionado,
            Constantes::$ANO => $anoSelecionado,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_RELATORIO_ATENDIMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_RELATORIO_ATENDIMENTO);

        return $view;
    }

    public static function montaRelatorio($repositorioORM, $numeroIdentificador, $periodo, $tipoRelatorio) {
        /* Membresia */
        $relatorioMembresia = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodo, $tipoRelatorio);
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
        $relatorio['membresiaPerformance'] = 0;
        if ($relatorio['membresiaMeta'] > 0) {
            $relatorio['membresiaPerformance'] = $relatorio['membresia'] / $relatorio['membresiaMeta'] * 100;
        }
        $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance']);
        $relatorio['quantidadeLideres'] = $quantidadeLideres;

        /* Célula */
        $relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodo, $tipoRelatorio);

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
        if ($relatorio['membresiaMeta'] > 0) {
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
        return number_format((double) $valor, 2, ',', '.');
    }

    public static function corDaLinhaPelaPerformance($valor) {
        $class = '';
        if ($valor == 0) {
            $class = 'dark';
        }
        if ($valor < 70 && $valor > 0) {
            $class = 'danger';
        }
        if ($valor > 70 && $valor <= 85) {
            $class = 'warning';
        }
        if ($valor > 85) {
            $class = 'success';
        }
        return $class;
    }

    public static function ordenacaoDiscipulos($discipulosLocal, $relatorio, $tipo) {

        if ($tipo === 1) {
            $campo = 'membresiaPerformance';
        }
        if ($tipo === 2) {
            $campo = 'celulaRealizadasPerformance';
        }
        if ($tipo === 3) {
            $campo = 'celulaPerformance';
        }
        $tamanhoArray = count($discipulosLocal);

        for ($i = 0; $i < $tamanhoArray; $i++) {
            for ($j = 0; $j < $tamanhoArray; $j++) {

                $discipulo1 = $discipulosLocal[$i];
                $grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
                $percentual1 = $relatorio[$grupoFilho1->getId()][$campo];

                $discipulo2 = $discipulosLocal[$j];
                $grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
                $percentual2 = $relatorio[$grupoFilho2->getId()][$campo];

                if ($percentual1 > $percentual2) {
                    $aux = $discipulo1;
                    $discipulosLocal[$i] = $discipulo2;
                    $discipulosLocal[$j] = $aux;
                }
            }
        }
        return $discipulosLocal;
    }

    public static function ordenacaoDiscipulosAtendimento($discipulos, $mes, $ano) {
        $relatorioDicipulo = array();
        foreach ($discipulos as $gpFilho) {
            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();

            if (count($grupoFilho) > 0) {
                $relatorioAtendimento = Grupo::relatorioDeAtendimentosAbaixo(
                                $grupoFilho->getGrupoPaiFilhoFilhos(), $mes, $ano
                );
            } else {
                $relatorioAtendimento[0] = -2;
            }

            $relatorioDicipulo[$grupoFilho->getId()] = $relatorioAtendimento[0];
        }

        $tamanhoArray = count($discipulos);

        for ($i = 0; $i < $tamanhoArray; $i++) {
            for ($j = 0; $j < $tamanhoArray; $j++) {

                $discipulo1 = $discipulos[$i];
                $grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
                $percentual1 = $relatorioDicipulo[$grupoFilho1->getId()];

                $discipulo2 = $discipulos[$j];
                $grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
                $percentual2 = $relatorioDicipulo[$grupoFilho2->getId()];

                if ($percentual1 > $percentual2) {
                    $aux = $discipulo1;
                    $discipulos[$i] = $discipulo2;
                    $discipulos[$j] = $aux;
                }
            }
        }

        return $discipulos;
    }

    public function testeAction() {
        try {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $setarDataEHora = false;

            $pessoa = $repositorioORM->getPessoaORM()->encontrarPorEmail('falecomleonardopereira@gmail.com');

            /* Inativando */
            $grupoResponsavels = $pessoa->getGrupoResponsavel();
            $gruposAtual = null;
            foreach ($grupoResponsavels as $gr) {
                $gr->setDataEHoraDeInativacao();
                $repositorioORM->getGrupoResponsavelORM()->persistir($gr, $setarDataEHora);
                $gruposAtual = $gr->getGrupo();
            }
            $gruposAtual->setDataEHoraDeInativacao();
            $repositorioORM->getGrupoORM()->persistir($gruposAtual, $setarDataEHora);

            $gpf = $gruposAtual->getGrupoPaiFilhoPai();
            $gpf->setDataEHoraDeInativacao();
            $repositorioORM->getGrupoPaiFilhoORM()->persistir($gpf, $setarDataEHora);

            $entidade = $gruposAtual->getEntidadeAtiva();
            $entidade->setNome('TRANSFERIDA - ' . $entidade->getNome());
            $repositorioORM->getEntidadeORM()->persistir($entidade, $setarDataEHora);

            /* Cadastrando */
            $grupoNovo = new Grupo();
            $repositorioORM->getGrupoORM()->persistir($grupoNovo);

            $novaEntidade = new Entidade();
            $novaEntidade->setGrupo($grupoNovo);
            $novaEntidade->setNome('NOVO GRUPO');
            $novaEntidade->setEntidadeTipo($repositorioORM->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
            $repositorioORM->getEntidadeORM()->persistir($novaEntidade);

            $pessoaPai = $repositorioORM->getPessoaORM()->encontrarPorEmail('rsilverio2012@hotmail.com');
            $grPai = $pessoaPai->getGrupoResponsavel()[0];
            $grupoPai = $grPai->getGrupo();

            $grupoPF = new GrupoPaiFilho();
            $grupoPF->setGrupoPaiFilhoFilho($grupoNovo);
            $grupoPF->setGrupoPaiFilhoPai($grupoPai);
            $repositorioORM->getGrupoPaiFilhoORM()->persistir($grupoPF);

            $grupoResponsavelNovo = new GrupoResponsavel();
            $grupoResponsavelNovo->setGrupo($grupoNovo);
            $grupoResponsavelNovo->setPessoa($pessoa);
            $repositorioORM->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);

            $gpessoas = $gruposAtual->getGrupoPessoa();
            foreach ($gpessoas as $gp) {
                $grupoPessoa = new GrupoPessoa();
                $grupoPessoa->setGrupo($grupoNovo);
                $grupoPessoa->setPessoa($gp->getPessoa());
                $grupoPessoa->setGrupoPessoaTipo($gp->getGrupoPessoaTipo());
                $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);
            }
            $geventos = $gruposAtual->getGrupoEventoAtivosPorTipo(GrupoEvento::CELULA);
            foreach ($geventos as $ge) {
                $grupoEvento = new GrupoEvento();
                $grupoEvento->setGrupo($grupoNovo);
                $grupoEvento->setEvento($ge->getEvento());
                $repositorioORM->getGrupoEventoORM()->persistir($grupoEvento);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
