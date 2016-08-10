<?php

namespace Cadastro\Controller;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Controller\Helper\RepositorioORM;
use Cadastro\Form\CelulaForm;
use Cadastro\Form\ConstantesForm;
use Doctrine\ORM\EntityManager;
use Entidade\Entity\Evento;
use Entidade\Entity\EventoCelula;
use Entidade\Entity\GrupoEvento;
use Exception;
use Lancamento\Controller\Helper\LancamentoORM;
use Login\Controller\Helper\Constantes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de lancamento
 */
class CadastroController extends AbstractActionController {

    private $_doctrineORMEntityManager;

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Função padrão, traz a tela para lancamento
     * GET /cadastro[:pagina[/:id]]
     */
    public function indexAction() {

        /* Verificando rota */
        $pagina = $this->getEvent()->getRouteMatch()->getParam(ConstantesCadastro::$PAGINA, 1);
        if ($pagina == ConstantesCadastro::$PAGINA_CELULAS) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULAS,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_CELULA) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULA,
            ));
        }
        if ($pagina == ConstantesCadastro::$PAGINA_SALVAR_CELULA) {
            return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                        Constantes::$ACTION => ConstantesCadastro::$PAGINA_SALVAR_CELULA,
            ));
        }

        return new ViewModel();
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelulas
     */
    public function celulasAction() {
        /* Verificando se a célula foi cadastrada */
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $nomeHospedeiroCelulaCadastrado = '';
        if (!empty($sessao->nomeHospedeiroCelulaCadastrado)) {
            $nomeHospedeiroCelulaCadastrado = $sessao->nomeHospedeiroCelulaCadastrado;
            unset($sessao->nomeHospedeiroCelulaCadastrado);
        }
        $view = new ViewModel();
        /* Javascript */
        $layoutJS = new ViewModel(array(
            ConstantesForm::$LAYOUT_NOME_HOSPEDEIRO_CELULA_CADASTRADO => $nomeHospedeiroCelulaCadastrado
        ));
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_CELULAS);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_CELULAS);
        return $view;
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelula
     */
    public function celulaAction() {
        $celulaForm = new CelulaForm(ConstantesForm::$FORM_CELULA);

        $view = new ViewModel(array(
            ConstantesForm::$FORM_CELULA => $celulaForm,
        ));
        /* Javascript */
        $layoutJS = new ViewModel();
        $layoutJS->setTemplate(ConstantesForm::$LAYOUT_JS_CELULA);
        $view->addChild($layoutJS, ConstantesForm::$LAYOUT_STRING_JS_CELULA);

        $layoutJSValidacao = new ViewModel();
        $layoutJSValidacao->setTemplate(ConstantesForm::$LAYOUT_JS_CELULA_VALIDACAO);
        $view->addChild($layoutJSValidacao, ConstantesForm::$LAYOUT_STRING_JS_CELULA_VALIDACAO);

        return $view;
    }

    /**
     * Função para salvar a células
     * POST /cadastroSalvarCelula
     */
    public function salvarCelulaAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $post_data = $request->getPost();

                /* Entidades */
                $eventoCelula = new EventoCelula();
                $celulaForm = new CelulaForm(ConstantesForm::$FORM_CELULA);
                $celulaForm->setInputFilter($eventoCelula->getInputFilter());
                $celulaForm->setData($post_data);

                /* validação */
                if ($celulaForm->isValid()) {
                    /* Entidades */
                    $evento = new Evento();
                    $grupoEvento = new GrupoEvento();

                    /* Repositorios */
                    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
                    $lancamentoORM = new LancamentoORM($this->getDoctrineORMEntityManager());

                    /* Entidade selecionada */
                    $sessao = new Container(Constantes::$NOME_APLICACAO);
                    $idEntidadeAtual = $sessao->idEntidadeAtual;
                    $entidade = $lancamentoORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);

                    $validatedData = $celulaForm->getData();

                    $evento->setData_criacao(date('Y-m-d'));
                    $evento->setHora_criacao(date('H:s:i'));
                    $evento->setHora($validatedData[ConstantesForm::$FORM_HORA]);
                    $evento->setDia($validatedData[ConstantesForm::$FORM_DIA_DA_SEMANA]);
                    $evento->setEventoTipo($repositorioORM->getEventoTipoORM()->encontrarPorIdEventoTipo(2));

                    $eventoCelula->exchangeArray($celulaForm->getData());
                    $eventoCelula->setTelefone_hospedeiro($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] . $validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO]);
                    $eventoCelula->setCidade('');
                    $eventoCelula->setLogradouro('');
                    $eventoCelula->setBairro('');
                    $eventoCelula->setComplemento('');
                    $eventoCelula->setCep($validatedData[ConstantesForm::$FORM_CEP_LOGRADOURO]);
                    $eventoCelula->setEvento($evento);

                    $grupoEvento->setData_criacao(date('Y-m-d'));
                    $grupoEvento->setHora_criacao(date('H:s:i'));
                    $grupoEvento->setGrupo($entidade->getGrupo());
                    $grupoEvento->setEvento($evento);

                    /* Persistindo */
                    $lancamentoORM->getEventoORM()->persistirEvento($evento);
                    $repositorioORM->getEventoCelulaORM()->persistirEventoCelula($eventoCelula);
                    $repositorioORM->getGrupoEventoORM()->persistirGrupoEvento($grupoEvento);
                    /* Sessão */
                    $sessao->nomeHospedeiroCelulaCadastrado = $eventoCelula->getNome_hospedeiro();
                } else {
                    echo "ERRO: Cadastro invalido!";
                }

                return $this->forward()->dispatch(ConstantesCadastro::$CONTROLLER_CADASTRO, array(
                            Constantes::$ACTION => ConstantesCadastro::$PAGINA_CELULAS,
                ));
            } catch (Exception $exc) {
                echo $exc->getMessage();
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

}
