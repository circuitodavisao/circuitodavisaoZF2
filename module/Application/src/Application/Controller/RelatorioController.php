<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Helper\FuncoesEntidade;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Json\Json;
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
     * GET /relatorio[/tipoRelatorio][/mes/ano]
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $mes = $post_data['mes'];
            $ano = $post_data['ano'];
        }
        if (empty($mes)) {
            $mes = (int) $this->params()->fromRoute('mes', 0);
            if ($mes == 0) {
                $mes = date('m');
            }
        }
        if (empty($ano)) {
            $ano = (int) $this->params()->fromRoute('ano', 0);
            if ($ano == 0) {
                $ano = date('Y');
            }
        }
        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
        $relatorio = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, $tipoRelatorio, $mes, $ano);

        $dados = array(
            'mes' => $mes,
            'ano' => $ano,
            'relatorio' => $relatorio,
            'periodoInicial' => $arrayPeriodoDoMes[0],
            'periodoFinal' => $arrayPeriodoDoMes[1],
            'tipoRelatorio' => $tipoRelatorio,
        );

        return new ViewModel($dados);
    }

    public function pessoasFrequentesAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $html = '';

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos(0);
        $html .= '<table class="table table-condesed">';
        $arrayPeriodo = Funcoes::montaPeriodo(-3);
        $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
        $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);

        if ($grupoPaiFilhoFilhos) {
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $dadosEntidade = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
                $html .= '<tr class="info">';
                $html .= '<td colspan="2">' . $dadosEntidade . '</td>';
                $html .= '</tr>';
                $grupoPessoas = $grupoFilho->getGrupoPessoa();
                if ($grupoPessoas) {
                    $contadorDePessoas = 0;
                    foreach ($grupoPessoas as $grupoPessoa) {
                        $contadorDeEventos = 0;
                        $pessoa = $grupoPessoa->getPessoa();
                        if (($grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::VISITANTE ||
                                $grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::CONSOLIDACAO) &&
                                $grupoPessoa->verificarSeEstaAtivo()) {

                            $frequencias = $pessoa->getEventoFrequencia();
                            if ($frequencias) {
                                foreach ($frequencias as $eventoFrequencia) {
                                    $dataParaComparar = strtotime($eventoFrequencia->getDiaStringPadraoBanco());
                                    if ($dataParaComparar >= $dataDoInicioDoPeriodoParaComparar && $eventoFrequencia->getFrequencia() == 'S') {
                                        $contadorDeEventos ++;
                                    }
                                }
                                if ($contadorDeEventos >= 6) {
                                    $html .= '<tr>';
                                    $html .= '<td>' . $pessoa->getNome() . '</td>';
                                    $html .= '<td>' . $pessoa->getTelefone() . '</td>';
                                    $html .= '</tr>';
                                    $contadorDePessoas++;
                                }
                            }
                        }
                    }
                    if ($contadorDePessoas === 0) {
                        $html .= '<tr class="warning">';
                        $html .= '<td colspan="2">Sem pessoas frequentes</td>';
                        $html .= '</tr>';
                    }
                }
            }
        }
        $html .= '</table>';
        $view = new ViewModel(array('html' => $html));
        return $view;
    }

    public function atendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();

        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }

        /* Aba selecionada e ciclo */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        $periodo = 0;
        if (empty($parametro)) {
            $abaSelecionada = 1;
        } else {
            $periodo = -1;
            $abaSelecionada = $parametro;
        }
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
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

    public function liderAction() {
        $idUrl = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idUrl);
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $entidade->getGrupo());
        $periodo = 0; // atual
        $tipoRelatorioEquipe = 2;
        $retornaJson = true;
        $relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe, $retornaJson);

        $response = $this->getResponse();
        $response->setContent($relatorio);
        return $response;
    }

    public function buscarDadosGrupoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idGrupo = $post_data['idGrupo'];
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
                $tipoRelatorioEquipe = 2;
                $periodoInicial = 0;
                $relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodoInicial, $tipoRelatorioEquipe);

                $grupoResponsabilidades = $grupo->getResponsabilidadesAtivas();
                $fotos = '';
                foreach ($grupoResponsabilidades as $grupoResponsabilidade) {
                    $fotos .= FuncoesEntidade::tagImgComFotoDaPessoa($grupoResponsabilidade->getPessoa(), 96);
                }
                $resposta = true;
                $dados = array();
                $dados['nomeLideres'] = $grupo->getNomeLideresAtivos();
                $dados['fotos'] = $fotos;
                $dados['celulaQuantidade'] = $relatorio['celulaQuantidade'];
                $dados['quantidadeLideres'] = $relatorio['quantidadeLideres'];
                $dados['resposta'] = $resposta;
                $response->setContent(Json::encode($dados));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    public function buscarNumeracoesDisponivelAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idGrupo = $post_data['idGrupo'];
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
                $arrayDeNumerosUsados = array();
                if ($grupo->getGrupoPaiFilhoFilhosAtivosReal()) {
                    $filhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
                    foreach ($filhos as $filho) {
                        if ($filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero()) {
                            $numero = $filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero();
                            $arrayDeNumerosUsados[] = $numero;
                        }
                    }
                }
                $resposta = true;
                $dados = array();
                $dados['numerosUsados'] = $arrayDeNumerosUsados;
                $dados['resposta'] = $resposta;
                $response->setContent(Json::encode($dados));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    const relatorioMembresia = 1;
    const relatorioCelulaRealizadas = 2;
    const relatorioCelulaQuantidade = 3;
    const relatorioMembresiaECelula = 4;

    public static function relatorioCompleto($repositorio, $grupo, $tipoRelatorio, $mes, $ano) {
        $relatorio = array();
        $todosFilhos = array();
        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
        $diferencaDePeriodos = self::diferencaDePeriodos($arrayPeriodoDoMes[0], $arrayPeriodoDoMes[1]);

        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
            $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
            if ($grupoPaiFilhoFilhos) {
                foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
                    $adicionar = true;
                    if (count($todosFilhos) > 0) {
                        foreach ($todosFilhos as $filho) {
                            if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
                                $adicionar = false;
                                break;
                            }
                        }
                    }
                    if ($adicionar) {
                        $todosFilhos[] = $grupoPaiFilhoFilho;
                    }
                }
            }
        }

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioSomado = 2;

        $relatorioDiscipulos = array();
        $numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
        $soma = array();
        $somaTotal = array();
        $soma[0][1] = 0;
        $soma[0][2] = 0;
        $soma[0][3] = 0;
        $soma[0][4] = 0;
        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
            $relatorio[0][$indiceDeArrays] = RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $indiceDeArrays, $tipoRelatorioPessoal, false, $tipoRelatorio);
            $soma[0][11] += $relatorio[0][$indiceDeArrays]['membresiaCulto'];
            $soma[0][12] += $relatorio[0][$indiceDeArrays]['membresiaArena'];
            $soma[0][13] += $relatorio[0][$indiceDeArrays]['membresiaDomingo'];
            $soma[0][1] += $relatorio[0][$indiceDeArrays]['membresia'];
            $soma[0][2] += $relatorio[0][$indiceDeArrays]['membresiaPerformance'];
            $soma[0][3] += $relatorio[0][$indiceDeArrays]['celula'];
            $soma[0][4] += $relatorio[0][$indiceDeArrays]['celulaPerformance'];
            $soma[0][5] += $relatorio[0][$indiceDeArrays]['celulaRealizadas'];
            $soma[0][6] += $relatorio[0][$indiceDeArrays]['celulaRealizadasPerformance'];
            $soma[0][7] += $relatorio[0][$indiceDeArrays]['celulaQuantidade'];
            foreach ($todosFilhos as $filho) {
                $grupoFilho = $filho->getGrupoPaiFilhoFilho();
                $dataInativacao = null;
                if ($filho->getData_inativacao()) {
                    $dataInativacao = $filho->getData_inativacaoStringPadraoBanco();
                }
                $numeroIdentificadorFilho = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho, $dataInativacao);
                $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays] = RelatorioController::montaRelatorio($repositorio, $numeroIdentificadorFilho, $indiceDeArrays, $tipoRelatorioSomado, false, $tipoRelatorio);
                $lideres = RelatorioController::totalLideres($repositorio, $indiceDeArrays, $grupoFilho);
                $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['quantidadeLideres'] = $lideres;
                $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['quantidadeLideres'] * Constantes::$META_LIDER;
                if ($tipoRelatorio === RelatorioController::relatorioMembresia || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                    if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] > 0 && $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] > 0) {
                        $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaPerformance'] = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresia'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] * 100;
                    }
                }
                if ($tipoRelatorio === RelatorioController::relatorioCelulaQuantidade || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                    $performanceCelula = 0;
                    if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] > 0) {
                        $performanceCelula = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celula'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMeta'] * 100;
                        $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaPerformance'] = $performanceCelula;
                    }
                }
                if ($tipoRelatorio === RelatorioController::relatorioCelulaRealizadas || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                    $performanceCelula = 0;
                    if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] > 0) {
                        $performanceCelula = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadas'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] * 100;
                        $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadasPerformance'] = $performanceCelula;
                    }
                }
                $soma[$grupoFilho->getId()][1] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresia'];
                $soma[$grupoFilho->getId()][2] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaPerformance'];
                $soma[$grupoFilho->getId()][3] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celula'];
                $soma[$grupoFilho->getId()][4] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaPerformance'];
                $soma[$grupoFilho->getId()][5] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadas'];
                $soma[$grupoFilho->getId()][6] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadasPerformance'];
            }
            foreach ($relatorio[0][$indiceDeArrays] as $key => $value) {
                $somaTotal[$indiceDeArrays][$key] += $value;
            }
        }

        $relatorio[0]['mediaMembresiaCulto'] = $soma[0][11] / $diferencaDePeriodos;
        $relatorio[0]['mediaMembresiaArena'] = $soma[0][12] / $diferencaDePeriodos;
        $relatorio[0]['mediaMembresiaDomingo'] = $soma[0][13] / $diferencaDePeriodos;
        $relatorio[0]['mediaMembresia'] = $soma[0][1] / $diferencaDePeriodos;
        $relatorio[0]['mediaMembresiaPerformance'] = $soma[0][2] / $diferencaDePeriodos;
        $relatorio[0]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[0]['mediaMembresiaPerformance'], 1);
        $relatorio[0]['mediaMembresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[0]['mediaMembresiaPerformance'], 2);
        $relatorio[0]['mediaCelula'] = $soma[0][3] / $diferencaDePeriodos;
        $relatorio[0]['mediaCelulaPerformance'] = $soma[0][4] / $diferencaDePeriodos;
        $relatorio[0]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[0]['mediaCelulaPerformance'], 1);
        $relatorio[0]['mediaCelulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[0]['mediaCelulaPerformance'], 2);
        $relatorio[0]['mediaCelulaRealizadas'] = $soma[0][5] / $diferencaDePeriodos;
        $relatorio[0]['mediaCelulaRealizadasPerformance'] = $soma[0][6] / $diferencaDePeriodos;
        $relatorio[0]['mediaCelulaQuantidade'] = $soma[0][7] / $diferencaDePeriodos;
        $relatorio[0]['lideres'] = 'MEU';
        $relatorio[0]['lideresFotos'] = $grupo->getFotosLideresAtivos();

        $somaTotal['mediaMembresiaCulto'] = $relatorio[0]['mediaMembresiaCulto'];
        $somaTotal['mediaMembresiaArena'] = $relatorio[0]['mediaMembresiaArena'];
        $somaTotal['mediaMembresiaDomingo'] = $relatorio[0]['mediaMembresiaDomingo'];
        $somaTotal['mediaMembresia'] += $relatorio[0]['mediaMembresia'];
        $somaTotal['mediaCelula'] += $relatorio[0]['mediaCelula'];
        $somaTotal['mediaCelulaRealizadas'] += $relatorio[0]['mediaCelulaRealizadas'];

        foreach ($todosFilhos as $filho) {
            $grupoFilho = $filho->getGrupoPaiFilhoFilho();
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaMembresia'] = $soma[$grupoFilho->getId()][1] / $diferencaDePeriodos;
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaMembresiaPerformance'] = $soma[$grupoFilho->getId()][2] / $diferencaDePeriodos;
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaCelula'] = $soma[$grupoFilho->getId()][3] / $diferencaDePeriodos;
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaPerformance'] = $soma[$grupoFilho->getId()][4] / $diferencaDePeriodos;
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaRealizadas'] = $soma[$grupoFilho->getId()][5] / $diferencaDePeriodos;
            $relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaRealizadasPerformance'] = $soma[$grupoFilho->getId()][6] / $diferencaDePeriodos;
        }
        $filhosOrdenado = RelatorioController::ordenacaoDiscipulos($todosFilhos, $relatorioDiscipulos, $tipoRelatorio);
        $contadorFilhos = 1;
        foreach ($filhosOrdenado as $filhoOrdenado) {
            $grupoFilhoOrdenado = $filhoOrdenado->getGrupoPaiFilhoFilho();
            for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
                $relatorio[$contadorFilhos][$indiceDeArrays] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()][$indiceDeArrays];
                foreach ($relatorio[$contadorFilhos][$indiceDeArrays] as $key => $value) {
                    $somaTotal[$indiceDeArrays][$key] += $value;
                }
            }
            $relatorio[$contadorFilhos]['mediaMembresia'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresia'];
            $relatorio[$contadorFilhos]['mediaMembresiaPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresiaPerformance'];
            $relatorio[$contadorFilhos]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresiaPerformance']);
            $relatorio[$contadorFilhos]['mediaCelula'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelula'];
            $relatorio[$contadorFilhos]['mediaCelulaPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaPerformance'];
            $relatorio[$contadorFilhos]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaPerformance']);
            $relatorio[$contadorFilhos]['mediaCelulaRealizadas'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadas'];
            $relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadasPerformance'];
            $relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadasPerformance']);
            $relatorio[$contadorFilhos]['lideres'] = $grupoFilhoOrdenado->getNomeLideresAtivos();
            $relatorio[$contadorFilhos]['lideresFotos'] = $grupoFilhoOrdenado->getFotosLideresAtivos();
            $contadorFilhos++;

            $somaTotal['mediaMembresia'] += $relatorio[$contadorFilhos]['mediaMembresia'];
            $somaTotal['mediaCelula'] += $relatorio[$contadorFilhos]['mediaCelula'];
            $somaTotal['mediaCelulaRealizadas'] += $relatorio[$contadorFilhos]['mediaCelulaRealizadas'];
        }

        /* TOTAL */
        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
            foreach ($somaTotal[$indiceDeArrays] as $key => $value) {
                $relatorio[$contadorFilhos][$indiceDeArrays][$key] += $value;
            }
        }
        $somaFinal = array();
        for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
            if ($tipoRelatorio === RelatorioController::relatorioMembresia || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['membresia'] / $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaMeta'] * 100;
                $somaFinal['membresiaCulto'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaCulto'];
                $somaFinal['membresiaArena'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaArena'];
                $somaFinal['membresiaDomingo'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaDomingo'];
                $somaFinal['membresia'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresia'];
                $somaFinal['membresiaPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'];
                $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'], 1);
                $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'], 2);
            }
            if ($tipoRelatorio === RelatorioController::relatorioCelulaQuantidade || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                $relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['celula'] / $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaMeta'] * 100;
                $somaFinal['celula'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celula'];
                $somaFinal['celulaPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'];
                $relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'], 1);
                $relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'], 2);
            }
            if ($tipoRelatorio === RelatorioController::relatorioCelulaRealizadas || $tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
                $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadasPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadas'] / $relatorio[$contadorFilhos][$indiceDeArrays]['celulaQuantidade'] * 100;
                $somaFinal['celulaQuantidade'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaQuantidade'];
                $somaFinal['celulaRealizadas'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadas'];
                $somaFinal['celulaRealizadasPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadasPerformance'];
            }
        }
        $relatorio[$contadorFilhos]['mediaMembresiaCulto'] = $somaFinal['membresiaCulto'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaMembresiaArena'] = $somaFinal['membresiaArena'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaMembresiaDomingo'] = $somaFinal['membresiaDomingo'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaMembresia'] = $somaFinal['membresia'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaMembresiaPerformance'] = $somaFinal['membresiaPerformance'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaMembresiaPerformance'], 1);
        $relatorio[$contadorFilhos]['mediaMembresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaMembresiaPerformance'], 2);
        $relatorio[$contadorFilhos]['mediaCelula'] = $somaFinal['celula'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaCelulaPerformance'] = $somaFinal['celulaPerformance'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaCelulaPerformance'], 1);
        $relatorio[$contadorFilhos]['mediaCelulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaCelulaPerformance'], 2);
        $relatorio[$contadorFilhos]['mediaCelulaRealizadas'] = $somaFinal['celulaRealizadas'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaCelulaQuantidade'] = $somaFinal['celulaQuantidade'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformance'] = $somaFinal['celulaRealizadasPerformance'] / $diferencaDePeriodos;
        $relatorio[$contadorFilhos]['lideres'] = 'TOTAL';

        return $relatorio;
    }

    public static function totalLideres($repositorioORM, $periodo, $grupo) {
        $tipoRelatorio = 1; //pessoal
        $somaTotal = 0;
        $numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo);
        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodo);
        $somaTotal += $fatoLider[0]['lideres'];

        $filhos1 = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($filhos1) {
            foreach ($filhos1 as $grupoPaiFilho1) {
                $grupo1 = $grupoPaiFilho1->getGrupoPaiFilhoFilho();
                $numeroIdentificador1 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo1);
                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador1, $tipoRelatorio, $periodo);
                $somaTotal += $fatoLider[0]['lideres'];
                $filhos2 = $grupo1->getGrupoPaiFilhoFilhosAtivos($periodo);
                if ($filhos2) {
                    foreach ($filhos2 as $grupoPaiFilhoFilho2) {
                        $grupo2 = $grupoPaiFilhoFilho2->getGrupoPaiFilhoFilho();
                        $numeroIdentificador2 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo2);
                        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador2, $tipoRelatorio, $periodo);
                        $somaTotal += $fatoLider[0]['lideres'];
                        $filhos3 = $grupo2->getGrupoPaiFilhoFilhosAtivos($periodo);
                        if ($filhos3) {
                            foreach ($filhos3 as $grupoPaiFilhoFilho3) {
                                $grupo3 = $grupoPaiFilhoFilho3->getGrupoPaiFilhoFilho();
                                $numeroIdentificador3 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo3);
                                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador3, $tipoRelatorio, $periodo);
                                $somaTotal += $fatoLider[0]['lideres'];
                                $filhos4 = $grupo3->getGrupoPaiFilhoFilhosAtivos($periodo);
                                if ($filhos4) {
                                    foreach ($filhos4 as $grupoPaiFilhoFilho4) {
                                        $grupo4 = $grupoPaiFilhoFilho4->getGrupoPaiFilhoFilho();
                                        $numeroIdentificador4 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo4);
                                        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador4, $tipoRelatorio, $periodo);
                                        $somaTotal += $fatoLider[0]['lideres'];
                                        $filhos5 = $grupo4->getGrupoPaiFilhoFilhosAtivos($periodo);
                                        if ($filhos5) {
                                            foreach ($filhos5 as $grupoPaiFilhoFilho5) {
                                                $grupo5 = $grupoPaiFilhoFilho5->getGrupoPaiFilhoFilho();
                                                $numeroIdentificador5 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo5);
                                                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador5, $tipoRelatorio, $periodo);
                                                $somaTotal += $fatoLider[0]['lideres'];
                                                $filhos6 = $grupo5->getGrupoPaiFilhoFilhosAtivos($periodo);
                                                if ($filhos6) {
                                                    foreach ($filhos6 as $grupoPaiFilhoFilho6) {
                                                        $grupo6 = $grupoPaiFilhoFilho6->getGrupoPaiFilhoFilho();
                                                        $numeroIdentificador6 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo6);
                                                        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador6, $tipoRelatorio, $periodo);
                                                        $somaTotal += $fatoLider[0]['lideres'];
                                                        $filhos7 = $grupo6->getGrupoPaiFilhoFilhosAtivos($periodo);
                                                        if ($filhos7) {
                                                            foreach ($filhos7 as $grupoPaiFilhoFilho7) {
                                                                $grupo7 = $grupoPaiFilhoFilho7->getGrupoPaiFilhoFilho();
                                                                $numeroIdentificador7 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo7);
                                                                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador7, $tipoRelatorio, $periodo);
                                                                $somaTotal += $fatoLider[0]['lideres'];
                                                                $filhos8 = $grupo7->getGrupoPaiFilhoFilhosAtivos($periodo);
                                                                if ($filhos8) {
                                                                    foreach ($filhos8 as $grupoPaiFilhoFilho8) {
                                                                        $grupo8 = $grupoPaiFilhoFilho8->getGrupoPaiFilhoFilho();
                                                                        $numeroIdentificador8 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo8);
                                                                        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador8, $tipoRelatorio, $periodo);
                                                                        $somaTotal += $fatoLider[0]['lideres'];
                                                                        $filhos9 = $grupo8->getGrupoPaiFilhoFilhosAtivos($periodo);
                                                                        if ($filhos9) {
                                                                            foreach ($filhos9 as $grupoPaiFilhoFilho9) {
                                                                                $grupo9 = $grupoPaiFilhoFilho9->getGrupoPaiFilhoFilho();
                                                                                $numeroIdentificador9 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo9);
                                                                                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador9, $tipoRelatorio, $periodo);
                                                                                $somaTotal += $fatoLider[0]['lideres'];
                                                                                $filhos10 = $grupo8->getGrupoPaiFilhoFilhosAtivos($periodo);
                                                                                if ($filhos10) {
                                                                                    foreach ($filhos10 as $grupoPaiFilhoFilho10) {
                                                                                        $grupo10 = $grupoPaiFilhoFilho10->getGrupoPaiFilhoFilho();
                                                                                        $numeroIdentificador10 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo10);
                                                                                        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador10, $tipoRelatorio, $periodo);
                                                                                        $somaTotal += $fatoLider[0]['lideres'];
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $somaTotal;
    }

    public static function montaRelatorio($repositorioORM, $numeroIdentificador, $periodoInicial, $tipoRelatorio, $inativo = false, $qualRelatorio = 1) {
        unset($relatorio);
        if ($tipoRelatorio === 1) {
            $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoInicial, $inativo);
            $quantidadeLideres = $fatoLider[0]['lideres'];
            $relatorio['quantidadeLideres'] = $quantidadeLideres;
        }
        $relatorioLancamento = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio);
        foreach ($relatorioLancamento as $key => $value) {
            $soma[$key] = 0;
            foreach ($value as $campo) {
                foreach ($campo as $keyCampo => $valorCampo) {
                    $soma[$key] += $valorCampo;
                }
            }
        }
        $relatorio['membresiaMeta'] = Constantes::$META_LIDER * $quantidadeLideres;
        /* Membresia */
        if ($qualRelatorio === RelatorioController::relatorioMembresia || $qualRelatorio === RelatorioController::relatorioMembresiaECelula) {
            $relatorio['membresiaCulto'] = $soma[RelatorioController::dimensaoTipoCulto];
            $relatorio['membresiaArena'] = $soma[RelatorioController::dimensaoTipoArena];
            $relatorio['membresiaDomingo'] = $soma[RelatorioController::dimensaoTipoDomingo];
            $relatorio['membresia'] = RelatorioController::calculaMembresia(
                            $soma[RelatorioController::dimensaoTipoCulto], $soma[RelatorioController::dimensaoTipoArena], $soma[RelatorioController::dimensaoTipoDomingo]);
            if ($relatorio['membresiaMeta'] > 0 && $relatorio['membresia'] > 0) {
                $relatorio['membresiaPerformance'] = $relatorio['membresia'] / $relatorio['membresiaMeta'] * 100;
                $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance']);
                $relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance'], 2);
            }
            if ($relatorio['membresiaPerformance'] == '' || $relatorio['membresiaPerformance'] == 0) {
                $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance(0);
                $relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance(0, 2);
            }
        }
        /* Célula */
        if ($qualRelatorio === RelatorioController::relatorioCelulaRealizadas ||
                $qualRelatorio === RelatorioController::relatorioCelulaQuantidade ||
                $qualRelatorio === RelatorioController::relatorioMembresiaECelula) {
            $relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio);
            $relatorioCelulaDeElite = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaDeElitePorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio);

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
            $performanceCelulasDeElite = 0;
            $celulasDeElite = $relatorioCelulaDeElite[0]['celulaDeElite'];
            if ($celulasDeElite) {
                $performanceCelulasDeElite = $celulasDeElite / $quantidadeCelulas * 100;
            }
            $relatorio['celula'] = $soma[RelatorioController::dimensaoTipoCelula];
            $relatorio['celulaPerformance'] = $performanceCelula;
            $relatorio['celulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaPerformance']);
            $relatorio['celulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaPerformance'], 2);
            $relatorio['celulaQuantidade'] = $quantidadeCelulas;
            $relatorio['celulaRealizadas'] = $quantidadeCelulasRealizadas;
            $relatorio['celulaRealizadasPerformance'] = $performanceCelulasRealizadas;
            $relatorio['celulaRealizadasPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaRealizadasPerformance']);
            $relatorio['celulaDeElite'] = $celulasDeElite;
            $relatorio['celulaDeElitePerformance'] = $performanceCelulasDeElite;
            $relatorio['celulaDeElitePerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaDeElitePerformance']);
        }
        return $relatorio;
    }

    public static function saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo(RepositorioORM $repositorioORM, Grupo $grupo, $periodo) {
        $relatorio = array();
        $grupoEventosCelula = $grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);
        $contagem = 0;
        foreach ($grupoEventosCelula as $grupoEventoCelula) {
            $eventoId = $grupoEventoCelula->getEvento()->getId();
            $resultado = $repositorioORM->getFatoCicloORM()->verificaFrequenciasPorCelulaEPeriodo($periodo, $eventoId);
            $resposta = 0;
            if ($resultado >= 7) {
                $resposta = 1;
            }
            $relatorio[$contagem]['eventoId'] = $eventoId;
            $relatorio[$contagem]['resposta'] = $resposta;
            $relatorio[$contagem]['resultado'] = $resultado;
            $relatorio[$contagem]['hospedeiro'] = $grupoEventoCelula->getEvento()->getEventoCelula()->getNome_hospedeiroPrimeiroNome();
            $contagem++;
        }
        return $relatorio;
    }

    public static function diferencaDePeriodos($periodoInicial, $periodoFinal) {
        $diferencaDePeriodos = 1;
        if ($periodoInicial < 0) {
            $periodoInicial *= -1;
        }
        if ($periodoFinal === 0) {
            $periodoFinal = 1;
        }
        if ($periodoFinal === -1) {
            $periodoFinal = 0;
        }
        $diferencaDePeriodos = $periodoInicial + $periodoFinal;
        return $diferencaDePeriodos;
    }

    /**
     * Calcula a membresia
     * @param integer $valorCulto
     * @param integer $valorArena
     * @param integer $valorDomingo
     * @return integer
     */
    public static function calculaMembresia($valorCulto, $valorArena, $valorDomingo) {
        return ($valorCulto / 3) + ($valorArena / 2) + $valorDomingo;
    }

    public static function formataNumeroRelatorio($valor) {
        return number_format((double) $valor, 2, ',', '.');
    }

    const MARGEM_D = 0;
    const MARGEM_C = 50;
    const MARGEM_B = 75;
    const MARGEM_A = 100;

    public static function corDaLinhaPelaPerformance($valor, $tipo = 1) {
        $class = 'dark';
        if ($valor >= RelatorioController::MARGEM_A) {
            $class = 'info';
            if ($tipo === 2) {
                $class = 'Excelente! você está entre os melhores!';
            }
        }
        if (($valor < RelatorioController::MARGEM_A && $valor > RelatorioController::MARGEM_B)) {
            $class = 'success';
            if ($tipo === 2) {
                $class = 'Parabéns! Continue e logo estará entre os melhores';
            }
        }
        if (($valor <= RelatorioController::MARGEM_B && $valor > RelatorioController::MARGEM_C)) {
            $class = 'warning';
            if ($tipo === 2) {
                $class = 'Muito bom! Você está no caminho continue focado!';
            }
        }
        if (($valor <= RelatorioController::MARGEM_C && $valor > RelatorioController::MARGEM_D)) {
            $class = 'danger';
            if ($tipo === 2) {
                $class = 'Vamos lá a persistência é o caminho, continue!';
            }
        }
        if ($valor <= RelatorioController::MARGEM_D) {
            $class = 'dark';
            if ($tipo === 2) {
                $class = 'Vamos lá a persistência é o caminho, continue!';
            }
        }
        return $class;
    }

    public static function corDaLinhaPelaPerformanceClasse($valor) {
        $class = 'dark';
        if ($valor == 'A') {
            $class = 'info';
        }
        if ($valor == 'B') {
            $class = 'success';
        }
        if ($valor == 'C') {
            $class = 'warning';
        }
        if ($valor == 'D') {
            $class = 'danger';
        }
        return $class;
    }

    const ORDENACAO_TIPO_MEMBRESIA = 9;
    const ORDENACAO_TIPO_CELULA = 10;

    public static function ordenacaoDiscipulos($discipulosLocal, $relatorio, $tipo) {
        $campo = '';
        if ($tipo === 1) {
            $campo = 'mediaMembresiaPerformance';
        }
        if ($tipo === 2) {
            $campo = 'mediaCelulaRealizadasPerformance';
        }
        if ($tipo === 3) {
            $campo = 'mediaCelulaPerformance';
        }
        if ($tipo === 4) {
            $campo = 'membresiaCulto';
        }
        if ($tipo === 5) {
            $campo = 'membresiaArena';
        }
        if ($tipo === 6) {
            $campo = 'membresiaDomingo';
        }
        if ($tipo === 8) {
            $campo = 'celulaDeElitePerformance';
        }
        if ($tipo === RelatorioController::ORDENACAO_TIPO_MEMBRESIA) {
            $campo = 'membresia';
        }
        if ($tipo === RelatorioController::ORDENACAO_TIPO_CELULA) {
            $campo = 'celula';
        }
        $tamanhoArray = count($discipulosLocal);

        for ($i = 0; $i < $tamanhoArray; $i++) {
            for ($j = 0; $j < $tamanhoArray; $j++) {

                $discipulo1 = $discipulosLocal[$i];
                $discipulo2 = $discipulosLocal[$j];

                if ($tipo === RelatorioController::ORDENACAO_TIPO_MEMBRESIA ||
                        $tipo === RelatorioController::ORDENACAO_TIPO_CELULA) {
                    $grupoFilho1 = $discipulo1;
                    $grupoFilho2 = $discipulo2;
                } else {
                    $grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
                    $grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
                }

                if ($tipo != 0) {
                    $percentual1 = $relatorio[$grupoFilho1->getId()][$campo];
                    $percentual2 = $relatorio[$grupoFilho2->getId()][$campo];
                } else {
                    $percentual1 = $grupoFilho1->getEntidadeAtiva()->getNumero();
                    if ($percentual1 < 0) {
                        $percentual1 = ($percentual1 * -1) + 100;
                    }
                    $percentual2 = $grupoFilho2->getEntidadeAtiva()->getNumero();
                    if ($percentual2 < 0) {
                        $percentual2 = ($percentual2 * -1) + 100;
                    }
                }

                if (($tipo != 0 && $percentual1 > $percentual2) || ($tipo == 0 && $percentual1 < $percentual2)) {
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
                                $grupoFilho->getGrupoPaiFilhoFilhosAtivos(), $mes, $ano
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

    public static function montarRelatorioSomandoTodoTimeAbaixoNoPeriodo($repositorio, $grupo, $periodoComecoDoMes, $periodoFimDoMes) {
        $relatorio['quantidadeLideres'] = 0;
        $relatorio['celulaQuantidade'] = 0;
        $tipoRelatorioPessoal = 1;
        $tipoRelatorioSomado = 2;
        $mostrarInativos = true;

        $numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
        $relatorioPessoal = RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $periodoComecoDoMes, $tipoRelatorioPessoal, $periodoFimDoMes);
        $relatorio['quantidadeLideres'] += $relatorioPessoal['quantidadeLideres'];
        $relatorio['celulaQuantidade'] += $relatorioPessoal['celulaQuantidade'];

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodoComecoDoMes);
        if ($grupoPaiFilhoFilhos) {
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $dataInativacao = null;
                if ($gpFilho->getData_inativacao()) {
                    $dataInativacao = $gpFilho->getData_inativacaoStringPadraoBanco();
                }
                $numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho, $dataInativacao);
                $relatorioDiscipulo = RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $periodoComecoDoMes, $tipoRelatorioSomado, $periodoFimDoMes, $mostrarInativos);
                $relatorio['quantidadeLideres'] += $relatorioDiscipulo['quantidadeLideres'];
                $relatorio['celulaQuantidade'] += $relatorioDiscipulo['celulaQuantidade'];

//                echo "<br />" . $grupoFilho->getEntidadeAtiva()->infoEntidade();
//                echo " - " . $grupoFilho->getNomeLideresAtivos();
//                echo " - ['quantidadeLideres']: " . $relatorioDiscipulo['quantidadeLideres'];
            }
        }
        return $relatorio;
    }

}
