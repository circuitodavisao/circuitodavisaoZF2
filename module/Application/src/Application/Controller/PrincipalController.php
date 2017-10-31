<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\RepositorioORM;
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

    private $repositorio;

    /**
     * Função padrão, traz a tela principal
     * GET /principal
     */
    public function indexAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);

        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $periodo = -1;
        $relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal);
        $relatorioEquipe = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe);

        $dados = array();
        $arrayPeriodo = Funcoes::montaPeriodo($periodo);
        $dados['periodoExtenso'] = $arrayPeriodo[0];
        $dados['relatorio'] = $relatorio;
        $dados['relatorioEquipe'] = $relatorioEquipe;

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
        unset($sessao->idSessao);
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
            $dados = array();
            $dados['idGrupo'] = $idSessao;
            $dados['entidade'] = $entidade;
            $dados['idEntidadeTipo'] = $entidadeLogada->getTipo_id();
            $dados['tenhoDiscipulosAtivos'] = $tenhoDiscipulosAtivos;
            $dados['mostrarParaReenviarEmails'] = $mostrarParaReenviarEmails;
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
            $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);

            $grupoPaiFilhoPai = $grupoSessao->getGrupoPaiFilhoPaiAtivo();
            $grupoPaiFilhoPai->setDataEHoraDeInativacao();
            $this->getRepositorio()->getGrupoPaiFilhoORM()->persistir($grupoPaiFilhoPai, false);

            foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                $grupoResponsavel->setDataEHoraDeInativacao();
                $this->getRepositorio()->getGrupoResponsavelORM()->persistir($grupoResponsavel, false);
            }

            unset($sessao->idSessao);
            $sessao->mostrarNotificacao = true;
            $sessao->nomePessoa = $grupoSessao->getEntidadeAtiva()->infoEntidade();
            $sessao->exclusao = true;
            return $this->redirect()->toRoute('principal');
        }
    }

    public function enviarEmailAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        try {
            $idSessao = $sessao->idSessao;
            unset($sessao->idSessao);
            if ($idSessao) {
                $grupoSessao = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idSessao);
                foreach ($grupoSessao->getResponsabilidadesAtivas() as $grupoResponsavel) {
                    $pessoaSelecionada = $grupoResponsavel->getPessoa();
                    if ($pessoaSelecionada->getToken()) {
                        CadastroController::enviarEmailParaCompletarOsDados($this->getRepositorio(), $sessao->idPessoa, $pessoaSelecionada->getToken(), $pessoaSelecionada);
                    }
                }

                $sessao->mostrarNotificacao = true;
                $sessao->emailEnviado = true;
                return $this->redirect()->toRoute('principal');
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
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

    function getRepositorio() {
        if (empty($this->repositorio)) {
            $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
        }
        return $this->repositorio;
    }

}
