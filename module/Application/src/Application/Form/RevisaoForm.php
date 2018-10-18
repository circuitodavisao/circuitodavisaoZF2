<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Evento;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: RevisaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar revisao
 */
class RevisaoForm extends Form {

	/**
	 * Contrutor
	 * @param String $name
	 * @param GrupoAluno $alunos
	 */
	public function __construct($name = null, Evento $evento = null) {
		parent::__construct($name);

		/**
		 * Configuração do formulário
		 */
		$this->setAttributes(array(
			Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
			Constantes::$FORM_ACTION => '/cadastroRevisaoFinalizar',
		));

		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_ID)
		);

		$this->add(
			(new Csrf())
			->setName(Constantes::$INPUT_CSRF)
		);

		/* Dia da data de nascimento */
		$arrayDiaDataNascimento = array();
		$arrayDiaDataNascimento[0] = Constantes::$TRADUCAO_DIA;
		for ($indiceDiaDoMes = 1; $indiceDiaDoMes <= 31; $indiceDiaDoMes++) {
			$numeroAjustado = str_pad($indiceDiaDoMes, 2, 0, STR_PAD_LEFT);
			$arrayDiaDataNascimento[$indiceDiaDoMes] = $numeroAjustado;
		}
		$inputSelectDiaDataNascimento = new Select();
		$inputSelectDiaDataNascimento->setName(Constantes::$FORM_INPUT_DIA);
		$inputSelectDiaDataNascimento->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			Constantes::$FORM_ID => Constantes::$FORM_INPUT_DIA,
		));
		$inputSelectDiaDataNascimento->setValueOptions($arrayDiaDataNascimento);
		$this->add($inputSelectDiaDataNascimento);

		/* Mês da data de nascimento */
		$arrayMesDataNascimento = array();
		$arrayMesDataNascimento[0] = Constantes::$TRADUCAO_MES;
		for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
			$numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
			$arrayMesDataNascimento[$indiceMesNoAno] = $numeroAjustado;
		}
		$inputSelectMesDataNascimento = new Select();
		$inputSelectMesDataNascimento->setName(Constantes::$FORM_INPUT_MES);
		$inputSelectMesDataNascimento->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			Constantes::$FORM_ID => Constantes::$FORM_INPUT_MES,
		));
		$inputSelectMesDataNascimento->setValueOptions($arrayMesDataNascimento);
		$this->add($inputSelectMesDataNascimento);

		/* Ano da data de nascimento */
		$arrayAnoDataNascimento = array();
		$arrayAnoDataNascimento[0] = Constantes::$TRADUCAO_ANO;
		$anoAtual = date('Y');
		for ($indiceAno = $anoAtual; $indiceAno <= ($anoAtual + 1); $indiceAno++) {
			$arrayAnoDataNascimento[$indiceAno] = $indiceAno;
		}
		$inputSelectAnoDataNascimento = new Select();
		$inputSelectAnoDataNascimento->setName(Constantes::$FORM_INPUT_ANO);
		$inputSelectAnoDataNascimento->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			Constantes::$FORM_ID => Constantes::$FORM_INPUT_ANO,
		));
		$inputSelectAnoDataNascimento->setValueOptions($arrayAnoDataNascimento);
		$this->add($inputSelectAnoDataNascimento);

		$this->add(
			(new Text())
			->setName(Constantes::$FORM_NOME)
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => Constantes::$FORM_NOME,
				Constantes::$FORM_PLACEHOLDER => 'Não Obrigatório',
			])
		);

		if($evento){
			$this->get(Constantes::$FORM_ID)->setValue($evento->getId());
			$explodeData = explode('-', $evento->getData());
			$this->get(Constantes::$FORM_INPUT_DIA)->setValue($explodeData[2]);
			$this->get(Constantes::$FORM_INPUT_MES)->setValue($explodeData[1]);
			$this->get(Constantes::$FORM_INPUT_ANO)->setValue($explodeData[0]);
			$this->get(Constantes::$FORM_NOME)->setValue($evento->getNome());
		}
	}

}
