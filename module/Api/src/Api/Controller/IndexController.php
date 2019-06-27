<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

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

	public function logarChurchProConsolidacaoAction() {
        $this->setResponseWithHeader();
		$response = $this->getResponse();
		$request = $this->getRequest();

		error_log('logarChurchProConsolidacaoAction');

		if ($request->isGet()) {
			error_log('get');
		}

		if ($request->isPost()) {
			error_log('post');
		}

		error_log(print_r($request->getPost(), true));

		if ($request->isPost()) {

			$data = Json::decode($request->getContent());
			error_log($data);

			$dataPost = Json::decode($request->getPost());
			error_log($dataPost);

			$adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
			$adapter->setIdentityValue($data[Constantes::$INPUT_USUARIO]);
			$adapter->setCredentialValue(md5($data[Constantes::$INPUT_SENHA]));
			$authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
			if ($authenticationResult->isValid()) {

				/* Verificar se existe pessoa por email informado */
				$pessoa = $this->getRepositorio()->getPessoaORM()->encontrarPorEmail($data[Constantes::$INPUT_USUARIO]);
				/* Tem responsabilidade(s) */
				if (count($pessoa->getResponsabilidadesAtivas()) > 0) {
					$idEquipe = null;
					$idIgreja = null;
					$grupoSelecionado = null;
					foreach($pessoa->getResponsabilidadesAtivas() as $grupoResponsavel){
						if($grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
							$grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
							$grupoResponsavel->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
								$grupoSelecionado = $grupoResponsavel->getGrupo();
							}
					}
					if($grupoSelecionado){
						$idEquipe = $grupoSelecionado->getGrupoEquipe()->getId();
						$idIgreja = $grupoSelecionado->getGrupoIgreja()->getId();
					}
					$response->setContent(Json::encode(
						array(
							'ok' => 'true',
							'email' => $data[Constantes::$INPUT_USUARIO],
							'senha' => $data[Constantes::$INPUT_SENHA],
							'equipe_id' => $idEquipe,
							'igreja_id' => $idIgreja,
						)));
				} else {
					$response->setContent(Json::encode(
						array(
							'ok' => 'false',
						)));
				}

			} else {
				$response->setContent(Json::encode(
					array('ok' => 'false')));
			}
			$response->setContent(Json::encode(
				array('post' => 'passou')));
		}else{
			$response->setContent(Json::encode(
				array('get' => 'true')));
		}

		return $response;
	}

}
