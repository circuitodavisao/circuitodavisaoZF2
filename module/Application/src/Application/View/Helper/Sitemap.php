<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;
use Application\Model\Entity\EntidadeTipo;

/**
 * Nome: Sitemap.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view
 */
class Sitemap extends AbstractHelper {

	private $caminho;

	public function __construct() {

	}

	public function __invoke($caminho) {
		$this->setCaminho($caminho);
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';
		$id = '';
		if(substr($this->getCaminho(),0,9) == 'principal'){
			$id = $this->view->pessoaLogada->getId();
		}
		if(substr($this->getCaminho(),0,9) == 'relatorio'){
			$id = $this->view->grupoLogado->getId();
		}
		$funcaoOnClickPessoal = 'mostrarSplash(); funcaoCircuito("'.$this->getCaminho().'", '.$id.')';
		$linkDeQuemEstaLogado = '<a href="#" '.$this->view->funcaoOnclick($funcaoOnClickPessoal).'><i class="fa fa-home"></i> '.$this->view->pessoaLogada->getNomePrimeiro().'</a>';
		$html .= $linkDeQuemEstaLogado;

		$grupoSelecionado = $this->view->grupo;
		$stringOrdem = '';

		while($grupoSelecionado->getId() !== $this->view->grupoLogado->getId()){
			$stringGrupo = '';
			if($pessoas = $grupoSelecionado->getPessoasAtivas()){
				$cotadorDePessoas = count($pessoas);
				$contador = 1;
				foreach($pessoas as $pessoa){
					if($contador === 1){
						$stringGrupo .= ' > ';
					}
					if($contador === 2){
						$stringGrupo .= ' & ';
					}
					if(substr($this->getCaminho(),0,9) == 'principal'){
						$id = $pessoa->getId();
					}
					if(substr($this->getCaminho(),0,9) == 'relatorio'){
						$id = $grupoSelecionado->getId();
					}
					$funcaoOnClickPessoal = 'mostrarSplash(); funcaoCircuito("'.$this->getCaminho().'", '.$id.')';
					$linkDeQuemEstaLogado = '<a href="#" '.$this->view->funcaoOnclick($funcaoOnClickPessoal).'>'.$pessoa->getNomePrimeiro().'</a>';
					$stringGrupo .= $linkDeQuemEstaLogado;

					$contador++;
				}
			}
			$somenteNumerosDaEntidade = false;
			if($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === EntidadeTipo::subEquipe){
				$somenteNumerosDaEntidade = true;
			}
			$stringGrupo .= ' - '.$grupoSelecionado->getEntidadeAtiva()->infoEntidade($somenteNumerosDaEntidade);
			$stringOrdem = $stringGrupo . $stringOrdem;
			if($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()){
				$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
			}else{
				break;
			}
		}
		$html .= $stringOrdem;
		return $html;
	}

	public function setCaminho($caminho){
		$this->caminho = $caminho;
	}

	public function getCaminho(){
		return $this->caminho;
	}
}
