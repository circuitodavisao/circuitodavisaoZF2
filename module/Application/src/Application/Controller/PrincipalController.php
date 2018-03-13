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
        $sessao->getManager()->getConfig();

        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
        $grupo = $entidade->getGrupo();

        $eCasal = $grupo->verificaSeECasal();
        $arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno(date('m'), date('Y'));
        $relatorio = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, RelatorioController::relatorioMembresiaECelula, date('m'), date('Y'));

        $mostrarPrincipal = true;
        if (!$entidade->verificarSeEstaAtivo()) {
            $mostrarPrincipal = false;
        }

        $idRelatorio = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 1);

        $dados = array(
            'relatorio' => $relatorio,
            'periodoInicial' => $arrayPeriodoDoMes[0],
            'periodoFinal' => $arrayPeriodoDoMes[1],
            'mostrarPrincipal' => $mostrarPrincipal,
            'eCasal' => $eCasal,
            'idRelatorio' => $idRelatorio,
            'grupo' => $grupo,
            'repositorio' => $this->getRepositorio(),
            'pessoa' => $pessoa,
        );


        $tipoRelatorioPessoal = 1;
        $tipoRelatorioEquipe = 2;
        $periodo = -1;
        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho);
                $relatorio12 = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe, false, RelatorioController::relatorioCelulaRealizadas);
                $relatorio12Pessoal = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioPessoal, false, RelatorioController::relatorioCelulaRealizadas);
                if ($relatorio12['celulaQuantidade'] > 0) {
                    if ($relatorio12['celulaRealizadas'] < $relatorio12['celulaQuantidade']) {
                        $discipulos[] = $gpFilho;
                    }
                }
            }
            $dados['discipulos'] = $discipulos;
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
