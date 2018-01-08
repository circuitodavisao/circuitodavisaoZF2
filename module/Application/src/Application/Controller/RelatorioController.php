<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoEvento;
use Application\Model\Entity\GrupoPaiFilho;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\GrupoResponsavel;
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
     * GET /relatorio[/tipoRelatorio][/abaSelecionada]
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio());
        $periodoInicial = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
        $periodoFinal = $this->getEvent()->getRouteMatch()->getParam('periodoFinal', 0);

        $tipoRelatorioPessoal = 1;
        $relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodoInicial, $tipoRelatorioPessoal, $periodoFinal);

        $tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');

        $mostrarBotaoPeriodoAnterior = true;
        $mostrarBotaoPeriodoAfrente = true;
        $arrayPeriodo = Funcoes::montaPeriodo($periodoInicial);
        $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
        $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
        if ($grupo->getGrupoPaiFilhoPaiAtivo()) {
            $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($grupo->getGrupoPaiFilhoPaiAtivo()->getData_criacaoStringPadraoBanco());
            if ($dataDoGrupoPaiFilhoCriacaoParaComparar >= $dataDoInicioDoPeriodoParaComparar) {
                $mostrarBotaoPeriodoAnterior = false;
            }
        }
        $dados = array(
            RelatorioController::stringRelatorio => $relatorio,
            'tipoRelatorio' => $tipoRelatorio,
            'periodoInicial' => $periodoInicial,
            'periodoFinal' => $periodoFinal,
            'mostrarBotaoPeriodoAnterior' => $mostrarBotaoPeriodoAnterior,
            'mostrarBotaoPeriodoAfrente' => $mostrarBotaoPeriodoAfrente,
        );

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodoInicial);
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $dataInativacao = null;
                if ($gpFilho->getData_inativacao()) {
                    $dataInativacao = $gpFilho->getData_inativacaoStringPadraoBanco();
                }
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho, $dataInativacao);
                $tipoRelatorioSomado = 2;
                $relatorioDiscipulos[$grupoFilho->getId()] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodoInicial, $tipoRelatorioSomado);
            }

            $discipulosOrdenado = RelatorioController::ordenacaoDiscipulos($grupoPaiFilhoFilhos, $relatorioDiscipulos, $tipoRelatorio);

            $dados['discipulosOrdenado'] = $discipulosOrdenado;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
        }

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

    public static function montaRelatorio($repositorioORM, $numeroIdentificador, $periodoInicial, $tipoRelatorio, $periodoFinal = 0, $calcularDadosPassado = false, $ciclosPassados = 0) {
        unset($relatorio);
        /* Membresia */
        $relatorioMembresia = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio, $periodoFinal);
        $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoInicial);
        $quantidadeLideres = $fatoLider[0]['lideres'];
        foreach ($relatorioMembresia as $key => $value) {
            $soma[$key] = 0;
            foreach ($value as $campo) {
                foreach ($campo as $keyCampo => $valorCampo) {
                    $soma[$key] += $valorCampo;
                }
            }
        }
        $diferencaDePeriodos = 1;
        if ($periodoFinal !== 0) {
            $diferencaDePeriodos = $periodoFinal - $periodoInicial;
        }
        if ($diferencaDePeriodos < 0) {
            $diferencaDePeriodos *= -1;
        }
        $relatorio['membresiaCulto'] = $soma[RelatorioController::dimensaoTipoCulto] / $diferencaDePeriodos;
        $relatorio['membresiaArena'] = $soma[RelatorioController::dimensaoTipoArena] / $diferencaDePeriodos;
        $relatorio['membresiaDomingo'] = $soma[RelatorioController::dimensaoTipoDomingo] / $diferencaDePeriodos;
        $relatorio['membresiaMeta'] = Constantes::$META_LIDER * $quantidadeLideres;
        $relatorio['membresia'] = RelatorioController::calculaMembresia(
                        $soma[RelatorioController::dimensaoTipoCulto], $soma[RelatorioController::dimensaoTipoArena], $soma[RelatorioController::dimensaoTipoDomingo]) / $diferencaDePeriodos;
        if ($relatorio['membresiaMeta'] > 0 && $relatorio['membresia'] > 0) {
            $relatorio['membresiaPerformance'] = $relatorio['membresia'] / $relatorio['membresiaMeta'] * 100;
            $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance']);
            $relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance'], 2);
        }
        if ($relatorio['membresiaPerformance'] == '' || $relatorio['membresiaPerformance'] == 0) {
            $relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance(0);
            $relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance(0, 2);
        }
        $relatorio['quantidadeLideres'] = $quantidadeLideres;

        /* Célula */
        if ($periodoFinal != 0) {
            $periodoCelula = $periodoFinal;
        }
        if ($periodoFinal === 0) {
            $periodoCelula = -1;
        }
        $relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoCelula, $tipoRelatorio);
        $relatorioCelulaDeElite = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaDeElitePorNumeroIdentificador($numeroIdentificador, $periodoCelula, $tipoRelatorio);

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
        $relatorio['celula'] = $soma[RelatorioController::dimensaoTipoCelula] / $diferencaDePeriodos;
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

        if ($tipoRelatorio === 1) {
            /* calculo de diferenca do periodo passado */
            $periodoPassado = $periodoInicial - 1;
            $relatorioMembresiaPassado = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoPassado, $tipoRelatorio, $periodoFinal);
            foreach ($relatorioMembresiaPassado as $key => $value) {
                $somaPassado[$key] = 0;
                foreach ($value as $campo) {
                    foreach ($campo as $keyCampo => $valorCampo) {
                        $somaPassado[$key] += $valorCampo;
                    }
                }
            }
            $relatorioPassado = array();
            $relatorioPassado['membresiaCulto'] = $somaPassado[RelatorioController::dimensaoTipoCulto] / $diferencaDePeriodos;
            $relatorioPassado['membresiaArena'] = $somaPassado[RelatorioController::dimensaoTipoArena] / $diferencaDePeriodos;
            $relatorioPassado['membresiaDomingo'] = $somaPassado[RelatorioController::dimensaoTipoDomingo] / $diferencaDePeriodos;
            $relatorioPassado['celula'] = $somaPassado[RelatorioController::dimensaoTipoCelula] / $diferencaDePeriodos;
            $relatorioPassado['membresia'] = RelatorioController::calculaMembresia(
                            $somaPassado[RelatorioController::dimensaoTipoCulto], $somaPassado[RelatorioController::dimensaoTipoArena], $somaPassado[RelatorioController::dimensaoTipoDomingo]) / $diferencaDePeriodos;


            if ($relatorioPassado['membresiaCulto']) {
                $relatorio['diferencaCulto'] = $relatorio['membresiaCulto'] * 100 / $relatorioPassado['membresiaCulto'] - 100;
                $relatorio['diferencaCultoSeta'] = 'up';
                $relatorio['diferencaCultoFrase'] = 'AUMENTOU';
            } else {
                $relatorio['diferencaCulto'] = 0;
                $relatorio['diferencaCultoSeta'] = 'down';
                $relatorio['diferencaCultoFrase'] = 'DIMINUIU';
            }
            if ($relatorioPassado['membresiaArena']) {
                $relatorio['diferencaArena'] = $relatorio['membresiaArena'] * 100 / $relatorioPassado['membresiaArena'] - 100;
                $relatorio['diferencaArenaSeta'] = 'up';
                $relatorio['diferencaArenaFrase'] = 'AUMENTOU';
            } else {
                $relatorio['diferencaArena'] = 0;
                $relatorio['diferencaArenaSeta'] = 'down';
                $relatorio['diferencaArenaFrase'] = 'DIMINUIU';
            }
            if ($relatorioPassado['membresiaDomingo']) {
                $relatorio['diferencaDomingo'] = $relatorio['membresiaDomingo'] * 100 / $relatorioPassado['membresiaDomingo'] - 100;
                $relatorio['diferencaDomingoSeta'] = 'up';
                $relatorio['diferencaDomingoFrase'] = 'AUMENTOU';
            } else {
                $relatorio['diferencaDomingo'] = 0;
                $relatorio['diferencaDomingoSeta'] = 'down';
                $relatorio['diferencaDomingoFrase'] = 'DIMINUIU';
            }
            if ($relatorioPassado['membresia']) {
                $relatorio['diferencaMembresia'] = $relatorio['membresia'] * 100 / $relatorioPassado['membresia'] - 100;
                $relatorio['diferencaMembresiaSeta'] = 'up';
                $relatorio['diferencaMembresiaFrase'] = 'AUMENTOU';
            } else {
                $relatorio['diferencaMembresia'] = 0;
                $relatorio['diferencaMembresiaSeta'] = 'down';
                $relatorio['diferencaMembresiaFrase'] = 'DIMINUIU';
            }
            if ($relatorioPassado['celula']) {
                $relatorio['diferencaCelula'] = $relatorio['celula'] * 100 / $relatorioPassado['celula'] - 100;
                $relatorio['diferencaCelulaSeta'] = 'up';
                $relatorio['diferencaCelulaFrase'] = 'AUMENTOU';
            } else {
                $relatorio['diferencaCelula'] = 0;
                $relatorio['diferencaCelulaSeta'] = 'down';
                $relatorio['diferencaCelulaFrase'] = 'DIMINUIU';
            }
        }

        /* Relatorio passados */
        if ($calcularDadosPassado) {
            $relatorioMembresiaPassadas = array();
            for ($indice = (-1 * $ciclosPassados); $indice < 0; $indice++) {
                $periodoParaUsar = $indice;
                $relatoriosPassados = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoParaUsar, $tipoRelatorio);
                $fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoParaUsar);
                $quantidadeLideres = $fatoLider[0]['lideres'];
                $metasPassadas = Constantes::$META_LIDER * $quantidadeLideres;
                foreach ($relatoriosPassados as $key => $value) {
                    $somaPassadoMaisAntigos[$key] = 0;
                    foreach ($value as $campo) {
                        foreach ($campo as $keyCampo => $valorCampo) {
                            $somaPassadoMaisAntigos[$key] += $valorCampo;
                        }
                    }
                }
                $membresiaAntiga = RelatorioController::calculaMembresia(
                                $somaPassadoMaisAntigos[RelatorioController::dimensaoTipoCulto], $somaPassadoMaisAntigos[RelatorioController::dimensaoTipoArena], $somaPassadoMaisAntigos[RelatorioController::dimensaoTipoDomingo]);

                $relatorioMembresiaPassadas[$indice]['meta'] = $metasPassadas;
                $relatorioMembresiaPassadas[$indice]['membresia'] = $membresiaAntiga;
            }
            $relatorio['membresiasPassadas'] = $relatorioMembresiaPassadas;
        }
        return $relatorio;
    }

    public static function saberQuaisdasMinhasCelulasSaoDeElite(RepositorioORM $repositorioORM, Grupo $grupo, $periodoInicial, $periodoFinal) {
        $relatorio = array();
        $grupoEventosCelula = $grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);
        $contagem = 0;
        foreach ($grupoEventosCelula as $grupoEventoCelula) {
            $eventoId = $grupoEventoCelula->getEvento()->getId();
            $resultado = $repositorioORM->getFatoCicloORM()->verificaFrequenciasPorCelulaEPeriodo($periodoInicial, $eventoId, $periodoFinal);
            $relatorio[$contagem]['eventoId'] = $eventoId;
            $relatorio[$contagem]['valor'] = $resultado;
            $relatorio[$contagem]['hospedeiro'] = $grupoEventoCelula->getEvento()->getEventoCelula()->getNome_hospedeiroPrimeiroNome();
            $contagem++;
        }
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
                $class = 'Excelente, você está entre os melhores!';
            }
        }
        if (($valor < RelatorioController::MARGEM_A && $valor > RelatorioController::MARGEM_B)) {
            $class = 'success';
            if ($tipo === 2) {
                $class = 'Parabéns, continue e logo estará entre os melhores';
            }
        }
        if (($valor <= RelatorioController::MARGEM_B && $valor > RelatorioController::MARGEM_C)) {
            $class = 'warning';
            if ($tipo === 2) {
                $class = 'Muito bom, você está no caminho continue focado!';
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

    public static function ordenacaoDiscipulos($discipulosLocal, $relatorio, $tipo) {
        $campo = '';
        if ($tipo === 1) {
            $campo = 'membresiaPerformance';
        }
        if ($tipo === 2) {
            $campo = 'celulaRealizadasPerformance';
        }
        if ($tipo === 3) {
            $campo = 'celulaPerformance';
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

    public function testeAction() {
        try {

            $setarDataEHora = false;

            $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail('falecomleonardopereira@gmail.com');

            /* Inativando */
            $grupoResponsavels = $pessoa->getGrupoResponsavel();
            $gruposAtual = null;
            foreach ($grupoResponsavels as $gr) {
                $gr->setDataEHoraDeInativacao();
                $this->getRepositorio()->getGrupoResponsavelORM()->persistir($gr, $setarDataEHora);
                $gruposAtual = $gr->getGrupo();
            }
            $gruposAtual->setDataEHoraDeInativacao();
            $this->getRepositorio()->getGrupoORM()->persistir($gruposAtual, $setarDataEHora);

            $gpf = $gruposAtual->getGrupoPaiFilhoPai();
            $gpf->setDataEHoraDeInativacao();
            $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($gpf, $setarDataEHora);

            $entidade = $gruposAtual->getEntidadeAtiva();
            $entidade->setNome('TRANSFERIDA - ' . $entidade->getNome());
            $this->getRepositorio()->getEntidadeORM()->persistir($entidade, $setarDataEHora);

            /* Cadastrando */
            $grupoNovo = new Grupo();
            $this->getRepositorio()->getGrupoORM()->persistir($grupoNovo);

            $novaEntidade = new Entidade();
            $novaEntidade->setGrupo($grupoNovo);
            $novaEntidade->setNome('NOVO GRUPO');
            $novaEntidade->setEntidadeTipo($this->getRepositorio()->getEntidadeTipoORM()->encontrarPorId(EntidadeTipo::subEquipe));
            $this->getRepositorio()->getEntidadeORM()->persistir($novaEntidade);

            $pessoaPai = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail('rsilverio2012@hotmail.com');
            $grPai = $pessoaPai->getGrupoResponsavel()[0];
            $grupoPai = $grPai->getGrupo();

            $grupoPF = new GrupoPaiFilho();
            $grupoPF->setGrupoPaiFilhoFilho($grupoNovo);
            $grupoPF->setGrupoPaiFilhoPai($grupoPai);
            $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPF);

            $grupoResponsavelNovo = new GrupoResponsavel();
            $grupoResponsavelNovo->setGrupo($grupoNovo);
            $grupoResponsavelNovo->setPessoa($pessoa);
            $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavelNovo);

            $gpessoas = $gruposAtual->getGrupoPessoa();
            foreach ($gpessoas as $gp) {
                $grupoPessoa = new GrupoPessoa();
                $grupoPessoa->setGrupo($grupoNovo);
                $grupoPessoa->setPessoa($gp->getPessoa());
                $grupoPessoa->setGrupoPessoaTipo($gp->getGrupoPessoaTipo());
                $this->getRepositorio()->getGrupoPessoaORM()->persistir($grupoPessoa);
            }
            $geventos = $gruposAtual->getGrupoEventoAtivosPorTipo(GrupoEvento::CELULA);
            foreach ($geventos as $ge) {
                $grupoEvento = new GrupoEvento();
                $grupoEvento->setGrupo($grupoNovo);
                $grupoEvento->setEvento($ge->getEvento());
                $this->getRepositorio()->getGrupoEventoORM()->persistir($grupoEvento);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
