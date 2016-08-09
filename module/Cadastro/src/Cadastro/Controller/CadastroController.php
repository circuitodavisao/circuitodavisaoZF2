<?php

namespace Cadastro\Controller;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Cadastro\Form\CelulaForm;
use Cadastro\Form\ConstantesForm;
use Doctrine\ORM\EntityManager;
use Entidade\Controller\Helper\EntidadesORM;
use Entidade\Entity\Evento;
use Entidade\Entity\EventoCelula;
use Entidade\Entity\Grupo;
use Exception;
use Lancamento\Controller\Helper\ConstantesLancamento;
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

        return new ViewModel();
    }

    /**
     * Função para ver listagem de células
     * GET /cadastroCelulas
     */
    public function celulasAction() {

        return new ViewModel();
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

                $eventoCelula = new EventoCelula();
                $evento = new Evento();
                $celulaForm = new CelulaForm(ConstantesForm::$FORM_CELULA);
                $celulaForm->setInputFilter($eventoCelula->getInputFilter());
                $celulaForm->setData($post_data);

                /* validação */
                if ($celulaForm->isValid()) {
                    $validatedData = $celulaForm->getData();

                    $eventoCelula->exchangeArray($celulaForm->getData());
                    $evento->setData_criacao(date('Y-m-d'));
                    $evento->setHora_criacao(date('H:s:i'));
                    $eventoCelula->setTelefone_hospedeiroe($validatedData[ConstantesForm::$FORM_DDD_HOSPEDEIRO] . $validatedData[ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO]);

                    $entidadesORM = new EntidadesORM($this->getDoctrineORMEntityManager());

                    /* Grupo selecionado */
                    $grupo = $this->getGrupoSelecionado($entidadesORM);
//
//                    /* Salvar a pessoa e o grupo pessoa correspondente */
//                    $loginORM->getPessoaORM()->persistirPessoaNova($pessoa);
//                    $lancamentoORM->getGrupoPessoaORM()->cadastrar($lancamentoORM, $pessoa, $grupo, $post_data[ConstantesLancamento::$INPUT_TIPO], $validatedData[ConstantesLancamento::$INPUT_NUCLEO_PERFEITO]);
//
//                    /* Pondo valores na sessao */
//                    $sessao = new Container(Constantes::$NOME_APLICACAO);
//                    $sessao->nomePessoaCadastrada = $pessoa->getNome();
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

    /**
     * Recupera o grupo do perfil selecionado
     * @param EntidadesORM $entidadesORM
     * @return Grupo
     */
    private function getGrupoSelecionado($entidadesORM) {
        $sessao = new Container(Constantes::$NOME_APLICACAO);
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $entidadesORM->getEntidadeORM()->encontrarPorIdEntidade($idEntidadeAtual);
        return $entidade->getGrupo();
    }

}
