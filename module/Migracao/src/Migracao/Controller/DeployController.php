<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Application\Model\Entity\EventoTipo;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

/**
 * Nome: DeployController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de deploy
 */
class DeployController extends CircuitoController {

	/**
	 * Contrutor sobrecarregado com os serviços de ORM e Autenticador
	 */
	public function __construct(
		EntityManager $doctrineORMEntityManager = null) {
			if (!is_null($doctrineORMEntityManager)) {
				parent::__construct($doctrineORMEntityManager);
			}
		}

	/**
	 * Função padrão, traz a tela para login
	 * GET /deploy
	 */
	public function indexAction() {

		$token = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		if ($token === 'c76ec8866438d1e6ddc90909b0debbe3') {
			$comando = 'sudo git pull https://lpmagalhaes:leonardo142857@github.com/circuitodavisao/circuitodavisaoZf2.git master 2>&1';
			system($comando, $saida);
			echo '<pre>'.$saida.'</pre>';
		} else {
			echo "Sem token";
		}

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);
		return $viewModel;
	}

	public function verUsuarioAction() {
		$idPessoa = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		$resultado = array();
		if ($idPessoa) {
			$repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
			$pessoas = array();
			if (intval($idPessoa)) {
				$pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($idPessoa);
				$pessoas[] = $pessoa;
			} else {
				$resposta = $repositorioORM->getPessoaORM()->encontrarPorNome($idPessoa);
				for ($indiceResposta = 0; $indiceResposta < count($resposta); $indiceResposta++) {
					$pessoa = $repositorioORM->getPessoaORM()->encontrarPorId($resposta[$indiceResposta]['id']);
					$pessoas[] = $pessoa;
				}
			}

			$dados = array();
			foreach ($pessoas as $pessoa) {
				$dados['data_criacao'] = $pessoa->getData_criacaoStringPadraoBanco();
				$dados['id'] = $pessoa->getId();
				$dados['nome'] = $pessoa->getNome();
				$dados['documento'] = $pessoa->getDocumento();
				if ($pessoa->getTelefone()) {
					$dados['telefone'] = $pessoa->getTelefone();
				}
				$dados['email'] = $pessoa->getEmail();
				$dados['senha'] = $pessoa->getSenha();
				$dados['token'] = $pessoa->getToken();
				$dados['token-data'] = $pessoa->getToken_data();
				$dados['token-hora'] = $pessoa->getToken_hora();
				if (intval($idPessoa)) {
					if ($pessoa->getPessoaHierarquiaAtivo()) {
						$dados['hierarquia'] = $pessoa->getPessoaHierarquiaAtivo()->getHierarquia()->getNome();
					}
					if ($grupoResponsaveis = $pessoa->getResponsabilidadesAtivas()) {
						foreach ($grupoResponsaveis as $grupoResponsavel) {
							$dados['Grupo'] = $grupoResponsavel->getGrupo()->getId();
							foreach ($grupoResponsavel->getGrupo()->getEntidade() as $entidade) {
								$dados['Entidade-' . $grupoResponsavel->getId() . ' Status'] = $entidade->verificarSeEstaAtivo();
								$dados['Entidade-' . $grupoResponsavel->getId()] = $entidade->infoEntidade();
							}
							if ($grupoEventoCelula = $grupoResponsavel->getGrupo()->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula)) {
								foreach ($grupoEventoCelula as $grupoEvento) {
									$dados['Celula ' . $grupoEvento->getId() . ' Status'] = $grupoEvento->getEvento()->verificarSeEstaAtivo();
									$dados['Celula ' . $grupoEvento->getId() . ' DataCriacao'] = $grupoEvento->getEvento()->getData_criacaoStringPadraoBrasil();
									$dados['Celula ' . $grupoEvento->getId() . ' HoraCriacao'] = $grupoEvento->getEvento()->getHora_criacao();
									if ($grupoEvento->getEvento()->getData_inativacao()) {
										$dados['Celula ' . $grupoEvento->getId() . ' DataExclusao'] = $grupoEvento->getEvento()->getData_inativacaoStringPadraoBrasil();
									}
									$dados['Celula ' . $grupoEvento->getId() . ' Hospedeiro'] = $grupoEvento->getEvento()->getEventoCelula()->getNome_hospedeiro();
									$dados['Celula ' . $grupoEvento->getId() . ' Dia'] = $grupoEvento->getEvento()->getDia();
									$dados['Celula ' . $grupoEvento->getId() . ' Hora'] = $grupoEvento->getEvento()->getHora();
								}
							}
						}
					}
				}
				$resultado[] = $dados;
			}
		}

		return new ViewModel(array('resultado' => $resultado,));
	}

	public function eleitorAction(){

		$bloco = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		if($bloco == 1000){
			$listaParaTeste = 8;
			$eleitores = $this->getRepositorio()->getEleitorORM()->encontrarPorLista($listaParaTeste);
		}else{
			$eleitores = $this->getRepositorio()->getEleitorORM()->encontrarPorBloco($bloco);
		}
		$totalDeRegistros = (int) $this->getRepositorio()->getEleitorORM()->totalDeRegistros();
		$totalDeBlocos = (int) ($totalDeRegistros / 100);

		$dados = array('eleitores' => $eleitores, 'totalDeBlocos' => $totalDeBlocos, 'html' => $html,);
		$view = new ViewModel($dados);
		return $view;
	}

	public function eleitorRelatorioAction(){
		$html = '';
		$resultados = $this->getRepositorio()->getEleitorORM()->relatorioDeEnvio();
		$relatorioAjustado = array();
		foreach($resultados as $resultado){
			$relatorioAjustado[$resultado['lista']][$resultado['situacao']] = $resultado['valor'];
		}

		$html .= '<div align="center">';
		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-bordered" style="width: 500px" >';
		$html .= '<thead>';
		$html .= '<tr class="dark">';	
		$html .= '<td colspan="5" class="text-center">Relatório de Envio Eleitor</td>';
		$html .= '</tr>';	

		$html .= '<tr class="dark">';	
		$html .= '<td>Lista</td>';
		$html .= '<td>Pendente</td>';
		$html .= '<td>Enviado</td>';
		$html .= '<td>Invalido</td>';
		$html .= '<td>Duplicado</td>';
		$html .= '<td>Total</td>';
		$html .= '</tr>';	
		$html .= '</thead>';
		$html .= '<tbody>';

		$somaPorTipo = array();
		foreach($relatorioAjustado as $key => $relatorio){

			$label = '';
			switch($key){
			case 1: $label = 'AGENDA DELMASSO'; break;
			case 2: $label = 'CV'; break;
			case 3: $label = 'CV'; break;
			case 4: $label = 'RUA'; break;
			case 5: $label = 'FIEL'; break;
			case 6: $label = 'EPLEPSIA'; break;
			case 7: $label = 'GUARA'; break;
			case 8: $label = 'TESTE INTERNO'; break;
			case 9: $label = '2014'; break;
			case 10: $label = 'AUDIENCIA'; break;
			case 11: $label = 'PEDOFILIA'; break;
			case 12: $label = 'TAXISTA'; break;
			case 13: $label = 'GABINETE'; break;
			case 14: $label = 'OUTROS'; break;
			}

			$html .= '<tr>';	
			$html .= '<td>'.$label.'</td>';
			$soma = 0;
			for($i = 1; $i <= 4; $i++){
				$html .= '<td>'.$relatorio[$i].'</td>';
				$soma += $relatorio[$i];
				$somaPorTipo[$i] += $relatorio[$i];
			}
			$html .= '<td>'.$soma.'</td>';
			$html .= '</tr>';	
		}

		$html .= '<tr class="dark">';	
		$html .= '<td>TOTAL</td>';
		$soma = 0;
		for($i = 1; $i <= 4; $i++){
			$html .= '<td>'.$somaPorTipo[$i].'</td>';
			$soma += $somaPorTipo[$i];
		}
		$html .= '<td>'.$soma.'</td>';
		$html .= '</tr>';	

		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= '</div>';

		$dados = array('html' => $html,);
		$view = new ViewModel($dados);
		return $view;
	}

	public function eleitorMudarSituacaoAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$resposta = false;
		if ($request->isPost()) {
			$this->getRepositorio()->iniciarTransacao();
			try {
				$post_data = $request->getPost();
				$idEleitor = $post_data['idEleitor'];
				$tipo = $post_data['tipo'];

				$eleitor = $this->getRepositorio()->getEleitorORM()->encontrarPorId($idEleitor);
				$eleitor->setSituacao($tipo);
				$this->getRepositorio()->getEleitorORM()->persistir($eleitor, $alterarDataDeCriacao = false);

				$this->getRepositorio()->fecharTransacao();
				$resposta = true;
				$response->setContent(Json::encode( array('response' => $resposta,'situacao' => $eleitor->getSituacao())));
			} catch (Exception $exc) {
				$this->getRepositorio()->desfazerTransacao();
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}


}
