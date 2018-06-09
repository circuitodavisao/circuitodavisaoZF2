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
use Application\Model\Entity\PessoaHierarquia;
use Application\Model\Entity\Situacao;
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
//        $alunosComFaltas = CursoController::pegarAlunosComFaltas($grupo, $turmas);

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
//            'alunosComFaltas' => $alunosComFaltas,
            'turmas' => $turmas,
        );

        $tipoRelatorioPessoal = 1;
        $periodo = -1;

        $relatorioCelulas = array();
        $relatorioCelulas[$grupo->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupo, $periodo);
        $grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
        if ($grupoPaiFilhoFilhos) {
            $relatorioDiscipulos = array();
            $discipulos = array();
            foreach ($grupoPaiFilhoFilhos as $gpFilho) {
                $discipulos[] = $gpFilho;
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $relatorioCelulas[$grupoFilho->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho, $periodo);

                $grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($periodo);
                if ($grupoPaiFilhoFilhos144) {
                    foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
                        $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                        $relatorioCelulas[$grupoFilho144->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho144, $periodo);

                        $grupoPaiFilhoFilhos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos($periodo);
                        if ($grupoPaiFilhoFilhos1728) {
                            foreach ($grupoPaiFilhoFilhos1728 as $gpFilho1728) {
                                $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                $relatorioCelulas[$grupoFilho1728->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho1728, $periodo);

                                $grupoPaiFilhoFilhos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos($periodo);
                                if ($grupoPaiFilhoFilhos20736) {
                                    foreach ($grupoPaiFilhoFilhos20736 as $gpFilho20736) {
                                        $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                        $relatorioCelulas[$grupoFilho20736->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho20736, $periodo);

                                        $grupoPaiFilhoFilhos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos($periodo);
                                        if ($grupoPaiFilhoFilhos248832) {
                                            foreach ($grupoPaiFilhoFilhos248832 as $gpFilho248832) {
                                                $grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
                                                $relatorioCelulas[$grupoFilho248832->getId()] = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($this->getRepositorio(), $grupoFilho248832, $periodo);
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
            $periodo = 0;
            $quantidadeDeDiscipulos = count($grupoSessao->getGrupoPaiFilhoFilhosAtivos($periodo));
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

            $tenhoSolicitacaoPendente = false;
            if ($solicitacoes = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorObjeto($grupoSessao->getId())) {
                foreach ($solicitacoes as $solicitacaoId) {
                    $solicitacao = $this->getRepositorio()->getSolicitacaoORM()->encontrarPorId($solicitacaoId['id']);
                    if ($solicitacao->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() === Situacao::PENDENTE_DE_ACEITACAO ||
                            $solicitacao->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() === Situacao::ACEITO_AGENDADO) {
                        $tenhoSolicitacaoPendente = true;
                    }
                }
            }


            $dados = array();
            $dados['idGrupo'] = $idSessao;
            $dados['entidade'] = $entidade;
            $dados['idEntidadeTipo'] = $entidadeLogada->getTipo_id();
            $dados['naoPodeInativar'] = ($tenhoDiscipulosAtivos || $tenhoSolicitacaoPendente);
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
            $formulario = new NumeracaoForm(Constantes::$FORM, $grupo);
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
