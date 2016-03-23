<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Form\Element;
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
            'method' => 'POST',
            'class' => 'form-horizontal',
            'id' => 'contact',
        ));

        /**
         * Email de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_EMAIL)
                        ->setAttributes([
                            'class' => 'gui-input',
                            'id' => 'email',
                            'placeholder' => Constantes::$TRADUCAO_USUARIO_PLACEHOLDER,
                            'required' => 'required',
                            'onkeypress' => 'capsLock(event)',
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
                            'class' => 'gui-input',
                            'id' => 'senha',
                            'placeholder' => Constantes::$TRADUCAO_SENHA_PLACEHOLDER,
                            'required' => 'required',
                            'onkeypress' => 'capsLock(event)',
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        $send = new Element(Constantes::$INPUT_ENTRAR);
        $send->setValue(Constantes::$TRADUCAO_ENTRAR);
        $send->setName('entrar');
        $send->setAttributes(array(
            'type' => 'submit',
            'class' => 'button btn-primary-circuito mr10 pull-right',
        ));
        $this->add($send);
    }

}
