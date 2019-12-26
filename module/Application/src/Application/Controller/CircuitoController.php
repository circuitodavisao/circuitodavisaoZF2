<?php

namespace Application\Controller;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\ORM\RepositorioORM;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\Registro;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

/**
 * Nome: CircuitoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle com propriedade do ORM
 */
class CircuitoController extends AbstractActionController {

    private $_doctrineORMEntityManager;
    private $repositorio;

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
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
     * Mostrar as mensagens de erro
     * @param type $mensagens
     */
    public function direcionaErroDeCadastro($mensagens) {
        echo "ERRO: Cadastro invalido!<br /><br />########################<br />";
        foreach ($mensagens as $l => $value) {
            echo "key? $l<br >";
            foreach ($value as $key => $value) {
                echo "$key => $value <br />";
            }
        }
    }

    public function inativarFatoLiderPorGrupo($grupo, $dataParaInativar = null) {
        $numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
        $fatoLiderSelecionado = $this->getRepositorio()->getFatoLiderORM()->encontrarFatoLiderPorNumeroIdentificador($numeroIdentificador);
        if ($fatoLiderSelecionado) {
            $fatoLiderSelecionado->setDataEHoraDeInativacao($dataParaInativar);
            $this->getRepositorio()->getFatoLiderORM()->persistir($fatoLiderSelecionado, false);
        }
    }

    /**
     * Retona a entidade Logada
     */
    public static function getEntidadeLogada($repositorioORM, $sessao) {
        $idEntidadeAtual = $sessao->idEntidadeAtual;
        $entidade = $repositorioORM->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        return $entidade;
    }

    /**
     * Verifica a sessao
     */
    public static function verificandoSessao($sessao, $abstractActionController) {
        if (empty($sessao->idPessoa)) {
            return $abstractActionController->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                        Constantes::$ACTION => Constantes::$ACTION_INDEX,
                        Constantes::$TIPO => 4,
                        Constantes::$MENSAGEM => 'Sua sessão expirou!',
            ));
        } else {
            return true;
        }
    }

    /**
     * Verifica a sessao
     */
    public static function direcionandoAoLogin($abstractActionController) {
        return $abstractActionController->forward()->dispatch(Constantes::$CONTROLLER_LOGIN, array(
                    Constantes::$ACTION => Constantes::$ACTION_INDEX,
                    Constantes::$TIPO => 4,
                    Constantes::$MENSAGEM => 'Sua sessão expirou!',
        ));
    }

    public function getRepositorio() {
        if (empty($this->repositorio)) {
            $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
        }
        return $this->repositorio;
    }

    function check_user_agent($type = NULL) {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($type == 'bot') {
            // matches popular bots
            if (preg_match("/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent)) {
                return true;
                // watchmouse|pingdom\.com are "uptime services"
            }
        } else if ($type == 'browser') {
            // matches core browser types
            if (preg_match("/mozilla\/|opera\//", $user_agent)) {
                return true;
            }
        } else if ($type == 'mobile') {
            // matches popular mobile devices that have small screens and/or touch inputs
            // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
            // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
            if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
                // these are the most common
                return true;
            } else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
                // these are less common, and might not be worth checking
                return true;
            }
        }
        return false;
    }

	public function validarSeSouIgrejaOuEquipeOuCoordencaoOuRegiao(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::equipe
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
	}

	public function validarSeSouIgrejaOuEquipeOuRegiao(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::equipe
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
	}

	public function validarSeSouIgrejaOuEquipe(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja
			&&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::equipe){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
	}

	public function validarSeSouIgreja(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
	}

	public function validarSeSouRegiao(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
    }

    public function validarSeSouRegiaoOuIgreja(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
        if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
            &&  $entidade->getEntidadeTipo()->getId() !== EntidadeTipo::igreja){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
    }
    
    public function validarSeSouPresidencial(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		if($entidade->getEntidadeTipo()->getId() !== EntidadeTipo::presidencial){
				return $this->redirect()->toRoute(Constantes::$ROUTE_PRINCIPAL, array(
					Constantes::$ACTION => 'semAcesso',
				));
			}
	}

	public function registrarLog($acao, $extra){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		
		$registro = new Registro();	
		$registro->setGrupo($entidade->getGrupo());
		$registro->setRegistroAcao($this->getRepositorio()->getRegistroAcaoORM()->encontrarPorId($acao));
		$registro->setExtra($extra);
		$registro->setIp(Funcoes::pegarIp());
		$this->getRepositorio()->getRegistroORM()->persistir($registro);
	}
}
