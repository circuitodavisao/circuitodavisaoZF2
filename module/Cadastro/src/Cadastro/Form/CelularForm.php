<?php

namespace Cadastro\Form;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Entidade\Entity\GrupoAluno;
use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Form;

/**
 * Nome: CelularForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para atualizar o ddd + celular
 */
class CelularForm extends Form {

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

        /* DDD */
        $this->add(
                (new Number())
                        ->setName(ConstantesCadastro::$FORM_INPUT_DDD)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesCadastro::$FORM_INPUT_DDD,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesCadastro::$TRADUCAO_DDD,
                        ])
        );

        /* Celular */
        $this->add(
                (new Number())
                        ->setName(ConstantesCadastro::$FORM_INPUT_CELULAR)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesCadastro::$FORM_INPUT_CELULAR,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesCadastro::$TRADUCAO_CELULAR,
                        ])
        );

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
