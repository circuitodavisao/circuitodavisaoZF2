<?php

namespace Lancamento\Form;

use Lancamento\Controller\Helper\ConstantesLancamento;
use Login\Controller\Helper\Constantes;
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
    public function __construct($name = null, $grupoPessoaTipos) {
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
                        ->setName(ConstantesLancamento::$INPUT_NOME)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_NOME,
                            Constantes::$FORM_STRING_PLACEHOLDER => ConstantesLancamento::$TRADUCAO_NOME,
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                        ])
        );

        /**
         * Telefone
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(ConstantesLancamento::$INPUT_TELEFONE)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT . ' ' . ConstantesLancamento::$CLASS_PHONE,
                            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_TELEFONE,
                            Constantes::$FORM_STRING_PLACEHOLDER => ConstantesLancamento::$TRADUCAO_TELEFONE,
                            Constantes::$FORM_STRING_REQUIRED => Constantes::$FORM_STRING_REQUIRED,
                        ])
        );

        /**
         * Select de tipos
         */
        $arrayGPT[0] = ConstantesLancamento::$TRADUCAO_SELECIONE;
        foreach ($grupoPessoaTipos as $gpt) {
            $arrayGPT[$gpt->getId()] = $gpt->getNome();
        }
        // elemento do tipo Select
        $select = new Select();
        $select->setName(ConstantesLancamento::$INPUT_TIPO);
        $select->setAttributes(array(
            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_TIPO,
        ));
        $select->setValueOptions($arrayGPT);
        $this->add($select);

        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(ConstantesLancamento::$INPUT_NUCLEO_PERFEITO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_NUCLEO_PERFEITO,
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 0,
                                    Constantes::$FORM_STRING_LABEL => ' NENHUM ',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 'C',
                                    Constantes::$FORM_STRING_LABEL => ' CO LIDER <br />',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                3 => array(
                                    Constantes::$FORM_STRING_VALUE => 'L',
                                    Constantes::$FORM_STRING_LABEL => ' LIDER EM TREINAMENTO',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                            ),
                        ])
        );
        $element = $this->get(ConstantesLancamento::$INPUT_NUCLEO_PERFEITO);
        $element->setLabelOptions(['disable_html_escape' => true]);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
