<?php

namespace Cadastro\Form;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
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
            ConstantesForm::$FORM_ACTION => ConstantesCadastro::$FORM_ACTION_CADASTRO_GRUPO_FINALIZAR,
        ));

        Endereco::MontaEnderecoFormulario($this);

        /* DDD */
        $this->add(
                (new Number())
                        ->setName(ConstantesCadastro::$FORM_INPUT_DDD)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesCadastro::$FORM_INPUT_DDD,
                        ])
        );

        /* Celular */
        $this->add(
                (new Number())
                        ->setName(ConstantesCadastro::$FORM_INPUT_CELULAR)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesCadastro::$FORM_INPUT_CELULAR,
                        ])
        );

        /* Codigo Verificador */
        $this->add(
                (new Number())
                        ->setName(ConstantesCadastro::$FORM_INPUT_CODIGO_VERIFICADOR)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesCadastro::$FORM_INPUT_CODIGO_VERIFICADOR,
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
                        ->setName(ConstantesForm::$FORM_ID)
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_ID,
                        ])
                        ->setValue($idPessoa)
        );
    }

}
