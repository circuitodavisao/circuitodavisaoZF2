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

		error_log('checkoutAction');

		if ($request->isGet()) {
			error_log('get');
		}

		if ($request->isPost()) {
			error_log('post');
		}

		error_log(print_r($request->getPost(), true));

		if ($request->isPost()) {

			error_log('Json');
			$data = Json::decode($request->getContent());
			error_log(print_r($data, true));

			error_log('post');
			$dataPost = $request->getPost();
			error_log(print_r($dataPost,true));

			Funcoes::enviarEmail('falecomleonardopereira@gmail.com', 'teste chekout cielo', 'json post');
		}

		$response->setContent(Json::encode(array('ok' => true,)));
		return $response;
	}
}
