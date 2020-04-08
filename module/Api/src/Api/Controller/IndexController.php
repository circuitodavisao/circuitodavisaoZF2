<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Application\Controller\Helper\Funcoes;

class IndexController extends AbstractRestfulController {

	public function indexAction() {
		$this->setResponseWithHeader();
		return new JsonModel(array('data' => "Bem Vindo a API do Circuito da Visao"));
	}

	public function setResponseWithHeader() {
		$response = $this->getResponse();
		$response->getHeaders()
			->addHeaderLine('Access-Control-Allow-Origin', '*')
			->addHeaderLine('Access-Control-Allow-Methods', 'POST ');
	}

	public function checkoutAction() {
		$this->setResponseWithHeader();
		$response = $this->getResponse();
		$request = $this->getRequest();

		static $ESTADO_PAGO = 2;
		static $ESTADO_AUTORIZADO_CARTAO_CREDITO = 7;

		error_log('checkoutAction');

		error_log(print_r($request->getPost(), true));

		if ($request->isPost()) {
			error_log('post');
			$dataPost = $request->getPost();
			$email = $dataPost['customer_email'];
			$produto_id = $dataPost['product_id'];
			$estado_pagamento = $dataPost['payment_status'];
			if(
				intVal($estado_pagamento) === $ESTADO_PAGO ||
				intVal($estado_pagamento) === $ESTADO_AUTORIZADO_CARTAO_CREDITO
			){
				error_log('AUTORIZADO');
				if($pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($email)){
				error_log('ACHOU PESSOA');
				if($turmaPessoa = $pessoa->getTurmaPessoaAtivo()){
				error_log('EH ALUNO');

					/* validar qual produto e alterar o financeiro */
//					$alterarDataDeCriacaoFinanceiro = false;
//					$cadastroNovo = false;				
//					$idDisciplina = null;
//					$disciplina = $this->getRepositorio()->getDisciplinaORM()->encontrarPorId($idDisciplina);
//					$turmaPessoaFinanceiro = $turmaPessoa->getTurmaPessoaFinanceiroPorDisciplina($disciplina->getId());
//					if (!$turmaPessoaFinanceiro) {
//						$turmaPessoaFinanceiro = new TurmaPessoaFinanceiro();	
//						$cadastroNovo = true;						
//					}
//					$turmaPessoaFinanceiro->setDisciplina($disciplina);
//					$qualAvaliacao = null;
//					$mes = date('m');
//					$ano = date('Y');
//					switch ($qualAvaliacao) {
//					case 1:
//						$turmaPessoaFinanceiro->setValor1($valor);
//						$turmaPessoaFinanceiro->setMes1($mes);
//						$turmaPessoaFinanceiro->setAno1($ano);
//						break;
//					case 2:
//						$turmaPessoaFinanceiro->setValor2($valor);
//						$turmaPessoaFinanceiro->setMes2($mes);
//						$turmaPessoaFinanceiro->setAno2($ano);
//						break;
//					case 3:
//						$turmaPessoaFinanceiro->setValor3($valor);
//						$turmaPessoaFinanceiro->setMes3($mes);
//						$turmaPessoaFinanceiro->setAno3($ano);
//						break;
//					}
//					if($cadastroNovo){
//						$alterarDataDeCriacaoFinanceiro = true;
//						if(!$turmaPessoaFinanceiro->getValor1()){
//							$turmaPessoaFinanceiro->setValor1('N');
//						}
//						if(!$turmaPessoaFinanceiro->getValor2()){
//							$turmaPessoaFinanceiro->setValor2('N');
//						}
//						if(!$turmaPessoaFinanceiro->getValor3()){
//							$turmaPessoaFinanceiro->setValor3('N');
//						}
//					}					
				}
				}
			}
		}

		$response->setContent(Json::encode(array('ok' => true,)));
		return $response;
	}
}
