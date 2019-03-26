<?php

namespace Application\Controller;

use Migracao\Controller\IndexController;
use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\EventoTipo;
use Application\Model\Entity\Grupo;
use Application\Model\Entity\GrupoPessoaTipo;
use Application\Model\Entity\Curso;
use Application\Model\Entity\EntidadeTipo;
use Application\Model\Entity\SolicitacaoTipo;
use Application\Model\Entity\RegistroAcao;
use Application\Model\Entity\Situacao;
use Application\Model\Helper\FuncoesEntidade;
use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Exception;
use Zend\Json\Json;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use DateTime;

/**
 * Nome: RelatorioController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações da tela principal
 */
class RelatorioController extends CircuitoController {

	const dimensaoTipoCelula = 1;
	const dimensaoTipoCulto = 2;
	const dimensaoTipoArena = 3;
	const dimensaoTipoDomingo = 4;
	const stringRelatorio = 'relatorio';
	const stringPeriodoSelecionado = 'periodoSelecionado';

	/**
	 * Contrutor sobrecarregado com os serviços de ORM
	 */
	public function __construct(EntityManager $doctrineORMEntityManager = null) {
		if (!is_null($doctrineORMEntityManager)) {
			parent::__construct($doctrineORMEntityManager);
		}
	}

	/**
	 * Função padrão, traz a tela principal
	 * GET /relatorio[/tipoRelatorio][/mes/ano]
	 */
	public function indexAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$pessoaLogada = $this->getRepositorio()->getPessoaORM()->encontrarPorId($sessao->idPessoa);
		$grupoLogado = $grupo;

		if($sessao->idSessao > 0){
			$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($sessao->idSessao);
			unset($sessao->idSessao);
		}

		$tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$post_data = $request->getPost();
			$mes = $post_data['mes'];
			$ano = $post_data['ano'];
		}
		if (empty($mes)) {
			$mes = (int) $this->params()->fromRoute('mes', 0);
			if ($mes == 0) {
				$mes = date('m');
			}
		}
		if (empty($ano)) {
			$ano = (int) $this->params()->fromRoute('ano', 0);
			if ($ano == 0) {
				$ano = date('Y');
			}
		}
		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$relatorio = RelatorioController::relatorioCompleto($this->getRepositorio(), $grupo, $tipoRelatorio, $mes, $ano);

		switch($tipoRelatorio){
		case RelatorioController::relatorioMembresia: self::registrarLog(RegistroAcao::VER_RELATORIO_MEMBRESIA, $extra = ''); break;
		case RelatorioController::relatorioCelulaRealizadas: self::registrarLog(RegistroAcao::VER_RELATORIO_CELULA_REALIZADAS, $extra = ''); break;
		case RelatorioController::relatorioCelulaQuantidade: self::registrarLog(RegistroAcao::VER_RELATORIO_CELULA_QUANTIDADE, $extra = ''); break;
		case RelatorioController::relatorioCelulasDeElite: self::registrarLog(RegistroAcao::VER_RELATORIO_CELULA_DE_ELITE, $extra = ''); break;
		case RelatorioController::relatorioParceiroDeDeus: self::registrarLog(RegistroAcao::VER_RELATORIO_PARCEIRO_DE_DEUS, $extra = ''); break;
		}

		$dados = array(
			'mes' => $mes,
			'ano' => $ano,
			'relatorio' => $relatorio,
			'periodoInicial' => $arrayPeriodoDoMes[0],
			'periodoFinal' => $arrayPeriodoDoMes[1],
			'tipoRelatorio' => $tipoRelatorio,
			'entidade' => $entidade,
			'grupo' => $grupo,
			'grupoLogado' => $grupoLogado,
			'repositorio' => $this->getRepositorio(),
			'pessoaLogada' => $pessoaLogada,
		);

		$view = new ViewModel($dados);

		return $view;
	}

	public function lideresAction() {
		$html = '';
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno(date('m'), date('Y'));

		$todosFilhos = array();
			for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
//		$indiceDeArrays = -1;
		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
		if ($grupoPaiFilhoFilhos) {
			foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
				$adicionar = true;
				if (count($todosFilhos) > 0) {
					foreach ($todosFilhos as $filho) {
						if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
							$adicionar = false;
							break;
						}
					}
				}
				if ($adicionar) {
					$todosFilhos[] = $grupoPaiFilhoFilho;
				}
			}
		}
			}


		$quantidade = 0;
		$html .= '<table class="table table-condensed table-hover bg-light mt15">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th colspan="2">Times</th>';
		$html .= '<th>Data Criação</th>';
		$html .= '<th>Quantidade</th>';
		$html .= '<th>Data Inativação</th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		foreach ($todosFilhos as $filho) {
			$dados = self::linhaTabelaFatoLider($filho, $this->getRepositorio(), 1, $quantidade);
			$html .= $dados['html'];
			$quantidade = $dados['quantidade'];
			if ($todosLideres1 = self::todosLideresAbaixoNoPeriodo($filho, $arrayPeriodoDoMes)) {
				foreach ($todosLideres1 as $filho1) {
					$dados = self::linhaTabelaFatoLider($filho1, $this->getRepositorio(), 2, $quantidade);
					$html .= $dados['html'];
					$quantidade = $dados['quantidade'];
					if ($todosLideres2 = self::todosLideresAbaixoNoPeriodo($filho1, $arrayPeriodoDoMes)) {
						foreach ($todosLideres2 as $filho2) {
							$dados = self::linhaTabelaFatoLider($filho2, $this->getRepositorio(), 3, $quantidade);
							$html .= $dados['html'];
							$quantidade = $dados['quantidade'];
							if ($todosLideres3 = self::todosLideresAbaixoNoPeriodo($filho2, $arrayPeriodoDoMes)) {
								foreach ($todosLideres3 as $filho3) {
									$dados = self::linhaTabelaFatoLider($filho3, $this->getRepositorio(), 4, $quantidade);
									$html .= $dados['html'];
									$quantidade = $dados['quantidade'];
									if ($todosLideres4 = self::todosLideresAbaixoNoPeriodo($filho3, $arrayPeriodoDoMes)) {
										foreach ($todosLideres4 as $filho4) {
											$dados = self::linhaTabelaFatoLider($filho4, $this->getRepositorio(), 5, $quantidade);
											$html .= $dados['html'];
											$quantidade = $dados['quantidade'];
											if ($todosLideres5 = self::todosLideresAbaixoNoPeriodo($filho4, $arrayPeriodoDoMes)) {
												foreach ($todosLideres5 as $filho5) {
													$dados = self::linhaTabelaFatoLider($filho5, $this->getRepositorio(), 6, $quantidade);
													$html .= $dados['html'];
													$quantidade = $dados['quantidade'];
													if ($todosLideres6 = self::todosLideresAbaixoNoPeriodo($filho5, $arrayPeriodoDoMes)) {
														foreach ($todosLideres6 as $filho6) {
															$dados = self::linhaTabelaFatoLider($filho6, $this->getRepositorio(), 7, $quantidade);
															$html .= $dados['html'];
															$quantidade = $dados['quantidade'];
															if ($todosLideres7 = self::todosLideresAbaixoNoPeriodo($filho6, $arrayPeriodoDoMes)) {
																foreach ($todosLideres7 as $filho7) {
																	$dados = self::linhaTabelaFatoLider($filho7, $this->getRepositorio(), 8, $quantidade);
																	$html .= $dados['html'];
																	$quantidade = $dados['quantidade'];
																	if ($todosLideres8 = self::todosLideresAbaixoNoPeriodo($filho7, $arrayPeriodoDoMes)) {
																		foreach ($todosLideres8 as $filho8) {
																			$dados = self::linhaTabelaFatoLider($filho8, $this->getRepositorio(), 9, $quantidade);
																			$html .= $dados['html'];
																			$quantidade = $dados['quantidade'];
																			if ($todosLideres9 = self::todosLideresAbaixoNoPeriodo($filho8, $arrayPeriodoDoMes)) {
																				foreach ($todosLideres9 as $filho9) {
																					$dados = self::linhaTabelaFatoLider($filho9, $this->getRepositorio(), 10, $quantidade);
																					$html .= $dados['html'];
																					$quantidade = $dados['quantidade'];
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<h1>Quantidade: '.$quantidade.'</h1>';
		return new ViewModel(array('html' => $html));
	}

	static function todosLideresAbaixoNoPeriodo($filho, $arrayPeriodoDoMes) {
		$grupo = $filho->getGrupoPaiFilhoFilho();
		$todosFilhos = array();
		for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
			$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
			if ($grupoPaiFilhoFilhos) {
				foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
					$adicionar = true;
					if (count($todosFilhos) > 0) {
						foreach ($todosFilhos as $filho) {
							if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
								$adicionar = false;
								break;
							}
						}
					}
					if ($adicionar) {
						$todosFilhos[] = $grupoPaiFilhoFilho;
					}
				}
			}
		}

		return $todosFilhos;
	}

	static function linhaTabelaFatoLider($filho, $repositorio, $tabulacao = 0, $quantidade) {
		$html = '';
		$grupoFilho = $filho->getGrupoPaiFilhoFilho();
		$dataInativacao = null;
		if ($filho->getData_inativacao()) {
			$dataInativacao = $filho->getData_inativacaoStringPadraoBanco();
		}
		$numeroIdentificadorFilho = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho, $dataInativacao);
		$fatoLideres = $repositorio->getFatoLiderORM()->encontrarVariosFatoLiderPorNumeroIdentificador($numeroIdentificadorFilho);

		$html .= '<tr>';
		$html .= '<td>';
		if ($tabulacao) {
			for ($i = 1; $i <= $tabulacao; $i++) {
				$html .= '|----';
			}
		}
		$html .= $grupoFilho->getEntidadeAtiva()->infoEntidade() . '</td>';
		$html .= '<td>' . $grupoFilho->getNomeLideresAtivos() . '</td>';
		foreach ($fatoLideres as $fatoLider) {
			$html .= '<td>id: ' . $fatoLider->getId() . ' - ' . $fatoLider->getData_criacaoStringPadraoBrasil() . '</td>';
			$html .= '<td>' . $fatoLider->getLideres() . '</td>';
			$html .= '<td>' . ($fatoLider->getData_inativacao() ? $fatoLider->getData_inativacaoStringPadraoBrasil() : 'Null') . '</td>';
			$quantidade += $fatoLider->getLideres();
		}
		if ($celulas = $grupoFilho->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula, 1)) {
			$html .= '<td>';
			foreach ($celulas as $celula) {
				$html .= '- Celula ' . $celula->getData_criacaoStringPadraoBrasil();
			}
			$html .= '</td>';
		}
		$html .= '</tr>';

		$dados = array();
		$dados['html'] = $html;
		$dados['quantidade'] = $quantidade;
		return $dados;
	}

	public function pessoasFrequentesAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$html = '';

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos(0);
		$html .= '<table class="table table-condesed">';
		$arrayPeriodo = Funcoes::montaPeriodo(-3);
		$stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
		$dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);

		if ($grupoPaiFilhoFilhos) {
			foreach ($grupoPaiFilhoFilhos as $gpFilho) {
				$grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
				$dadosEntidade = $grupoFilho->getEntidadeAtiva()->infoEntidade() . ' - ' . $grupoFilho->getNomeLideresAtivos();
				$html .= '<tr class="info">';
				$html .= '<td colspan="2">' . $dadosEntidade . '</td>';
				$html .= '</tr>';
				$grupoPessoas = $grupoFilho->getGrupoPessoa();
				if ($grupoPessoas) {
					$contadorDePessoas = 0;
					foreach ($grupoPessoas as $grupoPessoa) {
						$contadorDeEventos = 0;
						$pessoa = $grupoPessoa->getPessoa();
						if (($grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::VISITANTE ||
							$grupoPessoa->getGrupoPessoaTipo()->getId() === GrupoPessoaTipo::CONSOLIDACAO) &&
							$grupoPessoa->verificarSeEstaAtivo()) {

								$frequencias = $pessoa->getEventoFrequencia();
								if ($frequencias) {
									foreach ($frequencias as $eventoFrequencia) {
										$dataParaComparar = strtotime($eventoFrequencia->getDiaStringPadraoBanco());
										if ($dataParaComparar >= $dataDoInicioDoPeriodoParaComparar && $eventoFrequencia->getFrequencia() == 'S') {
											$contadorDeEventos ++;
										}
									}
									if ($contadorDeEventos >= 6) {
										$html .= '<tr>';
										$html .= '<td>' . $pessoa->getNome() . '</td>';
										$html .= '<td>' . $pessoa->getTelefone() . '</td>';
										$html .= '</tr>';
										$contadorDePessoas++;
									}
								}
							}
					}
					if ($contadorDePessoas === 0) {
						$html .= '<tr class="warning">';
						$html .= '<td colspan="2">Sem pessoas frequentes</td>';
						$html .= '</tr>';
					}
				}
			}
		}
		$html .= '</table>';
		$view = new ViewModel(array('html' => $html));
		return $view;
	}

	public function atendimentoAction() {
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();

		/* Verificar data de cadastro da responsabilidade */
		$validacaoNesseMes = 0;
		$grupoResponsavel = $grupo->getGrupoResponsavelAtivo();
		if ($grupoResponsavel->verificarSeFoiCadastradoNesseMes()) {
			$validacaoNesseMes = 1;
		}

		$request = $this->getRequest();		
		if($request->isPost()){
			$postDados = $request->getPost();
			$mesSelecionado = $postDados['mes'];
			$anoSelecionado = $postDados['ano'];
		} else{
			$mesSelecionado = date('m');
			$anoSelecionado = date('Y');
		}
		
		$todosFilhos = $grupo->getGrupoPaiFilhoFilhosPorMesEAno($mesSelecionado, $anoSelecionado);

		$view = new ViewModel(array(
			Constantes::$GRUPOS_ABAIXO => $todosFilhos,
			Constantes::$VALIDACAO_NESSE_MES => $validacaoNesseMes,
			Constantes::$ABA_SELECIONADA => $abaSelecionada,
			Constantes::$MES => $mesSelecionado,
			Constantes::$ANO => $anoSelecionado,
		));

		/* Javascript especifico */
		$layoutJS = new ViewModel();
		$layoutJS->setTemplate(Constantes::$TEMPLATE_JS_RELATORIO_ATENDIMENTO);
		$view->addChild($layoutJS, Constantes::$STRING_JS_RELATORIO_ATENDIMENTO);

		self::registrarLog(RegistroAcao::VER_RELATORIO_ATENDIMENTO, $extra = '');
		return $view;
	}

	public function liderAction() {
		$idUrl = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idUrl);
		$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $entidade->getGrupo());
		$periodo = 0; // atual
		$tipoRelatorioEquipe = 2;
		$retornaJson = true;
		$relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $tipoRelatorioEquipe, $retornaJson);

		$response = $this->getResponse();
		$response->setContent($relatorio);
		return $response;
	}

	public function aproveitamentoDoIvAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$repositorio = $this->getRepositorio();
		$arrayComTodosRelatorios = Array();	
		$arrayComTodasAsTurmas = Array();
		$relatorioAjustado = Array();
		if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao ||			
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
			$grupoSelecionado = $entidade->getGrupo();       
			$grupoSelecionadoGrupoPaiFilhoFilhos = $grupoSelecionado->getGrupoPaiFilhoFilhosAtivos(0);
			if($grupoSelecionadoGrupoPaiFilhoFilhos){
				foreach($grupoSelecionadoGrupoPaiFilhoFilhos as $GrupoPaiFilhos){					
					$filho = $GrupoPaiFilhos->getGrupoPaiFilhoFilho();								
					if($filho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){				   					
						$resultado = self::relatorioAlunosETurmas($this->getRepositorio(), $filho->getEntidadeAtiva());									      					  										
						$turmasComAulaAberta = $resultado[1];
						$relatoriosInicial = $resultado[2];		
						foreach($turmasComAulaAberta as $turmas){					
							$arrayComTodasAsTurmas[] = $turmas;
						}
						foreach($relatoriosInicial as $relatorios){					
							$arrayComTodosRelatorios[] = $relatorios;
						}
					}
					$temCoordenacao = false;
					if($filho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
						$GrupoPaifilhoDosFilhos = $filho->getGrupoPaiFilhoFilhosAtivos(0);
						do{
							foreach($GrupoPaifilhoDosFilhos as $GrupoPaiFilhoInterior){
								$filhoInterior = $GrupoPaiFilhoInterior->getGrupoPaiFilhoFilho();
								if($filhoInterior->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
									$resultado = self::relatorioAlunosETurmas($this->getRepositorio(), $filhoInterior->getEntidadeAtiva());
									$turmasComAulaAberta = $resultado[1];
									$relatoriosInicial = $resultado[2];		
									foreach($turmasComAulaAberta as $turmas){					
										$arrayComTodasAsTurmas[] = $turmas;
									}
									foreach($relatoriosInicial as $relatorios){					
										$arrayComTodosRelatorios[] = $relatorios;
									}
								}
								if($filhoInterior->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
									$temCoordenacao = true;
								}                           
							}
							$GrupoPaifilhoDosFilhos = $filhoInterior->getGrupoPaiFilhoFilhosAtivos(0);
						} while ($temCoordenacao);
					}	
				}
			}		
			
			foreach($arrayComTodosRelatorios as $relatorioInicial){
				foreach($arrayComTodasAsTurmas as $turmaComAulaAberta){
					if($relatorioInicial->getTurma_id() === $turmaComAulaAberta->getId()){
						$idGrupo = substr($relatorioInicial->getNumero_identificador(), (count($relatorioInicial->getNumero_identificador())-8));
						$idGrupoIgreja = substr($relatorioInicial->getNumero_identificador(), 0, 8);
						$grupo = $repositorio->getGrupoORM()->encontrarPorId($idGrupo);								
						$grupoIgreja = $repositorio->getGrupoORM()->encontrarPorId($idGrupoIgreja);
						$grupoDoDiscipuloAbaixoDeQuemEstaLogado = $this->pegarGrupoDoDiscipuloAbaixoDeQuemEstaLogado($grupo);
						$situacao = $repositorio->getSituacaoORM()->encontrarPorId($relatorioInicial->getSituacao_id());
						$disciplinaPosicao = $turmaComAulaAberta->getTurmaAulaAtiva()->getAula()->getDisciplina()->getPosicao();
						if($grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
							$grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao){						
							$relatorioAjustado[$grupoIgreja->getEntidadeAtiva()->getNome()][$disciplinaPosicao][$situacao->getNome()]++;
						}
						if($grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){			 	
							$relatorioAjustado['COORDENAÇÃO ' . $grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getNumero()][$disciplinaPosicao][$situacao->getNome()]++;
						}					
						
						$relatorioAjustado[$disciplinaPosicao][$situacao->getId()]++;
						$relatorioAjustado['total'][$situacao->getId()]++;
					}				  
				}					
			}
			$disciplinas = $arrayComTodasAsTurmas[0]->getCurso()->getDisciplina();
		}
		if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||			
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja ||
			$entidade->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
			$resultado = self::relatorioAlunosETurmas($this->getRepositorio(), $entidade);
			$arrayComTodasAsTurmas = $resultado[1];
			$arrayComTodosRelatorios = $resultado[2];			

			foreach($arrayComTodosRelatorios as $relatorioInicial){
				foreach($arrayComTodasAsTurmas as $turmaComAulaAberta){
					if($relatorioInicial->getTurma_id() === $turmaComAulaAberta->getId()){
						$idGrupo = substr($relatorioInicial->getNumero_identificador(), (count($relatorioInicial->getNumero_identificador())-8));
						$grupo = $repositorio->getGrupoORM()->encontrarPorId($idGrupo);					
						 $grupoDoDiscipuloAbaixoDeQuemEstaLogado = $this->pegarGrupoDoDiscipuloAbaixoDeQuemEstaLogado($grupo);
						 $situacao = $repositorio->getSituacaoORM()->encontrarPorId($relatorioInicial->getSituacao_id());
						 $disciplinaPosicao = $turmaComAulaAberta->getTurmaAulaAtiva()->getAula()->getDisciplina()->getPosicao();
					 	if($grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::equipe){						
					 		$relatorioAjustado[$grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getNome()][$disciplinaPosicao][$situacao->getNome()]++;
					 	}
						if($grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){			 	
					 		$relatorioAjustado[$grupoDoDiscipuloAbaixoDeQuemEstaLogado->getEntidadeAtiva()->infoEntidade()][$disciplinaPosicao][$situacao->getNome()]++;
					 	}					
						
					 	$relatorioAjustado[$disciplinaPosicao][$situacao->getId()]++;
					 	$relatorioAjustado['total'][$situacao->getId()]++;
					 }				  
				}					
			}

			 $disciplinas = $arrayComTodasAsTurmas[0]->getCurso()->getDisciplina();
		}
		
		
		self::registrarLog(RegistroAcao::VER_RELATORIO_APROVEITAMENTO_DO_IV, $extra = '');
		return new ViewModel(array(
			'relatorio' => $relatorioAjustado,
			'disciplinas' => $disciplinas,			
		));
	}
	
	public function pegarGrupoDoDiscipuloAbaixoDeQuemEstaLogado($grupoASerVerificado){
		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoLogado = $entidade->getGrupo();
		$grupoDoDiscipuloAbaixoDeQuemEstaLogado = $grupoASerVerificado;
		while ($grupoASerVerificado->getId() !== $grupoLogado->getId()) {				
				$grupoDoDiscipuloAbaixoDeQuemEstaLogado = $grupoASerVerificado;
				$grupoASerVerificado = $grupoASerVerificado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();				
                if ($grupoASerVerificado->getId() === $grupoLogado->getId()) {
                    break;
                }
			}
		return $grupoDoDiscipuloAbaixoDeQuemEstaLogado;
	}

	public function institutoAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$resultado = self::relatorioAlunosETurmas($this->getRepositorio(), $entidade);
		$relatorioAjustado = $resultado[0];
		$turmasComAulaAberta = $resultado[1];

		self::registrarLog(RegistroAcao::VER_RELATORIO_APROVEITAMENTO_DO_IV, $extra = '');
		return new ViewModel(array(
			'relatorio'=> $relatorioAjustado,
			'turmas' => $turmasComAulaAberta,
		));
	}

	static public function relatorioAlunosETurmas($repositorio, $entidade, $turmasAtivas = true){		
		$relatorioAjustado = array();
		$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $entidade->getGrupo());
		
		$relatorioInicial = $repositorio->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador);
		$turmasComAulaAberta = array();
		if($turmasAtivas){				
				$turmas = $entidade->getGrupo()->getGrupoIgreja()->getTurma();
		}

		if(!$turmasAtivas){
				$turmas = $entidade->getGrupo()->getGrupoIgreja()->getTurmasInativas();
		}

		foreach($turmas as $turma){
			if($turmasAtivas && $turma->getTurmaAulaAtiva()){
				$turmasComAulaAberta[] = $turma;
			}

			if(!$turmasAtivas && !$turma->verificarSeEstaAtivo()){
				$turmasComAulaAberta[] = $turma;
			}
		}
		
		$relatorioAjustado = array();
		foreach($relatorioInicial as $relatorio){
			foreach($turmasComAulaAberta as $turma){
				if($relatorio->getTurma_id() === $turma->getId()){
					$idGrupo = substr($relatorio->getNumero_identificador(), (count($relatorio->getNumero_identificador())-8));
					$grupo = $repositorio->getGrupoORM()->encontrarPorId($idGrupo);
					$situacao = $repositorio->getSituacaoORM()->encontrarPorId($relatorio->getSituacao_id());
					if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
						$relatorioAjustado[$grupo->getGrupoEquipe()->getEntidadeAtiva()->getNome()][$turma->getId()][$situacao->getNome()]++;
					}
					if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe){	
						$nomeDaEquipe = $grupo->getGrupoEquipe()->getEntidadeAtiva()->getNome();
						$sub = $nomeDaEquipe . ' ' .$grupo->getGrupoSubEquipe()->getEntidadeAtiva()->getNumero();
						$relatorioAjustado[$sub][$turma->getId()][$situacao->getNome()]++;								
					}
					if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){							
						if(count($grupo->getResponsabilidadesAtivas()) > 0){
							$relatorioAjustado[$grupo->getEntidadeAtiva()->infoEntidade()][$turma->getId()][$situacao->getNome()]++;	
						}
						if(count($grupo->getResponsabilidadesAtivas()) == 0){
							$relatorioAjustado[$grupo->getGrupoSubEquipe()->getEntidadeAtiva()->infoEntidade()][$turma->getId()][$situacao->getNome()]++;
						}											
					}
					$relatorioAjustado[$turma->getId()][$situacao->getId()]++;
					$relatorioAjustado['total'][$situacao->getId()]++;
				}
			}
		}

		
		$resultado = array();
		$resultado[0] = $relatorioAjustado;
		$resultado[1] = $turmasComAulaAberta;
		$resultado[2] = $relatorioInicial;
		       
		return $resultado;
		
	}

	public function buscarDadosGrupoAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$idGrupo = $post_data['idGrupo'];
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
				$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
				$tipoRelatorioEquipe = 2;
				$periodoInicial = -8;
				$relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodoInicial, $tipoRelatorioEquipe, false, RelatorioController::relatorioCelulaQuantidade);
				$grupoResponsabilidades = $grupo->getResponsabilidadesAtivas();
				$fotos = '';
				foreach ($grupoResponsabilidades as $grupoResponsabilidade) {
					$fotos .= FuncoesEntidade::tagImgComFotoDaPessoa($grupoResponsabilidade->getPessoa(), 96);
				}
				$resposta = true;
				$dados = array();
				$dados['nomeLideres'] = $grupo->getNomeLideresAtivos();
				$dados['fotos'] = $fotos;
				$dados['celulaQuantidade'] = $relatorio['celulaQuantidade'];
				$dados['quantidadeLideres'] = $relatorio['quantidadeLideres'];
				if($dados['quantidadeLideres'] === null){
					$dados['quantidadeLideres'] = 0;
				}
				$dados['resposta'] = $resposta;

				if($grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays = 1)){
					$dados['temDiscipulos'] = true;
				}
				if($solicitacoesDoObjeto = $this->getRepositorio()->getSolicitacaoORM()->encontrarSolicitacoesPorObjeto1($grupo->getId())){
					$temSolicitacaoPendente = false;
					foreach($solicitacoesDoObjeto as $solicitacao){
						$idSituacao = $solicitacao->getSolicitacaoSituacaoAtiva()->getSituacao()->getId();
						if($idSituacao !== Situacao::CONCLUIDO
							&& $idSituacao !== Situacao::RECUSAO){
								$temSolicitacaoPendente = true;
							}
					}
					if($temSolicitacaoPendente){
						$dados['temSolicitacaoPendente'] = true;
					}
				}
				
				/* se tem celula adiciona os dados */
				$grupoEventoCelulas = Array();
				if($grupoEventoCelulaEstrategica = $grupo->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelulaEstrategica)){						
					foreach($grupoEventoCelulaEstrategica as $grupoEventoEstrategica){
						$grupoEventoCelulas[] = $grupoEventoEstrategica;
					}				
				}

				if($grupoEventoCelulasNormais = $grupo->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula)){
					foreach($grupoEventoCelulasNormais as $grupoEventoCelulaNormal){
						$grupoEventoCelulas[] = $grupoEventoCelulaNormal;
					}
				}

				if(count($grupoEventoCelulas) > 0){										
					$contadorDeCelulas = 1;
					foreach($grupoEventoCelulas as $grupoEvento)	{
						if($grupoEvento->verificarSeEstaAtivo()){
							$dados['celulas'][$contadorDeCelulas]['idGrupoEvento'] = $grupoEvento->getId();
							$dados['celulas'][$contadorDeCelulas]['diaDaSemana'] = $grupoEvento->getEvento()->getDia();

							$viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
							$tradutor = $viewHelperManager->get('translate');

							$dados['celulas'][$contadorDeCelulas]['nomeHospedeiro'] ='Dia: '.$tradutor(Funcoes::diaDaSemanaPorDia($grupoEvento->getEvento()->getDia(),1)).' Hora: '.$grupoEvento->getEvento()->getHora(). ' Hospedeiro: '.$grupoEvento->getEvento()->getEventoCelula()->getNome_hospedeiro(). ' Local: ' . $grupoEvento->getEvento()->getEventoCelula()->getLogradouro().' '.$grupoEvento->getEvento()->getEventoCelula()->getComplemento();
							$contadorDeCelulas++;
						}
					}
				}

				$response->setContent(Json::encode($dados));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	public function buscarNumeracoesDisponivelAction() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		if ($request->isPost()) {
			try {
				$post_data = $request->getPost();
				$idGrupo = $post_data['idGrupo'];
				$grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($idGrupo);
				$arrayDeNumerosUsados = array();
				if ($grupo->getGrupoPaiFilhoFilhosAtivosReal()) {
					$filhos = $grupo->getGrupoPaiFilhoFilhosAtivosReal();
					foreach ($filhos as $filho) {
						if ($filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero()) {
							$numero = $filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero();
							$arrayDeNumerosUsados[] = $numero;
						}
					}
				}
				$resposta = true;
				$dados = array();
				$dados['numerosUsados'] = $arrayDeNumerosUsados;
				$dados['resposta'] = $resposta;
				$response->setContent(Json::encode($dados));
			} catch (Exception $exc) {
				echo $exc->getTraceAsString();
			}
		}
		return $response;
	}

	const relatorioMembresia = 1;
	const relatorioCelulaRealizadas = 2;
	const relatorioCelulaQuantidade = 3;
	const relatorioMembresiaECelula = 4;
	const relatorioCelulasDeElite = 8;
	const relatorioParceiroDeDeus = 9;
	const membresia = 1;
	const membresiaPerformance = 2;
	const celula = 3;
	const celulaPerformance = 4;
	const celulaRealizadas = 5;
	const celulaRealizadasPerformance = 6;
	const celulaQuantidade = 7;
	const membresiaCulto = 8;
	const membresiaArena = 9;
	const membresiaDomingo = 10;
	const celulaDeElitePerformance = 11;
	const dadosPessoais = 0;
	const parceiroDeDeusValor = 12;
	const celulaQuantidadeEstrategica = 13;

	public static function relatorioCompleto($repositorio, $grupo, $tipoRelatorio, $mes, $ano, $tudo = true, $somado = false, $periodo = 0) {
		$relatorio = array();
		$todosFilhos = array();
		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		if($periodo > 0){
			$arrayPeriodoDoMes[0] = $periodo;
			$arrayPeriodoDoMes[1] = $periodo;
		}		
		if($mes == date('m') && $ano == date('Y') && $periodo === 'atual'){
			$arrayPeriodoDoMes[1] = 0;
		}
		$diferencaDePeriodos = self::diferencaDePeriodos($arrayPeriodoDoMes[0], $arrayPeriodoDoMes[1], $mes, $ano);		
		if($tudo){
			for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
				if($grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays)){
					foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
						$adicionar = true;
						if (count($todosFilhos) > 0) {
							foreach ($todosFilhos as $filho) {
								if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
									$adicionar = false;
									break;
								}
							}
						}
						if ($adicionar) {
							$todosFilhos[] = $grupoPaiFilhoFilho;
						}
					}
				}
			}
		}
		$tipoRelatorioPessoal = 1;
		$tipoRelatorioSomado = 2;
		$relatorioDiscipulos = array();
		$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
		$soma = array();
		$somaTotal = array();
		$contagemDeArray = 1;
		for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
			$qualRelatorioParaUsar = $tipoRelatorioPessoal;
			if($somado){
				$qualRelatorioParaUsar = $tipoRelatorioSomado;
			}

			if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
				&& $grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao){

					if($tipoRelatorio === self::relatorioParceiroDeDeus){
						$relatorio[self::dadosPessoais][$indiceDeArrays]
							= $repositorio->getFatoFinanceiroORM()->fatosPorNumeroIdentificador($numeroIdentificador,$indiceDeArrays, $mes, $ano, $qualRelatorioParaUsar);
						$soma[self::dadosPessoais][self::parceiroDeDeusValor] += $relatorio[self::dadosPessoais][$indiceDeArrays]['valor'];
					}else{
						$relatorio[self::dadosPessoais][$indiceDeArrays]
							= RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $indiceDeArrays, $qualRelatorioParaUsar, false, $tipoRelatorio);
						$soma[self::dadosPessoais][self::membresia] += $relatorio[self::dadosPessoais][$indiceDeArrays]['membresia'];
						$soma[self::dadosPessoais][self::membresiaPerformance] += $relatorio[self::dadosPessoais][$indiceDeArrays]['membresiaPerformance'];
						$soma[self::dadosPessoais][self::celula] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celula'];
						$soma[self::dadosPessoais][self::celulaPerformance] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaPerformance'];
						$soma[self::dadosPessoais][self::celulaRealizadas] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaRealizadas'];
						$soma[self::dadosPessoais][self::celulaRealizadasPerformance] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaRealizadasPerformance'];
						$soma[self::dadosPessoais][self::celulaQuantidade] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidade'];
						$soma[self::dadosPessoais][self::celulaQuantidadeEstrategica] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidadeEstrategica'];

						$soma[self::dadosPessoais][self::membresiaCulto] += $relatorio[self::dadosPessoais][$indiceDeArrays]['membresiaCulto'];
						$soma[self::dadosPessoais][self::membresiaArena] += $relatorio[self::dadosPessoais][$indiceDeArrays]['membresiaArena'];
						$soma[self::dadosPessoais][self::membresiaDomingo] += $relatorio[self::dadosPessoais][$indiceDeArrays]['membresiaDomingo'];

						if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite ||
							$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
								$dadosCelulasDeElite = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupo, $indiceDeArrays, $contagemDeArray, $mes, $ano);
								$meta = $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidade'];
								if ($relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidade'] > 2) {
									$meta = number_format($relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidade'] / 2);
								}
								$metaEstrategicas = $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidadeEstrategica'];
								if ($relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidadeEstrategica'] > 2) {
									$metaEstrategicas = number_format($relatorio[self::dadosPessoais][$indiceDeArrays]['celulaQuantidadeEstrategica'] / 2);
								}
								$relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeEliteMeta'] = $meta;
								$relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeEliteMetaEstrategica'] = $metaEstrategicas;
								$relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeElite'] = $dadosCelulasDeElite['elite'];
								$relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeElitePerformance'] 
									= $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeElite'] / 
									($relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeEliteMeta'] 
									+ $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeEliteMetaEstrategica'])
									* 100;
								$soma[self::dadosPessoais][self::celulaDeElitePerformance] += $relatorio[self::dadosPessoais][$indiceDeArrays]['celulaDeElitePerformance'];
							}
					}
				}

			$contadorRegioesCoordenacoesEIgrejas = array();
			foreach ($todosFilhos as $filho) {
				$grupoFilho = $filho->getGrupoPaiFilhoFilho();


					if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::regiao
						&& $grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() !== EntidadeTipo::coordenacao ){

							$dataInativacao = null;
							if ($filho->getData_inativacao()) {
								$dataInativacao = $filho->getData_inativacaoStringPadraoBanco();
							}
							$numeroIdentificadorFilho = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho, $dataInativacao);

							if($tipoRelatorio === self::relatorioParceiroDeDeus){

								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]
									= $repositorio->getFatoFinanceiroORM()->fatosPorNumeroIdentificador($numeroIdentificadorFilho,$indiceDeArrays, $mes, $ano, $tipoRelatorioSomado);
								$soma[$grupoFilho->getId()][self::parceiroDeDeusValor] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['valor'];

							}else{
								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays] = RelatorioController::montaRelatorio($repositorio, $numeroIdentificadorFilho, $indiceDeArrays, $tipoRelatorioSomado, $estaInativo, $tipoRelatorio);
							}

						}else{

							if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao ){
								$contadorRegioesCoordenacoesEIgrejas['regiao']++;
							}

							if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao ){
								$contadorRegioesCoordenacoesEIgrejas['coordenacao']++;
							}

							$todosDiscipulosRegiaoOuCoordenacao = array();
							for ($indiceDeArrays1 = $arrayPeriodoDoMes[0]; $indiceDeArrays1 <= $arrayPeriodoDoMes[1]; $indiceDeArrays1++) {
								if($grupoPaiFilhoFilhos1 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays1)){
									foreach ($grupoPaiFilhoFilhos1 as $grupoPaiFilhoFilho1) {
										$adicionar = true;
										if (count($todosDiscipulosRegiaoOuCoordenacao) > 0) {
											foreach ($todosDiscipulosRegiaoOuCoordenacao as $filho1) {
												if ($filho1->getId() === $grupoPaiFilhoFilho1->getId()) {
													$adicionar = false;
													break;
												}
											}
										}
										if ($adicionar) {
											$todosDiscipulosRegiaoOuCoordenacao[] = $grupoPaiFilhoFilho1;
										}
									}
								}
							}

							$relatorioSomado1 = array();
							foreach ($todosDiscipulosRegiaoOuCoordenacao as $filho1) {
								$grupoFilho1 = $filho1->getGrupoPaiFilhoFilho();

								if($grupoFilho1->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
									$contadorRegioesCoordenacoesEIgrejas['igreja']++;

									$numeroIdentificadorFilho1 = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho1, $dataInativacao);

									if($tipoRelatorio === self::relatorioParceiroDeDeus){

										$relatorioDiscipulos1
											= $repositorio->getFatoFinanceiroORM()->fatosPorNumeroIdentificador($numeroIdentificadorFilho1,$indiceDeArrays, $mes, $ano, $tipoRelatorioSomado);

											$relatorioSomado1 += $relatorioDiscipulos1['valor'];

									}else{

										$dataInativacao = null;
										if ($filho1->getData_inativacao()) {
											$dataInativacao = $filho1->getData_inativacaoStringPadraoBanco();
										}
										$estaInativo = false;
										if(!$filho1->verificarSeEstaAtivo()){
											$estaInativo = true;
										}

										$relatorioDiscipulos1 = RelatorioController::montaRelatorio($repositorio, $numeroIdentificadorFilho1, $indiceDeArrays, $tipoRelatorioSomado, $estaInativo, $tipoRelatorio);
										foreach($relatorioDiscipulos1 as $key => $val){
											$relatorioSomado1[$key] += $val;
										}
									}

								}
								if($grupoFilho1->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
									$contadorRegioesCoordenacoesEIgrejas['coordenacao']++;

									$todosDiscipulosRegiaoOuCoordenacao1 = array();
									for ($indiceDeArrays2 = $arrayPeriodoDoMes[0]; $indiceDeArrays2 <= $arrayPeriodoDoMes[1]; $indiceDeArrays2++) {
										if($grupoPaiFilhoFilhos2 = $grupoFilho1->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays2)){
											foreach ($grupoPaiFilhoFilhos2 as $grupoPaiFilhoFilho2) {
												$adicionar = true;
												if (count($todosDiscipulosRegiaoOuCoordenacao1) > 0) {
													foreach ($todosDiscipulosRegiaoOuCoordenacao1 as $filho2) {
														if ($filho2->getId() === $grupoPaiFilhoFilho2->getId()) {
															$adicionar = false;
															break;
														}
													}
												}
												if ($adicionar) {
													$todosDiscipulosRegiaoOuCoordenacao1[] = $grupoPaiFilhoFilho2;
												}
											}
										}
									}

									foreach ($todosDiscipulosRegiaoOuCoordenacao1 as $filho2) {
										$grupoFilho2 = $filho2->getGrupoPaiFilhoFilho();

										if($grupoFilho2->getEntidadeAtiva()->getEntidadeTipo() === EntidadeTipo::igreja){

											$contadorRegioesCoordenacoesEIgrejas['igreja']++;

											$numeroIdentificadorFilho2 = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho2, $dataInativacao);

											if($tipoRelatorio === self::relatorioParceiroDeDeus){

												//TODO

											}else{

												$dataInativacao = null;
												if ($filho2->getData_inativacao()) {
													$dataInativacao = $filho2->getData_inativacaoStringPadraoBanco();
												}
												$estaInativo = false;
												if(!$filho2->verificarSeEstaAtivo()){
													$estaInativo = true;
												}

												$relatorioDiscipulos2 = RelatorioController::montaRelatorio($repositorio, $numeroIdentificadorFilho2, $indiceDeArrays, $tipoRelatorioSomado, $estaInativo, $tipoRelatorio);
												foreach($relatorioDiscipulos2 as $key => $val){
													$relatorioSomado1[$key] += $val;
												}

											}

										}

									}

								}
							}

							if($tipoRelatorio === self::relatorioParceiroDeDeus){
								//$soma[$grupoFilho->getId()][self::parceiroDeDeusValor] = $relatorioSomado1;
							}else{
								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays] = $relatorioSomado1;
							}

						}

					if ($tipoRelatorio === RelatorioController::relatorioMembresia ||
						$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
							if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMetaSomada'] > 0 ) {
								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaPerformance'] = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresia'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMetaSomada'] * 100;
							}
						}
					if ($tipoRelatorio === RelatorioController::relatorioCelulaQuantidade ||
						$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
							$performanceCelula = 0;
							if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMetaSomada'] > 0) {
								$performanceCelula = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celula'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaMetaSomada'] * 100;
								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaPerformance'] = $performanceCelula;
							}
						}
					if ($tipoRelatorio === RelatorioController::relatorioCelulaRealizadas ||
						$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
							$performanceCelula = 0;
							if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] > 0) {
								$performanceCelula = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadas'] / ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] + $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidadeEstrategica']) * 100;
								$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadasPerformance'] = $performanceCelula;
							}
						}
					$soma[$grupoFilho->getId()][self::membresia] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresia'];
					$soma[$grupoFilho->getId()][self::membresiaPerformance] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['membresiaPerformance'];
					$soma[$grupoFilho->getId()][self::celula] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celula'];
					$soma[$grupoFilho->getId()][self::celulaPerformance] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaPerformance'];
					$soma[$grupoFilho->getId()][self::celulaRealizadas] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadas'];
					$soma[$grupoFilho->getId()][self::celulaRealizadasPerformance] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaRealizadasPerformance'];
					if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
						$somaCelulasDeElite = 0;

						$relatorioCelula = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupoFilho, $indiceDeArrays, $contagemDeArray, $mes, $ano);
						$somaCelulasDeElite += $relatorioCelula['elite'];
						$grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
						if ($grupoPaiFilhoFilhos144) {
							foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
								$grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
								$relatorioCelula = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupoFilho144, $indiceDeArrays, $contagemDeArray, $mes, $ano);
								$somaCelulasDeElite += $relatorioCelula['elite'];

								$grupoPaiFilhoFilhos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
								if ($grupoPaiFilhoFilhos1728) {
									foreach ($grupoPaiFilhoFilhos1728 as $gpFilho1728) {
										$grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
										$relatorioCelula = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupoFilho1728, $indiceDeArrays, $contagemDeArray, $mes, $ano);
										$somaCelulasDeElite += $relatorioCelula['elite'];

										$grupoPaiFilhoFilhos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
										if ($grupoPaiFilhoFilhos20736) {
											foreach ($grupoPaiFilhoFilhos20736 as $gpFilho20736) {
												$grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
												$relatorioCelula = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupoFilho20736, $indiceDeArrays, $contagemDeArray, $mes, $ano);
												$somaCelulasDeElite += $relatorioCelula['elite'];

												$grupoPaiFilhoFilhos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
												if ($grupoPaiFilhoFilhos248832) {
													foreach ($grupoPaiFilhoFilhos248832 as $gpFilho248832) {
														$grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
														$relatorioCelula = RelatorioController::saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo($repositorio, $grupoFilho248832, $indiceDeArrays, $contagemDeArray, $mes, $ano);
														$somaCelulasDeElite += $relatorioCelula['elite'];
													}
												}
											}
										}
									}
								}
							}
						}

						$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeElite'] = $somaCelulasDeElite;
						$meta = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'];
						if ($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] > 2) {
							$meta = number_format($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaQuantidade'] / 2);
						}
						$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeEliteMeta'] = $meta;
						$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeElitePerformance'] = $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeElite'] / $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeEliteMeta'] * 100;
						$soma[$grupoFilho->getId()][self::celulaDeElitePerformance] += $relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['celulaDeElitePerformance'];
					}

					$dataCriacao = $filho->getData_criacaoStringPadraoBanco();
					$dataInativacao = $filho->getData_inativacaoStringPadraoBanco();

					$tempoDataCriacao = strtotime($dataCriacao);
					$tempoDataInativacao = strtotime($dataInativacao);

					/* validar se mostra os dados */
					$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['mostrar'] = true;

					$arrayPeriodo = Funcoes::montaPeriodo($indiceDeArrays);
					/* data de criacao */
					$dataFimPeriodo = $arrayPeriodo[6].'-'.$arrayPeriodo[5].'-'.$arrayPeriodo[4];
					$tempoDataFimPeriodo = strtotime($dataFimPeriodo);
					if($tempoDataCriacao > $tempoDataFimPeriodo){
						$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['mostrar'] = false;
					}

					/* data de inativacao */
					$dataInicioPeriodo = $arrayPeriodo[3].'-'.$arrayPeriodo[2].'-'.$arrayPeriodo[1];
					$tempoDataInicioPeriodo = strtotime($dataInicioPeriodo);
					if($filho->getData_inativacao()){
						if($tempoDataInativacao < $tempoDataInicioPeriodo){
							$relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['mostrar'] = false;
						}
					}
					/* Fim validar */
					if($relatorioDiscipulos[$grupoFilho->getId()][$indiceDeArrays]['mostrar']){
						$relatorioDiscipulos[$grupoFilho->getId()]['periodosParaDividir']++;
					}
			}

			/* Somanto ao total os dados de quem esta logado */
			foreach ($relatorio[self::dadosPessoais][$indiceDeArrays] as $key => $value) {
				$somaTotal[$indiceDeArrays][$key] += $value;
			}
			$contagemDeArray++;
		}

		if($tipoRelatorio !== self::relatorioParceiroDeDeus){			
			$relatorio[self::dadosPessoais]['mediaMembresia'] = $soma[self::dadosPessoais][self::membresia] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaMembresiaPerformance'] = $soma[self::dadosPessoais][self::membresiaPerformance] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[self::dadosPessoais]['mediaMembresiaPerformance'], 1);
			$relatorio[self::dadosPessoais]['mediaMembresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[self::dadosPessoais]['mediaMembresiaPerformance'], 2);
			$relatorio[self::dadosPessoais]['mediaCelula'] = $soma[self::dadosPessoais][self::celula] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaCelulaPerformance'] = $soma[self::dadosPessoais][self::celulaPerformance] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[self::dadosPessoais]['mediaCelulaPerformance'], 1);
			$relatorio[self::dadosPessoais]['mediaCelulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[self::dadosPessoais]['mediaCelulaPerformance'], 2);
			$relatorio[self::dadosPessoais]['mediaCelulaRealizadas'] = $soma[self::dadosPessoais][self::celulaRealizadas] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaCelulaRealizadasPerformance'] = $soma[self::dadosPessoais][self::celulaRealizadasPerformance] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaCelulaQuantidade'] = ($soma[self::dadosPessoais][self::celulaQuantidade] + $soma[self::dadosPessoais]['celulaQuantidadeEstrategica']) / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaMembresiaCulto'] = $soma[self::dadosPessoais][self::membresiaCulto] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaMembresiaArena'] = $soma[self::dadosPessoais][self::membresiaArena] / $diferencaDePeriodos;
			$relatorio[self::dadosPessoais]['mediaMembresiaDomingo'] = $soma[self::dadosPessoais][self::membresiaDomingo] / $diferencaDePeriodos;
			if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
				$relatorio[self::dadosPessoais]['mediaCelulaDeElitePerformance'] = $soma[self::dadosPessoais][self::celulaDeElitePerformance] / $diferencaDePeriodos;
			}
			$somaTotal['mediaMembresiaCulto'] = $relatorio[self::dadosPessoais]['mediaMembresiaCulto'];
			$somaTotal['mediaMembresiaArena'] = $relatorio[self::dadosPessoais]['mediaMembresiaArena'];
			$somaTotal['mediaMembresiaDomingo'] = $relatorio[self::dadosPessoais]['mediaMembresiaDomingo'];
			$somaTotal['mediaMembresia'] += $relatorio[self::dadosPessoais]['mediaMembresia'];
			$somaTotal['mediaCelula'] += $relatorio[self::dadosPessoais]['mediaCelula'];
			$somaTotal['mediaCelulaRealizadas'] += $relatorio[self::dadosPessoais]['mediaCelulaRealizadas'];
		}else{
			$relatorio[self::dadosPessoais]['somaValor'] = $soma[self::dadosPessoais][self::parceiroDeDeusValor];
			if($grupo->getId() === 1 || $grupo->getId() === 1225){
				$relatorio[self::dadosPessoais]['parceiroDeDeusMeta'] = 0;
			}
			$somaTotal['parceiroDeDeusMeta'] = 0;
		}
		$relatorio[self::dadosPessoais]['lideres'] = 'MEU';
		$relatorio[self::dadosPessoais]['lideresFotos'] = $grupo->getFotosLideresAtivos();
		$relatorio[self::dadosPessoais]['lideresEntidade'] = $grupo->getEntidadeAtiva()->infoEntidade();
		$relatorio[self::dadosPessoais]['lideresSigla'] = $grupo->getEntidadeAtiva()->getSigla();
		$relatorio[self::dadosPessoais]['grupo'] = $grupo->getId();

		if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao
			|| $grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao ){
				$relatorio[self::dadosPessoais]['mostrar'] = 'nao';
			}

		foreach ($todosFilhos as $filho) {
			$grupoFilho = $filho->getGrupoPaiFilhoFilho();
			if($tipoRelatorio !== self::relatorioParceiroDeDeus){
				$periodosParaDividir = $relatorioDiscipulos[$grupoFilho->getId()]['periodosParaDividir'];
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaMembresia'] = $soma[$grupoFilho->getId()][self::membresia] / $periodosParaDividir;
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaMembresiaPerformance'] = $soma[$grupoFilho->getId()][self::membresiaPerformance] / $periodosParaDividir;
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelula'] = $soma[$grupoFilho->getId()][self::celula] / $periodosParaDividir;
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaPerformance'] = $soma[$grupoFilho->getId()][self::celulaPerformance] / $periodosParaDividir;
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaRealizadas'] = $soma[$grupoFilho->getId()][self::celulaRealizadas] / $periodosParaDividir;
				$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaRealizadasPerformance'] = $soma[$grupoFilho->getId()][self::celulaRealizadasPerformance] / $periodosParaDividir;
				if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
					$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaDeElitePerformance'] = $soma[$grupoFilho->getId()][self::celulaDeElitePerformance] / $periodosParaDividir;
					$relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaDeElitePerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilho->getId()]['mediaCelulaDeElitePerformance'], 1);
				}
			}else{
				$relatorioDiscipulos[$grupoFilho->getId()]['somaValor'] += $soma[$grupoFilho->getId()][self::parceiroDeDeusValor];
				/* Gambiara da ceilandia */
				if($grupo->getId() === 1){
					$valorMeta = 0;
					switch($grupoFilho->getId()){
					case 2: // bkb
						$valorMeta = 6340;
						break;
					case 216: // hunters
						$valorMeta = 4500;
						break;
					case 347: // spartans
						$valorMeta = 3621;
						break;
					case 529:// falcons
						$valorMeta = 9181;
						break;
					case 713://galatas
						$valorMeta = 2500;
						break;
					case 757:// power
						$valorMeta = 2500;
						break;
					case 794: // fire
						$valorMeta = 1926;
						break;
					case 830: // fight
						$valorMeta = 6708;
						break;
					case 1024://titans
						$valorMeta = 1870;
						break;
					case 1049://bonde
						$valorMeta = 2000;
						break;
					case 1085: //save
						$valorMeta = 500;
						break;
					case 1093://sucesso
						$valorMeta = 0;
						break;
					case 1103: // falcons 2
						$valorMeta = 2000;
						break;
					case 1142: // figth 5
						$valorMeta = 2000;
						break;
					case 1199: // yuri e sarah
						$valorMeta = 0;
						break;
					}
				}

				/* gambiara da sede */
				if($grupo->getId() === 1225){
					switch($grupoFilho->getId()){
					case 1226://invictus
						$valorMeta = 7900;
						break;
					case 1398://profetas
						$valorMeta = 23224;
						break;
					case 1543:// leoes
						$valorMeta = 5200;
						break;
					case 1683://turbo
						$valorMeta = 14500;
						break;
					case 1846://onda
						$valorMeta = 4500;
						break;
					case 1982://doc
						$valorMeta = 1000;
						break;
					case 1996://plenos
						$valorMeta = 4240;
						break;
					case 2039://conexao
						$valorMeta = 0;
						break;
					case 2054://atos
						$valorMeta = 4500;
						break;
					case 2151://rocks
						$valorMeta = 2180;
						break;
					case 2193://combate
						$valorMeta = 3500;
						break;
					case 2239://a liga
						$valorMeta = 1550;
						break;
					case 2267://life
						$valorMeta = 3150;
						break;
					case 2293://demalso
						$valorMeta = 0;
						break;
					case 2294:// swat
						$valorMeta = 600;
						break;
					case 2362://turbo 18
						$valorMeta = 3750;
						break;
					case 2447://turbo 17
						$valorMeta = 3750;
						break;
					case 2507:// invictus 3
						$valorMeta = 1750;
						break;
					}
				}

				$relatorioDiscipulos[$grupoFilho->getId()]['parceiroDeDeusMeta'] = $valorMeta;
				$relatorioDiscipulos[$grupoFilho->getId()]['parceiroDeDeusPerformance'] = $relatorioDiscipulos[$grupoFilho->getId()]['somaValor'] /	$relatorioDiscipulos[$grupoFilho->getId()]['parceiroDeDeusMeta'] * 100;
			}
		}

		$filhosOrdenado = RelatorioController::ordenacaoDiscipulos($todosFilhos, $relatorioDiscipulos, $tipoRelatorio);
		$contadorFilhos = 1;
		foreach ($filhosOrdenado as $filhoOrdenado) {
			for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
				$grupoFilhoOrdenado = $filhoOrdenado->getGrupoPaiFilhoFilho();
				$relatorio[$contadorFilhos][$indiceDeArrays] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()][$indiceDeArrays];
				foreach ($relatorio[$contadorFilhos][$indiceDeArrays] as $key => $value) {
					$somaTotal[$indiceDeArrays][$key] += $value;
				}
			}

			if($tipoRelatorio !== self::relatorioParceiroDeDeus){
				$relatorio[$contadorFilhos]['mediaMembresia'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresia'];
				$relatorio[$contadorFilhos]['mediaMembresiaPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresiaPerformance'];
				$relatorio[$contadorFilhos]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaMembresiaPerformance']);
				$relatorio[$contadorFilhos]['mediaCelula'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelula'];
				$relatorio[$contadorFilhos]['mediaCelulaPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaPerformance'];
				$relatorio[$contadorFilhos]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaPerformance']);
				$relatorio[$contadorFilhos]['mediaCelulaRealizadas'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadas'];
				$relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadasPerformance'];
				$relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaRealizadasPerformance']);
				if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
					$relatorio[$contadorFilhos]['mediaCelulaDeElitePerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaDeElitePerformance'];
					$relatorio[$contadorFilhos]['mediaCelulaDeElitePerformanceClass'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['mediaCelulaDeElitePerformanceClass'];
				}
			}else{
				$relatorio[$contadorFilhos]['somaValor'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['somaValor'];
				$relatorio[$contadorFilhos]['parceiroDeDeusMeta'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['parceiroDeDeusMeta'];
				$relatorio[$contadorFilhos]['parceiroDeDeusPerformance'] = $relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['parceiroDeDeusPerformance'];
				$relatorio[$contadorFilhos]['parceiroDeDeusPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorioDiscipulos[$grupoFilhoOrdenado->getId()]['parceiroDeDeusPerformance']);
				$somaTotal['parceiroDeDeusMeta'] += $relatorio[$contadorFilhos]['parceiroDeDeusMeta'];
			}

			$relatorio[$contadorFilhos]['lideres'] = $grupoFilhoOrdenado->getNomeLideresAtivos();
			if($grupoFilhoOrdenado->getEntidadeAtiva()){
				$relatorio[$contadorFilhos]['lideresFotos'] = $grupoFilhoOrdenado->getFotosLideresAtivos();
				$relatorio[$contadorFilhos]['lideresEntidade'] = $grupoFilhoOrdenado->getEntidadeAtiva()->infoEntidade($somenteNumeros = true);
				if($grupoFilhoOrdenado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
					$relatorio[$contadorFilhos]['lideresEntidade'] = 'COORD. '.$grupoFilhoOrdenado->getEntidadeAtiva()->getNumero();
				}
				$relatorio[$contadorFilhos]['lideresSigla'] = $grupoFilhoOrdenado->getEntidadeAtiva()->getSigla();
				$relatorio[$contadorFilhos]['lideresEntidadeTipo'] = $grupoFilhoOrdenado->getEntidadeAtiva()->getEntidadeTipo()->getId();
			}
			$relatorio[$contadorFilhos]['grupo'] = $grupoFilhoOrdenado->getId();

			$somaTotal['mediaMembresia'] += $relatorio[$contadorFilhos]['mediaMembresia'];
			$somaTotal['mediaCelula'] += $relatorio[$contadorFilhos]['mediaCelula'];
			$somaTotal['mediaCelulaRealizadas'] += $relatorio[$contadorFilhos]['mediaCelulaRealizadas'];
			$contadorFilhos++;
		}

		/* TOTAL */
		for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
			foreach ($somaTotal[$indiceDeArrays] as $key => $value) {
				if($value){
					$relatorio[$contadorFilhos][$indiceDeArrays][$key] += $value;
				}
			}
		}
		$somaFinal = array();
		for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
			if ($tipoRelatorio === RelatorioController::relatorioMembresia ||
				$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
					$relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['membresia'] / $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaMetaSomada'] * 100;
					$somaFinal['membresiaCulto'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaCulto'];
					$somaFinal['membresiaArena'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaArena'];
					$somaFinal['membresiaDomingo'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaDomingo'];
					$somaFinal['membresia'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresia'];
					$somaFinal['membresiaPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'];
					$relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'], 1);
					$relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['membresiaPerformance'], 2);
				}
			if ($tipoRelatorio === RelatorioController::relatorioCelulaQuantidade ||
				$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
					$relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['celula'] / $relatorio[$contadorFilhos][$indiceDeArrays]['membresiaMetaSomada'] * 100;
					$somaFinal['celula'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celula'];
					$somaFinal['celulaPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'];
					$relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'], 1);
					$relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos][$indiceDeArrays]['celulaPerformance'], 2);
				}
			if ($tipoRelatorio === RelatorioController::relatorioCelulaRealizadas ||
				$tipoRelatorio === RelatorioController::relatorioMembresiaECelula) {
					$relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadasPerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadas'] / $relatorio[$contadorFilhos][$indiceDeArrays]['celulaQuantidade'] * 100;
					$somaFinal['celulaQuantidade'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaQuantidade'];
					$somaFinal['celulaQuantidadeEstrategica'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaQuantidadeEstrategica'];
					$somaFinal['celulaRealizadas'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadas'];
					$somaFinal['celulaRealizadasPerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaRealizadasPerformance'];
				}
			if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
				$relatorio[$contadorFilhos][$indiceDeArrays]['celulaDeElitePerformance'] = $relatorio[$contadorFilhos][$indiceDeArrays]['celulaDeElite'] / $relatorio[$contadorFilhos][$indiceDeArrays]['celulaDeEliteMeta'] * 100;
				$somaFinal['celulaDeElitePerformance'] += $relatorio[$contadorFilhos][$indiceDeArrays]['celulaDeElitePerformance'];
			}
			if($tipoRelatorio === self::relatorioParceiroDeDeus){
				$somaFinal['somaValor'] += $relatorio[$contadorFilhos][$indiceDeArrays]['valor'];
			}
		}

		if($tipoRelatorio !== self::relatorioParceiroDeDeus){
			$relatorio[$contadorFilhos]['mediaMembresiaCulto'] = $somaFinal['membresiaCulto'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaMembresiaArena'] = $somaFinal['membresiaArena'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaMembresiaDomingo'] = $somaFinal['membresiaDomingo'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaMembresia'] = $somaFinal['membresia'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaMembresiaPerformance'] = $somaFinal['membresiaPerformance'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaMembresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaMembresiaPerformance'], 1);
			$relatorio[$contadorFilhos]['mediaMembresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaMembresiaPerformance'], 2);
			$relatorio[$contadorFilhos]['mediaCelula'] = $somaFinal['celula'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaCelulaPerformance'] = $somaFinal['celulaPerformance'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaCelulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaCelulaPerformance'], 1);
			$relatorio[$contadorFilhos]['mediaCelulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio[$contadorFilhos]['mediaCelulaPerformance'], 2);
			$relatorio[$contadorFilhos]['mediaCelulaRealizadas'] = $somaFinal['celulaRealizadas'] / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaCelulaQuantidade'] = ($somaFinal['celulaQuantidade'] + $somaFinal['celulaQuantidadeEstrategica']) / $diferencaDePeriodos;
			$relatorio[$contadorFilhos]['mediaCelulaRealizadasPerformance'] = $somaFinal['celulaRealizadasPerformance'] / $diferencaDePeriodos;
			if ($tipoRelatorio === RelatorioController::relatorioCelulasDeElite) {
				$relatorio[$contadorFilhos]['mediaCelulaDeElitePerformance'] = $somaFinal['celulaDeElitePerformance'] / $diferencaDePeriodos;
			}
		}else{
			$relatorio[$contadorFilhos]['somaValor'] = $somaFinal['somaValor'];
			$relatorio[$contadorFilhos]['parceiroDeDeusMeta'] = $somaTotal['parceiroDeDeusMeta'];
			$relatorio[$contadorFilhos]['parceiroDeDeusPerformance'] = $relatorio[$contadorFilhos]['somaValor'] / $relatorio[$contadorFilhos]['parceiroDeDeusMeta'] * 100;
		}
		$relatorio[$contadorFilhos]['lideres'] = 'TOTAL';
		$relatorio[$contadorFilhos]['regiao'] = $contadorRegioesCoordenacoesEIgrejas['regiao'];
		$relatorio[$contadorFilhos]['coordenacao'] = $contadorRegioesCoordenacoesEIgrejas['coordenacao'];
		$relatorio[$contadorFilhos]['igreja'] = $contadorRegioesCoordenacoesEIgrejas['igreja'];

		return $relatorio;
	}

	public static function totalLideres($repositorioORM, $periodo, $grupo, $mostrarDados = false) {
		$tipoRelatorio = 1; //pessoal
		$somaTotal = 0;
		$numeroIdentificador = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo);
		$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodo);
		if ($mostrarDados) {
			Funcoes::var_dump($fatoLider);
		}
		$somaTotal += $fatoLider[0]['lideres'];

		$filhos1 = $grupo->getGrupoPaiFilhoFilhosAtivos($periodo);
		if ($filhos1) {
			foreach ($filhos1 as $grupoPaiFilho1) {
				$grupo1 = $grupoPaiFilho1->getGrupoPaiFilhoFilho();
				$numeroIdentificador1 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo1);
				$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador1, $tipoRelatorio, $periodo);
				if ($mostrarDados) {
					Funcoes::var_dump($fatoLider);
				}
				$somaTotal += $fatoLider[0]['lideres'];
				$filhos2 = $grupo1->getGrupoPaiFilhoFilhosAtivos($periodo);
				if ($filhos2) {
					foreach ($filhos2 as $grupoPaiFilhoFilho2) {
						$grupo2 = $grupoPaiFilhoFilho2->getGrupoPaiFilhoFilho();
						$numeroIdentificador2 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo2);
						$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador2, $tipoRelatorio, $periodo);
						if ($mostrarDados) {
							Funcoes::var_dump($fatoLider);
						}
						$somaTotal += $fatoLider[0]['lideres'];
						$filhos3 = $grupo2->getGrupoPaiFilhoFilhosAtivos($periodo);
						if ($filhos3) {
							foreach ($filhos3 as $grupoPaiFilhoFilho3) {
								$grupo3 = $grupoPaiFilhoFilho3->getGrupoPaiFilhoFilho();
								$numeroIdentificador3 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo3);
								$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador3, $tipoRelatorio, $periodo);
								if ($mostrarDados) {
									Funcoes::var_dump($fatoLider);
								}
								$somaTotal += $fatoLider[0]['lideres'];
								$filhos4 = $grupo3->getGrupoPaiFilhoFilhosAtivos($periodo);
								if ($filhos4) {
									foreach ($filhos4 as $grupoPaiFilhoFilho4) {
										$grupo4 = $grupoPaiFilhoFilho4->getGrupoPaiFilhoFilho();
										$numeroIdentificador4 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo4);
										$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador4, $tipoRelatorio, $periodo);
										if ($mostrarDados) {
											Funcoes::var_dump($fatoLider);
										}
										$somaTotal += $fatoLider[0]['lideres'];
										$filhos5 = $grupo4->getGrupoPaiFilhoFilhosAtivos($periodo);
										if ($filhos5) {
											foreach ($filhos5 as $grupoPaiFilhoFilho5) {
												$grupo5 = $grupoPaiFilhoFilho5->getGrupoPaiFilhoFilho();
												$numeroIdentificador5 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo5);
												$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador5, $tipoRelatorio, $periodo);
												if ($mostrarDados) {
													Funcoes::var_dump($fatoLider);
												}
												$somaTotal += $fatoLider[0]['lideres'];
												$filhos6 = $grupo5->getGrupoPaiFilhoFilhosAtivos($periodo);
												if ($filhos6) {
													foreach ($filhos6 as $grupoPaiFilhoFilho6) {
														$grupo6 = $grupoPaiFilhoFilho6->getGrupoPaiFilhoFilho();
														$numeroIdentificador6 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo6);
														$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador6, $tipoRelatorio, $periodo);
														if ($mostrarDados) {
															Funcoes::var_dump($fatoLider);
														}
														$somaTotal += $fatoLider[0]['lideres'];
														$filhos7 = $grupo6->getGrupoPaiFilhoFilhosAtivos($periodo);
														if ($filhos7) {
															foreach ($filhos7 as $grupoPaiFilhoFilho7) {
																$grupo7 = $grupoPaiFilhoFilho7->getGrupoPaiFilhoFilho();
																$numeroIdentificador7 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo7);
																$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador7, $tipoRelatorio, $periodo);
																if ($mostrarDados) {
																	Funcoes::var_dump($fatoLider);
																}
																$somaTotal += $fatoLider[0]['lideres'];
																$filhos8 = $grupo7->getGrupoPaiFilhoFilhosAtivos($periodo);
																if ($filhos8) {
																	foreach ($filhos8 as $grupoPaiFilhoFilho8) {
																		$grupo8 = $grupoPaiFilhoFilho8->getGrupoPaiFilhoFilho();
																		$numeroIdentificador8 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo8);
																		$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador8, $tipoRelatorio, $periodo);
																		if ($mostrarDados) {
																			Funcoes::var_dump($fatoLider);
																		}
																		$somaTotal += $fatoLider[0]['lideres'];
																		$filhos9 = $grupo8->getGrupoPaiFilhoFilhosAtivos($periodo);
																		if ($filhos9) {
																			foreach ($filhos9 as $grupoPaiFilhoFilho9) {
																				$grupo9 = $grupoPaiFilhoFilho9->getGrupoPaiFilhoFilho();
																				$numeroIdentificador9 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo9);
																				$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador9, $tipoRelatorio, $periodo);
																				if ($mostrarDados) {
																					Funcoes::var_dump($fatoLider);
																				}
																				$somaTotal += $fatoLider[0]['lideres'];
																				$filhos10 = $grupo8->getGrupoPaiFilhoFilhosAtivos($periodo);
																				if ($filhos10) {
																					foreach ($filhos10 as $grupoPaiFilhoFilho10) {
																						$grupo10 = $grupoPaiFilhoFilho10->getGrupoPaiFilhoFilho();
																						$numeroIdentificador10 = $repositorioORM->getFatoCicloORM()->montarNumeroIdentificador($repositorioORM, $grupo10);
																						$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador10, $tipoRelatorio, $periodo);
																						if ($mostrarDados) {
																							Funcoes::var_dump($fatoLider);
																						}
																						$somaTotal += $fatoLider[0]['lideres'];
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $somaTotal;
	}

	const tipoPessoa = 1;

	public static function montaRelatorio($repositorioORM, $numeroIdentificador, $periodoInicial, $tipoRelatorio, $inativo = false, $qualRelatorio = 1) {
		unset($relatorio);
		$relatorioCelula = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio);
		$quantidadeCelulas = $relatorioCelula[0]['quantidade'];

		$relatorioCelulaEstrategicas = $repositorioORM->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio, $estrategica = true);
		$quantidadeCelulasEstrategicas = $relatorioCelulaEstrategicas[0]['quantidade'];		

		$relatorio['celulaQuantidade'] = $quantidadeCelulas;
		$relatorio['celulaQuantidadeEstrategica'] = $quantidadeCelulasEstrategicas;
		$fatoLider = $repositorioORM->getFatoLiderORM()->encontrarPorNumeroIdentificador($numeroIdentificador, $tipoRelatorio, $periodoInicial, $inativo);
		$quantidadeLideres = $fatoLider[0]['lideres'];
		$relatorio['quantidadeLideres'] = $quantidadeLideres;
		$relatorioLancamento = $repositorioORM->getFatoCicloORM()->montarRelatorioPorNumeroIdentificador($numeroIdentificador, $periodoInicial, $tipoRelatorio);
		foreach ($relatorioLancamento as $key => $value) {
			$soma[$key] = 0;
			foreach ($value as $campo) {
				foreach ($campo as $keyCampo => $valorCampo) {
					$soma[$key] += $valorCampo;
				}
			}
		}
		$relatorio['membresiaMeta'] = Constantes::$META_LIDER * $quantidadeCelulas;
		$relatorio['membresiaMetaEstrategica'] = (Constantes::$META_LIDER/2) * $quantidadeCelulasEstrategicas;
		$relatorio['membresiaMetaSomada'] = $relatorio['membresiaMeta'] + $relatorio['membresiaMetaEstrategica'];
		/* Membresia */
		if ($qualRelatorio === RelatorioController::relatorioMembresia || $qualRelatorio === RelatorioController::relatorioMembresiaECelula) {
			$relatorio['membresiaCulto'] = $soma[RelatorioController::dimensaoTipoCulto];
			$relatorio['membresiaArena'] = $soma[RelatorioController::dimensaoTipoArena];
			$relatorio['membresiaDomingo'] = $soma[RelatorioController::dimensaoTipoDomingo];
			$relatorio['membresia'] = RelatorioController::calculaMembresia(
				$soma[RelatorioController::dimensaoTipoCulto], $soma[RelatorioController::dimensaoTipoArena], $soma[RelatorioController::dimensaoTipoDomingo]);
			if ($relatorio['membresiaMetaSomada'] > 0 && $relatorio['membresia'] > 0) {
				$relatorio['membresiaPerformance'] = $relatorio['membresia'] / $relatorio['membresiaMetaSomada'] * 100;
				$relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance']);
				$relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['membresiaPerformance'], 2);
			}
			if ($relatorio['membresiaPerformance'] == '' || $relatorio['membresiaPerformance'] == 0) {
				$relatorio['membresiaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance(0);
				$relatorio['membresiaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance(0, 2);
			}
		}
		/* Célula */
		if ($qualRelatorio === RelatorioController::relatorioCelulaRealizadas ||
			$qualRelatorio === RelatorioController::relatorioCelulaQuantidade ||
			$qualRelatorio === RelatorioController::relatorioMembresiaECelula ||
			$qualRelatorio === RelatorioController::relatorioCelulasDeElite) {
				$quantidadeCelulasRealizadas = 0;
				if ($relatorioCelula[0]['realizadas'] || $relatorioCelulaEstrategicas[0]['realizadas']) {
					$quantidadeCelulasRealizadas += $relatorioCelula[0]['realizadas'];
					$quantidadeCelulasRealizadas += $relatorioCelulaEstrategicas[0]['realizadas'];
				}

				$performanceCelulasRealizadas = 0;
				if ($quantidadeCelulas ||$quantidadeCelulasEstrategicas) {
					$performanceCelulasRealizadas = $quantidadeCelulasRealizadas / ($quantidadeCelulas + $quantidadeCelulasEstrategicas) * 100;
				}
				$performanceCelula = 0;
				if ($relatorio['membresiaMetaSomada'] > 0) {
					$performanceCelula = $soma[RelatorioController::dimensaoTipoCelula] / $relatorio['membresiaMetaSomada'] * 100;
				}
				$relatorio['celula'] = $soma[RelatorioController::dimensaoTipoCelula];
				$relatorio['celulaPerformance'] = $performanceCelula;
				$relatorio['celulaPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaPerformance']);
				$relatorio['celulaPerformanceFrase'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaPerformance'], 2);
				$relatorio['celulaRealizadas'] = $quantidadeCelulasRealizadas;
				$relatorio['celulaRealizadasPerformance'] = $performanceCelulasRealizadas;
				$relatorio['celulaRealizadasPerformanceClass'] = RelatorioController::corDaLinhaPelaPerformance($relatorio['celulaRealizadasPerformance']);
			}
		return $relatorio;
	}

	public static function saberQuaisDasMinhasCelulasSaoDeElitePorPeriodo(RepositorioORM $repositorioORM, Grupo $grupo, $periodo, $contagemDoPeriodo, $mes, $ano) {
		$relatorio = array();
		$grupoEventosCelula = $grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);
		if($grupoEventosCelulaEstrategica = $grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelulaEstrategica)){
			foreach($grupoEventosCelulaEstrategica as $grupoEvento){
				$grupoEventosCelula[] = $grupoEvento;
			}
		}
		$contagem = 0;
		$contagemCelulasDeElite = 0;
		$fatosSetenta = $repositorioORM->getFatoSetentaORM()->encontrarPorIdGrupo($grupo->getId(), $mes, $ano);
		foreach ($grupoEventosCelula as $grupoEventoCelula) {
			$ehElite = 'N';
			$eventoId = $grupoEventoCelula->getEvento()->getId();
			foreach($fatosSetenta as $fatoSetenta){
				if($fatoSetenta->getGrupo_evento_id() === $grupoEventoCelula->getId()){
					switch($contagemDoPeriodo){
					case 1: $ehElite = $fatoSetenta->getE1(); break;
					case 2: $ehElite = $fatoSetenta->getE2(); break;
					case 3: $ehElite = $fatoSetenta->getE3(); break;
					case 4: $ehElite = $fatoSetenta->getE4(); break;
					case 5: $ehElite = $fatoSetenta->getE5(); break;
					case 6: $ehElite = $fatoSetenta->getE6(); break;
					}
				}
			}
			$resposta = 0;
			if ($ehElite == 'S') {
				$resposta = 1;
				$contagemCelulasDeElite++;
			}
			$relatorio[$contagem]['eventoId'] = $eventoId;
			$relatorio[$contagem]['resposta'] = $resposta;
			$relatorio[$contagem]['hospedeiro'] = $grupoEventoCelula->getEvento()->getEventoCelula()->getNome_hospedeiroPrimeiroNome();
			$contagem++;
		}

		$relatorio['elite'] = $contagemCelulasDeElite;
		return $relatorio;
	}

	public static function diferencaDePeriodos($periodoInicial, $periodoFinal, $mes = null, $ano = null){
		$contador = 0;
		for ($indiceDeArrays = $periodoInicial; $indiceDeArrays <= $periodoFinal; $indiceDeArrays++) {
			$contador++;
		}
		return $contador;
	}

	/**
	 * Calcula a membresia
	 * @param integer $valorCulto
	 * @param integer $valorArena
	 * @param integer $valorDomingo
	 * @return integer
	 */
	public static function calculaMembresia($valorCulto, $valorArena, $valorDomingo) {
		return ($valorCulto / 3) + ($valorArena / 2) + $valorDomingo;
	}

	public static function formataNumeroRelatorio($valor) {
		return number_format((double) $valor, 0, '', '');
	}

	const MARGEM_D = 0;
	const MARGEM_C = 70;
	const MARGEM_B = 85;
	const MARGEM_A = 100;

	public static function corDaLinhaPelaPerformance($valor, $tipo = 1) {
		$class = 'dark';
		if ($valor >= RelatorioController::MARGEM_A) {
			$class = 'success';
			if ($tipo === 2) {
				$class = 'Excelente! Você está entre os melhores!';
			}
		}
		if (($valor < RelatorioController::MARGEM_A && $valor >= RelatorioController::MARGEM_B)) {
			$class = 'success';
			if ($tipo === 2) {
				$class = 'Parabéns! Continue e logo estará entre os melhores!';
			}
		}
		if (($valor < RelatorioController::MARGEM_B && $valor >= RelatorioController::MARGEM_C)) {
			$class = 'warning';
			if ($tipo === 2) {
				$class = 'Muito bom! Você está no caminho, continue focado!';
			}
		}
		if (($valor < RelatorioController::MARGEM_C && $valor >= RelatorioController::MARGEM_D)) {
			$class = 'danger';
			if ($tipo === 2) {
				$class = 'Vamos lá! A persistência é o caminho, continue!';
			}
		}
		if ($valor <= RelatorioController::MARGEM_D) {
			$class = 'dark';
			if ($tipo === 2) {
				$class = 'Vamos lá! A persistência é o caminho, continue!';
			}
		}
		return $class;
	}

	public static function corDaLinhaPelaPerformanceClasse($valor) {
		$class = 'dark';
		if ($valor == 'A') {
			$class = 'info';
		}
		if ($valor == 'B') {
			$class = 'success';
		}
		if ($valor == 'C') {
			$class = 'warning';
		}
		if ($valor == 'D') {
			$class = 'danger';
		}
		return $class;
	}

	public static function ordenacaoDiscipulos($discipulosLocal, $relatorio, $tipo) {
		$campo = '';
		if ($tipo === 1) {
			$campo = 'mediaMembresiaPerformance';
		}
		if ($tipo === 2) {
			$campo = 'mediaCelulaRealizadasPerformance';
		}
		if ($tipo === 3) {
			$campo = 'mediaCelulaPerformance';
		}
		if ($tipo === 4) {
			$campo = 'membresiaCulto';
		}
		if ($tipo === 5) {
			$campo = 'membresiaArena';
		}
		if ($tipo === 6) {
			$campo = 'membresiaDomingo';
		}
		if ($tipo === 8) {
			$campo = 'mediaCelulaDeElitePerformance';
		}
		if ($tipo === 9) {
			$campo = 'parceiroDeDeusPerformance';
		}
		$tamanhoArray = count($discipulosLocal);

		for ($i = 0; $i < $tamanhoArray; $i++) {
			for ($j = 0; $j < $tamanhoArray; $j++) {

				$discipulo1 = $discipulosLocal[$i];
				$discipulo2 = $discipulosLocal[$j];

				$grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
				$grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();

				if ($tipo != 0) {
					$percentual1 = $relatorio[$grupoFilho1->getId()][$campo];
					$percentual2 = $relatorio[$grupoFilho2->getId()][$campo];
				} else {
					$percentual1 = $grupoFilho1->getEntidadeAtiva()->getNumero();
					if ($percentual1 < 0) {
						$percentual1 = ($percentual1 * -1) + 100;
					}
					$percentual2 = $grupoFilho2->getEntidadeAtiva()->getNumero();
					if ($percentual2 < 0) {
						$percentual2 = ($percentual2 * -1) + 100;
					}
				}

				if (($tipo != 0 && $percentual1 > $percentual2) || ($tipo == 0 && $percentual1 < $percentual2)) {
					$aux = $discipulo1;
					$discipulosLocal[$i] = $discipulo2;
					$discipulosLocal[$j] = $aux;
				}
			}
		}
		return $discipulosLocal;
	}

	public static function ordenacaoDiscipulosAtendimento($discipulos, $mes, $ano) {
		$relatorioDicipulo = array();
		foreach ($discipulos as $gpFilho) {
			$grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();

			if (count($grupoFilho) > 0) {
				$relatorioAtendimento = Grupo::relatorioDeAtendimentosAbaixo(
					$grupoFilho->getGrupoPaiFilhoFilhosAtivos(), $mes, $ano
				);
			} else {
				$relatorioAtendimento[0] = -2;
			}

			$relatorioDicipulo[$grupoFilho->getId()] = $relatorioAtendimento[0];
		}

		$tamanhoArray = count($discipulos);

		for ($i = 0; $i < $tamanhoArray; $i++) {
			for ($j = 0; $j < $tamanhoArray; $j++) {

				$discipulo1 = $discipulos[$i];
				$grupoFilho1 = $discipulo1->getGrupoPaiFilhoFilho();
				$percentual1 = $relatorioDicipulo[$grupoFilho1->getId()];

				$discipulo2 = $discipulos[$j];
				$grupoFilho2 = $discipulo2->getGrupoPaiFilhoFilho();
				$percentual2 = $relatorioDicipulo[$grupoFilho2->getId()];

				if ($percentual1 > $percentual2) {
					$aux = $discipulo1;
					$discipulos[$i] = $discipulo2;
					$discipulos[$j] = $aux;
				}
			}
		}

		return $discipulos;
	}

	public static function montarRelatorioSomandoTodoTimeAbaixoNoPeriodo($repositorio, $grupo, $periodoComecoDoMes, $periodoFimDoMes) {
		$relatorio['quantidadeLideres'] = 0;
		$relatorio['celulaQuantidade'] = 0;
		$tipoRelatorioPessoal = 1;
		$tipoRelatorioSomado = 2;
		$mostrarInativos = true;

		$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
		$relatorioPessoal = RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $periodoComecoDoMes, $tipoRelatorioPessoal, $periodoFimDoMes);
		$relatorio['quantidadeLideres'] += $relatorioPessoal['quantidadeLideres'];
		$relatorio['celulaQuantidade'] += $relatorioPessoal['celulaQuantidade'];

		$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($periodoComecoDoMes);
		if ($grupoPaiFilhoFilhos) {
			foreach ($grupoPaiFilhoFilhos as $gpFilho) {
				$grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
				$dataInativacao = null;
				if ($gpFilho->getData_inativacao()) {
					$dataInativacao = $gpFilho->getData_inativacaoStringPadraoBanco();
				}
				$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupoFilho, $dataInativacao);
				$relatorioDiscipulo = RelatorioController::montaRelatorio($repositorio, $numeroIdentificador, $periodoComecoDoMes, $tipoRelatorioSomado, $periodoFimDoMes, $mostrarInativos);
				$relatorio['quantidadeLideres'] += $relatorioDiscipulo['quantidadeLideres'];
				$relatorio['celulaQuantidade'] += $relatorioDiscipulo['celulaQuantidade'];

				//                echo "<br />" . $grupoFilho->getEntidadeAtiva()->infoEntidade();
				//                echo " - " . $grupoFilho->getNomeLideresAtivos();
				//                echo " - ['quantidadeLideres']: " . $relatorioDiscipulo['quantidadeLideres'];
			}
		}
		return $relatorio;
	}

	public function lideresQueNaoLancamAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();

		$todosFilhos = array();
		$mes = date('m');
		$ano = date('Y');
		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$diferencaDePeriodos = self::diferencaDePeriodos($arrayPeriodoDoMes[0], $arrayPeriodoDoMes[1]);

		for ($indiceDeArrays = $arrayPeriodoDoMes[0]; $indiceDeArrays <= $arrayPeriodoDoMes[1]; $indiceDeArrays++) {
			$grupoPaiFilhoFilhos = $grupo->getGrupoPaiFilhoFilhosAtivos($indiceDeArrays);
			if ($grupoPaiFilhoFilhos) {
				foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
					$adicionar = true;
					if (count($todosFilhos) > 0) {
						foreach ($todosFilhos as $filho) {
							if ($filho->getId() === $grupoPaiFilhoFilho->getId()) {
								$adicionar = false;
								break;
							}
						}
					}
					if ($adicionar) {
						$todosFilhos[] = $grupoPaiFilhoFilho;
					}
				}
			}
		}

		$html = '';
		$html .= '<h1>Lideres que nao lançam</h1>';
		foreach($todosFilhos as $filho){
			$grupo = $filho->getGrupoPaiFilhoFilho();
			if($grupoResponsabilidades = $grupo->getResponsabilidadesAtivas()){
				foreach($grupoResponsabilidades as $grupoResponsavel){
					$pessoa = $grupoResponsavel->getPessoa();
					$html .= '<br /> Pessoa: ' . $pessoa->getNome();
					if($eventoFrequencias = $pessoa->getEventoFrequencia()){
						$ultimaFrequencia = null;
						foreach($eventoFrequencias as $eventoFrequencia){
							$mostrar = false;
							if($eventoFrequencia->getEvento()->getEventoTipo()->getId() === EventoTipo::tipoCelula){
								$mostrar = true;
							}
							if($mostrar){
								if($ultimaFrequencia === null){
									$ultimaFrequencia = $eventoFrequencia;
								}
								if($ultimaFrequencia->getId() < $eventoFrequencia->getId()){
									$ultimaFrequencia = $eventoFrequencia;
								}
							}
						}
						if($ultimaFrequencia){
							$html .= ' - Ultimo lançamento de celula: ' . $ultimaFrequencia->getData_criacaoStringPadraoBrasil();
						}
					}
				}
			}
			if ($todosLideres1 = self::todosLideresAbaixoNoPeriodo($filho, $arrayPeriodoDoMes)) {
				foreach ($todosLideres1 as $filho1) {
					if ($todosLideres2 = self::todosLideresAbaixoNoPeriodo($filho1, $arrayPeriodoDoMes)) {
						foreach ($todosLideres2 as $filho2) {
							if ($todosLideres3 = self::todosLideresAbaixoNoPeriodo($filho2, $arrayPeriodoDoMes)) {
								foreach ($todosLideres3 as $filho3) {
									if ($todosLideres4 = self::todosLideresAbaixoNoPeriodo($filho3, $arrayPeriodoDoMes)) {
										foreach ($todosLideres4 as $filho4) {
											if ($todosLideres5 = self::todosLideresAbaixoNoPeriodo($filho4, $arrayPeriodoDoMes)) {
												foreach ($todosLideres5 as $filho5) {
													if ($todosLideres6 = self::todosLideresAbaixoNoPeriodo($filho5, $arrayPeriodoDoMes)) {
														foreach ($todosLideres6 as $filho6) {
															if ($todosLideres7 = self::todosLideresAbaixoNoPeriodo($filho6, $arrayPeriodoDoMes)) {
																foreach ($todosLideres7 as $filho7) {
																	if ($todosLideres8 = self::todosLideresAbaixoNoPeriodo($filho7, $arrayPeriodoDoMes)) {
																		foreach ($todosLideres8 as $filho8) {
																			if ($todosLideres9 = self::todosLideresAbaixoNoPeriodo($filho8, $arrayPeriodoDoMes)) {
																				foreach ($todosLideres9 as $filho9) {
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return new ViewModel(array('html' => $html));
	}

	public function rankingCelulaAction(){
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){
			$sessao = new Container(Constantes::$NOME_APLICACAO);

			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

			$postDados = $request->getPost();

			$mes = $postDados['mes'];
			$ano = $postDados['ano'];

			if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
				$grupoIgreja = $entidade->getGrupo()->getGrupoIgreja();
				$fatosRankingCelula = $this->getRepositorio()->getFatoRankingCelulaORM()->encontrarPorIdGrupoIgreja($grupoIgreja->getId(), $mes, $ano);
			}

			if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
				$entidade->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
					$grupoEquipe = $entidade->getGrupo()->getGrupoEquipe();
					$fatosRankingCelula = $this->getRepositorio()->getFatoRankingCelulaORM()->encontrarPorIdGrupoEquipe($grupoEquipe->getId(), $mes, $ano);
				}
			$dados['fatos'] = $fatosRankingCelula;
			$dados['repositorio'] = $this->getRepositorio();
			$dados['filtrado'] = true;
		}else{
			$mes = date('m');
			$ano = date('Y');
		}
		$dados['mes'] = $mes;
		$dados['ano'] = $ano;

		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$dados['periodoInicial'] = $arrayPeriodoDoMes[0];
		$dados['periodoFinal'] = $arrayPeriodoDoMes[1];

		self::registrarLog(RegistroAcao::VER_RELATORIO_RANKING_CELULA_, $extra = '');
		return new ViewModel($dados);
	}

	public function celulasNaoRealizadasAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupo = $entidade->getGrupo();
		$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
		$periodo = -1;
		$arrayPeriodo = Funcoes::montaPeriodo($periodo);
		$inicioPeriodo = $arrayPeriodo[3].'-'.$arrayPeriodo[2].'-'.$arrayPeriodo[1];
        $dateFormatada = DateTime::createFromFormat('Y-m-d', $inicioPeriodo);

		$relatorio = $this->getRepositorio()->getFatoCelulaORM()->encontrarPorNumeroIdentificadorEDataCriacao($numeroIdentificador, $dateFormatada);

		self::registrarLog(RegistroAcao::VER_RELATORIO_CELULAS_NAO_REALIZADAS, $extra = '');
		return new ViewModel(
			array(
				'relatorio' => $relatorio,
				'repositorio' => $this->getRepositorio(),
				'periodo' => $periodo,
			));
	}

const relatorioAlunosQueNaoForamAAula = 1;
const relatorioAlunosComFaltas = 2;
public function alunosAction(){
	$sessao = new Container(Constantes::$NOME_APLICACAO);

	$tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');
	switch(tipoRelatorio){
	case self::relatorioAlunosQueNaoForamAAula: self::registrarLog(RegistroAcao::VER_RELATORIO_ALUNOS_QUE_NAO_FORAM_A_AULA, $extra = ''); break;
	case self::relatorioAlunosComFaltas: self::registrarLog(RegistroAcao::VER_RELATORIO_ALUNOS_REPROVANDO, $extra = ''); break;
	}

	$idEntidadeAtual = $sessao->idEntidadeAtual;
	$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
	$grupo = $entidade->getGrupo();
	$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
	$relatorioInicial = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador);

	$relatorioAjustado = array();
	$turmas = $grupo->getGrupoIgreja()->getTurma();

	foreach($relatorioInicial as $relatorio){
		if($relatorio->getSituacao_id() === Situacao::ATIVO || $relatorio->getSituacao_id() === Situacao::ESPECIAL){
			foreach($turmas as $turma){
				if($relatorio->getTurma_id() === $turma->getId()){
					if($turma->getTurmaAulaAtiva()){
						$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($relatorio->getTurma_pessoa_id());

						$linkWhatsapp = '<i class="btn btn-xs btn-default btn-disabled fa fa-ban"></i>';
						$telefone = 'SEM TELEFONE';
						if($turmaPessoa->getPessoa()->getTelefone()){
							$linkWhatsapp = '<a  class="btn btn-success btn-xs" href="https://api.whatsapp.com/send?phone=55'.$turmaPessoa->getPessoa()->getTelefone().'"><i class="fa fa-whatsapp"></i></a>';
							$telefone = $turmaPessoa->getPessoa()->getTelefone();
						}
						$nomeEquipe = '';
						if($turmaPessoaAtivo = $turmaPessoa->getPessoa()->getGrupoPessoaAtivo()){
							$nomeEquipe = CursoController::nomeEquipeTurmaPessoa($turmaPessoa, $grupoPessoaAtivo);
						}

						if($tipoRelatorio === self::relatorioAlunosQueNaoForamAAula){
							if($turmaPessoaAulas = $turmaPessoa->getTurmaPessoaAula()){
								$assistiuAAula = false;
								foreach($turmaPessoaAulas as $turmaPessoaAula){
									if($turmaPessoaAula->getAula()->getId() === $turma->getTurmaAulaAtiva()->getAula()->getId()){
										if($turmaPessoaAula->verificarSeEstaAtivo()){
											$assistiuAAula = true;
											break;
										}
									}

								}
							}

							if(!$assistiuAAula){
								$dados = array();
								$dados['matricula'] = $turmaPessoa->getId();
								$dados['nome'] = $turmaPessoa->getPessoa()->getNome();
								$dados['time'] = $nomeEquipe;
								$dados['telefone'] = $telefone;
								$dados['mensagem'] = $linkWhatsapp;
								$relatorioAjustado[$turma->getId()][] = $dados;
							}

						}
						if($tipoRelatorio === self::relatorioAlunosComFaltas){
							$contadorDeFaltas = 0;
							$aulaAtiva = $turma->getTurmaAulaAtiva()->getAula();
							foreach ($turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getAulaOrdenadasPorPosicao() as $aula) {
								$temFalta = true;
								if($turma->getTurmaAulaAtiva()->getAula()->getId() === $aula->getId()){
									break;
								}
								if($turmaPessoaAula = $this->getRepositorio()->getTurmaPessoaAulaORM()->encontrarPorTurmaPessoaEAula($turmaPessoa->getId(), $aula->getId())){
									if($turmaPessoaAula->verificarSeEstaAtivo()){
										if ($turmaPessoaAula->getReposicao() == 'N') {
											$temFalta = false;

										}
									}
								}
								if($temFalta){
									$contadorDeFaltas++;
								}
							}

							if($contadorDeFaltas > 1 && $contadorDeFaltas <= 3){
								$dados = array();
								$dados['matricula'] = $turmaPessoa->getId();
								$dados['nome'] = $turmaPessoa->getPessoa()->getNome();
								$dados['time'] = $nomeEquipe;
								$dados['telefone'] = $telefone;
								$dados['mensagem'] = $linkWhatsapp;
								$dados['faltas'] = $contadorDeFaltas;
								$relatorioAjustado[$turma->getId()][] = $dados;
							}
						}
					}
					break;
				}
			}
		}
	}
	return new ViewModel(
		array(
			'repositorio' => $this->getRepositorio(),
			'turmas' => $turmas,
			'relatorio' => $relatorioAjustado,
			'tipoRelatorio' => $tipoRelatorio,
		));
}

public function alunosNaSemanaAction(){
	$sessao = new Container(Constantes::$NOME_APLICACAO);

	$tipoRelatorio = (int) $this->params()->fromRoute('tipoRelatorio');

	$idEntidadeAtual = $sessao->idEntidadeAtual;
	$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
	$grupo = $entidade->getGrupo();
	$numeroIdentificador = $this->getRepositorio()->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupo);
	$relatorioInicial = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador);

	$relatorioAjustado = array();
	$turmas = $grupo->getGrupoIgreja()->getTurma();
	foreach($relatorioInicial as $relatorio){
		if($relatorio->getSituacao_id() === Situacao::ATIVO || $relatorio->getSituacao_id() === Situacao::ESPECIAL){
			foreach($turmas as $turma){
				if($relatorio->getTurma_id() === $turma->getId()){
					if($turma->getTurmaAulaAtiva()){
						$relatorioAjustado[$turma->getId()][7]++;
						$turmaPessoa = $this->getRepositorio()->getTurmaPessoaORM()->encontrarPorId($relatorio->getTurma_pessoa_id());
						if($turmaPessoaAulas = $turmaPessoa->getTurmaPessoaAula()){
							$assistiuAAula = false;
							foreach($turmaPessoaAulas as $turmaPessoaAula){
								if($turmaPessoaAula->getAula()->getId() === $turma->getTurmaAulaAtiva()->getAula()->getId()){
									if($turmaPessoaAula->verificarSeEstaAtivo()){
										$assistiuAAula = true;
										break;
									}
								}

							}
							if($assistiuAAula){
								$diaDaSemana = date('w', strtotime($turmaPessoaAula->getData_criacaoStringPadraoBanco()));
								$relatorioAjustado[$turma->getId()][$diaDaSemana]++;
							}
						}
					}
					break;
				}
			}
		}
	}

	self::registrarLog(RegistroAcao::VER_RELATORIO_ALUNOS_NA_SEMANA, $extra = '');
	return new ViewModel(
		array(
			'turmas' => $turmas,
			'relatorio' => $relatorioAjustado,
		));
}

	public static function pegarCelulasNaoRealizadasNoPeriodo(RepositorioORM $repositorioORM, Grupo $grupo, $periodo) {
		$relatorio = array();
		$grupoEventosCelula = $grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoCelula);

		foreach ($grupoEventosCelula as $grupoEventoCelula) {
			$eventoId = $grupoEventoCelula->getEvento()->getId();
			$resultado = $repositorioORM->getFatoCicloORM()->verificaFrequenciasPorCelulaEPeriodo($periodo, $eventoId);
			$realizada = false;
			if($resultado > 0){
				$realizada = true;
			}
			$relatorio[$grupoEventoCelula->getId()]['realizada'] = $realizada;
		}
		return $relatorio;
	}

	public function geradorMetasAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$mes = 12; //Temporariamente Fixo só pra agilizar
		$ano = 2018; //Temporariamente Fixo só pra agilizar
		$comparacao = 2; // Temporariamente Fixo também, comparação = like
		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$periodoInicial = $arrayPeriodoDoMes[0];
		$periodoFinal = $arrayPeriodoDoMes[1];
		
		$todosFilhos = Array();
		$repositorio = $this->getRepositorio();
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoPaiFilhoFilhos = $entidade->getGrupo()->getGrupoPaiFilhoFilhosAtivos($periodoFinal);
		$situacaoAtiva = 1;
		$situacaoEspecial = 6;

		foreach ($grupoPaiFilhoFilhos as $grupoPaiFilhoFilho) {
			$todosFilhos[] = $grupoPaiFilhoFilho;
		}
		$arrayComRelatorios = Array();
		foreach ($todosFilhos as $filho) {
			$somaCelulas = 0;
			$somaAlunos = 0;
			$somaMetaDeAlunos = 0;
			$somaMetaDeEnvioRevisao = 0;
			$somaTOTAL = 0;
			$grupo = $filho->getGrupoPaiFilhoFilho();
			$relatorio = Array();
			if($grupo->getEntidadeAtiva()->getNome()){
					$relatorio['nome'] = $grupo->getEntidadeAtiva()->getNome();
			}	else {
				$relatorio['nome'] = 'COORDENAÇÃO '.$grupo->getEntidadeAtiva()->getNumero();
			}
			$relatorio['tipo'] = 'titulo';
			$arrayComRelatorios[] = $relatorio;
			if($grupo->getId() == 1225 || $grupo->getId() == 1){
				$grupoPaiFilhoFilhosEquipe = $grupo->getGrupoPaiFilhoFilhosAtivos($periodoFinal);
				foreach ($grupoPaiFilhoFilhosEquipe as $grupoPaiFilhoFilhoEquipes) {
					$grupo = $grupoPaiFilhoFilhoEquipes->getGrupoPaiFilhoFilho();
					$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
					$relatorioCelula = $repositorio->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoFinal, $comparacao);
					$relatorio = Array();
					if($grupo->getEntidadeAtiva()->getNome()){
							$relatorio['nome'] = $grupo->getEntidadeAtiva()->getNome();
					}
					$relatorio['celulas'] = $relatorioCelula[0]['quantidade'];
					if($relatorioCursosDesordenados = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador)){
						foreach($relatorioCursosDesordenados as $fatoCurso){
							$turmaDoFatoCurso = $this->getRepositorio()->getTurmaORM()->encontrarPorId($fatoCurso->getTurma_id());							
							if($turmaDoFatoCurso->getAno() <= 2018){
								if($fatoCurso->getSituacao_id() == $situacaoAtiva || $fatoCurso->getSituacao_id() == $situacaoEspecial){
									$relatorio['alunos']++;
								}
							}							
						}
					}
					$meta['metaDeAlunos'] = number_format($relatorio['alunos'] / 100 * 10);
					$meta['metaDeEnvioRevisao'] = number_format($relatorio['celulas'] / 100 * 10);

					if(!$relatorio['alunos']){
						$relatorio['alunos'] = 0;
					}
					if(!$relatorio['celulas']){
						$relatorio['celulas'] = 0;
					}
					$meta['total'] = $relatorio['celulas'] + $meta['metaDeAlunos'] + $meta['metaDeEnvioRevisao'];
					$relatorio['meta'] = $meta;
					$arrayComRelatorios[] = $relatorio;
					$somaAlunos += $relatorio['alunos'];
					$somaCelulas += $relatorio['celulas'];
					$somaMetaDeAlunos += $meta['metaDeAlunos'];
					$somaMetaDeEnvioRevisao += $meta['metaDeEnvioRevisao'];
					$somaTOTAL += $meta['total'];
				}
				$relatorio['nome'] = 'TOTAL';
				$relatorio['celulas'] = $somaCelulas;
				$relatorio['alunos'] = $somaAlunos;
				$relatorio['meta']['metaDeAlunos'] = $somaMetaDeAlunos;
				$relatorio['meta']['metaDeEnvioRevisao'] = $somaMetaDeEnvioRevisao;
				$relatorio['meta']['total'] = $somaTOTAL;
				$arrayComRelatorios[] = $relatorio;
			}

			if($grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === 4 || $grupo->getEntidadeAtiva()->getEntidadeTipo()->getId() === 3){
				$grupoPaiFilhoFilhosEquipe = $grupo->getGrupoPaiFilhoFilhosAtivos($periodoFinal);
				foreach ($grupoPaiFilhoFilhosEquipe as $grupoPaiFilhoFilhoEquipes) {
					$grupo = $grupoPaiFilhoFilhoEquipes->getGrupoPaiFilhoFilho();
					$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($repositorio, $grupo);
					$relatorioCelula = $repositorio->getFatoCicloORM()->montarRelatorioCelulaPorNumeroIdentificador($numeroIdentificador, $periodoFinal, $comparacao);					
					$relatorio = Array();
					if($grupo->getEntidadeAtiva()->getNome()){
							$relatorio['nome'] = $grupo->getEntidadeAtiva()->getNome();
					}
					$relatorio['celulas'] = $relatorioCelula[0]['quantidade'];
					$turmas = $grupo->getGrupoIgreja()->getTurma();
					$relatorioCursos = array();
					if($relatorioCursosDesordenados = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador)){
						foreach($relatorioCursosDesordenados as $fatoCurso){
							$turmaDoFatoCurso = $this->getRepositorio()->getTurmaORM()->encontrarPorId($fatoCurso->getTurma_id());							
							if($turmaDoFatoCurso->getAno() <= 2018){
								if($fatoCurso->getSituacao_id() == $situacaoAtiva || $fatoCurso->getSituacao_id() == $situacaoEspecial){
									$relatorio['alunos']++;
								}
							}
						}
					}
					$meta['metaDeAlunos'] = number_format($relatorio['alunos'] / 100 * 10);
					$meta['metaDeEnvioRevisao'] = number_format($relatorio['celulas'] / 100 * 10);

					if(!$relatorio['alunos']){
						$relatorio['alunos'] = 0;
					}
					if(!$relatorio['celulas']){
						$relatorio['celulas'] = 0;
					}
					$meta['total'] = $relatorio['celulas'] + $meta['metaDeAlunos'] + $meta['metaDeEnvioRevisao'];
					$relatorio['meta'] = $meta;
					$arrayComRelatorios[] = $relatorio;
					$somaAlunos += $relatorio['alunos'];
					$somaCelulas += $relatorio['celulas'];
					$somaMetaDeAlunos += $meta['metaDeAlunos'];
					$somaMetaDeEnvioRevisao += $meta['metaDeEnvioRevisao'];
					$somaTOTAL += $meta['total'];
				}
				$relatorio['nome'] = 'TOTAL';
				$relatorio['celulas'] = $somaCelulas;
				$relatorio['alunos'] = $somaAlunos;
				$relatorio['meta']['metaDeAlunos'] = $somaMetaDeAlunos;
				$relatorio['meta']['metaDeEnvioRevisao'] = $somaMetaDeEnvioRevisao;
				$relatorio['meta']['total'] = $somaTOTAL;
				$arrayComRelatorios[] = $relatorio;
			}
		}

		$request = $this->getRequest();
		if($request->isPost()){
			$postDados = $request->getPost();
			$mes = $postDados['mes'];
			$ano = $postDados['ano'];
		}else{
			$mes = date('m');
			$ano = date('Y');
		}

		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		$dados['arrayComRelatorios'] = $arrayComRelatorios;
		return new ViewModel($dados);
	}

	public function exclusaoCelulasAction() {

		$sessao = new Container(Constantes::$NOME_APLICACAO);
		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
		$grupoIgreja = $entidade->getGrupo()->getGrupoIgreja();
		$solicitacoes = $grupoIgreja->getSolicitacao();
		$request = $this->getRequest();
		if($request->isPost()){
			$postDados = $request->getPost();
			$mes = $postDados['mes'];
			$ano = $postDados['ano'];
		}else{
			$mes = date('m');
			$ano = date('Y');
		}
			$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
			$periodoInicial = $arrayPeriodoDoMes[0];
			$periodoFinal = $arrayPeriodoDoMes[1];
			$relatorioCelulasExcluidas = array();
			$totalCiclo = array();
			$totalGeral = 0;
			foreach ($solicitacoes as $solicitacao) {
				$solicitacaoSituacaoAtiva = $solicitacao->getSolicitacaoSituacaoAtiva();
				$mesDaSolicitacao = $solicitacaoSituacaoAtiva->getData_criacaoMes();
				$diaDaSolicitacao = $solicitacaoSituacaoAtiva->getData_criacaoDia();
				$anoDaSolicitacao = $solicitacaoSituacaoAtiva->getData_criacaoAno();
				$dataAjustadaDaSolicitacao = date('Y-m-d', mktime(0, 0, 0, $mesDaSolicitacao, $diaDaSolicitacao - 1, $anoDaSolicitacao));
				for ($indiceDeArrays = $periodoInicial; $indiceDeArrays <= $periodoFinal; $indiceDeArrays++) {
					$arrayPeriodo = Funcoes::montaPeriodo($indiceDeArrays);
					$dataFimPeriodo = $arrayPeriodo[6].'-'.$arrayPeriodo[5].'-'.$arrayPeriodo[4];
					$dataInicioPeriodo = $arrayPeriodo[3].'-'.$arrayPeriodo[2].'-'.$arrayPeriodo[1];
					if($solicitacaoSituacaoAtiva->getSituacao()->getId() === Situacao::CONCLUIDO && $dataAjustadaDaSolicitacao >= $dataInicioPeriodo && $dataAjustadaDaSolicitacao <= $dataFimPeriodo){
						if($grupo = $this->getRepositorio()->getGrupoORM()->encontrarPorId($solicitacao->getObjeto1())){
							$nomeDaEquipe = $grupo->getGrupoEquipe()->getEntidadeAtiva()->getNome();
						}
						if($solicitacao->getSolicitacaoTipo()->getId() === SolicitacaoTipo::REMOVER_CELULA){
							$relatorioCelulasExcluidas[$nomeDaEquipe][$indiceDeArrays]++;
							$relatorioCelulasExcluidas[$nomeDaEquipe]['fotoDaEquipe'] = $grupo->getGrupoEquipe()->getFotosLideresAtivos();
							$relatorioCelulasExcluidas[$nomeDaEquipe]['total'] ++;
							$totalCiclo[$indiceDeArrays]++;
							$totalGeral++;
						}
						if($solicitacao->getSolicitacaoTipo()->getId() === SolicitacaoTipo::REMOVER_LIDER){
							if($grupoEventoCelulasInativas = $grupo->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula, $ativo = 2)){
								foreach ($grupoEventoCelulasInativas as $celulasInativas) {
									if($celulasInativas->getData_inativacaoStringPadraoBanco() == $dataAjustadaDaSolicitacao){
										$relatorioCelulasExcluidas[$nomeDaEquipe][$indiceDeArrays]++;
									  $relatorioCelulasExcluidas[$nomeDaEquipe]['fotoDaEquipe'] = $grupo->getGrupoEquipe()->getFotosLideresAtivos();
										$relatorioCelulasExcluidas[$nomeDaEquipe]['total'] ++;
										$totalCiclo[$indiceDeArrays]++;
										$totalGeral++;
									}
								}
							}
						}
					}

				}
			}
			uksort($relatorioCelulasExcluidas, function ($ak, $bk) use ($relatorioCelulasExcluidas) {
			    $a = $relatorioCelulasExcluidas[$ak];
			    $b = $relatorioCelulasExcluidas[$bk];
			    if ($a['total'] === $b['total']) return $ak - $bk;
			    return $a['total'] < $b['total'] ? 1 : -1;
			});

			$relatorioCelulasExcluidas['TOTAL']['total'] = $totalGeral;
			for ($indiceDeArrays = $periodoInicial; $indiceDeArrays <= $periodoFinal; $indiceDeArrays++) {
					$relatorioCelulasExcluidas['TOTAL'][$indiceDeArrays] = $totalCiclo[$indiceDeArrays];
			}
			$dados['relatorioCelulasExcluidas'] = $relatorioCelulasExcluidas;
			$dados['periodoInicial'] = $periodoInicial;
			$dados['periodoFinal'] = $periodoFinal;

		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		return new ViewModel($dados);
	}

	public function setentaAction() {
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){
			$sessao = new Container(Constantes::$NOME_APLICACAO);

			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

			$postDados = $request->getPost();

			$mes = $postDados['mes'];
			$ano = $postDados['ano'];

			$processar = true;
			/* mostrar aparti de novembro de 2018 */
			if($ano == 2018 && $mes <= 10){
				$processar = false;
			}

			if($processar){
				if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
					$grupoIgreja = $entidade->getGrupo()->getGrupoIgreja();
					$fatosSetenta = $this->getRepositorio()->getFatoSetentaORM()->encontrarPorIdGrupoIgreja($grupoIgreja->getId(), $mes, $ano);
				}

				if($entidade->getEntidadeTipo()->getId() === EntidadeTipo::equipe ||
					$entidade->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
						$grupoEquipe = $entidade->getGrupo()->getGrupoEquipe();
						$fatosSetenta = $this->getRepositorio()->getFatoSetentaORM()->encontrarPorIdGrupoEquipe($grupoEquipe->getId(), $mes, $ano);
					}

				$arrayLideres = array();
				foreach($fatosSetenta as $fato){
					if($fato->getSetenta() == 'S'){
						$arrayLideres[$fato->getGrupo_id()]['setenta'] = 'S';
					}

					if($arrayLideres[$fato->getGrupo_id()]['celula']){
						$validacaoSeJaTem = false;
						foreach($arrayLideres[$fato->getGrupo_id()]['celula'] as $fatoVerificar){
							if($fato->getGrupo_evento_id() === $fatoVerificar->getGrupo_evento_id()){
								$validacaoSeJaTem = true;
							}
						}
						if(!$validacaoSeJaTem){
							$arrayLideres[$fato->getGrupo_id()]['celula'][] = $fato;
						}
					}else{
						$arrayLideres[$fato->getGrupo_id()]['celula'][] = $fato;
					}
				}

				$dados['lideres'] = $arrayLideres;
				$dados['repositorio'] = $this->getRepositorio();
			}
			$dados['filtrado'] = true;
		}else{
			$mes = date('m');
			$ano = date('Y');
		}
		$dados['mes'] = $mes;
		$dados['ano'] = $ano;

		$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
		$dados['periodoInicial'] = $arrayPeriodoDoMes[0];
		$dados['periodoFinal'] = $arrayPeriodoDoMes[1];

		self::registrarLog(RegistroAcao::VER_RELATORIO_SETENTA, $extra = '');
		return new ViewModel($dados);
	}

	public function registroAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		$registros = $entidade->getGrupo()->getRegistro();
		$registrosOrganizados = array();

		foreach($registros as $registro){
			$registrosOrganizados[$registro->getData_criacaoStringPadraoBrasil()][] = $registro;
		}

		$dados = array();
		$dados['registros'] = $registrosOrganizados;

		self::registrarLog(RegistroAcao::VER_RELATORIO_DE_REGISTRO, $extra = '');

		return new ViewModel($dados);
	}

	public function registroVerificarAction(){
		$sessao = new Container(Constantes::$NOME_APLICACAO);

		$idEntidadeAtual = $sessao->idEntidadeAtual;
		$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);

		
		$registros = $this->getRepositorio()->getRegistroORM()->verificarRegistroDeCelulaPorData();
		$registrosOrganizados = array();

		$informacao = Array();		
		foreach($registros as $registro){				
			$nomeIgreja = $registro->getGrupo()->getGrupoIgreja()->getEntidadeAtiva()->infoEntidade();
			$nomeEquipe = $registro->getGrupo()->getGrupoEquipe()->getEntidadeAtiva()->infoEntidade();		
			$informacao[$nomeIgreja][$nomeEquipe]++;
			$informacao[$nomeIgreja]['TOTAL']++;			
		}

		$dados = array();
		$dados['registros'] = $informacao;

		self::registrarLog(RegistroAcao::VER_RELATORIO_DE_REGISTRO, $extra = '');

		return new ViewModel($dados);
	}

	public function discipuladoAction(){
		$request = $this->getRequest();
		$dados = array();
		if($request->isPost()){
			$sessao = new Container(Constantes::$NOME_APLICACAO);

			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$grupoPaiFilhoFilhos = $entidade->getGrupo()->getGrupoPaiFilhoFilhosAtivosReal();

			$postDados = $request->getPost();

			$mes = $postDados['mes'];
			$ano = $postDados['ano'];

			$dados['repositorio'] = $this->getRepositorio();
			$dados['filtrado'] = true;
			$dados['grupoPaiFilhoFlhos'] = $grupoPaiFilhoFilhos;
		}else{
			if(date('m') == 1){
				$mes = 12;
				$ano = date('Y') - 1;
			}else{
				$mes = date('m') - 1;
				$ano = date('Y');
			}
		}
		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		return new ViewModel($dados);
	}

	static public function relatorioDiscipulado($repositorio, $grupo, $mesAnterior, $anoAnterior){
		$relatorio = null;
		if($grupo->getGrupoEventoAtivosPorTipo(EventoTipo::tipoDiscipulado)){
			if($fatoDiscipulados = $repositorio->getFatoDiscipuladoORM()->entidadePorGrupoMesAno($grupo->getId(), $mesAnterior, $anoAnterior)){
				$relatorio = array();

				$relatorioGeral = array();
				$relatorioGeral['pontualidade'] = 0;
				$relatorioGeral['assiduidade'] = 0;
				$relatorioGeral['administrativo'] = 0;
				$relatorioGeral['oracao'] = 0;
				$relatorioGeral['palavra'] = 0;

				$totalDeFatos = 0;
				foreach($fatoDiscipulados as $fatoDiscipulado){
					if($fatoDiscipulado->getObservacao()){
						$relatorio['observacoes'][] = $fatoDiscipulado->getObservacao();
					}
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['pontualidade'] += $fatoDiscipulado->getPontualidade();
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['assiduidade'] += $fatoDiscipulado->getAssiduidade();
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['administrativo'] += $fatoDiscipulado->getAdministrativo();
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['oracao'] += $fatoDiscipulado->getOracao();
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['palavra'] += $fatoDiscipulado->getPalavra();
					$relatorio['discipulados'][$fatoDiscipulado->getGrupo_evento_id()]['quantidade']++;

					$relatorioGeral['pontualidade'] += $fatoDiscipulado->getPontualidade();
					$relatorioGeral['assiduidade'] += $fatoDiscipulado->getAssiduidade();
					$relatorioGeral['administrativo'] += $fatoDiscipulado->getAdministrativo();
					$relatorioGeral['oracao'] += $fatoDiscipulado->getOracao();
					$relatorioGeral['palavra'] += $fatoDiscipulado->getPalavra();
					$totalDeFatos++;
				}
				$relatorioGeral['pontualidade'] /= $totalDeFatos;
				$relatorioGeral['assiduidade'] /= $totalDeFatos;
				$relatorioGeral['administrativo'] /= $totalDeFatos;
				$relatorioGeral['oracao'] /= $totalDeFatos;
				$relatorioGeral['palavra'] /= $totalDeFatos;

				$media = ($relatorioGeral['pontualidade']
					+$relatorioGeral['assiduidade']
					+$relatorioGeral['administrativo']
					+$relatorioGeral['oracao']
					+$relatorioGeral['palavra'])/5;
				$relatorio['media'] = number_format($media);

				foreach($relatorio['discipulados'] as $chave => $valor){
					if($grupoEvento = $repositorio->getGrupoEventoORM()->encontrarPorId($chave)){
						$info = $grupoEvento->getEvento()->getNome();
						$relatorio['discipulados'][$chave]['info'] = $info;
						$totalDeFatos = $relatorio['discipulados'][$chave]['quantidade'];
						$relatorio['discipulados'][$chave]['pontualidade'] = number_format($relatorio['discipulados'][$chave]['pontualidade'] / $totalDeFatos);
						$relatorio['discipulados'][$chave]['assiduidade'] = number_format($relatorio['discipulados'][$chave]['assiduidade'] / $totalDeFatos);
						$relatorio['discipulados'][$chave]['administrativo'] = number_format($relatorio['discipulados'][$chave]['administrativo'] / $totalDeFatos);
						$relatorio['discipulados'][$chave]['oracao'] = number_format($relatorio['discipulados'][$chave]['oracao'] / $totalDeFatos);
						$relatorio['discipulados'][$chave]['palavra'] = number_format($relatorio['discipulados'][$chave]['palavra'] / $totalDeFatos);
					}
				}
			}
		}

		return $relatorio;
	}

	public function geradorDeMetaAction() {
		$dados = array();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$post_data = $request->getPost();
			$nome = $post_data['nome'];
			$mes = $post_data['mes'];
			$ano = $post_data['ano'];
			$lideres = $post_data['lideres'];
			$celulas = $post_data['celulas'];
			$celulasBeta = $post_data['celulasBeta'];
			$alunos1m = $post_data['alunos1m'];
			$alunos2m = $post_data['alunos2m'];
			$alunos3m = $post_data['alunos3m'];

			$dados['nome'] = $nome;
			$dados['lideres'] = $lideres;
			$dados['celulas'] = $celulas;
			$dados['celulasBeta'] = $celulasBeta;
			$dados['alunos1m'] = $alunos1m;
			$dados['alunos2m'] = $alunos2m;
			$dados['alunos3m'] = $alunos3m;
			$dados['postado'] = true;

			$sessao = new Container(Constantes::$NOME_APLICACAO);
			$idEntidadeAtual = $sessao->idEntidadeAtual;
			$arrayPeriodoDoMes = Funcoes::encontrarPeriodoDeUmMesPorMesEAno($mes, $ano);
			$periodo = $arrayPeriodoDoMes[1]; 
			$comparacao = 2; // Temporariamente Fixo também, comparação = like
			$todosFilhos = Array();
			$repositorio = $this->getRepositorio();
			$entidade = $this->getRepositorio()->getEntidadeORM()->encontrarPorId($idEntidadeAtual);
			$listaDeFilhos12 = array();
			$somaTotalLideres = 0;
			$somaTotalLideresMeta = 0;
			$somaTotalCelulas = 0;
			$somaTotalCelulasMeta = 0;
			$somaTotalCelulasBeta = 0;
			$somaTotalCelulasBetaMeta = 0;
			$somaTotalAlunos1 = 0;
			$somaTotalAlunos1Meta = 0;
			$somaTotalAlunos2 = 0;
			$somaTotalAlunos2Meta = 0;
			$somaTotalAlunos3 = 0;
			$somaTotalAlunos3Meta = 0;
			if($grupoPaiFilhoFilhos = $entidade->getGrupo()->getGrupoPaiFilhoFilhosAtivos($periodo)){
				foreach ($grupoPaiFilhoFilhos as $filho) {
					$grupoFilho = $filho->getGrupoPaiFilhoFilho();
					$dadosFilho = array();

					$dadosFilho['informacao'] = '';
					if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
						$dadosFilho['informacao'] = 'REGIÃO ' . $grupoFilho->getEntidadeAtiva()->getNome();
					}
					if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
						$dadosFilho['informacao'] = 'COORDENAÇÃO ' . $grupoFilho->getEntidadeAtiva()->getNumero();
					}
					if($grupoFilho->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
						$dadosFilho['informacao'] = 'IGREJA ' . $grupoFilho->getEntidadeAtiva()->getNome();
					}

					$listaDeFilhos144 = array();
					$somaParcialLideres = 0;
					$somaParcialLideresMeta = 0;
					$somaParcialCelulas = 0;
					$somaParcialCelulasMeta = 0;
					$somaParcialCelulasBeta = 0;
					$somaParcialCelulasBetaMeta = 0;
					$somaParcialAlunos1 = 0;
					$somaParcialAlunos1Meta = 0;
					$somaParcialAlunos2 = 0;
					$somaParcialAlunos2Meta = 0;
					$somaParcialAlunos3 = 0;
					$somaParcialAlunos3Meta = 0;
					if($grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos($periodo)){
						foreach ($grupoPaiFilhoFilhos144 as $filho144) {
							$grupoFilho144 = $filho144->getGrupoPaiFilhoFilho();
							$dadosFilho144 = array();
							$dadosFilho144['informacao'] = '';
							if($grupoFilho144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::regiao){
								$dadosFilho144['informacao'] = 'REGIÃO ' . $grupoFilho144->getEntidadeAtiva()->getNome();
							}
							if($grupoFilho144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::coordenacao){
								$dadosFilho144['informacao'] = 'COORDENAÇÃO ' . $grupoFilho144->getEntidadeAtiva()->getNumero();
							}
							if($grupoFilho144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::igreja){
								$dadosFilho144['informacao'] = 'IGREJA ' . $grupoFilho144->getEntidadeAtiva()->getNome();
							}
							if($grupoFilho144->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::equipe){
								$dadosFilho144['informacao'] = 'EQUIPE ' . $grupoFilho144->getEntidadeAtiva()->getNome();
							}

							$numeroIdentificador = $repositorio->getFatoCicloORM()->montarNumeroIdentificador($this->getRepositorio(), $grupoFilho144);
							$tipoRelatorio = RelatorioController::relatorioCelulaQuantidade;
							$relatorio = RelatorioController::montaRelatorio($this->getRepositorio(), $numeroIdentificador, $periodo, $somado = 2, false, $tipoRelatorio);
							$somaIgrejaAlunos1 = 0;
							$somaIgrejaAlunos2 = 0;
							$somaIgrejaAlunos3 = 0;
							if($relatorioCursos = $this->getRepositorio()->getFatoCursoORM()->encontrarFatoCursoPorNumeroIdentificador($numeroIdentificador)){
								foreach($relatorioCursos as $fatoCurso){
									if($fatoCurso->getSituacao_id() === Situacao::ATIVO || $fatoCurso->getSituacao_id() === Situacao::ESPECIAL){
										$turma = $this->getRepositorio()->getTurmaORM()->encontrarPorId($fatoCurso->getTurma_id());
										$adicionar = false;
										if(intVal($turma->getAno()) < intVal($ano)){
											$adicionar = true;
										}
										if(intVal($turma->getAno()) === intVal($ano)){
											if(intVal($turma->getMes()) <= intVal($mes)){
												$adicionar = true;
											}
										}
										if($adicionar){
											if($turma->getTurmaAulaAtiva()){
												if($turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getPosicao() === 1){
													$somaIgrejaAlunos1++;
												}
												if($turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getPosicao() === 2){
													$somaIgrejaAlunos2++;
												}
												if($turma->getTurmaAulaAtiva()->getAula()->getDisciplina()->getPosicao() === 3){
													$somaIgrejaAlunos3++;
												}
											}
										}
									}
								}
							}

							$dadosFilho144['lideres'] = $relatorio['quantidadeLideres'];
							$dadosFilho144['lideresMeta'] = $lideres > 0 ? $lideres / 100 * $relatorio['quantidadeLideres'] : $relatorio['quantidadeLideres'];

							$dadosFilho144['celulas'] = $relatorio['celulaQuantidade'];
							$dadosFilho144['celulasMeta'] = $celulas > 0 ? $celulas / 100 * $relatorio['celulaQuantidade'] : $relatorio['celulaQuantidade'];

							$dadosFilho144['celulasBeta'] = $relatorio['celulaQuantidadeEstrategica'];
							$dadosFilho144['celulasBetaMeta'] = $celulasBeta > 0 ? $celulasBeta / 100 * $relatorio['celulaQuantidadeEstrategica'] : $relatorio['celulaQuantidadeEstrategica'];

							$dadosFilho144['alunos1'] = $somaIgrejaAlunos1;
							$dadosFilho144['alunos1Meta'] = $alunos1m > 0 ? $alunos1m / 100 * $somaIgrejaAlunos1 : $somaIgrejaAlunos1;

							$dadosFilho144['alunos2'] = $somaIgrejaAlunos2;
							$dadosFilho144['alunos2Meta'] = $alunos1m > 0 ? $alunos2m / 100 * $somaIgrejaAlunos2 : $somaIgrejaAlunos2;

							$dadosFilho144['alunos3'] = $somaIgrejaAlunos3;
							$dadosFilho144['alunos3Meta'] = $alunos3m > 0 ? $alunos3m / 100 * $somaIgrejaAlunos3 : $somaIgrejaAlunos3;
	
							$somaParcialLideres += $dadosFilho144['lideres'];
							$somaParcialLideresMeta += $dadosFilho144['lideresMeta'];
							$somaParcialCelulas += $dadosFilho144['celulas'];
							$somaParcialCelulasMeta += $dadosFilho144['celulasMeta'];
							$somaParcialCelulasBeta += $dadosFilho144['celulasBeta'];
							$somaParcialCelulasBetaMeta += $dadosFilho144['celulasBetaMeta'];
							$somaParcialAlunos1 += $dadosFilho144['alunos1'];
							$somaParcialAlunos1Meta += $dadosFilho144['alunos1Meta'];
							$somaParcialAlunos2 += $dadosFilho144['alunos2'];
							$somaParcialAlunos2Meta += $dadosFilho144['alunos2Meta'];
							$somaParcialAlunos3 += $dadosFilho144['alunos3'];
							$somaParcialAlunos3Meta += $dadosFilho144['alunos3Meta'];

							$somaTotalLideres += $dadosFilho144['lideres'];
							$somaTotalLideresMeta += $dadosFilho144['lideresMeta'];
							$somaTotalCelulas += $dadosFilho144['celulas'];
							$somaTotalCelulasMeta += $dadosFilho144['celulasMeta'];
							$somaTotalCelulasBeta += $dadosFilho144['celulasBeta'];
							$somaTotalCelulasBetaMeta += $dadosFilho144['celulasBetaMeta'];
							$somaTotalAlunos1 += $dadosFilho144['alunos1'];
							$somaTotalAlunos1Meta += $dadosFilho144['alunos1Meta'];
							$somaTotalAlunos2 += $dadosFilho144['alunos2'];
							$somaTotalAlunos2Meta += $dadosFilho144['alunos2Meta'];
							$somaTotalAlunos3 += $dadosFilho144['alunos3'];
							$somaTotalAlunos3Meta += $dadosFilho144['alunos3Meta'];

							$listaDeFilhos144[] = $dadosFilho144;
						}
					}
					$total12 = array();
					$total12['informacao'] = 'TOTAL PARCIAL';
					$total12['lideres'] = $somaParcialLideres;	
					$total12['lideresMeta'] = $somaParcialLideresMeta;
					$total12['celulas'] = $somaParcialCelulas;	
					$total12['celulasMeta'] = $somaParcialCelulasMeta;	
					$total12['celulasBeta'] = $somaParcialCelulasBeta;	
					$total12['celulasBetaMeta'] = $somaParcialCelulasBetaMeta;	
					$total12['alunos1'] = $somaParcialAlunos1;	
					$total12['alunos1Meta'] = $somaParcialAlunos1Meta;	
					$total12['alunos2'] = $somaParcialAlunos2;	
					$total12['alunos2Meta'] = $somaParcialAlunos2Meta;	
					$total12['alunos3'] = $somaParcialAlunos3;	
					$total12['alunos3Meta'] = $somaParcialAlunos3Meta;	
					$listaDeFilhos144[] = $total12;
					$dadosFilho['filhos'] = $listaDeFilhos144;
					$listaDeFilhos12[] = $dadosFilho;
				}
			}
			$totalGeral = array();
			$totalGeral['informacao'] = 'TOTAL';
			$totalGeral['lideres'] = $somaTotalLideres;	
			$totalGeral['lideresMeta'] = $somaTotalLideresMeta;	
			$totalGeral['celulas'] = $somaTotalCelulas;	
			$totalGeral['celulasMeta'] = $somaTotalCelulasMeta;	
			$totalGeral['celulasBeta'] = $somaTotalCelulasBeta;	
			$totalGeral['celulasBetaMeta'] = $somaTotalCelulasBetaMeta;
			$totalGeral['alunos1'] = $somaTotalAlunos1;	
			$totalGeral['alunos1Meta'] = $somaTotalAlunos1Meta;	
			$totalGeral['alunos2'] = $somaTotalAlunos2;	
			$totalGeral['alunos2Meta'] = $somaTotalAlunos2Meta;	
			$totalGeral['alunos3'] = $somaTotalAlunos3;	
			$totalGeral['alunos3Meta'] = $somaTotalAlunos3Meta;	
			$listaDeFilhos12[] = $totalGeral;
			$dados['filhos'] = $listaDeFilhos12;
		}
		if (empty($mes)) {
			$mes = date('m');
		}
		if (empty($ano)) {
			$ano = date('Y');
		}

		$dados['mes'] = $mes;
		$dados['ano'] = $ano;
		$view = new ViewModel($dados);
		return $view;
	}
}
