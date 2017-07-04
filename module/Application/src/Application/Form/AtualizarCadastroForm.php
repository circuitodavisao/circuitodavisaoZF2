<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Form;

/**
 * Nome: AtualizarCadastroForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para finalizar o cadastro
 */
class AtualizarCadastroForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param int $idPessoa
     */
    public function __construct($name = null, $idPessoa) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
            Constantes::$FORM_ACTION => Constantes::$FORM_ACTION_CADASTRO_GRUPO_FINALIZAR,
        ));

        Endereco::MontaEnderecoFormulario($this);

        /* DDD */
        $this->add(
                (new Number())
                        ->setName(Constantes::$FORM_INPUT_DDD)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_INPUT_DDD,
                        ])
        );

        /* Celular */
        $this->add(
                (new Number())
                        ->setName(Constantes::$FORM_INPUT_CELULAR)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_INPUT_CELULAR,
                        ])
        );

        /* Codigo Verificador */
        $this->add(
                (new Number())
                        ->setName(Constantes::$FORM_INPUT_CODIGO_VERIFICADOR)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_INPUT_CODIGO_VERIFICADOR,
                        ])
        );

        /* CSRF */
        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        /* Id */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$FORM_ID)
                        ->setAttributes([
                            Constantes::$FORM_ID => Constantes::$FORM_ID,
                        ])
                        ->setValue($idPessoa)
        );
    }

}
