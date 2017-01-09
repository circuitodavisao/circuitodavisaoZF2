<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: CadastrarPessoaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar pessoa na tela de lançamento
 */
class CadastrarPessoaForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param array $grupoPessoaTipos
     */
    public function __construct($name = null, $grupoPessoaTipos = null) {
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
                        ])
        );

        /**
         * DDD
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_DDD)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_DDD,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_DDD,
                        ])
        );
        /**
         * Telefone
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_TELEFONE)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_TELEFONE,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_TELEFONE,
                        ])
        );

        if (!empty($grupoPessoaTipos)) {
            /**
             * Select de tipos
             */
            $arrayGPT[''] = Constantes::$TRADUCAO_SELECIONE;
            foreach ($grupoPessoaTipos as $gpt) {
                $arrayGPT[$gpt->getId()] = $gpt->getNome();
            }
            // elemento do tipo Select
            $select = new Select();
            $select->setName(Constantes::$INPUT_TIPO);
            $select->setAttributes(array(
                Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                Constantes::$FORM_STRING_ID => Constantes::$INPUT_TIPO,
            ));
            $select->setValueOptions($arrayGPT);
            $this->add($select);
        }
        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(Constantes::$INPUT_NUCLEO_PERFEITO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_NUCLEO_PERFEITO,
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 0,
                                    Constantes::$INPUT_SELECTED => Constantes::$INPUT_SELECTED,
                                    Constantes::$FORM_STRING_LABEL => ' NONE',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 'C',
                                    Constantes::$FORM_STRING_LABEL => ' CO-LEADER',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                3 => array(
                                    Constantes::$FORM_STRING_VALUE => 'L',
                                    Constantes::$FORM_STRING_LABEL => ' LEADER IN TRAINING',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                            ),
                        ])
        );
        $element = $this->get(Constantes::$INPUT_NUCLEO_PERFEITO);
        $element->setLabelOptions(['disable_html_escape' => true]);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
