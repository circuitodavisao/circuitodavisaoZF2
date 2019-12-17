<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Entidade;
use Application\Model\Entity\Pessoa;
use Application\Controller\LancamentoController;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de fichas do revisão
 */
class ListagemFichasParaRevisao extends AbstractHelper {

	public function __construct() {

	}

	public function __invoke() {
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';
		$pessoas = array();
		$lideres = array();
		$arrayDeEventos = Array();
		if($this->view->multiplos){
			$arrayDeEventos = $this->view->arrayDeEventosRevisoes;
		} else {
			$arrayDeEventos[] = $this->view->evento;
		}
		foreach ($arrayDeEventos as $evento) {
			$frequencias = $evento->getEventoFrequencia();
			if (count($frequencias) > 0) {
				foreach ($frequencias as $frequencia) {
					$revisionista = $frequencia->getPessoa();
					LancamentoController::ajustarPessoa($frequencia->getPessoa()->getId(), $this->view->repositorioORM);
					$pessoa = new Pessoa();
					$pessoa->setId($frequencia->getId());
					$pessoa->setNome($revisionista->getNome());                      
					$pessoa->setSexo($revisionista->getSexo());
					$pessoa->setData_nascimento($revisionista->getData_nascimento());
					if ($frequencia->getFrequencia() == 'S') {
						$pessoa->setNoRevisao(true);
					}

					if ($grupoPessoa = $revisionista->getGrupoPessoaAtivo()) {
						$pessoa->setGrupoPessoa($grupoPessoa);
						$pessoa->setEntidade($grupoPessoa->getGrupo()->getEntidadeAtiva());
						$pessoas[] = $pessoa;
					}else{
						$grupo = $revisionista->getResponsabilidadesAtivas()[0]->getGrupo();
						$infoEntidade = $grupo->getEntidadeAtiva()->infoEntidade();
						$nomeIgreja = $grupo->getGrupoIgreja()->getEntidadeAtiva()->getNome();
						$pessoa->setGrupoPessoa($nomeIgreja);
						$pessoa->setEntidade($infoEntidade);
						$lideres[] = $pessoa;
					}
				}
			}
		}

		$html .= $this->view->templateFormularioTopo('Fichas do Revisionista/Líderes');
		$html .= '<div class="panel-body bg-light">';
		/* Sem pessoas cadastrados */
		if (count($pessoas) == 0) {
			$html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Revisionistas Cadastrados!</div>';
		} else {
			$html .= '<div class="alert alert-info">Revisionistas</div>';
			$html .= '<input id="fooFilter" type="text" class="form-control" placeholder="Filtro">';
			$html .= '<table class="table table-condensed table-hover bg-light mt15 footable" data-filter="#fooFilter">';
			$html .= '<thead>';
			$html .= '<tr>';

			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_MATRICULA);
			$html .= '</th>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
			$html .= '</th>';

			$html .= '<th class="text-center">Sexo</th>';
			$html .= '<th class="text-center">Igreja</th>';
			$html .= '<th class="text-center">Time</th>';
			$html .= '<th class="text-center">Idade</th>';
			$html .= '<th class="text-center">Ativo no Revisão</th>';
			$html .= '<td></td>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach ($pessoas as $pessoa) {                
				if($this->view->multiplos){ 
					$entidadeAcima = $pessoa->getGrupoPessoa()->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::EQUIPE || 
						$entidadeAcima->getEntidadeTipo()->getId() === Entidade::IGREJA                         
					){
						$nomeEntidadeAcimaArrumado = ' - ' . $entidadeAcima->getNome();                    
					}
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE){                                  
						$nomeEntidadeAcimaArrumado = ' - ' . $entidadeAcima->infoEntidade();                    
					}
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::COORDENACAO){                                  
						$nomeEntidadeAcimaArrumado = ' - COORDENAÇÃO: ' . $entidadeAcima->getNumero();                    
					}  
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::REGIONAL){                                  
						$nomeEntidadeAcimaArrumado = ' - REGIÃO: ' . $entidadeAcima->getNome();                    
					}               
				}
				$sexo = $pessoa->getSexo();
				if(!$sexo) {
					$sexo = 'Não Informado';
				}
				$html .= '<tr>';
				$html .= '<td class="text-center">' . $pessoa->getId() . '</td>';
				$html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';
				$html .= '<td class="text-center">' . $sexo . '</td>';                
				$html .= '<td class="text-center">' . $pessoa->getGrupoPessoa()->getGrupo()->getGrupoIgreja()->getEntidadeAtiva()->getNome() . '</td>';                
				$html .= '<td class="text-center">' . $pessoa->getEntidade()->infoEntidade() . $nomeEntidadeAcimaArrumado . '</td>';
				$html .= '<td class="text-center">' . $pessoa->getIdade() . '</td>';
				$html .= '<td class="text-center">';
				if ($pessoa->getNoRevisao()) {
					$html .= '<span class="label label-success">ATIVO NO REVIS&Atilde;O</span>';
				}
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$stringNomeDaFuncaoOnClickInserir = 'funcaoCircuito("cadastro' . Constantes::$PAGINA_FICHA_REVISAO . '", ' . $pessoa->getId() . ', true)';
				$html .= $this->view->botaoLink('Ficha', Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
				$html .= '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
		}

		if (count($lideres) == 0) {
			$html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem Líderes Cadastrados!</div>';
		} else {
			$html .= '<div class="alert alert-info">Líderes</div>';
			$html .= '<input id="fooFilter1" type="text" class="form-control" placeholder="Filtro">';
			$html .= '<table class="table table-condensed table-hover bg-light mt15 footable" data-filter="#fooFilter1">';
			$html .= '<thead>';
			$html .= '<tr>';

			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_MATRICULA);
			$html .= '</th>';
			$html .= '<th class="text-center">';
			$html .= $this->view->translate(Constantes::$TRADUCAO_NOME_REVISIONISTA);
			$html .= '</th>';

			$html .= '<th class="text-center">Igreja</th>';
			$html .= '<th class="text-center">Time</th>';
			$html .= '<th class="text-center">Ativo no Revisão</th>';
			$html .= '<td></td>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			foreach ($lideres as $pessoa) {                
				if($this->view->multiplos){ 
					$entidadeAcima = $pessoa->getGrupoPessoa()->getGrupo()->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai()->getEntidadeAtiva();
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::EQUIPE || 
						$entidadeAcima->getEntidadeTipo()->getId() === Entidade::IGREJA                         
					){
						$nomeEntidadeAcimaArrumado = ' - ' . $entidadeAcima->getNome();                    
					}
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE){                                  
						$nomeEntidadeAcimaArrumado = ' - ' . $entidadeAcima->infoEntidade();                    
					}
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::COORDENACAO){                                  
						$nomeEntidadeAcimaArrumado = ' - COORDENAÇÃO: ' . $entidadeAcima->getNumero();                    
					}  
					if($entidadeAcima->getEntidadeTipo()->getId() === Entidade::REGIONAL){                                  
						$nomeEntidadeAcimaArrumado = ' - REGIÃO: ' . $entidadeAcima->getNome();                    
					}               
				}
				$html .= '<tr>';
				$html .= '<td class="text-center">' . $pessoa->getId() . '</td>';
				$html .= '<td class="text-center"><span class="visible-lg visible-md">' . $pessoa->getNome() . '</span><span class="visible-sm visible-xs">' . $pessoa->getNomePrimeiroUltimo() . '</span></td>';
				$html .= '<td class="text-center">' . $pessoa->getGrupoPessoa() . '</td>';                
				$html .= '<td class="text-center">' . $pessoa->getEntidade() . '</td>';
				$html .= '<td class="text-center">';
				if ($pessoa->getNoRevisao()) {
					$html .= '<span class="label label-success">ATIVO NO REVIS&Atilde;O</span>';
				}
				$html .= '</td>';
				$html .= '<td class="text-center">';
				$html .= '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';

		}
		$html .= '</div>';
		/* Fim panel-body */
		$html .= '<div class="panel-footer text-right">';
		/* Botões */
		$stringNomeDaFuncaoOnClickCadastro = 'funcaoCircuito("cadastro' . Constantes::$PAGINA_FICHA_REVISIONISTAS . '", ' . $pessoa->getId() . ')';
		$html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
		/* Fim Botões */
		$html .= '</div>';
		/* Fim panel-footer */

		$html .= $this->view->templateFormularioRodape();
		return $html;
	}

}
