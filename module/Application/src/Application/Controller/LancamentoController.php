<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Form\CadastrarAtendimentoForm;
use Application\Form\CadastrarPessoaForm;
use Application\Model\Entity\EventoFrequencia;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoAtendimento;
use Application\Model\Entity\GrupoPessoa;
use Application\Model\Entity\Pessoa;
use Application\Model\ORM\RepositorioORM;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Json\Json;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: LancamentoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class LancamentoController extends CircuitoController {

    private $_translator;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Translator
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null, Translator $translator = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(Constantes::$PAGINA, 1);
        if ($pagina == Constantes::$PAGINA_MUDAR_FREQUENCIA) {
            $grupo = $entidade->getGrupo();
            $grupo->setRelatorioPendente($repositorioORM);

            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_MUDAR_FREQUENCIA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_MUDAR_ATENDIMENTO) {

            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_MUDAR_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ENVIAR_RELATORIO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ENVIAR_RELATORIO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ALTERAR_NOME) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ALTERAR_NOME,
            ));
        }
        if ($pagina == Constantes::$PAGINA_REMOVER_PESSOA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_REMOVER_PESSOA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CADASTRAR_PESSOA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRAR_PESSOA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_CADASTRAR_PESSOA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_FICHA_REVISAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_FICHA_REVISAO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SALVAR_PESSOA) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SALVAR_PESSOA,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ATENDIMENTO) {
            $parametro = $this->params()->fromRoute(Constantes::$ID);
            if (empty($parametro)) {
                $abaSelecionada = 1;
            } else {
                if ($parametro == 1) {
                    $abaSelecionada = 1;
                } else {

                    $abaSelecionada = 2;
                }
            }
            $mes = Funcoes::mesPorAbaSelecionada($abaSelecionada);
            $sessao->mesAtendimento = $mes;
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_LANCAR_ATENDIMENTO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_LANCAR_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_LANCAR_ATENDIMENTO_EDIT) {
            $sessao->editAtendimento = true;
            ;
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_LANCAR_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_SALVAR_ATENDIMENTO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_SALVAR_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_EXCLUIR_ATENDIMENTO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_EXCLUIR_ATENDIMENTO,
            ));
        }
        if ($pagina == Constantes::$PAGINA_ATENDIMENTO_EXCLUSAO_CONFIRMACAO) {
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_ATENDIMENTO_EXCLUSAO_CONFIRMACAO,
            ));
        }
        /* Funcoes */
        if ($pagina == Constantes::$PAGINA_FUNCOES) {
            /* Registro de sessão com o id passado na função */
            $request = $this->getRequest();
            $post_data = $request->getPost();

            $sessao->idFuncaoLancamento = $post_data[Constantes::$ID];
            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$PAGINA_FUNCOES,
            ));
        }

        /* Teste de funcao */
//        echo "testando funcao de alterarVisitanteParaConsolidacao <br />";
//        $repositorioORM->getGrupoPessoaORM()->alterarVisitanteParaConsolidacao($repositorioORM);

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
            $mes1 = Funcoes::mesPorAbaSelecionada($abaSelecionada);
            $ano1 = Funcoes::anoPorAbaSelecionada($abaSelecionada);
            if ($abaSelecionada == 1) {
                $cicloSelecionado = Funcoes::cicloAtual($mes1, $ano1);
            }
            if ($abaSelecionada == 2) {
                $cicloSelecionado = Funcoes::totalCiclosMes($mes1, $ano1);
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

        $mesSelecionado = Funcoes::mesPorAbaSelecionada($abaSelecionada);
        $anoSelecionado = Funcoes::anoPorAbaSelecionada($abaSelecionada);
        $grupo = $entidade->getGrupo();
        $eventos = $grupo->getGrupoEventoNoCiclo($cicloSelecionado, $mesSelecionado, $anoSelecionado);

        /* Verificar quantidade de pessoas já cadastradas */
        $contagemDePessoasCadastradas = count($grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado));
        $validacaoPessoasCadastradas = 0;
        if ($contagemDePessoasCadastradas > Constantes::$QUANTIDADE_MAXIMA_PESSOAS_NO_LANÇAMENTO) {
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
            Constantes::$ENTIDADE => $entidade,
            Constantes::$ABA_SELECIONADA => $abaSelecionada,
            Constantes::$CICLO_SELECIONADO => (int) $cicloSelecionado,
            Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),
            Constantes::$STATUS_ENVIO => $statusEnvio,
            Constantes::$VALIDACAO => $validacaoPessoasCadastradas,
            Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
            Constantes::$VALIDACAO_ENTIDADE_INATIVA => $validacaoEntidadeInativa,
            Constantes::$ENTIDADE_INATIVA => $entidadeInativa,
            Constantes::$LANCAMENTO_ORM => $repositorioORM,
                )
        );

        /* Verificando se alguem foi cadastrado */
        $nomePessoaCadastrada = '';
        if (!empty($sessao->nomePessoaCadastrada)) {
            $nomePessoaCadastrada = $sessao->nomePessoaCadastrada;
            unset($sessao->nomePessoaCadastrada);
        }
        /* Javascript especifico */
        $layoutJS = new ViewModel(array(Constantes::$NOME_PESSOA_CADASTRADA => $nomePessoaCadastrada,));
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_LANCAMENTO);

        $layoutJS2 = new ViewModel(array(Constantes::$QUANTIDADE_EVENTOS_CICLOS => count($eventos),));
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_LANCAMENTO_MODAL_EVENTOS);

        return $view;
    }

    /**
     * Abri tela para cadastro de pessoa para lançamento
     * @return ViewModel
     */
    public function cadastrarPessoaAction() {
        /* Helper Controller */
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $tipos = $repositorioORM->getGrupoPessoaTipoORM()->tipoDePessoaLancamento();
        /* Formulario */
        $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA, $tipos);

        $view = new ViewModel(array(
            Constantes::$FORM_CADASTRAR_PESSOA => $formCadastrarPessoa,
        ));

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA);
        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA);

        /* Javascript especifico de validação */
        $layoutJS2 = new ViewModel();
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA_VALIDACAO);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_CADASTRAR_PESSOA_VALIDACAO);

        return $view;
    }

    /**
     * Abri tela para cadastro de pessoa o revisão de vidas
     * @return ViewModel
     */
    public function cadastrarPessoaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $grupoPessoa = $repositorioORM->getGrupoPessoaORM()->encontrarPorId($sessao->idFuncaoLancamento);
        $pessoa = $grupoPessoa->getPessoa();
        $pessoa->setData_revisao(date('Y-m-d'));
        $repositorioORM->getPessoaORM()->persistir($pessoa);
        return new ViewModel();
    }

    /**
     * Abri tela com a ficha do revisão de vidas
     * @return ViewModel
     */
    public function fichaRevisaoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $grupoPessoa = $repositorioORM->getGrupoPessoaORM()->encontrarPorId($sessao->idFuncaoLancamento);
        $pessoa = $grupoPessoa->getPessoa();
        try {
            if (!empty($pessoa->getTurmaPessoaAtiva())) {
                $turmaPessoa = $pessoa->getTurmaPessoaAtiva();
            }
            return new ViewModel(
                    array(
                Constantes::$TURMA => $turmaPessoa->getTurma(),
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
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $repositorioORM->getGrupoORM()->setRelatorioEnviado($entidade->getGrupo());

        $view = new ViewModel();
        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_PESSOA);
        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_PESSOA);

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
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorIdPessoa($explodeIdEventoFrequencia[1]);
                $evento = $repositorioORM->getEventoORM()->encontrarPorIdEvento($explodeIdEventoFrequencia[2]);
                /* Verificar se a frequencia ja existe */
                $mes = Funcoes::mesPorAbaSelecionada($aba);
                $ano = Funcoes::anoPorAbaSelecionada($aba);
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
                    $repositorioORM->getEventoFrequenciaORM()->persistirSemDispacharEventoFrequencia($frequencia);
                } else {
                    /* Persitir frequencia */
                    $eventoFrequencia = new EventoFrequencia();
                    $eventoFrequencia->setPessoa($pessoa);
                    $eventoFrequencia->setEvento($evento);
                    $eventoFrequencia->setFrequencia($valor);
                    $eventoFrequencia->setCiclo($ciclo);
                    $eventoFrequencia->setMes($mes);
                    $eventoFrequencia->setAno($ano);
                    $repositorioORM->getEventoFrequenciaORM()->persistirSemDispacharEventoFrequencia($eventoFrequencia);
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
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                $pessoa = $repositorioORM->getPessoaORM()->encontrarPorIdPessoa($idPessoa);
                $pessoa->setNome(strtoupper($nome));
                $repositorioORM->getPessoaORM()->persistir($pessoa);

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
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

            $grupoPessoa = $repositorioORM->getGrupoPessoaORM()->encontrarPorId($sessao->idFuncaoLancamento);
            $grupoPessoa->inativar();
            $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);

            return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => Constantes::$ROUTE_INDEX,
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
                $funcao = $post_data[Constantes::$FUNCAO];
                $id = $post_data[Constantes::$ID];
                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $sessao->idSessao = $id;
                $response->setContent(Json::encode(
                                array(
                                    'response' => 'true',
                                    'tipoDeRetorno' => 1,
                                    'url' => '/lancamento' . $funcao,
                )));
            } catch (Exception $exc) {
                echo $exc->getMessage();
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
                $formCadastrarPessoa = new CadastrarPessoaForm(Constantes::$FORM_CADASTRAR_PESSOA);
                $formCadastrarPessoa->setInputFilter($pessoa->getInputFilterPessoaFrequencia());
                $formCadastrarPessoa->setData($post_data);

                /* validação */
                if ($formCadastrarPessoa->isValid()) {
                    $validatedData = $formCadastrarPessoa->getData();

                    $pessoa->exchangeArray($formCadastrarPessoa->getData());
                    $pessoa->setTelefone($validatedData[Constantes::$INPUT_DDD] . $validatedData[Constantes::$INPUT_TELEFONE]);

                    /* Helper Controller */
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($repositorioORM);

                    /* Salvar a pessoa e o grupo pessoa correspondente */
                    $repositorioORM->getPessoaORM()->persistir($pessoa);
                    $grupoPessoaTipo = $repositorioORM->getGrupoPessoaTipoORM()->encontrarPorId($post_data[Constantes::$INPUT_TIPO]);

                    $grupoPessoa = new GrupoPessoa();
                    $grupoPessoa->setPessoa($pessoa);
                    $grupoPessoa->setGrupo($grupo);
                    $grupoPessoa->setGrupoPessoaTipo($grupoPessoaTipo);
                    $nucleoPerfeito = $validatedData[Constantes::$INPUT_NUCLEO_PERFEITO];
                    if ($nucleoPerfeito != 0) {
                        $grupoPessoa->setNucleo_perfeito($nucleoPerfeito);
                    }

                    $repositorioORM->getGrupoPessoaORM()->persistir($grupoPessoa);

                    /* Pondo valores na sessao */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $sessao->nomePessoaCadastrada = $pessoa->getNome();

                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                                Constantes::$ACTION => Constantes::$ROUTE_INDEX,
                    ));
                } else {
                    return $this->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                                Constantes::$ACTION => Constantes::$ROUTE_INDEX,
                    ));
                }
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    /**
     * Abri tela para relatorio de atendimento 
     * @return ViewModel
     */
    public function atendimentoAction() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();
        $mes = $sessao->mesAtendimento;
        if ($mes == date('n')) {
            $aba = 1;
        } else {
            $aba = 2;
        }
        $titulo = '';
        $texto = '';
        $mostrar = false;

        if ($sessao->tipoMensagem) {
            $mostrar = true;
            $titulo = $sessao->titulo;
            $texto = $sessao->mensagem;
            $sessao->tipoMensagem = null;
        }
        $view = new ViewModel(array(
            Constantes::$GRUPOS_ABAIXO => $gruposAbaixo,
            Constantes::$MES_ATENDIMENTO => $mes,
            Constantes::$ABA_SELECIONADA => $aba,
        ));
        $layoutJS2 = new ViewModel(array(Constantes::$TITULO_MENSAGEM => $titulo,
            Constantes::$TEXTO_MENSAGEM => $texto,
            Constantes::$MOSTRAR_MENSAGEM => $mostrar,
        ));
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_ATENDIMENTO);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_CADASTRAR_ATENDIMENTO);

        return $view;
    }

    /**
     * Abri tela para lancamento de atendimento 
     * @return ViewModel
     */
    public function lancarAtendimentoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $mes = $sessao->mesAtendimento;
        $atendimentosFiltrados = null;

        if ($sessao->editAtendimento) {
            $sessao->editAtendimento = false;
            $idAtendimento = $sessao->idSessao;
            $atendimento = $repositorioORM->getGrupoAtendimentoORM()->encontrarPorId($idAtendimento);
            $grupo = $repositorioORM->getGrupoORM()->encontrarPorId($atendimento->getGrupo_id());
            $idPessoaPai = $sessao->idPessoa;
            $pessoaPai = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoaPai);
            $nomePessoaPai = $pessoaPai->getNomePrimeiroPrimeiraSiglaUltimo();
            $atendimentos = $grupo->getGrupoAtendimento();
            foreach ($atendimentos as $a) {
                if ($a->verificarSeEstaAtivo()) {
                    $partes = explode("/", $a->getDia());
                    if ($partes[1] == $mes) {
                        $atendimentosFiltrados[] = $a;
                    }
                }
            }
            $numeroAtendimentos = count($atendimentosFiltrados);
            $formCadastrarAtendimento = new CadastrarAtendimentoForm(Constantes::$FORM_CADASTRAR_ATENDIMENTO, $grupo->getId(), $nomePessoaPai, $idPessoaPai, $atendimento);
        } else {
            $idGrupo = $sessao->idSessao;
            $idPessoaPai = $sessao->idPessoa;
            $pessoaPai = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoaPai);
            $nomePessoaPai = $pessoaPai->getNomePrimeiroPrimeiraSiglaUltimo();
            $grupo = $repositorioORM->getGrupoORM()->encontrarPorId($idGrupo);
            /* Traz um array  */
            $atendimentos = $grupo->getGrupoAtendimento();
            foreach ($atendimentos as $a) {
                if ($a->verificarSeEstaAtivo()) {
                    $partes = explode("/", $a->getDia());
                    if ($partes[1] == $mes) {
                        $atendimentosFiltrados[] = $a;
                    }
                }
            }
            $numeroAtendimentos = count($atendimentosFiltrados);
            $formCadastrarAtendimento = new CadastrarAtendimentoForm(Constantes::$FORM_CADASTRAR_ATENDIMENTO, $idGrupo, $nomePessoaPai, $idPessoaPai);
        }

        $view = new ViewModel(array(
            Constantes::$GRUPO => $grupo,
            Constantes::$NUMERO_ATENDIMENTOS => $numeroAtendimentos,
            Constantes::$NOME_LIDER_ATENDIMENTO => $nomePessoaPai,
            Constantes::$FORM_CADASTRAR_ATENDIMENTO => $formCadastrarAtendimento,
            Constantes::$ARRAY_ATENDIMENTOS_GRUPO => $atendimentosFiltrados,
            Constantes::$MES_ATENDIMENTO => $mes,
        ));

        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_ATENDIMENTO);
        $view->addChild($layoutJS, Constantes::$STRING_JS_CADASTRAR_ATENDIMENTO);

        $layoutJS2 = new ViewModel(array(Constantes::$MES_ATENDIMENTO => $mes,));
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_VALIDACAO_ATENDIMENTO);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_VALIDACAO_ATENDIMENTO);

        return $view;
    }

    function retornaProgressoUsuarioLogadoCabecalhoAtendimento() {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        $grupo = $entidade->getGrupo();
        $gruposAbaixo = $grupo->getGrupoPaiFilhoFilhos();
        $totalGruposFilhos = 0;
        $totalGruposAtendidos = 0;
        foreach ($gruposAbaixo as $gpFilho) {
            $totalGruposAtendido = 0;
            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
            $entidadeFilho = $grupoFilho->getEntidadeAtiva();
            $grupoResponsavel = $grupoFilho->getResponsabilidadesAtivas();
            if ($grupoResponsavel) {
                $atendimentosDoGrupo = $grupoFilho->getGrupoAtendimento();
                foreach ($atendimentosDoGrupo as $ga) {
                    if ($ga->verificarSeEstaAtivo()) {
                        $partes = explode("/", $ga->getDia());
                        if ($partes[1] == $sessao->mesAtendimento) {
                            $totalGruposAtendido++;
                        }
                    }
                }
                if ($totalGruposAtendido >= 1) {
                    $totalGruposAtendidos++;
                }

                $totalGruposFilhos++;
            }
        }

        $progresso = ($totalGruposAtendidos / $totalGruposFilhos) * 100;
        
        return $progresso."_".$totalGruposAtendidos;
    }

    /**
     * Muda atendimento
     * @return Json
     */
    public function mudarAtendimentoAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();
                $valor = $post_data['tipo'];
                $idEventoFrequencia = $post_data['idGrupo'];

                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $mes = $sessao->mesAtendimento;
                $atendimentosFiltrados = 0;
                $validatedData = $post_data;
                $timeNow = new DateTime();
                $grupoAtendimento = new GrupoAtendimento();

                if ($validatedData['tipo'] == 1) {
                    $grupoAtendimento->setGrupo_id($validatedData['idGrupo']);
                    $grupoAtendimento->setQuem($sessao->idPessoa);
                    if($mes != $timeNow->format('m')){
                        $timeNow->sub(new \DateInterval("P31D"));
                    }
                    $grupoAtendimento->setDia($timeNow->format('Y-m-d'));
                    $grupoAtendimento->setDataEHoraDeCriacao();
                    /* Helper Controller */

                    $grupoLancado = $repositorioORM->getGrupoORM()->encontrarPorId($grupoAtendimento->getGrupo_id());
                    $grupoAtendimento->setGrupo($grupoLancado);
                    $repositorioORM->getGrupoAtendimentoORM()->persistir($grupoAtendimento);
                    $grupo = $repositorioORM->getGrupoORM()->encontrarPorId($grupoAtendimento->getGrupo_id());
                    $atendimentos = $grupo->getGrupoAtendimento();
                    $atendimentosFiltrados = array();
                    foreach ($atendimentos as $a) {
                        if ($a->verificarSeEstaAtivo()) {
                            $partes = explode("/", $a->getDia());
                            if ($partes[1] == $mes) {
                                $atendimentosFiltrados[] = $a;
                            }
                        }
                    }
                    $numeroAtendimentos = count($atendimentosFiltrados);
                } else {
                    $grupo = $repositorioORM->getGrupoORM()->encontrarPorId($validatedData['idGrupo']);
                    $atendimentosOld = $grupo->getGrupoAtendimento();
                    $contador = 0;
                    foreach ($atendimentosOld as $a) {
                        
                        if ($a->verificarSeEstaAtivo()) {
                            $partes = explode("/", $a->getDia());
                            if ($partes[1] == $mes) {
                                if ($contador == 0) {
                                    $ateParaDesativar = $a;
                                    $contador++;
                                }
                            }
                        }
                    }
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                    $atendimentoNaSessao = $repositorioORM->getGrupoAtendimentoORM()->encontrarPorId($ateParaDesativar->getId());

                    /* Persistindo */
                    /* Inativando o Atendimento */
                    $atendimentoParaInativar = $atendimentoNaSessao;

                    $atendimentoParaInativar->setData_inativacao(Funcoes::dataAtual());
                    $atendimentoParaInativar->setHora_inativacao(Funcoes::horaAtual());
                    $repositorioORM->getGrupoAtendimentoORM()->persistir($atendimentoParaInativar);
                    $grupoNew = $repositorioORM->getGrupoORM()->encontrarPorId($validatedData['idGrupo']);
                    $atendimentos = $grupoNew->getGrupoAtendimento();
                    $atendimentosFiltrados = array();
                    foreach ($atendimentos as $a) {
                        if ($a->verificarSeEstaAtivo()) {
                            $partes = explode("/", $a->getDia());
                            if ($partes[1] == $mes) {
                                $atendimentosFiltrados[] = $a;
                            }
                        }
                    }
                    $numeroAtendimentos = count($atendimentosFiltrados);
                }
                $explodeProgresso = explode('_', $this->retornaProgressoUsuarioLogadoCabecalhoAtendimento());
                $progresso = number_format($explodeProgresso[0], 2, '.', '');
                if ($progresso > 50 && $progresso < 80) {
                    $colorBarTotal = "progress-bar-warning";
                } else if ($progresso >= 80) {
                    $colorBarTotal = "progress-bar-success";
                } else {
                    $colorBarTotal = "progress-bar-danger";
                }
                $response->setContent(Json::encode(
                                array('response' => 'true',
                                    Constantes::$NUMERO_ATENDIMENTOS => $numeroAtendimentos,
                                    Constantes::$PROGRESSO_ATENDIMENTOS  => $progresso,
                                    Constantes::$COR_BARRA_ATENDIMENTOS => $colorBarTotal,
                                    Constantes::$TOTAL_DE_ATENDIMENTOS_GRUPO => $explodeProgresso[1],)));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
    }

    /**
     * Salva um novo atendimento
     */
    public function salvarAtendimentoAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                $grupoAtendimento = new GrupoAtendimento();


                /* validação */
                $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                $validatedData = $post_data;
                if (!empty($validatedData['id'])) {

                    $grupoAtendimento->setId($validatedData['id']);
                    $grupoAtendimento->setGrupo_id($validatedData['idGrupo']);
                    $grupoAtendimento->setQuem($validatedData['quem']);
                    $grupoAtendimento->setDia(Funcoes::mudarPadraoData($validatedData['dataAtendimento'], 0));
                    $atendimentoAtual = $repositorioORM->getGrupoAtendimentoORM()->encontrarPorId($grupoAtendimento->getId());
                    $grupoAtendimento->setData_criacao($atendimentoAtual->getData_criacao());
                    $grupoAtendimento->setHora_criacao($atendimentoAtual->getHora_criacao());
                    /* Helper Controller */

                    $grupoLancado = $repositorioORM->getGrupoORM()->encontrarPorId($grupoAtendimento->getGrupo_id());
                    $grupoAtendimento->setGrupo($grupoLancado);
                    $repositorioORM->getGrupoAtendimentoORM()->persistir($grupoAtendimento);
                } else {
                    $grupoAtendimento->setGrupo_id($validatedData['idGrupo']);
                    $grupoAtendimento->setQuem($validatedData['quem']);
                    $grupoAtendimento->setDia(Funcoes::mudarPadraoData($validatedData['dataAtendimento'], 0));
                    $grupoAtendimento->setDataEHoraDeCriacao();
                    /* Helper Controller */

                    $grupoLancado = $repositorioORM->getGrupoORM()->encontrarPorId($grupoAtendimento->getGrupo_id());
                    $grupoAtendimento->setGrupo($grupoLancado);
                    $repositorioORM->getGrupoAtendimentoORM()->persistir($grupoAtendimento);
                }

                $sessao = new Container(Constantes::$NOME_APLICACAO);
                $grupoResponsavel = $grupoLancado->getResponsabilidadesAtivas();
                $pessoas = array();
                foreach ($grupoResponsavel as $gr) {
                    $p = $gr->getPessoa();
                    $imagem = 'placeholder.png';
                    if (!empty($p->getFoto())) {
                        $imagem = $p->getFoto();
                    }
                    $pessoas[] = $p;
                }
                $totalPessoas = count($pessoas);
                $nomeAtendimento = '';
                $contagem = 1;
                foreach ($pessoas as $p) {
                    if ($contagem == 2) {
                        $nomeAtendimento .= '&nbsp;&&nbsp;';
                    }
                    if ($totalPessoas == 1) {
                        $nomeAtendimento .= $p->getNomePrimeiroUltimo();
                    } else {// duas pessoas
                        $nomeAtendimento .= $p->getNomePrimeiroPrimeiraSiglaUltimo();
                    }
                    $contagem++;
                }
                /* Sessão */
                $sessao->tipoMensagem = 1;
                $sessao->titulo = Constantes::$TRADUCAO_MENSAGEM_TITULO_LANCAMENTO_ATENDIMENTO;
                $sessao->mensagem = "$nomeAtendimento";

                return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                            Constantes::$ACTION => Constantes::$PAGINA_ATENDIMENTO,
                ));
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    /**
     * Tela com formulário de exclusão de evento
     * GET /cadastroEventoExclusao
     */
    public function atendimentoExclusaoAction() {
        /* Verificando a se tem algum id na sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        if (!empty($sessao->idSessao)) {
            $atendimento = $repositorioORM->getGrupoAtendimentoORM()->encontrarPorId($sessao->idSessao);
            if ($atendimento->getQuem() == 0) {
                $nomeLider = "AMBOS";
            } else {
                $idPessoaLider = $atendimento->getQuem();
                $pessoaLider = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoaLider);
                $nomeLider = $pessoaLider->getNomePrimeiroUltimo();
            }
        }
        $view = new ViewModel(array(
            Constantes::$ENTIDADE_ATENDIMENTO => $atendimento,
            Constantes::$PESSOA_LIDER => $nomeLider,
        ));
        $layoutJS2 = new ViewModel();
        $layoutJS2->setTemplate(Constantes::$TEMPLATE_JS_CADASTRAR_ATENDIMENTO);
        $view->addChild($layoutJS2, Constantes::$STRING_JS_CADASTRAR_ATENDIMENTO);

        return $view;
    }

    /**
     * Tela com formulário de exclusão de atendimento
     * GET /cadastroEventoConfirmacao
     */
    public function atendimentoExclusaoConfirmacaoAction() {
        /* Verificando a se tem algum id na sessão */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $atendimentoNaSessao = new GrupoAtendimento();
        if (!empty($sessao->idSessao)) {
            $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
            $atendimentoNaSessao = $repositorioORM->getGrupoAtendimentoORM()->encontrarPorId($sessao->idSessao);

            /* Persistindo */
            /* Inativando o Atendimento */
            $atendimentoParaInativar = $atendimentoNaSessao;

            $atendimentoParaInativar->setData_inativacao(Funcoes::dataAtual());
            $atendimentoParaInativar->setHora_inativacao(Funcoes::horaAtual());
            $repositorioORM->getGrupoAtendimentoORM()->persistir($atendimentoParaInativar);

            /* Inativando o Grupo Evento */
        }

        /* Sessão */
        $sessao->tipoMensagem = 2;
        $sessao->titulo = Constantes::$TRADUCAO_MENSAGEM_TITULO_EXCLUSAO_ATENDIMENTO;
        $sessao->mensagem = "ID: " . $atendimentoNaSessao->getId();


        return $this->forward()->dispatch(Constantes::$CONTROLLER_LANCAMENTO, array(
                    Constantes::$ACTION => Constantes::$PAGINA_ATENDIMENTO,
        ));
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
     * @param RepositorioORM $repositorioORM
     * @return Grupo
     */
    private function getGrupoSelecionado($repositorioORM) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;

        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        return $entidade->getGrupo();
    }

}
