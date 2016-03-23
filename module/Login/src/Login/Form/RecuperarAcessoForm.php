<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Captcha\Dumb;
<<<<<<< HEAD
use Zend\Captcha\Image;
=======
>>>>>>> 6a3c7e5dd366cfa453ea8a8ddf37290657593110
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
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        /**
         * Radio para saber o que o usuario quer
         */
        $this->add(
                (new Radio())
                        ->setName(Constantes::$INPUT_OPCAO)
                        ->setAttributes([
                            'id' => Constantes::$INPUT_OPCAO,
                        ])
                        ->setOptions([
                            'value_options' => array(
                                1 => Constantes::$TRADUCAO_ESQUECI_MINHA_SENHA,
                                2 => Constantes::$TRADUCAO_ESQUECI_MEU_USUARIO,
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
                            'id' => Constantes::$INPUT_BOTAO_CANCELAR,
                            'onClick' => 'location.href=\'' . Constantes::$INDEX . '\'',
                            'class' => 'btn btn-info',
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
                            'id' => Constantes::$INPUT_BOTAO_CONTINUAR,
                            'onClick' => 'location.href=\'#\'',
                            'class' => 'btn btn-primary',
                        ])
                        ->setValue(Constantes::$TRADUCAO_CONTINUAR)
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
                            'id' => Constantes::$INPUT_EMAIL,
                            'placeholder' => Constantes::$TRADUCAO_USUARIO_PLACEHOLDER,
                            'onkeypress' => 'capsLock(event)',
                        ])
        );

        /**
         * Captcha de validacao de acesso
         */
<<<<<<< HEAD
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
=======
        $captcha = new Captcha(Constantes::$INPUT_CAPTCHA);
        $captcha->setCaptcha(new Dumb());
        $captcha->setLabel(Constantes::$TRADUCAO_CAPTCHA_LABEL);
        $this->add($captcha);
>>>>>>> 6a3c7e5dd366cfa453ea8a8ddf37290657593110

        /**
         * Botao enviar email
         */
        $this->add(
                (new Submit())
                        ->setName(Constantes::$INPUT_BOTAO_ENVIAR_EMAIL)
                        ->setValue(Constantes::$TRADUCAO_ENVIAR_EMAIL)
                        ->setAttributes([
                            'id' => Constantes::$INPUT_BOTAO_ENVIAR_EMAIL,
                            'class' => 'btn btn-success',
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
                            'id' => Constantes::$INPUT_CPF,
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
                            'id' => Constantes::$INPUT_DATA_NASCIMENTO,
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
                            'id' => Constantes::$INPUT_BOTAO_VERIFICAR_USUARIO,
                            'class' => 'btn btn-success',
                        ])
        );
    }

}
