<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\NovoEmailForm;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\EventoTipo;
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

        if ($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja) {
            $turmas = $grupo->getTurma();
        } else {
            $turmas = $grupo->getGrupoIgreja()->getTurma();
        }
        $alunosComFaltas = CursoController::pegarAlunosComFaltas($grupo, $turmas);

        $hierarquias = $this->getRepositorio()->getHierarquiaORM()->encontrarTodas();
        $dados = array(
            'relatorio' => $relatorio,
            'relatorioAnterior' => $relatorioAnterior,
            'periodoInicial' => $arrayPeriodoDoMes[0],
            'periodoFinal' => $arrayPeriodoDoMes[1],
            'mostrarPrincipal' => $mostrarPrincipal,
            'eCasal' => $eCasal,
            'grupo' => $grupo,
            'hierarquias' => $hierarquias,
            'repositorio' => $this->getRepositorio(),
            'pessoa' => $pessoa,
            'alunosComFaltas' => $alunosComFaltas,
            'turmas' => $turmas,
        );

        $tipoRelatorioEquipe = 2;
        $tipoRelatorioPessoal = 1;
        $periodo = -1;

        $relatorioCelulas = array();
        $relatorioCelulas[$grupo->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupo, $periodo);
        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            $relatorioDiscipulosPessoal = array();
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho);
                $relatorio12 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe, false, RelatorioController::relatorioCelulaRealizadas);
                $relatorio12Pessoal = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                if ($relatorio12['celulaQuantidade'] > 0) {
                    $relatorioCelulas[$grupoFilho->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho, $periodo);
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
                        $numeroIdentificador144 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho144, false, RelatorioController::relatorioCelulaRealizadas);
                        $relatorio144 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador144, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                        if ($relatorio144['celulaQuantidade'] > 0) {
                            $relatorioCelulas[$grupoFilho144->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho144, $periodo);
                            if ($relatorio144['celulaRealizadas'] < $relatorio144['celulaQuantidade']) {
                                $relatorioDiscipulos[$grupoFilho144->getId()] = $relatorio144;
                            }
                        }

                        $grupoPaiFilhoFilhos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos($periodo);
                        if ($grupoPaiFilhoFilhos1728) {
                            foreach ($grupoPaiFilhoFilhos1728 as $gpFilho1728) {
                                $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                $numeroIdentificador1728 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho1728, false, RelatorioController::relatorioCelulaRealizadas);
                                $relatorio1728 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador1728, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                                if ($relatorio1728['celulaQuantidade'] > 0) {
                                    $relatorioCelulas[$grupoFilho1728->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho1728, $periodo);
                                    if ($relatorio1728['celulaRealizadas'] < $relatorio1728['celulaQuantidade']) {
                                        $relatorioDiscipulos[$gpFilho1728->getId()] = $relatorio144;
                                    }
                                }

                                $grupoPaiFilhoFilhos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos($periodo);
                                if ($grupoPaiFilhoFilhos20736) {
                                    foreach ($grupoPaiFilhoFilhos20736 as $gpFilho20736) {
                                        $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                        $numeroIdentificador20736 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho20736, false, RelatorioController::relatorioCelulaRealizadas);
                                        $relatorio20736 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador20736, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                                        if ($relatorio20736['celulaQuantidade'] > 0) {
                                            $relatorioCelulas[$grupoFilho20736->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho20736, $periodo);
                                            if ($relatorio20736['celulaRealizadas'] < $relatorio20736['celulaQuantidade']) {
                                                $relatorioDiscipulos[$gpFilho20736->getId()] = $relatorio20736;
                                            }
                                        }

                                        $grupoPaiFilhoFilhos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos($periodo);
                                        if ($grupoPaiFilhoFilhos248832) {
                                            foreach ($grupoPaiFilhoFilhos248832 as $gpFilho248832) {
                                                $grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
                                                $numeroIdentificador248832 = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho248832, false, RelatorioController::relatorioCelulaRealizadas);
                                                $relatorio248832 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador248832, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                                                if ($relatorio248832['celulaQuantidade'] > 0) {
                                                    $relatorioCelulas[$grupoFilho248832->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho248832, $periodo);
                                                    if ($relatorio248832['celulaRealizadas'] < $relatorio248832['celulaQuantidade']) {
                                                        $relatorioDiscipulos[$gpFilho248832->getId()] = $relatorio248832;
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
            $dados['discipulos'] = $discipulos;
            $dados['discipulosRelatorio'] = $relatorioDiscipulos;
            $dados['discipulosRelatorioPessoal'] = $relatorioDiscipulosPessoal;
            $dados['relatorioCelulas'] = $relatorioCelulas;
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
