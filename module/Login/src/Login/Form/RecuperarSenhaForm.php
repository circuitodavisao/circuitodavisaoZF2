<?php

namespace Login\Form;

use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

/**
 * Nome: RecuperarSenhaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para recuperar senha
 */
class RecuperarSenhaForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, $idPessoa) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
            Constantes::$FORM_STRING_CLASS => 'form-horizontal',
        ));


        /**
         * Senha nova
         * Elemento do tipo text
         */
        $this->add(
                (new Password())
                        ->setName(Constantes::$INPUT_SENHA)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_SENHA,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_NOVA_SENHA_PLACEHOLDER,
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                            Constantes::$FORM_STRING_ONKEYUP => str_replace('#tipo', 1, Constantes::$FORM_STRING_FUNCAO_VERIFICAR_SENHAS),
                        ])
        );

        /**
         * Repetir Senha
         * Elemento do tipo text
         */
        $this->add(
                (new Password())
                        ->setName(Constantes::$INPUT_REPETIR_SENHA)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_REPETIR_SENHA,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_REPETIR_SENHA_PLACEHOLDER,
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                            Constantes::$FORM_STRING_ONKEYUP => str_replace('#tipo', 2, Constantes::$FORM_STRING_FUNCAO_VERIFICAR_SENHAS),
                        ])
        );

        /**
         * Elemento CSRF
         */
        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );


        /**
         * Botao verificar entrar
         */
        $this->add(
                (new Submit())
                        ->setName(Constantes::$INPUT_ALTERAR)
                        ->setValue(Constantes::$TRADUCAO_ALTERAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_ALTERAR,
                            Constantes::$FORM_STRING_CLASS => 'button btn-primary-circuito mr10 pull-right',
                            Constantes::$FORM_STRING_DISABLED => Constantes::$FORM_STRING_DISABLED,
                            Constantes::$FORM_STRING_ONCLICK => str_replace('#id', Constantes::$INPUT_ALTERAR, Constantes::$FORM_STRING_FUNCAO_DESABILITAR_ELEMENTO),
                        ])
        );


        /**
         * Elemento Hidden
         */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$INPUT_ID_PESSOA)
                        ->setValue($idPessoa)
        );
    }

}
