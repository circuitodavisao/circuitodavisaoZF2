<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\GrupoAluno;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: GrupoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar grupo
 */
class GrupoForm extends Form {

	private $hierarquia;

	/**
	 * Contrutor
	 * @param String $name
	 * @param GrupoAluno $alunos
	 */
	public function __construct($name = null, $hierarquia = null, $numeros = null) {
		parent::__construct($name);

		$this->setHierarquia($hierarquia);

		/**
		 * Configuração do formulário
		 */
		$this->setAttributes(array(
			Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
			Constantes::$FORM_ACTION => Constantes::$FORM_ACTION_CADASTRO_GRUPO_FINALIZAR,
		));

		/* IdAlunoSelecionado0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_ID_ALUNO_SELECIONADO . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_ID_ALUNO_SELECIONADO . '0',
			])
		);

		/* IdAlunoSelecionado1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_ID_ALUNO_SELECIONADO . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_ID_ALUNO_SELECIONADO . '1',
			])
		);

		/* IdAlunoSelecionado2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_ID_ALUNO_SELECIONADO . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_ID_ALUNO_SELECIONADO . '2',
			])
		);


		/* Nome0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_NOME . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_NOME . '0',
			])
		);
		/* Nome1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_NOME . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_NOME . '1',
			])
		);
		/* Nome2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_NOME . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_NOME . '2',
			])
		);
		/* email0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_EMAIL . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_EMAIL . '0',
			])
		);
		/* email1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_EMAIL . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_EMAIL . '1',
			])
		);
		/* email2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_EMAIL . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_EMAIL . '2',
			])
		);

		/* cpf0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_CPF . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_CPF . '0',
			])
		);
		/* cpf1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_CPF . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_CPF . '1',
			])
		);
		/* cpf2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_CPF . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_CPF . '2',
			])
		);
		/* hierarquia0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_HIERARQUIA . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_HIERARQUIA . '0',
			])
		);
		/* hierarquia1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_HIERARQUIA . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_HIERARQUIA . '1',
			])
		);
		/* hierarquiaf2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_HIERARQUIA . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_HIERARQUIA . '2',
			])
		);
		/* dataDeNascimento0 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_DATA_NASCIMENTO . '0')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_DATA_NASCIMENTO . '0',
			])
		);
		/* dataDeNascimento1 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_DATA_NASCIMENTO . '1')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_DATA_NASCIMENTO . '1',
			])
		);
		/* dataDeNascimentof2 */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_DATA_NASCIMENTO . '2')
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_DATA_NASCIMENTO . '2',
			])
		);

		/* nome aluno */
		$this->add(
			(new Hidden())
			->setName(Constantes::$FORM_NOME_ALUNO)
			->setAttributes([
				Constantes::$FORM_ID => Constantes::$FORM_NOME_ALUNO,
			])
		);

		/**
		 * Radio Estado Civil
		 */
		$classOption = 'block mt15';
		$this->add(
			(new Radio())
			->setName(Constantes::$INPUT_ESTADO_CIVIL)
			->setAttributes([
				Constantes::$FORM_STRING_ID => Constantes::$INPUT_ESTADO_CIVIL,
				Constantes::$FORM_ONCLICK => 'mostrarBotaoDeProsseguirDoEstadoCivil();',
			])
			->setOptions([
				Constantes::$FORM_STRING_VALUE_OPTIONS => array(
					1 => array(
						Constantes::$FORM_STRING_VALUE => 1,
						Constantes::$FORM_STRING_LABEL => ' Alone',
						Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
					),
					2 => array(
						Constantes::$FORM_STRING_VALUE => 2,
						Constantes::$FORM_STRING_LABEL => ' With the Spouse',
						Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
					),
				),
			])
		);
		$element = $this->get(Constantes::$INPUT_ESTADO_CIVIL);
		$element->setLabelOptions(['disable_html_escape' => true]);

		/* CPF */
		$this->add(
			(new Number())
			->setName(Constantes::$FORM_CPF)
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => Constantes::$FORM_CPF,
				Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_CPF,
				'pattern' => '\d*'
			])
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
		for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 100); $indiceAno--) {
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

		/* NOME */
		$this->add(
			(new Text())
			->setName('nome')
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => 'nome',
				Constantes::$FORM_PLACEHOLDER => 'Nome',
			])
		);

		/* Email */
		$this->add(
			(new Email())
			->setName(Constantes::$FORM_EMAIL)
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => Constantes::$FORM_EMAIL,
				Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_EMAIL,
			])
		);

		/* Repetir Email */
		$this->add(
			(new Email())
			->setName(Constantes::$FORM_REPETIR_EMAIL)
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => Constantes::$FORM_REPETIR_EMAIL,
				Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_REPETIR_EMAIL,
			])
		);

		$this->add(
			(new Csrf())
			->setName(Constantes::$INPUT_CSRF)
		);

		/* Hierarquia */
		$arrayHierarquia = array();
		$arrayHierarquia[0] = Constantes::$TRADUCAO_SELECIONE_A_HIERARQUIA;
		foreach ($this->getHierarquia() as $hierarquia) {
			$arrayHierarquia[$hierarquia->getId()] = $hierarquia->getNome();
		}

		$inputSelectHierarquia = new Select();
		$inputSelectHierarquia->setName(Constantes::$FORM_HIERARQUIA);
		$inputSelectHierarquia->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			Constantes::$FORM_ID => Constantes::$FORM_HIERARQUIA,
			Constantes::$FORM_ONCHANGE => 'mostrarBotaoDeInserirResponsavel(this.value);',
		));
		$inputSelectHierarquia->setValueOptions($arrayHierarquia);
		$this->add($inputSelectHierarquia);

		/* Numeracao */
		$arrayNumeracao = array();
		$arrayNumeracao[0] = Constantes::$FORM_SELECT;
		for ($indiceNumeroSubEquipe = 1; $indiceNumeroSubEquipe <= 48; $indiceNumeroSubEquipe++) {
			$adicionarNumero = true;
			if ($numeros) {
				foreach ($numeros as $numero) {
					if ($indiceNumeroSubEquipe == $numero) {
						$adicionarNumero = false;
					}
				}
			}
			if ($adicionarNumero) {
				$numeroAjustado = str_pad($indiceNumeroSubEquipe, 2, 0, STR_PAD_LEFT);
				$arrayNumeracao[$indiceNumeroSubEquipe] = $numeroAjustado;
			}
		}
		$inputSelectNumeracao = new Select();
		$inputSelectNumeracao->setName(Constantes::$FORM_NUMERACAO);
		$inputSelectNumeracao->setAttributes(array(
			Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
			Constantes::$FORM_ID => Constantes::$FORM_NUMERACAO,
			Constantes::$FORM_ONCHANGE => 'mostrarBotaoDeInserirDadosComplementares(this.value);',
		));
		$inputSelectNumeracao->setValueOptions($arrayNumeracao);
		$this->add($inputSelectNumeracao);

		/* Nome Entidade */
		$this->add(
			(new Text())
			->setName(Constantes::$FORM_NOME_ENTIDADE)
			->setAttributes([
				Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
				Constantes::$FORM_ID => Constantes::$FORM_NOME_ENTIDADE,
			])
		);
	}

	function getHierarquia() {
		return $this->hierarquia;
	}

	function setHierarquia($hierarquia) {
		$this->hierarquia = $hierarquia;
	}

}
