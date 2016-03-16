<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Captcha\Dumb;
use Zend\Form\Element\Button;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Radio;
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
                                1 => ' Esqueci minha senha? ',
                                2 => ' Sei minha senha mas sou bizonho ',
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
                            'class' => 'btn btn-info',
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
                            'required' => 'required',
                            'onkeypress' => 'capsLock(event)',
                        ])
        );

        /**
         * Captcha de validacao de acesso
         */
        $captcha = new Captcha(Constantes::$INPUT_CAPTCHA);
        $captcha->setCaptcha(new Dumb());
        $captcha->setLabel(Constantes::$TRADUCAO_CAPTCHA_LABEL);
        $this->add($captcha);
    }

}
