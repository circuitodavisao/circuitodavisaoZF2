<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: LoginForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para recuperar acesso
 */
class RecuperarAcessoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));

        /**
         * Radio para saber o que o usuario quer
         */
        $spanRadio = ' ';
        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(Constantes::$INPUT_OPCAO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_OPCAO,
                            Constantes::$FORM_STRING_CLASS => 'opcao',
                            Constantes::$FORM_STRING_ONCLICK => 'abrirContinuar();',
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 1,
                                    Constantes::$FORM_STRING_LABEL => Constantes::$TRADUCAO_ESQUECI_MINHA_SENHA,
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 2,
                                    Constantes::$FORM_STRING_LABEL => Constantes::$TRADUCAO_ESQUECI_MEU_USUARIO,
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption)
                                ),
                            ),
                        ])
        );

        /**
         * Usuário de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_USUARIO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_USUARIO,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_USUARIO_PLACEHOLDER,
                        ])
        );

        /**
         * CPF do usuario
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_CPF)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_CPF,
                            Constantes::$FORM_STRING_PLACEHOLDER => 'XX',
                            Constantes::$FORM_STRING_MAXLENGTH => 2,
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
    }

}
