<?php

namespace Login\Form;

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
        ));

        /**
         * Email de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName('email')
                        ->setAttributes([
                            'class' => 'form-control',
                            'id' => 'email',
                            'placeholder' => 'Email do usuario',
                        ])
        );

        /**
         * Senha de acesso
         * Elemento do tipo text
         */
        $this->add(
                (new Password())
                        ->setName('senha')
                        ->setAttributes([
                            'class' => 'form-control',
                            'id' => 'senha',
                            'placeholder' => 'Senha',
                        ])
        );
    }

}
