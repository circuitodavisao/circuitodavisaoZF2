<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Captcha\Dumb;
use Zend\Captcha\Image;
use Zend\Form\Element\Button;
use Zend\Form\Element\Captcha;
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
            Constantes::$FORM_STRING_METHOD => 'POST',
            Constantes::$FORM_STRING_CLASS => 'form-horizontal',
        ));

        /**
         * Radio para saber o que o usuario quer
         */
        $spanRadio = '<span class="radio"></span>';
        $classOption = 'option block mt15';
        $this->add(
                (new Radio())
                        ->setName(Constantes::$INPUT_OPCAO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_OPCAO,
                            Constantes::$FORM_STRING_CLASS => 'opcao',
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 1,
                                    Constantes::$FORM_STRING_LABEL => $spanRadio . Constantes::$TRADUCAO_ESQUECI_MINHA_SENHA,
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 2,
                                    Constantes::$FORM_STRING_LABEL => $spanRadio . Constantes::$TRADUCAO_ESQUECI_MEU_USUARIO,
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption)
                                ),
                            ),
                        ])
        );
        $element = $this->get(Constantes::$INPUT_OPCAO);
        $element->setLabelOptions(['disable_html_escape' => true]);

        /**
         * Botao de cancelar
         */
        $this->add(
                (new Button())
                        ->setName(Constantes::$INPUT_BOTAO_CANCELAR)
                        ->setLabel(Constantes::$TRADUCAO_CANCELAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_CANCELAR,
                            'onClick' => 'location.href=\'' . Constantes::$INDEX . '\'',
                            Constantes::$FORM_STRING_CLASS => 'button btn-default',
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
                            'onClick' => 'abrirOpcao();',
                            'class' => 'button btn-primary-circuito',
                        ])
                        ->setValue(Constantes::$TRADUCAO_CONTINUAR)
        );


        /**
         * Botao de voltar
         */
        $this->add(
                (new Button())
                        ->setName(Constantes::$INPUT_BOTAO_VOLTAR)
                        ->setLabel(Constantes::$TRADUCAO_CANCELAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_BOTAO_VOLTAR,
                            'onClick' => 'abrirOpcao(0);',
                            'class' => 'button btn-default',
                        ])
                        ->setValue(Constantes::$TRADUCAO_CANCELAR)
        );

        /**
         * Email de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_EMAIL)
                        ->setAttributes([
                            'class' => 'gui-input',
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_EMAIL,
                            'placeholder' => Constantes::$TRADUCAO_USUARIO_PLACEHOLDER,
                            'onkeypress' => 'capsLock(event)',
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
                                    'label' => 'Verification',
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
                            'class' => 'button btn-primary-circuito',
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
                            'class' => 'gui-input',
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_CPF,
                            'placeholder' => '##',
                        ])
        );

        /**
         * Data Nascimento do usuario
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_DATA_NASCIMENTO)
                        ->setAttributes([
                            'class' => 'gui-input',
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_DATA_NASCIMENTO,
                            'placeholder' => '##/##/####',
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
                            'class' => 'button btn-primary-circuito',
                        ])
        );
    }

}
