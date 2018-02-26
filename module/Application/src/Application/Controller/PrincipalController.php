<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\NovoEmailForm;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
use Application\Model\ORM\FatoRankingORM;
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
        $grupo = $entidade->getGrupo();
        $eCasal = $grupo->verificaSeECasal();
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);

        $idPessoa = $sessao->idPessoa;
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($idPessoa);

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $periodo = -1;

        $idRelatorio = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 1);
        if ($idRelatorio == 1) {
            $qualRelatorio = $tipoRelatorioPessoal;
        }
        if ($idRelatorio == 2) {
            $qualRelatorio = $tipoRelatorioEquipe;
        }
        $relatorioEquipe = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe);

        /* encontrando os periodos do mes atual e anterior */
        $arrayPeriodos = Funcoes::encontrarNumeroDePeriodosNoMesAtualEAnterior();
        $relatorioMedio = array();
        $relatorioMedio['pessoalAtual'] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $arrayPeriodos['periodoMesAtualInicial'], $qualRelatorio, $arrayPeriodos['periodoMesAtualFinal']);
        $relatorioMedio['pessoalAnterior'] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $arrayPeriodos['periodoMesAnteriorInicial'], $qualRelatorio, $arrayPeriodos['periodoMesAnteriorlFinal']);
        $relatorioMedio['celulasAtual'] = RelatorioController::saberQuaisdasMinhasCelulasSaoDeElite($this->getRepositorio(), $grupo, $arrayPeriodos['periodoMesAtualInicial'], $arrayPeriodos['periodoMesAtualFinal']);
        $relatorioMedio['celulasAnterior'] = RelatorioController::saberQuaisdasMinhasCelulasSaoDeElite($this->getRepositorio(), $grupo, $arrayPeriodos['periodoMesAnteriorInicial'], $arrayPeriodos['periodoMesAnteriorlFinal']);
        $relatorioMedioEquipe = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $arrayPeriodos['periodoMesAtualInicial'], $tipoRelatorioEquipe, $arrayPeriodos['periodoMesAtualFinal']);

        $relatorioMesAtual = array();
        $relatorioMesAtualEquipe = array();
        for ($indicePeriodosDoMesAtual = $arrayPeriodos['periodoMesAtualInicial']; $indicePeriodosDoMesAtual <= $arrayPeriodos['periodoMesAtualFinal']; $indicePeriodosDoMesAtual++) {
            $relatorioMesAtual[$indicePeriodosDoMesAtual] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $indicePeriodosDoMesAtual, $qualRelatorio);
            $relatorioMesAtualEquipe[$indicePeriodosDoMesAtual] = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $indicePeriodosDoMesAtual, $tipoRelatorioEquipe);            
        }
        $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();

        /* Pegando ranking */
        $isMobile = $this->check_user_agent('mobile');

        for ($indiceDeRankings = 1; $indiceDeRankings <= 2; $indiceDeRankings++) {
            if ($indiceDeRankings === 1) {
                $valorRanking = $grupo->getFatoRanking()->getRanking_membresia();
            }
            if ($indiceDeRankings === 2) {
                $valorRanking = $grupo->getFatoRanking()->getRanking_celula();
            }
            if (!$isMobile) {
                if ($valorRanking > 3) {
                    $qualArray[4] = $valorRanking - 2;
                    $qualArray[3] = $valorRanking - 1;
                    $qualArray[2] = $valorRanking;
                    $qualArray[1] = $valorRanking + 1;
                    $qualArray[0] = $valorRanking + 2;
                } else {
                    if ($valorRanking === 1) {
                        $qualArray[4] = $valorRanking;
                        $qualArray[3] = 2;
                        $qualArray[2] = 3;
                        $qualArray[1] = 4;
                        $qualArray[0] = 5;
                    }
                    if ($valorRanking === 2) {
                        $qualArray[4] = 1;
                        $qualArray[3] = $valorRanking;
                        $qualArray[2] = 3;
                        $qualArray[1] = 4;
                        $qualArray[0] = 5;
                    }
                    if ($valorRanking === 3) {
                        $qualArray[4] = 1;
                        $qualArray[3] = 2;
                        $qualArray[2] = $valorRanking;
                        $qualArray[1] = 4;
                        $qualArray[0] = 5;
                    }
                }
            } else {
                if ($valorRanking > 2) {
                    $qualArray[2] = $valorRanking - 1;
                    $qualArray[1] = $valorRanking;
                    $qualArray[0] = $valorRanking + 1;
                } else {
                    if ($valorRanking === 1) {
                        $qualArray[2] = $valorRanking;
                        $qualArray[1] = 2;
                        $qualArray[0] = 3;
                    }
                    if ($valorRanking === 2) {
                        $qualArray[2] = 1;
                        $qualArray[1] = $valorRanking;
                        $qualArray[0] = 3;
                    }
                    if ($valorRanking === 3) {
                        $qualArray[2] = 1;
                        $qualArray[1] = 2;
                        $qualArray[0] = $valorRanking;
                    }
                }
            }

            $totalDeRankings = count($qualArray) - 1;
            foreach ($qualArray as $valores) {
                $qualEncontrar = FatoRankingORM::RANKING_MEMBRESIA;
                if ($indiceDeRankings === 2) {
                    $qualEncontrar = FatoRankingORM::RANKING_CELULA;
                }
                $arrayFinal[$totalDeRankings] = $this->getRepositorio()->getFatoRankingORM()->encontrarPorRankingETipo($valores, $qualEncontrar);
                $totalDeRankings--;
            }
            if ($indiceDeRankings === 1) {
                $arrayMembresiaFatoRanking = $arrayFinal;
            }
            if ($indiceDeRankings === 2) {
                $arrayCelulaFatoRanking = $arrayFinal;
            }
        }

        $dados = array();
        $dados['idRelatorio'] = $idRelatorio;
        $dados['pessoa'] = $pessoa;
        $dados['eCasal'] = $eCasal;
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $dados['periodoExtenso'] = $arrayPeriodo[0];
        $dados['relatorioMesAtual'] = $relatorioMesAtual;
        $dados['relatorioMesAtualEquipe'] = $relatorioMesAtualEquipe;
        $dados['relatorioEquipe'] = $relatorioEquipe;
        $dados['relatorioMedio'] = $relatorioMedio;
        $dados['relatorioMedioEquipe'] = $relatorioMedioEquipe;
        $dados['repositorio'] = $this->getRepositorio();
        $dados['hierarquias'] = $hierarquias;
        $dados['grupo'] = $grupo;
        $dados['membresiaFatoRanking'] = $arrayMembresiaFatoRanking;
        $dados['celulaFatoRanking'] = $arrayCelulaFatoRanking;
        $dados['mostrarPrincipal'] = $mostrarPrincipal;

        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            $relatorioDiscipulosPessoal = array();
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho);
                $relatorio12 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe);
                $relatorio12Pessoal = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal);
                if ($relatorio12['celulaQuantidade'] > 0) {
                    if ($relatorio12['celulaRealizadas'] < $relatorio12['celulaQuantidade']) {
                        $relatorioDiscipulos[$grupoFilho->getId()] = $relatorio12;
                        $relatorioDiscipulosPessoal[$grupoFilho->getId()] = $relatorio12Pessoal;
                        $discipulos[] = $gpFilho;
                    }
                }

                $grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($periodo);
                if ($grupoPaiFilhoFilhos144) {
                    foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
                        $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                        $numeroIdentificador144 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho144);
                        $relatorio144 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador144, $periodo, $tipoRelatorioEquipe);
                        if ($relatorio144['celulaQuantidade'] > 0) {
                            if ($relatorio144['celulaRealizadas'] < $relatorio144['celulaQuantidade']) {
                                $relatorioDiscipulos[$grupoFilho144->getId()] = $relatorio144;
                            }
                        }
                    }
                }
            }
            $dados['discipulos'] = $discipulos;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
            $dados['discipulosRelatorioPessoal'] = $relatorioDiscipulosPessoal;
        }

        $view = new ViewModel($dados);
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate('layout/layout-js-principal');
        $view->addChild($layoutJS, 'layoutJSPrincipal');

        if ($sessao->jaMostreiANotificacao) {
            unset($sessao->mostrarNotificacao);
            unset($sessao->nomePessoa);
            unset($sessao->exclusao);
            unset($sessao->jaMostreiANotificacao);
        }

        return $view;
    }

    public function verAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
//        unset($sessao->idSessao);
        if ($idSessao) {

            $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
            $tenhoDiscipulosAtivos = false;
            $quantidadeDeDiscipulos = count($grupoSessao->getGrupoPaiFilhoFilhosAtivos());
            if ($quantidadeDeDiscipulos > 0) {
                $tenhoDiscipulosAtivos = true;
            }

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
            $dados['tenhoDiscipulosAtivos'] = $tenhoDiscipulosAtivos;
            $dados['mostrarParaReenviarEmails'] = $mostrarParaReenviarEmails;
            $dados['responsabilidades'] = $grupoSessao->getResponsabilidadesAtivas();
            $dados[Constantes::$LISTAGEM_EVENTOS] = $listagemDeEventos;
            $dados[Constantes::$TIPO_EVENTO] = EventoTipo::tipoCelula;
            $dados['mostrarExcluirCelula'] = false;
            if ($entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
                    $entidadeLogada->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
                $dados['mostrarExcluirCelula'] = true;
            }

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

    public function grupoExclusaoConfirmacaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idSessao = $sessao->idSessao;
        if ($idSessao) {
            $this->getRepositorio()->iniciarTransacao();
            try {
                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

                $grupoPaiFilhoPai = $grupoSessao->getGrupoPaiFilhoPaiAtivo();
                $grupoPaiFilhoPai->setDataEHoraDeInativacao();
                $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoPai, false);

                foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                    $grupoResponsavel->setDataEHoraDeInativacao();
                    $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavel, false);
                }
                $this->inativarFatoLiderPorGrupo($grupoSessao);

                $this->getRepositorio()->fecharTransacao();
                unset($sessao->idSessao);
                $sessao->mostrarNotificacao = true;
                $sessao->nomePessoa = $grupoSessao->getEntidadeAtiva()->infoEntidade();
                $sessao->exclusao = true;
                return $this->redirect()->toRoute('principal');
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $this->getRepositorio()->desfazerTransacao();
            }
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

    public function chamadaAction() {
        return new ViewModel();
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
