<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractRestfulController {

    public function getList() {
        $this->setResponseWithHeader();
        return new JsonModel(array('data' => "Bem Vindo a API do Circuito da Visao"));
    }

    public function setResponseWithHeader() {
        $response = $this->getResponse();
        $response->getHeaders()
                ->addHeaderLine('Access-Control-Allow-Origin', '*')
                ->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
    }

}
