<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;

/**
 * Nome: RevisaoForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar Fato Discipulado.            
 *              
 */
class FatoDiscipuladoForm extends Form {

	/**
	 * Contrutor
	 * @param String $name
	 */
	public function __construct($grupoEventoDiscipulado, $tradutor) {
		parent::__construct($name = null);
		/**
		 * Configuração do formulário
		 */
		$this->setAttributes(array(
			Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
			'action' => 'lancamentoDiscipuladoFinalizar',
		));

		$arraySelect = array();
		$arraySelect[0] = Constantes::$FORM_SELECT;
		foreach($grupoEventoDiscipulado as $grupoEvento){
			$arraySelect[$grupoEvento->getId()] = 'Nome: '. $grupoEvento->getEvento()->getNome(). ' - Dia: '.$tradutor(Funcoes::diaDaSemanaPorDia($grupoEvento->getEvento()->getDia(),1)).' Hora: '.$grupoEvento->getEvento()->getHora();
		}
		$inputSelectNota = new Select();
		$inputSelectNota->setName('idGrupoEvento');
		$inputSelectNota->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
		));
		$inputSelectNota->setValueOptions($arraySelect);
		$this->add($inputSelectNota);

		for ($indiceInputs = 1; $indiceInputs <= 5; $indiceInputs++) {
			$input = '';
			switch ($indiceInputs) {
			case 1:$input = 'lanche'; break;
			case 2:$input = 'avisos'; break;
			case 3:$input = 'administrativo'; break;
			case 4:$input = 'oracao'; break;
			case 5:$input = 'palavra'; break;
			}
			$arraySelect = array();
			$arraySelect['selecione'] = Constantes::$FORM_SELECT;
			for ($indiceNota = 0; $indiceNota <= 5; $indiceNota++) {
				$arraySelect[$indiceNota] = $indiceNota;
			}
			$inputSelectNota = new Select();
			$inputSelectNota->setName($input);
			$inputSelectNota->setAttributes(array(
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			));
			$inputSelectNota->setValueOptions($arraySelect);
			$this->add($inputSelectNota);
		}
		$this->add(
			(new Textarea())
			->setName('observacao')
			->setAttributes(array(
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			))
		);
		$this->add(
			(new Csrf())
			->setName(Constantes::$INPUT_CSRF)
		);
	}

}
