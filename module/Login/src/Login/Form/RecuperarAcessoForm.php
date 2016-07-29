<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Captcha\Image;
use Zend\Form\Element\Button;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Submit;
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
            Constantes::$FORM_STRING_CLASS => 'form-horizontal',
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
         * Botao de cancelar
         */
        $this->add(
                (new Button())
                        ->setName(Constantes::$INPUT_BOTAO_CANCELAR)
                        ->setLabel(Constantes::$TRADUCAO_CANCELAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_CANCELAR,
                            Constantes::$FORM_STRING_ONCLICK => 'location.href=\'' . Constantes::$INDEX . '\'',
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_BTN_DEFAULT_ESCURO,
                            'data-style' => 'zoom-in',
                        ])
                        ->setValue(Constantes::$TRADUCAO_CANCELAR)
        );
        /**
         * Botao de continuar
         */
        $this->add(
                (new Button())
                        ->setName(Constantes::$INPUT_BOTAO_CONTINUAR)
                        ->setLabel(Constantes::$TRADUCAO_CONTINUAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_CONTINUAR,
                            Constantes::$FORM_STRING_ONCLICK => 'abrirOpcao();',
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_BTN_PRETO,
                            'data-style' => 'zoom-in',
                        ])
                        ->setValue(Constantes::$TRADUCAO_CONTINUAR)
        );


        /**
         * Botao de voltar
         */
        $this->add(
                (new Button())
                        ->setName(Constantes::$INPUT_BOTAO_VOLTAR)
                        ->setLabel(Constantes::$TRADUCAO_VOLTAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_VOLTAR,
                            Constantes::$FORM_STRING_ONCLICK => 'abrirOpcao(0);',
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_BTN_DEFAULT_ESCURO,
                            'data-style' => 'zoom-in',
                        ])
                        ->setValue(Constantes::$TRADUCAO_VOLTAR)
        );

        /**
         * Email de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_EMAIL)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_EMAIL,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_USUARIO_PLACEHOLDER,
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                            Constantes::$FORM_STRING_ONKEYPRESS => Constantes::$FORM_STRING_FUNCAO_CAPSLOCK,
                        ])
        );

        /**
         * Captcha de validacao de acesso
         */
        $captcha = new Image(array(
            'expiration' => '300',
            'wordlen' => '1',
            'font' => 'public/fonts/arial.ttf',
            'fontSize' => '20',
            'imgDir' => 'public/captcha',
            'imgUrl' => '/captcha'
        ));
        $this->add(
                (new Captcha())
                        ->setName(Constantes::$INPUT_CAPTCHA)
                        ->setOptions(
                                array(
                                    Constantes::$FORM_STRING_LABEL => 'Verification',
                                    'captcha' => $captcha,
                        ))
        );

        /**
         * Botao enviar email
         */
        $this->add(
                (new Submit())
                        ->setName(Constantes::$INPUT_BOTAO_ENVIAR_EMAIL)
                        ->setValue(Constantes::$TRADUCAO_ENVIAR_EMAIL)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_ENVIAR_EMAIL,
                            Constantes::$FORM_STRING_CLASS => 'button btn-primary-circuito',
                            Constantes::$FORM_STRING_ONCLICK => str_replace('#id', Constantes::$INPUT_BOTAO_ENVIAR_EMAIL, Constantes::$FORM_STRING_FUNCAO_DESABILITAR_ELEMENTO),
                        ])
        );

        /**
         * CPF do usuario
         * Elemento do tipo text
         */
        $funcaoOnKeyUpCPF1 = str_replace('#idIcone', 'idIconeDigitoCPFEsqueciEmail', Constantes::$FORM_STRING_FUNCAO_VALIDAR_DIGITOS_CPF);
        $funcaoOnKeyUpCPF = str_replace('#idBotaoSubmit', Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO, $funcaoOnKeyUpCPF1);
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_CPF)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_CPF,
                            Constantes::$FORM_STRING_PLACEHOLDER => 'XX',
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                            Constantes::$FORM_STRING_MAXLENGTH => 2,
                            Constantes::$FORM_STRING_ONKEYUP => $funcaoOnKeyUpCPF,
                        ])
        );

        /**
         * Data Nascimento do usuario
         * Elemento do tipo text
         */
        $funcaoOnKeyUpDataNascimento1 = str_replace('#idIcone', 'idIconeDataNascimentoEsqueciEmail', Constantes::$FORM_STRING_FUNCAO_VALIDAR_DATA_NASCIMENTO);
        $funcaoOnKeyUpDataNascimento = str_replace('#idBotaoSubmit', Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO, $funcaoOnKeyUpDataNascimento1);
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_DATA_NASCIMENTO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => 'form-control date',
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_DATA_NASCIMENTO,
                            Constantes::$FORM_STRING_PLACEHOLDER => 'XX/XX/XXXX',
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                            Constantes::$FORM_STRING_MAXLENGTH => 10,
                            Constantes::$FORM_STRING_ONKEYUP => $funcaoOnKeyUpDataNascimento,
                        ])
        );

        /**
         * Botao verificar acesso
         */
        $this->add(
                (new Submit())
                        ->setName(Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO)
                        ->setValue(Constantes::$TRADUCAO_VERIFICAR_USUARIO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO,
                            Constantes::$FORM_STRING_CLASS => 'button btn-primary-circuito',
                            Constantes::$FORM_STRING_DISABLED => Constantes::$FORM_STRING_DISABLED,
                            Constantes::$FORM_STRING_ONCLICK => str_replace('#id', Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO, Constantes::$FORM_STRING_FUNCAO_DESABILITAR_ELEMENTO),
                        ])
        );
    }

}
