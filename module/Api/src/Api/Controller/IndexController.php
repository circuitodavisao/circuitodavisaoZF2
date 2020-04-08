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
		$request = $this->getRequest();

		if ($request->isPost()) {
			$json = Json::decode($request->getContent());
			error_log(print_r($json, true));
			Funcoes::enviarEmail('falecomleonardopereira@gmail.com', 'teste chekout cielo', 'json: '.$json);
		}

		return new JsonModel(array('ok' => true));
	}
}
