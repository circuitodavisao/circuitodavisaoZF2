<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemLideresTransferencia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de lideres para transferir
 */
class ListagemLideresTransferencia extends AbstractHelper {

	private $discipulos;
	private $mostrarLider;

	public function __construct() {

	}

	public function __invoke($discipulos, $mostrarLider = false) {
		$this->setDiscipulos($discipulos);
		$this->setMostrarLider($mostrarLider);
		return $this->renderHtml();
	}

	public function setMostrarLider($item){
		$this->mostrarLider = $item;
	}

	public function getMostrarLider(){
		return $this->mostrarLider;
	}

	public function verificarSeMostrarONo($grupo, $solicitacoes) {
		$mostrar = true;
		foreach ($solicitacoes as $solicitacao) {
			if ($grupo->getId() == $solicitacao->getObjeto1()) {
				$mostrar = false;
			}
		}
		return $mostrar;
	}

	public function renderHtml() {
		$html = '';

		$entidade = $this->view->grupo->getEntidadeAtiva();
		$nomeLideres = $this->view->grupo->getNomeLideresAtivos();
		$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
		$disabled = 'disabled="disabled"';
		if($this->getMostrarLider()){
			$disabled = '';
		}
		$html .= '<option '.$disabled.' class="grupoLogado" value="' . $this->view->grupo->getId() . '">' . $informacao . '</option>';
		foreach ($this->getDiscipulos() as $gpFilho) {
			$grupo = $gpFilho->getGrupoPaiFilhoFilho();
			$entidade = $grupo->getEntidadeAtiva();
			$nomeLideres = $grupo->getNomeLideresAtivos();
			$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
			$dispulos144 = $grupo->getGrupoPaiFilhoFilhosAtivos(1);
			$html .= '<option class="lider grupoEquipe grupo' . $grupo->getId() . '" value="' . $grupo->getId() . '">' . $informacao . '</option>';
			if ($dispulos144) {
				foreach ($dispulos144 as $gpFilho144) {
					$grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
					if($entidade = $grupoFilho144->getEntidadeAtiva()){

						$nomeLideres = $grupoFilho144->getNomeLideresAtivos();
						$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
						$classe = 'semDiscipulos';
						if ($dispulos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos(1)) {
							$classe = 'comDiscipulos';
						}
						$html .= '<option class="lider ' . $classe . ' grupo' . $grupo->getId() . ' grupo' . $grupoFilho144->getId() . '" value="' . $grupoFilho144->getId() . '">' . $informacao . '</option>';
						if ($dispulos1728) {
							foreach ($dispulos1728 as $gpFilho1728) {
								$grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
								if($entidade = $grupoFilho1728->getEntidadeAtiva()){
									$nomeLideres = $grupoFilho1728->getNomeLideresAtivos();
									$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
									$classe = 'semDiscipulos';
									if ($dispulos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos(1)) {
										$classe = 'comDiscipulos';
									}
									$html .= '<option class="lider ' . $classe . ' grupo' . $grupo->getId() . ' grupo' . $grupoFilho144->getId() . '" value="' . $grupoFilho1728->getId() . '">' . $informacao . '</option>';
									if ($dispulos20736) {
										foreach ($dispulos20736 as $gpFilho20736) {
											$grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
											if($entidade = $grupoFilho20736->getEntidadeAtiva()){
												$nomeLideres = $grupoFilho20736->getNomeLideresAtivos();
												$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;

												$classe = 'semDiscipulos';
												if ($dispulos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos(1)) {
													$classe = 'comDiscipulos';
												}
												$html .= '<option class="lider ' . $classe . ' grupo' . $grupo->getId() . ' grupo' . $grupoFilho144->getId() . ' grupo' . $grupoFilho20736->getId() . '" value="' . $grupoFilho20736->getId() . '">' . $informacao . '</option>';
												if ($dispulos248832) {
													foreach ($dispulos248832 as $gpFilho248832) {
														$grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
														if($entidade = $grupoFilho248832->getEntidadeAtiva()){
															$nomeLideres = $grupoFilho248832->getNomeLideresAtivos();
															$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;

															$classe = 'semDiscipulos';
															if ($dispulos2985984 = $grupoFilho248832->getGrupoPaiFilhoFilhosAtivos(1)) {
																$classe = 'comDiscipulos';
															}
															$html .= '<option class="lider ' . $classe . ' grupo' . $grupo->getId() . ' grupo' . $grupoFilho144->getId() . ' grupo' . $grupoFilho20736->getId() . ' grupo' . $grupoFilho248832->getId() . '" value="' . $grupoFilho248832->getId() . '">' . $informacao . '</option>';
															if ($dispulos2985984) {
																foreach ($dispulos2985984 as $gpFilho2985984) {
																	$grupoFilho2985984 = $gpFilho2985984->getGrupoPaiFilhoFilho();
																	if($entidade = $grupoFilho2985984->getEntidadeAtiva()){
																		$nomeLideres = $grupoFilho2985984->getNomeLideresAtivos();
																		$informacao = $entidade->infoEntidade() . ' - ' . $nomeLideres;
																		$classe = 'semDiscipulos';
																		$html .= '<option class="lider ' . $classe . ' grupo' . $grupo->getId() . ' grupo' . $grupoFilho144->getId() . ' grupo' . $grupoFilho20736->getId() . ' grupo' . $grupoFilho248832->getId() . '" value="' . $grupoFilho248832->getId() . '">' . $informacao . '</option>';
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
		return $html;
	}

	function getDiscipulos() {
		return $this->discipulos;
	}

	function setDiscipulos($discipulos) {
		$this->discipulos = $discipulos;
	}

}
