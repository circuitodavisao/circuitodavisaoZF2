<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: LoginForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para login
 */
class LoginForm extends Form {

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
         * Email de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_USUARIO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_USUARIO,
                            Constantes::$FORM_STRING_PLACEHOLDER => 'Informe seu CPF',
                            Constantes::$FORM_STRING_ONKEYPRESS => Constantes::$FORM_STRING_FUNCAO_CAPSLOCK,
                        ])
        );


        /**
         * Senha de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Password())
                        ->setName(Constantes::$INPUT_SENHA)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_SENHA,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_SENHA_PLACEHOLDER,
                            Constantes::$FORM_STRING_ONKEYPRESS => Constantes::$FORM_STRING_FUNCAO_CAPSLOCK,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
