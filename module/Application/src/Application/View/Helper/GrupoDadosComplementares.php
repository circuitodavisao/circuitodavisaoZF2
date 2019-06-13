<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\EntidadeTipo;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: GrupoDadosComplementares.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar os dados complementares do cadastro de grupo
 */
class GrupoDadosComplementares extends AbstractHelper {

	protected $form;

	public function __construct() {

	}

	public function __invoke($form) {
		$this->setForm($form);
		return $this->renderHtml();
	}

	public function renderHtml() {
		$html = '';
		$tipoEntidade = $this->view->tipoEntidade;

		/* se quem estiver for maior que igreja aparecera mais opções */
		if($tipoEntidade !== EntidadeTipo::coordenacao
			&& $tipoEntidade !== EntidadeTipo::regiao
			&& $tipoEntidade !== EntidadeTipo::nacional
			&& $tipoEntidade !== EntidadeTipo::presidencial){

				$nomeDoGrupo = '';
				$stringNome = 'Nome';
				$stringNumero = 'Número';        
				switch ($tipoEntidade) {
				case 5:
					$nomeDoGrupo = $stringNome . ' da Equipe';
					break;
				case 6:
					$nomeDoGrupo = $stringNumero . ' da Subequipe';
					break;
				case 7:
					$nomeDoGrupo = $stringNumero . ' da Subequipe';
					break;
				default:
					break;
				}

				/* Verificando o tipo de entidade */
				$mostrarBotao = Constantes::$CLASS_HIDDEN;
				$tipoDadosComplementar = 0;

				/* Numero da entidade abaixo */
				if ($tipoEntidade === EntidadeTipo::subEquipe ||
					$tipoEntidade === EntidadeTipo::equipe) {
						/* Selecionar Numeracao */
						$html .= '<label class = "field-label">' . $this->view->translate(Constantes::$TRADUCAO_SELECIONE_O_NUMERO_DA_SUB_EQUIPE);
						$html .= '</label>';
						$html .= $this->view->formSelect($this->getForm()->get(Constantes::$FORM_NUMERACAO));
						$tipoDadosComplementar = 1;
					}
				/* Nome da entidade abaixo */
				if ($tipoEntidade === EntidadeTipo::igreja) {
					/* Nome Entidade */
					$html .= '<label class="field-label">' . $nomeDoGrupo . '</label>';
					$html .= $this->view->formInput($this->getForm()->get(Constantes::$FORM_NOME_ENTIDADE));

					$mostrarBotao = '';
					$tipoDadosComplementar = 2;
				}

		$html .= '<div class="mt10">';

		$html .= '<div id="divInserirAlterarDadosComplementares" class="' . $mostrarBotao . '">';
		$html .= '<div id="divBotaoInserirSelectDadosComplementares">';
		$html .= $this->view->botaoLink(Constantes::$TRADUCAO_INSERIR, Constantes::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ', true)'));
		$html .= '</div>';

		$html .= '<div id = "divBotaoAlterarSelectDadosComplementares" class="hidden">';
		$html .= $this->view->botaoLink(Constantes::$TRADUCAO_ALTERAR, Constantes::$STRING_HASHTAG, 7, $this->view->funcaoOnClick('botaoAbreDadosComplementares(' . $tipoDadosComplementar . ', false)'));
		$html .= '</div>';
		$html .= '</div>';

		$html .= $this->view->botaoLink(Constantes::$TRADUCAO_VOLTAR, Constantes::$STRING_HASHTAG, 8, $this->view->funcaoOnClick('botaoVoltarDadosComplementares()'));

		$html .= '</div>';
			}else{
				$html .= '<div id="divSelecionarEntidadeTipo">';
				$html .= '<select id="idEntidadeTipo" name="idEntidadeTipo" class="form-control" onChange="mostrarBotaoSelecionarEntidadeTipo();">';
				$html .= '<option value="0">'.$this->view->translate(Constantes::$TRADUCAO_SELECIONE).'</option>';
				if($entidadeTipos = $this->view->entidadeTipos){
					foreach($entidadeTipos as $entidadeTipo){
						if($entidadeTipo->getId() !== EntidadeTipo::subEquipe
							&& $entidadeTipo->getId() !== EntidadeTipo::equipe
							&& $entidadeTipo->getId() !== EntidadeTipo::presidencial
							&& $entidadeTipo->getId() !== EntidadeTipo::nacional){

								$mostrar = false;
								if(($tipoEntidade === EntidadeTipo::nacional || $tipoEntidade === EntidadeTipo::regiao || $tipoEntidade === EntidadeTipo::presidencial) 

									&& ($entidadeTipo->getId() === EntidadeTipo::regiao 
									|| $entidadeTipo->getId() === EntidadeTipo::coordenacao
									|| $entidadeTipo->getId() === EntidadeTipo::igreja)){
										$mostrar = true;
									}	
								if(($tipoEntidade === EntidadeTipo::coordenacao) 
									&& ($entidadeTipo->getId() === EntidadeTipo::coordenacao 
									|| $entidadeTipo->getId() === EntidadeTipo::igreja)){
										$mostrar = true;
									}
							
								if($mostrar){
									$html .= '<option value="'.$entidadeTipo->getId().'">'.$entidadeTipo->getNome().'</option>';
								}
							}
					}
				}
				$html .= '</select>';
				$html .= '<div id="divBotaoSelecionarEntidadeTipo" class="hidden mt10">';
				$html .= $this->view->botaoSimples(Constantes::$TRADUCAO_SELECIONAR, $this->view->funcaoOnClick('selecionarEntidadeTipo()'));
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div id="divPreencherDadosComplementares" class="hidden">';

				$html .= '<div id="divPreencherNumeracaoEntidade" class="hidden">';
				$html .= '<label class = "field-label">' . $this->view->translate(Constantes::$TRADUCAO_SELECIONE_O_NUMERO_DA_SUB_EQUIPE);
				$html .= '</label>';
				$html .= $this->view->formSelect($this->getForm()->get(Constantes::$FORM_NUMERACAO));

				$html .= '<div class="mt10">';
				$funcaoInserirDadosComplementares = $this->view->funcaoOnClick('inserirNumeroEntidade()');
				$html .= $this->view->botaoLink(Constantes::$TRADUCAO_SELECIONAR, Constantes::$STRING_HASHTAG, 7, $funcaoInserirDadosComplementares);
				$html .= '</div>';

				$html .= '</div>';

				$html .= '<div id="divPreencherNomeEntidade" class="hidden">';
				$html .= '<label class="field-label">Nome do Time</label>';
				$html .= $this->view->formInput($this->getForm()->get(Constantes::$FORM_NOME_ENTIDADE));

				$html .= '<div class="mt10">';
				$funcaoInserirDadosComplementares = $this->view->funcaoOnClick('inserirNomeEntidade()');
				$html .= $this->view->botaoLink(Constantes::$TRADUCAO_SELECIONAR, Constantes::$STRING_HASHTAG, 7, $funcaoInserirDadosComplementares);
				$html .= '</div>';

				$html .= '</div>';

			}


		return $html;
	}

	function getForm() {
		return $this->form;
	}

	function setForm($form) {
		$this->form = $form;
	}

}
