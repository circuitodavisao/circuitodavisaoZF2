<?php

namespace Lancamento\Controller;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\EventoFrequencia;
use Entidade\Entity\Grupo;
use Entidade\Entity\Pessoa;
use Exception;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;
use Lancamento\Form\CadastrarPessoaForm;
use Login\Controller\Helper\Constantes;
use Login\Controller\Helper\LoginORM;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: LancamentoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class LancamentoController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $_translator;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, Translator $translator = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }

        if (!is_null($translator)) {
            $this->_translator = $translator;
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /lancamento[:pagina[/:id]]
     */
    public function indexAction() {
        /* Helper Controller */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesLancamento::$PAGINA, 1);
        if ($pagina == ConstantesLancamento::$PAGINA_MUDAR_FREQUENCIA) {
            $grupo = $entidade->getGrupo();
            $grupo->setRelatorioPendente($lancamentoORM);

            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_MUDAR_FREQUENCIA,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_ENVIAR_RELATORIO) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_ENVIAR_RELATORIO,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_ALTERAR_NOME) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_ALTERAR_NOME,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_REMOVER_PESSOA) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_REMOVER_PESSOA,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA_REVISAO) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA_REVISAO,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_FICHA_REVISAO) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_FICHA_REVISAO,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_SALVAR_PESSOA) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_SALVAR_PESSOA,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_ATENDIMENTO) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_ATENDIMENTO,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_LANCAR_ATENDIMENTO) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_LANCAR_ATENDIMENTO,
            ));
        }
        /* Funcoes */
        if ($pagina == ConstantesLancamento::$PAGINA_FUNCOES) {
            /* Registro de sessão com o id passado na função */
            $request = $this->getRequest();
            $post_data = $request->getPost();

            $sessao->idFuncaoLancamento = $post_data[Constantes::$ID];
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_FUNCOES,
            ));
        }

        /* Teste de funcao */
//        echo "testando funcao de alterarVisitanteParaConsolidacao <br />";
//        $lancamentoORM->getGrupoPessoaORM()->alterarVisitanteParaConsolidacao($lancamentoORM);

        /* Aba selecionada e ciclo */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        $explodeParamentro = explode('_', $parametro);
        if (empty($explodeParamentro[0])) {
            $abaSelecionada = 1;
        } else {
            if ($explodeParamentro[0] == 1) {
                $abaSelecionada = 1;
            } else {
                if ($explodeParamentro[0] == 2) {
                    $abaSelecionada = 2;
                } else {
                    $abaSelecionada = 3;
                }
            }
        }

        if (empty($explodeParamentro[1])) {
            $mes1 = FuncoesLancamento::mesPorAbaSelecionada($abaSelecionada);
            $ano1 = FuncoesLancamento::anoPorAbaSelecionada($abaSelecionada);
            if ($abaSelecionada == 1) {
                $cicloSelecionado = FuncoesLancamento::cicloAtual($mes1, $ano1);
            }
            if ($abaSelecionada == 2) {
                $cicloSelecionado = FuncoesLancamento::totalCiclosMes($mes1, $ano1);
            }
        } else {
            $cicloSelecionado = $explodeParamentro[1];
        }

        /* Envio de relatorio */
        $resposta = $entidade->getGrupo()->verificarSeFoiEnviadoORelatorio();
        $statusEnvio = 0; /* Sem relatorio */
        if (!empty($entidade->getGrupo()->getEnvio())) {
            if ($resposta) {
                $statusEnvio = 1; /* Relatorio Atualizado */
            } else {
                $statusEnvio = 2; /* Relatorio Dezatualizado */
            }
        }

        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($abaSelecionada);
        $grupo = $entidade->getGrupo();
        $eventos = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Verificar quantidade de pessoas já cadastradas */
        $contagemDePessoasCadastradas = count($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado));
        $validacaoPessoasCadastradas = 0;
        if ($contagemDePessoasCadastradas > ConstantesLancamento::$QUANTIDADE_MAXIMA_PESSOAS_NO_LANÇAMENTO) {
            $validacaoPessoasCadastradas = 1;
        }

        /* Verificar data de cadastro da responsabilidade */
        $validacaoNesseMes = 0;
        $grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
        if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
            $validacaoNesseMes = 1;
        }
        /* Verficar se tem grupo inativado caso seja cadastrado nesse mes */
        $validacaoEntidadeInativa = 0;
        $entidadeInativa = 0;
        if ($validacaoNesseMes == 1) {
            /* Preciso encontrar a pessoa logada e verificar as responsabilidade dele */
            $pessoa = $grupoResponsavel->getPessoa();
            if ($pessoa->verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($grupoResponsavel->getData_criacao())) {
                $grupoResponsavelInativadoNessaData = $pessoa->verificarSeTemAlgumaResponsabilidadeInativadoNaDataInformado($grupoResponsavel->getData_criacao());
                /* Verificar o tipo da entidade
                 * 6 - IGREJA
                 * 7 - EQUIPE
                 * 8 - SUB EQUIPE
                 */
                $grupoInativo = $grupoResponsavelInativadoNessaData->getGrupo();
                $entidadeInativa = $grupoInativo->getEntidadeAtiva();
                if ($entidadeInativa->getTipo_id() == 6 || $entidadeInativa->getTipo_id() == 7 || $entidadeInativa->getTipo_id() == 8) {
                    $validacaoEntidadeInativa = 1;
                }
            }
        }

        $view = new ViewModel(
                array(
            ConstantesLancamento::$ENTIDADE => $entidade,
            ConstantesLancamento::$ABA_SELECIONADA => $abaSelecionada,
            ConstantesLancamento::$CICLO_SELECIONADO => (int) $cicloSelecionado,
            ConstantesLancamento::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),
            ConstantesLancamento::$STATUS_ENVIO => $statusEnvio,
            ConstantesLancamento::$VALIDACAO => $validacaoPessoasCadastradas,
            ConstantesLancamento::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            ConstantesLancamento::$VALIDACAO_ENTIDADE_INATIVA => $validacaoEntidadeInativa,
            ConstantesLancamento::$ENTIDADE_INATIVA => $entidadeInativa,
            ConstantesLancamento::$LANCAMENTO_ORM => $lancamentoORM,
                )
        );

        /* Verificando se alguem foi cadastrado */
        $nomePessoaCadastrada = '';
        if (!empty($sessao->nomePessoaCadastrada)) {
            $nomePessoaCadastrada = $sessao->nomePessoaCadastrada;
            unset($sessao->nomePessoaCadastrada);
        }
        /* Javascript especifico */
        $layoutJS = new ViewModel(array(ConstantesLancamento::$NOME_PESSOA_CADASTRADA => $nomePessoaCadastrada,));
        $layoutJS->setTemplate(ConstantesLancamento::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, ConstantesLancamento::$STRING_JS_LANCAMENTO);

        $layoutJS2 = new ViewModel(array(ConstantesLancamento::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),));
        $layoutJS2->setTemplate(ConstantesLancamento::$TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS);
        $view->addChild($layoutJS2, ConstantesLancamento::$STRING_JS_LANCAMENTO_MODAL_EVENTOS);

        return $view;
    }

    /**
     * Abri tela para cadastro de pessoa para lançamento
     * @return ViewModel
     */
    public function cadastrarPessoaAction() {
        /* Helper Controller */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $tipos = $lancamentoORM->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        /* Formulario */
        $formCadastrarPessoa = new CadastrarPessoaForm(ConstantesLancamento::$FORM_CADASTRAR_PESSOA, $tipos);

        $view = new ViewModel(array(
            ConstantesLancamento::$FORM_CADASTRAR_PESSOA => $formCadastrarPessoa,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesLancamento::$TEMPLATE_JS_CADASTRAR_PESSOA);
        $view->addChild($layoutJS, ConstantesLancamento::$STRING_JS_CADASTRAR_PESSOA);

        /* Javascript especifico de validação */
        $layoutJS2 = new ViewModel();
        $layoutJS2->setTemplate(ConstantesLancamento::$TEMPLATE_JS_CADASTRAR_PESSOA_VALIDACAO);
        $view->addChild($layoutJS2, ConstantesLancamento::$STRING_JS_CADASTRAR_PESSOA_VALIDACAO);

        return $view;
    }

    /**
     * Abri tela para cadastro de pessoa o revisão de vidas
     * @return ViewModel
     */
    public function cadastrarPessoaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $loginORM = new LoginORM($this->getDoctrineORMEntityManager());
        $grupoPessoa = $lancamentoORM->getGrupoPessoaORM()->encontrarPorIdGrupoPessoa($sessao->idFuncaoLancamento);
        $pessoa = $grupoPessoa->getPessoa();
        $pessoa->setData_revisao(date('Y-m-d'));
        $loginORM->getPessoaORM()->persistirPessoa($pessoa);
        return new ViewModel();
    }

    /**
     * Abri tela com a ficha do revisão de vidas
     * @return ViewModel
     */
    public function fichaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $grupoPessoa = $lancamentoORM->getGrupoPessoaORM()->encontrarPorIdGrupoPessoa($sessao->idFuncaoLancamento);
        $pessoa = $grupoPessoa->getPessoa();
        try {
            if (!empty($pessoa->getTurmaPessoaAtiva())) {
                $turmaPessoa = $pessoa->getTurmaPessoaAtiva();
            }
            return new ViewModel(
                    array(
                ConstantesLancamento::$TURMA => $turmaPessoa->getTurma(),
                Constantes::$PESSOA => $pessoa,
                    )
            );
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Abri tela para enviar relatorio
     * @return ViewModel
     */
    public function enviarRelatorioAction() {
        /* Helper Controller */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        $entidade->getGrupo()->setRelatorioEnviado($lancamentoORM);

        $view = new ViewModel();
        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesLancamento::$TEMPLATE_JS_CADASTRAR_PESSOA);
        $view->addChild($layoutJS, ConstantesLancamento::$STRING_JS_CADASTRAR_PESSOA);

        return $view;
    }

    /**
     * Muda a frequência de um evento
     * @return Json
     */
    public function mudarFrequenciaAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $valor = $post_data['valor'];
                $idEventoFrequencia = $post_data['idEventoFrequencia'];
                $ciclo = $post_data['ciclo'];
                $aba = $post_data['aba'];
                $explodeIdEventoFrequencia = explode('_', $idEventoFrequencia);

                /* Helper Controller */
                $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
                $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

                $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($explodeIdEventoFrequencia[1]);
                $evento = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($explodeIdEventoFrequencia[2]);
                /* Verificar se a frequencia ja existe */
                $mes = FuncoesLancamento::mesPorAbaSelecionada($aba);
                $ano = FuncoesLancamento::anoPorAbaSelecionada($aba);
                $criteria = Criteria::create()
                        ->andWhere(Criteria::expr()->eq("evento_id", (int) $explodeIdEventoFrequencia[2]))
                        ->andWhere(Criteria::expr()->eq("ano", $ano))
                        ->andWhere(Criteria::expr()->eq("mes", $mes))
                        ->andWhere(Criteria::expr()->eq("ciclo", $ciclo));

                $eventosFiltrados = $pessoa->getEventoFrequencia()->matching($criteria);
                if ($eventosFiltrados->count() == 1) {
                    /* Frequencia existe */
                    $frequencia = $eventosFiltrados->first();
                    $frequencia->setFrequencia($valor);
                    $lancamentoORM->getEventoFrequenciaORM()->persistirSemDispacharEventoFrequencia($frequencia);
                } else {
                    /* Persitir frequencia */
                    $eventoFrequencia = new EventoFrequencia();
                    $eventoFrequencia->setPessoa($pessoa);
                    $eventoFrequencia->setEvento($evento);
                    $eventoFrequencia->setFrequencia($valor);
                    $eventoFrequencia->setCiclo($ciclo);
                    $eventoFrequencia->setMes($mes);
                    $eventoFrequencia->setAno($ano);
                    $lancamentoORM->getEventoFrequenciaORM()->persistirSemDispacharEventoFrequencia($eventoFrequencia);
                }
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    'idEvento' => $evento->getId())));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Alterar nome de uma pessoa
     * @return Json
     */
    public function alterarNomeAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $idPessoa = $post_data['idPessoa'];
                $nome = $post_data['nome'];

                /* Helper Controller */
                $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

                $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);
                $pessoa->setNome(strtoupper($nome));
                $loginORM->getPessoaORM()->persistirPessoa($pessoa);

                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'nomeAjustado' => $pessoa->getNomeListaDeLancamento(),
                )));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Remover pessoa da listagem
     * @return Json
     */
    public function removerPessoaAction() {
        try {
            $sessao = new Container(Constantes::$NOME_APLICACAO);

            /* Helper Controller */
            $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

            $grupoPessoa = $lancamentoORM->getGrupoPessoaORM()->encontrarPorIdGrupoPessoa($sessao->idFuncaoLancamento);
            $grupoPessoa->inativar();
            $lancamentoORM->getGrupoPessoaORM()->persistirGrupoPessoa($grupoPessoa);

            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$ROUTE_INDEX,
            ));
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Controle de funçoes da tela de lançamento
     * @return Json
     */
    public function funcoesAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $id = $post_data[Constantes::$ID];
                $funcao = $post_data[Constantes::$FUNCAO];

                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'tipoDeRetorno' => 1,
                                    'url' => '/lancamento' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Salva uma nova pessoa na linha de lançamento
     */
    public function salvarPessoaAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                $pessoa = new Pessoa();
                $formCadastrarPessoa = new CadastrarPessoaForm(ConstantesLancamento::$FORM_CADASTRAR_PESSOA);
                $formCadastrarPessoa->setInputFilter($pessoa->getInputFilterPessoaFrequencia());
                $formCadastrarPessoa->setData($post_data);

                /* validação */
                if ($formCadastrarPessoa->isValid()) {
                    $validatedData = $formCadastrarPessoa->getData();

                    $pessoa->exchangeArray($formCadastrarPessoa->getData());
                    $pessoa->setData_criacao(date('Y-m-d'));
                    $pessoa->setHora_criacao(date('H:s:i'));
                    $pessoa->setTelefone($validatedData[ConstantesLancamento::$INPUT_DDD] . $validatedData[ConstantesLancamento::$INPUT_TELEFONE]);

                    /* Helper Controller */
                    $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
                    $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($lancamentoORM);

                    /* Salvar a pessoa e o grupo pessoa correspondente */
                    $loginORM->getPessoaORM()->persistirPessoaNova($pessoa);
                    $lancamentoORM->getGrupoPessoaORM()->cadastrar($lancamentoORM, $pessoa, $grupo, $post_data[ConstantesLancamento::$INPUT_TIPO], $validatedData[ConstantesLancamento::$INPUT_NUCLEO_PERFEITO]);

                    /* Pondo valores na sessao */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->nomePessoaCadastrada = $pessoa->getNome();

                    return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                                Constantes::$ACTION => ConstantesLancamento::$ROUTE_INDEX,
                    ));
                } else {
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => ConstantesLancamento::$ROUTE_INDEX,
                    ));
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }

    /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    /**
     * Recupera translator
     * @return translator
     */
    public function getTranslator() {
        return $this->_translator;
    }

    /**
     * Recupera o grupo do perfil selecionado
     * @param LancamentoORM $lancamentoORM
     * @return Grupo
     */
    private function getGrupoSelecionado($lancamentoORM) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        return $entidade->getGrupo();
    }

    /**
     * Abri tela para relatorio de atendimento 
     * @return ViewModel
     */
    public function atendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();

        $view = new ViewModel(array(
            ConstantesLancamento::$GRUPOS_ABAIXO => $gruposAbaixo,
        ));

        return $view;
    }

    /**
     * Abri tela para lancamento de atendimento 
     * @return ViewModel
     */
    public function lancarAtendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);

        /* Verificando a se tem algum id na sessão */
        $grupoNaSessao = new Grupo();
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
        if (!empty($sessao->idSessao)) {
            $grupoNaSessao = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($sessao->idSessao);
        }



        $view = new ViewModel(array(
            ConstantesLancamento::$GRUPO => $grupoNaSessao,
        ));

        return $view;
    }

}
