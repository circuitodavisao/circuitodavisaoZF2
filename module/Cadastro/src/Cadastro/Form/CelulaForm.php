<?php

namespace Cadastro\Form;

use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\Form\Element\Button;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: CelulaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar célula
 */
class CelulaForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null) {
        parent::__construct($name);

        $this->setAttributes(array(
            ConstantesForm::$FORM_METHOD => ConstantesForm::$FORM_POST,
        ));

        /* Id */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_ID)
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_ID,
                        ])
        );

        /* Dia da semana */
        $arrayDiaDaSemana = array();
        $arrayDiaDaSemana[''] = ConstantesForm::$FORM_SELECT;
        for ($indiceDiaDaSemana = 1; $indiceDiaDaSemana <= 7; $indiceDiaDaSemana++) {
            $diaDaSemanaPorExtenso = FuncoesLancamento::diaDaSemanaPorDia($indiceDiaDaSemana, 1);
            $arrayDiaDaSemana[$indiceDiaDaSemana] = $diaDaSemanaPorExtenso;
        }
        $inputSelectDiaDaSemana = new Select();
        $inputSelectDiaDaSemana->setName(ConstantesForm::$FORM_DIA_DA_SEMANA);
        $inputSelectDiaDaSemana->setAttributes(array(
            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DIA_DA_SEMANA,
        ));
        $inputSelectDiaDaSemana->setValueOptions($arrayDiaDaSemana);
        $this->add($inputSelectDiaDaSemana);

        /* Hora */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_HORA)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HORA,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_HORA,
                        ])
        );

        /* CEP ou Logradouro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CEP_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CEP_LOGRADOURO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_CEP_LOGRADOURO,
                        ])
        );

        /* Complemento */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_COMPLEMENTO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_COMPLEMENTO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_COMPLEMENTO,
                        ])
        );

        /* Nome Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_NOME_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME_HOSPEDEIRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_NOME_HOSPEDEIRO,
                        ])
        );

        /* DDD Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_DDD_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DDD_HOSPEDEIRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_DDD_HOSPEDEIRO,
                        ])
        );

        /* Telefone Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_TELEFONE_HOSPEDEIRO,
                        ])
        );

        /* Botão de buscar CEP ou Lougradouro */
        $this->add(
                (new Button())
                        ->setName(ConstantesForm::$FORM_BUSCAR_CEP_LOGRADOURO)
                        ->setLabel(ConstantesForm::$TRADUCAO_BUSCAR_CEP_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_BUSCAR_CEP_LOGRADOURO,
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_BTN_DEFAULT_DARK,
                        ])
                        ->setValue(ConstantesForm::$TRADUCAO_BUSCAR_CEP_LOGRADOURO)
        );

        $this->add(
                (new Csrf())
                        ->setName(ConstantesForm::$FORM_CSRF)
        );
    }

}
