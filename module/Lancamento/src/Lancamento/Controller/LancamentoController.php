<?php

namespace Lancamento\Controller;

use Doctrine\ORM\EntityManager;
use Entidade\Entity\EventoFrequencia;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;
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
        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesLancamento::$PAGINA, 1);
        if ($pagina == ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_CADASTRAR_PESSOA,
            ));
        }
        if ($pagina == ConstantesLancamento::$PAGINA_MUDAR_FREQUENCIA) {
            return $this->forward()->dispatch(ConstantesLancamento::$CONTROLLER_LANCAMENTO, array(
                        Constantes::$ACTION => ConstantesLancamento::$PAGINA_MUDAR_FREQUENCIA,
            ));
        }
        /* Helper Controller */
        $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

        /* Aba selecionada e ciclo */
        $parametro = $this->params()->fromRoute(Constantes::$ID);
        $explodeParamentro = explode('_', $parametro);
        if (empty($explodeParamentro[0])) {
            $abaSelecionada = 1;
        } else {
            if ($explodeParamentro[0] == 1) {
                $abaSelecionada = 1;
            } else {
                $abaSelecionada = 2;
            }
        }
        if (empty($explodeParamentro[1])) {
            $mes1 = FuncoesLancamento::mesPorAbaSelecionada($abaSelecionada);
            $ano1 = FuncoesLancamento::anoPorAbaSelecionada($abaSelecionada);
            if ($abaSelecionada == 1) {
                $cicloSelecionado = FuncoesLancamento::cicloAtual($mes1, $ano1);
            } else {
                $cicloSelecionado = FuncoesLancamento::totalCiclosMes($mes1, $ano1);
            }
        } else {
            $cicloSelecionado = $explodeParamentro[1];
        }

//        /* Teste de alteracao de envio */
//        $grupo = $entidade->getGrupo();
//        $resposta = $grupo->verificarSeFoiEnviadoORelatorio();
//        if ($resposta) {
//            $resposta = 'Enviado';
//        } else {
//            $resposta = 'Nao Enviado';
//        }
//        echo "<br />Grupo ";
//        echo "<br />Verificar Status: " . $resposta;
//        echo "<br />Data envio: " . $grupo->getEnvio_data();
//        echo "<br />Hora envio: " . $grupo->getEnvio_hora();
//
//        /* Teste de aas e ciclo */
//        echo "<br /><br /> abaSelecionada$abaSelecionada-cicloSelecionado$cicloSelecionado";
        $view = new ViewModel(
                array(
            ConstantesLancamento::$ENTIDADE => $entidade,
            ConstantesLancamento::$ABA_SELECIONADA => $abaSelecionada,
            ConstantesLancamento::$CICLO_SELECIONADO => (int) $cicloSelecionado,
                )
        );

        /* Javascript especifico */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesLancamento::$TEMPLATE_JS_LANCAMENTO);
        $view->addChild($layoutJS, ConstantesLancamento::$STRING_JS_LANCAMENTO);

        return $view;
    }

    /**
     * Abri tela para cadastro de pessoa para lançamento
     * @return ViewModel
     */
    public function cadastrarPessoaAction() {
        return new ViewModel();
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
                $checkbox = $post_data['checkbox'];
                $ciclo = $post_data['ciclo'];
                $aba = $post_data['aba'];
                $explodeCheckbox = explode('_', $checkbox);

                /* Helper Controller */
                $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());
                if (count($explodeCheckbox) == 3) {
                    $loginORM = new LoginORM($this->getDoctrineORMEntityManager());

                    $pessoa = $loginORM->getPessoaORM()->encontrarPorIdPessoa($explodeCheckbox[1]);
                    $evento = $lancamentoORM->getEventoORM()->encontrarPorIdEvento($explodeCheckbox[2]);

                    $mes = FuncoesLancamento::mesPorAbaSelecionada($aba);
                    $ano = FuncoesLancamento::anoPorAbaSelecionada($aba);

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
                if (count($explodeCheckbox) == 2) {
                    $idEventoFrequencia = $explodeCheckbox[1];
                    $eventoFrequencia = $lancamentoORM->getEventoFrequenciaORM()->encontrarPorIdEventoFrequencia($idEventoFrequencia);
                    $eventoFrequencia->setFrequencia($valor);
                    $lancamentoORM->getEventoFrequenciaORM()->persistirSemDispacharEventoFrequencia($eventoFrequencia);
                }
                $response->setContent(Json::encode(array('response' => 'true')));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return $response;
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

}
