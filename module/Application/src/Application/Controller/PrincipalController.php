<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\HierarquiaForm;
use Application\Form\NovoEmailForm;
use Application\Form\NumeracaoForm;
use Application\Form\RecuperarSenhaForm;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Hierarquia;
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Helper\FuncoesEntidade;
use Application\View\Helper\BotaoSimples;
use Exception;
use Zend\Json\Json;
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

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $mostrarPrincipal = true;
        if (!$entidade->verificarSeEstaAtivo()) {
            $mostrarPrincipal = false;
        }

        $dados = array(
            'mostrarPrincipal' => $mostrarPrincipal,
        );

        $view = new ViewModel($dados);
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate('layout/layout-js-principal');
        $view->addChild($layoutJS, 'layoutJSPrincipal');

        return $view;
    }

    const MEMBRESIA = 'membresia';
    const PESSOAL = 'pessoal';
    const EQUIPE = 'equipe';
    const ORIGINAL = 'original';
    const ORIGINAL_PERFORMANCE = 'originalPerformance';
    const VISUAL = 'visual';
    const CLASSE_VALOR = 'classeValor';
    const CLASSE_PERIODO = 'classePeriodo';
    const CLASSE_COR = 'classeCor';
    const CLASSE_COR_MOSTRAGEM = 'classeCorMostragem';
    const CLASSE_STRING = 'classeString';
    const CLASSE_STRING_MOSTRAGEM = 'classeStringMostragem';

    public function dashboardAction($param) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $response = $this->getResponse();


        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $grupo = $entidade->getGrupo();

        $eCasal = $grupo->verificaSeECasal();
        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno(date('m'), date('Y'));
        $relatorio = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, RelatorioController::relatorioMembresiaECelula, date('m'), date('Y'));

        $mesAnterior = date('m') - 1;
        $anoAnterior = date('Y');
        if (date('m') == 1) {
            $mesAnterior = 12;
            $anoAnterior = date('Y') - 1;
        }
        $relatorioAnterior = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, RelatorioController::relatorioMembresiaECelula, $mesAnterior, $anoAnterior);

        $mostrarPrincipal = true;
        if (!$entidade->verificarSeEstaAtivo()) {
            $mostrarPrincipal = false;
        }

        /* Dados principais */
        $metas[0] = Funcoes::metaPorHierarquia(Hierarquia::LIDER_DE_CELULA);
        $metas[1] = Funcoes::metaPorHierarquia($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
        $multiplicadorDaMeta = 1;
        if ($eCasal) {
            $multiplicadorDaMeta = 2;
        }
        $metas[0][0] *= $multiplicadorDaMeta;
        if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() === Hierarquia::LIDER_DE_CELULA) {
            $metas[1][0] *= $multiplicadorDaMeta;
        }

        $respostaBase = 'Porque a sua eficiência com relação a média de membresia e quantidade de célula do mês de #mes #periodo';

        $mensagemModalClasse = '';
        $mensagemModalClasse .= '<h1 class="text-center"><b>O que é a Classe?</b></h1>';
        $mensagemModalClasse .= '<p><b>Resposta:</b> É a classificação do resultado do líder baseado na média de membresia e quantidade de pesoas em células do mês anterior.</p>';
        $performance = array();
        for ($indiceDeRelatorios = 1; $indiceDeRelatorios <= 2; $indiceDeRelatorios++) {
            if ($indiceDeRelatorios === 1) {
                $relatorio = $relatorioAnterior;
            }
            if ($indiceDeRelatorios === 2) {
                $relatorio = $relatorio;
            }

            /* Mês de apresentação */
            if ($indiceDeRelatorios === 2) {
                $mesPorExtenso = Funcoes::mesPorExtenso(date('m'), 1);
            }
            if ($indiceDeRelatorios === 1) {
                $mesAnterior = date('m') - 1;
                if (date('m') == 1) {
                    $mesAnterior = 12;
                }
                $mesPorExtenso = Funcoes::mesPorExtenso($mesAnterior, 1);
            }
            $respostaMesAjustado = str_replace('#mes', $mesPorExtenso, $respostaBase);
            /* FIM Mês de apresentação */


            $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL] = $relatorio[0]['mediaMembresia'];
            $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE] = $relatorio[0]['mediaMembresia'] / $metas[0][0] * 100;
            $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL] = $relatorio[(count($relatorio) - 1)]['mediaMembresia'];
            $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE] = $relatorio[(count($relatorio) - 1)]['mediaMembresia'] / $metas[1][0] * 100;

            $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] = $performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE];
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] > 100) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] = 100;
            }
            $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] = $performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE];
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] > 100) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] = 100;
            }

            $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] = 100;
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL] < $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR]) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] = $performance[self::MEMBRESIA][self::PESSOAL][self::VISUAL];
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_D &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_C) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'D';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'é menor que 70%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_C &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_B) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'C';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'ficou entre 70% a 84%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_B &&
                    $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] < RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'B';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'ficou entre 85% a 99%';
            }
            if ($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] >= RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] = 'A';
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO] = 'é maior que 100%';
            }

            $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] = 100;
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL] < $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR]) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] = $performance[self::MEMBRESIA][self::EQUIPE][self::VISUAL];
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_D &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_C) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'D';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'é menor que 70%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_C &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_B) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'C';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'ficou entre 70% a 84%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_B &&
                    $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] < RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'B';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'ficou entre 85% a 99%';
            }
            if ($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] >= RelatorioController::MARGEM_A) {
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] = 'A';
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO] = 'é maior que 100%';
            }

            $respostaAjustada[self::PESSOAL] = str_replace('#periodo', $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_PERIODO], $respostaMesAjustado);
            $respostaAjustada[self::EQUIPE] = str_replace('#periodo', $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_PERIODO], $respostaMesAjustado);
            $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] = RelatorioController::corDaLinhaPelaPerformanceClasse($performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING]);
            $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] = RelatorioController::corDaLinhaPelaPerformanceClasse($performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING]);

            if ($indiceDeRelatorios === 1) {
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR_MOSTRAGEM] = $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR];
                $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING_MOSTRAGEM] = $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING];
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR_MOSTRAGEM] = $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR];
                $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING_MOSTRAGEM] = $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING];
            }

            $mensagemModalClasse .= '<div class="alert alert-default alert-sm">';
            if ($indiceDeRelatorios === 1) {
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Porque sou Classe </b><span class="label label-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . ' label-sm">' .
                        $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] . ' </span></p>';
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Resposta:</b> ' . $respostaAjustada[self::PESSOAL] . '</p>';

                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Porque sou Classe </b><span class="label label-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . ' label-sm">' .
                        $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] . ' </span></p>';
                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Resposta:</b> ' . $respostaAjustada[self::EQUIPE] . '</p>';
            }
            if ($indiceDeRelatorios === 2) {
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Como estou em ' . $mesPorExtenso . '?</b></p>';
                $mensagemModalClasse .= '<p class="relatorioPessoal"><b>Resposta:</b> Estima-se que no mês de ' . $mesPorExtenso . ' você provavelmente será classe '
                        . '<span class="label label-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . ' label-sm">' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING] . ' </span></p>';

                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Como estou em ' . $mesPorExtenso . '?</b></p>';
                $mensagemModalClasse .= '<p class="relatorioEquipe hidden"><b>Resposta:</b> Estima-se que no mês de ' . $mesPorExtenso . ' você provavelmente será classe '
                        . '<span class="label label-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . ' label-sm">' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING] . ' </span></p>';
            }
            $mensagemModalClasse .= '</div>';

            /* Barra Membresia */
            $mensagemModalClasse .= '<div class="row">';
            $mensagemModalClasse .= '<div class="col-xs-4 text-right" style="font-size:10px;">Membresia</div>';
            $mensagemModalClasse .= '<div class="col-xs-5">';
            $mensagemModalClasse .= '<div class="progress">';
            $mensagemModalClasse .= '<div class="relatorioPessoal progress-bar progress-bar-' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR] . '" role = "progressbar" '
                    . 'aria-valuenow="' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] . '" '
                    . 'aria-valuemin="0" aria-valuemax="100" style="width: ' . $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_VALOR] . '%;">' .
                    number_format($performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL_PERFORMANCE], 2, ',', '.') . '%</div>';
            $mensagemModalClasse .= '<div class="relatorioEquipe hidden progress-bar progress-bar-' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR] . '" role = "progressbar" '
                    . 'aria-valuenow="' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] . '" '
                    . 'aria-valuemin="0" aria-valuemax="100" style="width: ' . $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_VALOR] . '%;">' .
                    number_format($performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL_PERFORMANCE], 2, ',', '.') . '%</div>';
            $mensagemModalClasse .= '</div>';
            $mensagemModalClasse .= '</div>';
            $mensagemModalClasse .= '<div class="relatorioPessoal col-xs-3" style="font-size:10px;">' . number_format($performance[self::MEMBRESIA][self::PESSOAL][self::ORIGINAL], 2, ',', '.') . ' de ' . $metas[0][0] . '</div>';
            $mensagemModalClasse .= '<div class="relatorioEquipe hidden col-xs-3" style="font-size:10px;">' . number_format($performance[self::MEMBRESIA][self::EQUIPE][self::ORIGINAL], 2, ',', '.') . ' de ' . $metas[1][0] . '</div>';
            $mensagemModalClasse .= '</div>';
        }
        /* Fim dados principais */

        /* Circuito me ajuda */
        /* Celulas nao realizadas */
        $periodo = -1;
        $htmlCelulasNaoRealizadas = '';
        for ($indiceCelulasNaoRealizadas = 1; $indiceCelulasNaoRealizadas < (count($relatorio) - 1); $indiceCelulasNaoRealizadas++) {
            $nomeLideres = $relatorio[$indiceCelulasNaoRealizadas]['lideres'];
            $celulasNaoRealizadas = $relatorio[$indiceCelulasNaoRealizadas][$periodo]['celulaQuantidade'] - $relatorio[$indiceCelulasNaoRealizadas][$periodo]['celulaRealizadas'];

            if ($celulasNaoRealizadas > 0) {
                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden info">';
                $htmlCelulasNaoRealizadas .= '<td colspan="2">EQUIPE - ' . $nomeLideres . '</td>';
                $htmlCelulasNaoRealizadas .= '</tr>';

                $idGrupo = $relatorio[$indiceCelulasNaoRealizadas]['grupo'];
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
                $relatorio12 = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, RelatorioController::relatorioMembresiaECelula, date('m'), date('Y'));
                for ($indiceCelulasNaoRealizadas12 = 0; $indiceCelulasNaoRealizadas12 < (count($relatorio12) - 1); $indiceCelulasNaoRealizadas12++) {
                    $nomeLideres12 = $relatorio12[$indiceCelulasNaoRealizadas12]['lideres'];
                    $celulasNaoRealizadas12 = $relatorio12[$indiceCelulasNaoRealizadas12][$periodo]['celulaQuantidade'] - $relatorio12[$indiceCelulasNaoRealizadas12][$periodo]['celulaRealizadas'];
                    if ($celulasNaoRealizadas12 > 0) {
                        $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                        $htmlCelulasNaoRealizadas .= '<td>' . $nomeLideres12 . '</td>';
                        $htmlCelulasNaoRealizadas .= '<td>' . $celulasNaoRealizadas12 . '</td>';
                        $htmlCelulasNaoRealizadas .= '</tr>';
                    }
                }

                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden primary">';
                $htmlCelulasNaoRealizadas .= '<td class="text-right">TOTAL</td>';
                $htmlCelulasNaoRealizadas .= '<td>' . $celulasNaoRealizadas . '</td>';
                $htmlCelulasNaoRealizadas .= '</tr>';
                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                $htmlCelulasNaoRealizadas .= '<td colspan="2"></td>';
                $htmlCelulasNaoRealizadas .= '</tr>';
            }
        }
        $indiceUltimoRegistroDoRelatorio = (count($relatorio) - 1);
        $totalDeCelulasNaoRealizadas = $relatorio[$indiceUltimoRegistroDoRelatorio][$periodo]['celulaQuantidade'] - $relatorio[$indiceUltimoRegistroDoRelatorio][$periodo]['celulaRealizadas'];
        /* Fim celulas nao realizadas */

        $funcaoOnClick = $this->getServiceLocator()->get('viewhelpermanager')->get('funcaoOnClick');
        $botaoSimples = $this->getServiceLocator()->get('viewhelpermanager')->get('botaoSimples');
        $htmlTabelaCircuitoMeAjuda = '';
        $htmlTabelaCircuitoMeAjuda .= '<table class="table table-condensed">';
        $htmlTabelaCircuitoMeAjuda .= '<thead>';
        $htmlTabelaCircuitoMeAjuda .= '<tr class="info">';
        $htmlTabelaCircuitoMeAjuda .= '<th colspan="2" class="text-center">Circuito me Ajuda ' . Funcoes::montaPeriodo($periodo = -1)[0] . '</th>';
        $htmlTabelaCircuitoMeAjuda .= '</tr>';
        $htmlTabelaCircuitoMeAjuda .= '</thead>';
        $htmlTabelaCircuitoMeAjuda .= '<tbody>';
        $htmlTabelaCircuitoMeAjuda .= '<tr>';
        $htmlTabelaCircuitoMeAjuda .= '<td class="text-center">C&eacute;lulas <b>N&atilde;o</b> Realizadas: ' . $totalDeCelulasNaoRealizadas . '</td>';
        $funcao = $funcaoOnClick('$(".linhaCelulasNaoRealizadas").toggleClass("hidden")');
        $htmlTabelaCircuitoMeAjuda .= '<td>' . $botaoSimples('<i class="fa fa-eye" />', $funcao, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro) . '</td>';
        $htmlTabelaCircuitoMeAjuda .= '</tr>';
        $htmlTabelaCircuitoMeAjuda .= $htmlCelulasNaoRealizadas;

        $htmlTabelaCircuitoMeAjuda .= '</tbody>';
        $htmlTabelaCircuitoMeAjuda .= '</table>';
        /* Fim circuito me ajuda */

        /* Barras de progresso */
        $totalDeRelatorios = count($relatorio) - 1;
        $barraDeProgressoBonita = $this->getServiceLocator()->get('viewhelpermanager')->get('barraDeProgressoBonita');

        /* Membresia */
        $corBarraDeProgressoPessoalMembresia = $relatorio[0]['mediaMembresiaPerformanceClass'];
        $fraseBarraDeProgressoPessoalMembresia = $relatorio[0]['mediaMembresiaPerformanceFrase'];
        $corBarraDeProgressoEquipeMembresia = $relatorio[$totalDeRelatorios]['mediaMembresiaPerformanceClass'];
        $fraseBarraDeProgressoEquipeMembresia = $relatorio[$totalDeRelatorios]['mediaMembresiaPerformanceFrase'];

        $divBarraDeProgressoPessoalMembresia = $barraDeProgressoBonita(
                'Membresia', $relatorio[0]['mediaMembresiaPerformanceClass'], $relatorio[0]['mediaMembresiaPerformance'], 'm0', true, $relatorio[0][-1]['membresiaMeta'], $relatorio[0]['mediaMembresia'], '');
        $divBarraDeProgressoEquipeMembresia = $barraDeProgressoBonita(
                'Membresia', $relatorio[$totalDeRelatorios]['mediaMembresiaPerformanceClass'], $relatorio[$totalDeRelatorios]['mediaMembresiaPerformance'], 'm0', true, $relatorio[$totalDeRelatorios][-1]['membresiaMeta'], $relatorio[$totalDeRelatorios]['mediaMembresia'], '');

        $htmlDadosMembresia = '';
        $class = 'class = "col-lg-3 col-md-3 col-sm-6 col-xs-12"';
        $numeroIndices = 4;
        for ($indice = 1; $indice <= $numeroIndices; $indice++) {
            switch ($indice) {
                case 1:
                    $label = 'PESSOAS CHEIAS DE F&Eacute;
                                            ';
                    $valor[1] = $relatorio[0]['mediaMembresiaCulto'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaMembresiaCulto'];
                    break;
                case 2:
                    $label = 'PESSOAS APAIXONADAS';
                    $valor[1] = $relatorio[0]['mediaMembresiaArena'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaMembresiaArena'];
                    break;
                case 3:
                    $label = 'PESSOAS ALIAN&Ccedil;ADAS';
                    $valor[1] = $relatorio[0]['mediaMembresiaDomingo'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaMembresiaDomingo'];
                    break;
                case 4: $label = 'MEMBROS ASS&Iacute;DUOS';
                    $valor[1] = $relatorio[0]['mediaMembresia'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaMembresia'];
                    break;
            }
            $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]);
            $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]);

            $htmlDadosMembresia .= '<div ' . $class . '>';
            $htmlDadosMembresia .= '<div class = "panel panel-tile text-center br-a br-grey">';
            $htmlDadosMembresia .= '<div class = "panel-body" style = "padding-bottom: 0px;">';
            $htmlDadosMembresia .= '<h1 class = "fs30 mt5 mbn">';

            $htmlDadosMembresia .= '<span class = "relatorioPessoal">' . $valor[1] . '</span>';
            $htmlDadosMembresia .= '<span class = "relatorioEquipe hidden">' . $valor[2] . '</span>';

            $htmlDadosMembresia .= ' </h1>';
            $htmlDadosMembresia .= '<h6>' . $label . '</h6>';
            $htmlDadosMembresia .= '</div>';
            $htmlDadosMembresia .= '<div class = "panel-footer br-t p12">';
            $htmlDadosMembresia .= '<span class = "fs11">';

            for ($indicePeriodosDoMesAtual = $arrayPeriodoDoMes[0]; $indicePeriodosDoMesAtual <= $arrayPeriodoDoMes[1]; $indicePeriodosDoMesAtual++) {
                if ($indicePeriodosDoMesAtual != $arrayPeriodoDoMes[0]) {
                    $htmlDadosMembresia .= ' | ';
                }
                switch ($indice) {
                    case 1:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['membresiaCulto'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['membresiaCulto'];
                        break;
                    case 2:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['membresiaArena'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['membresiaArena'];
                        break;
                    case 3:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['membresiaDomingo'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['membresiaDomingo'];
                        break;
                    case 4:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['membresia'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['membresia'];
                        $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]);
                        $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]);
                        break;
                }

                $htmlDadosMembresia .= '<span class="relatorioPessoal" style="font-size: 10px;">' . $valor[1] . '</span>';
                $htmlDadosMembresia .= '<span class="relatorioEquipe hidden" style="font-size: 10px;">' . $valor[2] . '</span>';
            }

            $htmlDadosMembresia .= '</span>';
            $htmlDadosMembresia .= '</div>';
            $htmlDadosMembresia .= '</div>';
            $htmlDadosMembresia .= '</div>';
        }
        /* Fim membresia */
        /* Celula */
        $corBarraDeProgressoPessoalCelula = $relatorio[0]['mediaCelulaPerformanceClass'];
        $fraseBarraDeProgressoPessoalCelula = $relatorio[0]['mediaCelulaPerformanceFrase'];
        $corBarraDeProgressoEquipeCelula = $relatorio[$totalDeRelatorios]['mediaCelulaPerformanceClass'];
        $fraseBarraDeProgressoEquipeCelula = $relatorio[$totalDeRelatorios]['mediaCelulaPerformanceFrase'];

        $divBarraDeProgressoPessoalCelula = $barraDeProgressoBonita(
                'Celula', $relatorio[0]['mediaCelulaPerformanceClass'], $relatorio[0]['mediaCelulaPerformance'], 'm0', true, $relatorio[0][-1]['membresiaMeta'], $relatorio[0]['mediaCelula'], '');
        $divBarraDeProgressoEquipeCelula = $barraDeProgressoBonita(
                'Celula', $relatorio[$totalDeRelatorios]['mediaCelulaPerformanceClass'], $relatorio[$totalDeRelatorios]['mediaCelulaPerformance'], 'm0', true, $relatorio[$totalDeRelatorios][-1]['membresiaMeta'], $relatorio[$totalDeRelatorios]['mediaCelula'], '');

        $htmlDadosCelula = '';
        $class = 'class = "col-lg-4 col-md-4 col-sm-4 col-xs-12"';
        $numeroIndices = 3;
        for ($indice = 1; $indice <= $numeroIndices; $indice++) {
            switch ($indice) {
                case 1:
                    $label = 'TOTAL DE CÉLULAS DE MULTIPLIÇÃO';
                    $valor[1] = $relatorio[0]['mediaCelulaQuantidade'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaCelulaQuantidade'];
                    $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]);
                    $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]);
                    break;
                case 2:
                    $label = 'PESSOAS FREQUENTE';
                    $valor[1] = $relatorio[0]['mediaCelula'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaCelula'];
                    $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]);
                    $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]);
                    break;
                case 3:
                    $label = 'CÉLULAS REALIZADAS';
                    $valor[1] = $relatorio[0]['mediaCelulaRealizadasPerformance'];
                    $valor[2] = $relatorio[$totalDeRelatorios]['mediaCelulaRealizadasPerformance'];
                    $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]) . '%';
                    $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]) . '%';
                    break;
            }
            $htmlDadosCelula .= '<div ' . $class . '>';
            $htmlDadosCelula .= '<div class="panel panel-tile text-center br-a br-grey">';
            $htmlDadosCelula .= '<div class="panel-body" style="padding-bottom: 0px;">';
            $htmlDadosCelula .= '<h1 class="fs30 mt5 mbn">';

            $htmlDadosCelula .= '<span class = "relatorioPessoal">' . $valor[1] . '</span>';
            $htmlDadosCelula .= '<span class = "relatorioEquipe hidden">' . $valor[2] . '</span>';

            $htmlDadosCelula .= '</h1>';
            $htmlDadosCelula .= '<h6>' . $label . '</h6>';
            $htmlDadosCelula .= '</div>';
            $htmlDadosCelula .= '<div class="panel-footer br-t p12">';
            $htmlDadosCelula .= '<span class="fs11">';

            for ($indicePeriodosDoMesAtual = $arrayPeriodoDoMes[0]; $indicePeriodosDoMesAtual <= $arrayPeriodoDoMes[1]; $indicePeriodosDoMesAtual++) {
                if ($indicePeriodosDoMesAtual != $arrayPeriodoDoMes[0]) {
                    $htmlDadosCelula .= ' | ';
                }
                switch ($indice) {
                    case 1:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['celulaQuantidade'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['celulaQuantidade'];
                        break;
                    case 2:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['celula'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['celula'];
                        break;
                    case 3:
                        $valor[1] = $relatorio[0][$indicePeriodosDoMesAtual]['celulaRealizadasPerformance'];
                        $valor[2] = $relatorio[$totalDeRelatorios][$indicePeriodosDoMesAtual]['celulaRealizadasPerformance'];
                        $valor[1] = RelatorioController::formataNumeroRelatorio($valor[1]) . '%';
                        $valor[2] = RelatorioController::formataNumeroRelatorio($valor[2]) . '%';
                        break;
                }

                $htmlDadosCelula .= '<span class = "relatorioPessoal" style = "font-size: 10px;">' . $valor[1] . '</span>';
                $htmlDadosCelula .= '<span class = "relatorioEquipe hidden" style = "font-size: 10px;">' . $valor[2] . '</span>';
            }

            $htmlDadosCelula .= '</span>';
            $htmlDadosCelula .= '</div>';
            $htmlDadosCelula .= '</div>';
            $htmlDadosCelula .= '</div>';
        }
        /* fim celula */
        /* fim barra de progressos */

        /* proximo nivel */
        $htmlDadosProximoNivel = '';
        switch ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId()) {
            case Hierarquia::LIDER_EM_TREINAMENTO:
                $idProximaHierarquia = Hierarquia::LIDER_DE_CELULA;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::LIDER_DE_CELULA);
                break;
            case Hierarquia::LIDER_DE_CELULA:
                $idProximaHierarquia = Hierarquia::OBREIRO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::OBREIRO);
                break;
            case Hierarquia::OBREIRO:
                $idProximaHierarquia = Hierarquia::DIACONO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::DIACONO);
                break;
            case Hierarquia::DIACONO:
                $idProximaHierarquia = Hierarquia::MISSIONARIO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::MISSIONARIO);
                break;
            case Hierarquia::MISSIONARIO:
                $idProximaHierarquia = Hierarquia::PASTOR;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::PASTOR);
                break;
            case Hierarquia::PASTOR:
                $idProximaHierarquia = Hierarquia::BISPO;
                $metas = Funcoes::metaPorHierarquia(Hierarquia::BISPO);
                break;
        }
        $stringProximaHierarquia = 'De ' . $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome() .
                ' para ' . $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($idProximaHierarquia)->getNome();
        $perfomanceMembresia = $relatorio[$totalDeRelatorios]['mediaMembresia'] / $metas[0] * 100;
        if ($perfomanceMembresia > 100) {
            $perfomanceMembresia = 100;
        }
        $perfomanceLideres = $relatorio[$totalDeRelatorios][-1]['quantidadeLideres'] / $metas[1] * 100;
        if ($perfomanceLideres > 100) {
            $perfomanceLideres = 100;
        }
        $validacaoMembresia = $perfomanceMembresia / 2;
        $validacaoLideres = $perfomanceLideres / 2;
        $valorBarra = $validacaoMembresia + $validacaoLideres;
        $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra);
        $labelBarra = RelatorioController::corDaLinhaPelaPerformance($valorBarra, 2);

        $htmlDadosProximoNivel .= '<p class=" well bg-default text-' . $corDaBarra . ' text-center">' . $labelBarra . '</p>';
        $htmlDadosProximoNivel .= $barraDeProgressoBonita(
                $stringProximaHierarquia . ' <span class="badge">?</span>', $corDaBarra, $valorBarra, 'm0', false, 0, 0, $extra = 'onclick="$(\'#divProximoNivel\').toggleClass(\'hidden\');"');
        $htmlDadosProximoNivel .= '<div id = "divProximoNivel" class = "row p10 hidden">';
        $htmlDadosProximoNivel .= '<div class = "panel">';

        $htmlDadosProximoNivel .= '<div class = "panel-body">';
        for ($indice = 0; $indice <= 1; $indice++) {
            switch ($indice) {
                case 0:
                    $stringMeta = 'Membresia';
                    $indiceRelatorio = 'membresia';
                    $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceMembresia);
                    $valorBarra = $perfomanceMembresia;
                    $alcancado = $relatorio[$totalDeRelatorios]['mediaMembresia'];
                    $meta = $metas[0];
                    break;
                case 1:
                    $stringMeta = 'Líderes';
                    $indiceRelatorio = 'quantidadeLideres';
                    $corDaBarra = RelatorioController::corDaLinhaPelaPerformance($perfomanceLideres);
                    $valorBarra = $perfomanceLideres;
                    $alcancado = $relatorio[$totalDeRelatorios][-1]['quantidadeLideres'];
                    $meta = $metas[1];
                    break;
            }

            $htmlDadosProximoNivel .= $barraDeProgressoBonita(
                    $stringMeta, $corDaBarra, $valorBarra, 'm25', true, $meta, $alcancado);
        }
        $htmlDadosProximoNivel .= '</div>';
        $htmlDadosProximoNivel .= '</div>';
        $htmlDadosProximoNivel .= '</div>';
        /* fim dados proximo nivel */

        $dados = array(
        'spanNomeDeQuemEstaLogado' => $pessoa->getNomePrimeiroUltimo(),
        'fotoPerfil' => FuncoesEntidade::nomeDaImagem($pessoa),
        'divModalDadosPrincipais' => $mensagemModalClasse,
        'classePessoalCor' => $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_COR_MOSTRAGEM],
        'classePessoalString' => $performance[self::MEMBRESIA][self::PESSOAL][self::CLASSE_STRING_MOSTRAGEM],
        'classeEquipeCor' => $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_COR_MOSTRAGEM],
        'classeEquipeString' => $performance[self::MEMBRESIA][self::EQUIPE][self::CLASSE_STRING_MOSTRAGEM],
        'tdValorCelulasNaoRealizadas' => $totalDeCelulasNaoRealizadas,
        'divTabelaCircuitoMeAjuda' => $htmlTabelaCircuitoMeAjuda,
        'corBarraDeProgressoPessoalMembresia' => $corBarraDeProgressoPessoalMembresia,
        'fraseBarraDeProgressoPessoalMembresia' => $fraseBarraDeProgressoPessoalMembresia,
        'corBarraDeProgressoEquipeMembresia' => $corBarraDeProgressoEquipeMembresia,
        'fraseBarraDeProgressoEquipeMembresia' => $fraseBarraDeProgressoEquipeMembresia,
        'divBarraDeProgressoPessoalMembresia' => $divBarraDeProgressoPessoalMembresia,
        'divBarraDeProgressoEquipeMembresia' => $divBarraDeProgressoEquipeMembresia,
        'divDadosMembresia' => $htmlDadosMembresia,
        'corBarraDeProgressoPessoalCelula' => $corBarraDeProgressoPessoalCelula,
        'fraseBarraDeProgressoPessoalCelula' => $fraseBarraDeProgressoPessoalCelula,
        'corBarraDeProgressoEquipeCelula' => $corBarraDeProgressoEquipeCelula,
        'fraseBarraDeProgressoEquipeCelula' => $fraseBarraDeProgressoEquipeCelula,
        'divBarraDeProgressoPessoalCelula' => $divBarraDeProgressoPessoalCelula,
        'divBarraDeProgressoEquipeCelula' => $divBarraDeProgressoEquipeCelula,
        'divDadosCelula' => $htmlDadosCelula,
        'divDadosProximoNivel' => $htmlDadosProximoNivel,
        'response' => $response,
        );

        $response->setContent(Json::encode($dados));

        return $response;
    }

    public function verAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        unset($sessao->idSessao);
        if ($idSessao) {

            $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

            $mostrarParaReenviarEmails = false;
            foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                $pessoaSelecionada = $grupoResponsavel->getPessoa();
                if ($pessoaSelecionada->getToken()) {
                    $mostrarParaReenviarEmails = true;
                }
            }

            $entidade = $grupoSessao->getEntidadeAtiva();
            $entidadeLogada = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($sessao->idEntidadeAtual);
            $listagemDeEventos = $entidade->getGrupo()->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);

            $dados = array();
            $dados['idGrupo'] = $idSessao;
            $dados['entidade'] = $entidade;
            $dados['idEntidadeTipo'] = $entidadeLogada->getTipo_id();
            $dados['mostrarParaReenviarEmails'] = $mostrarParaReenviarEmails;
            $dados['responsabilidades'] = $grupoSessao->getResponsabilidadesAtivas();
            $dados[Constantes::$LISTAGEM_EVENTOS] = $listagemDeEventos;
            $dados[Constantes::$TIPO_EVENTO] = EventoTipo::tipoCelula;

            return new ViewModel($dados);
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function grupoExclusaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        try {
            $this->getRepositorio()->iniciarTransacao();
            $idSessao = $sessao->idSessao;
            unset($sessao->idSessao);
            if ($idSessao) {

                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

                $dados = array();
                $dados['idGrupo'] = $idSessao;
                $dados['entidade'] = $grupoSessao->getEntidadeAtiva();
                $dados[Constantes::$EXTRA] = null;

                $view = new ViewModel($dados);
                /* Javascript */
                $layoutJS = new ViewModel();
                $layoutJS->setTemplate('layout/layout-js-exclusao');
                $view->addChild($layoutJS, 'layoutJSExclusao');

                return $view;
            } else {
                return $this->redirect()->toRoute('principal');
            }
            $this->getRepositorio()->fecharTransacao();
        } catch (Exception $exc) {
            $this->getRepositorio()->desfazerTransacao();
            echo $exc->getTraceAsString();
            $this->direcionaErroDeCadastro($exc->getMessage());
            CircuitoController::direcionandoAoLogin($this);
        }
    }

    public function novoEmailParaEnviarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $form = new NovoEmailForm(Constantes::$FORM, $idSessao);

            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);

            $view = new ViewModel(
                    array(
                Constantes::$FORM => $form,
                'nome' => $pessoa->getNome(),
            ));
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate('layout/layout-js-enviar-email');
            $view->addChild($layoutJS, 'layoutJSEnviarEmail');
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function enviarEmailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setEmail($post_data[Constantes::$INPUT_EMAIL]);
                $setarDataEHora = false;
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);
                if ($pessoa->getToken()) {
                    CadastroController::enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $pessoa->getToken(), $pessoa);
                }
                $sessao->mostrarNotificacao = true;
                $sessao->emailEnviado = true;
                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function emailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $form = new NovoEmailForm(Constantes::$FORM, $idSessao);
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $view = new ViewModel(
                    array(
                Constantes::$FORM => $form,
                'pessoa' => $pessoa,
            ));
            $layoutJS = new ViewModel();
            $layoutJS->setTemplate('layout/layout-js-enviar-email');
            $view->addChild($layoutJS, 'layoutJSEnviarEmail');
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function emailSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $email = $post_data[Constantes::$INPUT_EMAIL];
                $setarDataEHora = false;

                /* caso algum inativo esteja usando o email remover dele */
                if ($pessoaPesquisada = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email)) {
                    $pessoaPesquisada->setEmail('');
                    $this->getRepositorio()->getPessoaORM()->persistir($pessoaPesquisada, $setarDataEHora);
                }

                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setEmail($email);
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);
                $sessao->mostrarNotificacao = true;
                $sessao->emailAlterado = true;
                $this->getRepositorio()->fecharTransacao();

                $sessao->idSessao = $pessoa->getResponsabilidadesAtivas()[0]->getGrupo()->getId();
                return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                            Constantes::$ACTION => 'ver',
                ));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function hierarquiaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
            $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas($pessoaLogada->getPessoaHierarquiaAtivo()->getHierarquia()->getId());
            $formulario = new HierarquiaForm(Constantes::$FORM, $pessoa, $hierarquias);
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'pessoa' => $pessoa,
            ));
            unset($sessao->idSessao);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function hierarquiaSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                if ($pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getId() != $post_data[Constantes::$FORM_HIERARQUIA]) {
                    $setarDataEHora = false;
                    $pessoaHierarquiaAtivo = $pessoa->getPessoaHierarquiaAtivo();
                    $pessoaHierarquiaAtivo->setDataEHoraDeInativacao();
                    $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquiaAtivo, $setarDataEHora);

                    $pessoaHierarquia = new PessoaHierarquia();
                    $pessoaHierarquia->setPessoa($pessoa);
                    $novaHierarquia = $this->getRepositorio()->getHierarquiaORM()->encontrarPorId($post_data[Constantes::$FORM_HIERARQUIA]);
                    $pessoaHierarquia->setHierarquia($novaHierarquia);
                    $this->getRepositorio()->getPessoaHierarquiaORM()->persistir($pessoaHierarquia);

                    $sessao->mostrarNotificacao = true;
                    $sessao->hierarquiaAlterada = true;
                }
                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function senhaAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idSessao);
            $formulario = new RecuperarSenhaForm(Constantes::$FORM, $pessoa->getId());
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'pessoa' => $pessoa,
            ));
            unset($sessao->idSessao);

            /* Javascript especifico */
            $layoutJSIndex = new ViewModel();
            $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_SENHA);
            $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_SENHA);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function senhaSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idPessoa = $post_data[Constantes::$INPUT_ID_PESSOA];
                $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);
                $pessoa->setSenha($post_data[Constantes::$INPUT_SENHA]);
                $setarDataEHora = false;
                $this->getRepositorio()->getPessoaORM()->persistir($pessoa, $setarDataEHora);

                $Subject = 'Dados de Acesso ao CV';
                $ToEmail = $pessoa->getEmail();
                $Content = '<pre>Olá</pre><pre>Seu usuário é: ' . $pessoa->getEmail() . '</pre><pre>Sua Senha é: ' . $post_data[Constantes::$INPUT_SENHA] . '</pre>';
                Funcoes::enviarEmail($ToEmail, $Subject, $Content);

                $sessao->mostrarNotificacao = true;
                $sessao->senhaAlterada = true;

                $this->getRepositorio()->fecharTransacao();
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    public function numeracaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
            $grupoPai = $grupo->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
            $formulario = new NumeracaoForm(Constantes::$FORM, $grupoPai, $grupo);
            $view = new ViewModel(
                    array(
                'formulario' => $formulario,
                'grupo' => $grupo,
            ));
            unset($sessao->idSessao);

            /* Javascript especifico */
            $layoutJSIndex = new ViewModel();
            $layoutJSIndex->setTemplate(Constantes::$TEMPLATE_JS_RECUPERAR_SENHA);
            $view->addChild($layoutJSIndex, Constantes::$STRING_JS_RECUPERAR_SENHA);
            return $view;
        } else {
            return $this->redirect()->toRoute('principal');
        }
    }

    public function numeracaoSalvarAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $this->getRepositorio()->iniciarTransacao();
                $post_data = $request->getPost();
                $idGrupo = $post_data[Constantes::$FORM_ID];
                $grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
                $numeracao = $post_data[Constantes::$FORM_NUMERACAO];

                $entidade = $grupo->getEntidadeAtiva();
                if ($numeracao != $entidade->getNumero()) {
                    $entidade->setDataEHoraDeInativacao();
                    $setarDataEHora = false;
                    $this->getRepositorio()->getEntidadeORM()->persistir($entidade, $setarDataEHora);
                    $entidadeNova = new Entidade();
                    $entidadeNova->setGrupo($grupo);
                    $entidadeNova->setNumero($numeracao);
                    $entidadeNova->setEntidadeTipo($this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
                    $this->getRepositorio()->getEntidadeORM()->persistir($entidadeNova);
                }
                $this->getRepositorio()->fecharTransacao();
                $sessao->idSessao = $idGrupo;
                return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
                            Constantes::$ACTION => 'ver',
                ));
            } catch (Exception $exc) {
                $this->getRepositorio()->desfazerTransacao();
                echo $exc->getMessage();
            }
        }
    }

    /**
     * Controle de funçoes da tela de cadastro
     * @return Json
     */
    public function funcoesAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $funcao = $post_data[Constantes::$FUNCAO];
                $id = $post_data[Constantes::$ID];
                $sessao->idSessao = $id;
                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'tipoDeRetorno' => 1,
                                    'url' => '/' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        return $response;
    }

}
