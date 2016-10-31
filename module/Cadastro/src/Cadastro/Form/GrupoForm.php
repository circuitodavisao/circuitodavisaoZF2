<?php

namespace Cadastro\Form;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Entidade\Entity\GrupoAluno;
use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Number;
use Zend\Form\Element\Radio;
use Zend\Form\Form;

/**
 * Nome: GrupoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar grupo
 */
class GrupoForm extends Form {

    private $alunos;

    /**
     * Contrutor
     * @param String $name
     * @param GrupoAluno $alunos
     */
    public function __construct($name = null, $alunos = null) {
        parent::__construct($name);

        $this->setAlunos($alunos);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));

        /**
         * Radio Estado Civil
         */
        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(ConstantesCadastro::$INPUT_ESTADO_CIVIL)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => ConstantesCadastro::$INPUT_ESTADO_CIVIL,
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 1,
                                    Constantes::$FORM_STRING_LABEL => ' Sozinho',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 2,
                                    Constantes::$FORM_STRING_LABEL => ' Com o conjungê',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                            ),
                        ])
        );
        $element = $this->get(ConstantesCadastro::$INPUT_ESTADO_CIVIL);
        $element->setLabelOptions(['disable_html_escape' => true]);

        /* CPF */
        $this->add(
                (new Number())
                        ->setName(ConstantesForm::$FORM_CPF)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CPF,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_CPF,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_CPF,
                        ])
        );

        /* Email */
        $this->add(
                (new Email())
                        ->setName(ConstantesForm::$FORM_EMAIL)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_EMAIL,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_EMAIL,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_EMAIL,
                        ])
        );

        /* Repetir Email */
        $this->add(
                (new Email())
                        ->setName(ConstantesForm::$FORM_REPETIR_EMAIL)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_REPETIR_EMAIL,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_EMAIL,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_EMAIL,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

    function getAlunos() {
        return $this->alunos;
    }

    function setAlunos($alunos) {
        $this->alunos = $alunos;
    }

}
