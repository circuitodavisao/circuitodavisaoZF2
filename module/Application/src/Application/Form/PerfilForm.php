<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Pessoa;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: PerfilForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para alterar o perfil
 */
class PerfilForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param array $grupoPessoaTipos
     */
    public function __construct($name = null, Pessoa $pessoa) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));

        /**
         * Id
         * Elemento do tipo text
         */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$ID)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$ID,
                            Constantes::$FORM_STRING_VALUE => $pessoa->getId(),
                        ])
        );

        /**
         * Nome
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_NOME)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_NOME,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                            Constantes::$FORM_STRING_VALUE => $pessoa->getNome(),
                            'disabled' => 'true',
                        ])
        );

        /**
         * DDD
         * Elemento do tipo text
         */
        $this->add(
                (new Number())
                        ->setName(Constantes::$INPUT_DDD)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_DDD,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_DDD,
                            Constantes::$FORM_STRING_VALUE => substr($pessoa->getTelefone(), 0, 2),
                        ])
        );
        /**
         * Telefone
         * Elemento do tipo text
         */
        $this->add(
                (new Number())
                        ->setName(Constantes::$INPUT_TELEFONE)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_TELEFONE,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_TELEFONE,
                            Constantes::$FORM_STRING_VALUE => substr($pessoa->getTelefone(), 2),
                        ])
        );

        $this->add(
                (new Number())
                        ->setName(Constantes::$INPUT_CPF)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_CPF,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_CPF,
                            Constantes::$FORM_STRING_VALUE => str_pad($pessoa->getDocumento(), 11, 0, STR_PAD_LEFT),
                            'disabled' => 'true',
                        ])
        );

        $this->add(
                (new Email())
                        ->setName(Constantes::$INPUT_EMAIL)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_EMAIL,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_EMAIL,
                            Constantes::$FORM_STRING_VALUE => $pessoa->getEmail(),
                            'disabled' => 'true',
                        ])
        );

        $arraySexo = array();
        $arraySexo[0] = Constantes::$TRADUCAO_SELECIONE;
        $arraySexo['M'] = Constantes::$TRADUCAO_MASCULINO;
        $arraySexo['F'] = Constantes::$TRADUCAO_FEMININO;
        $inputSelectSexo = new Select();
        $inputSelectSexo->setName(Constantes::$INPUT_SEXO);
        $inputSelectSexo->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$INPUT_SEXO,
        ));
        $inputSelectSexo->setValueOptions($arraySexo);
        $inputSelectSexo->setValue($pessoa->getSexo());
        $this->add($inputSelectSexo);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
