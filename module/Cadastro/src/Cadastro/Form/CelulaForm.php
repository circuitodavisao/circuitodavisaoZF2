<?php

namespace Cadastro\Form;

use Entidade\Entity\EventoCelula;
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

    private $enderecoHidden;

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, EventoCelula $eventoCelula = null) {
        parent::__construct($name);

        $this->setAttributes(array(
            ConstantesForm::$FORM_METHOD => ConstantesForm::$FORM_POST,
        ));

        $this->setEnderecoHidden(ConstantesForm::$FORM_HIDDEN);

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
        $arrayHoras[''] = ConstantesForm::$TRADUCAO_SELECIONE;
        for ($indexHoras = 0; $indexHoras <= 23; $indexHoras++) {
            $valorFormatado = str_pad($indexHoras, 2, 0, STR_PAD_LEFT);
            $arrayHoras[$valorFormatado] = $valorFormatado;
        }
        $selectHora = new Select();
        $selectHora->setName(ConstantesForm::$FORM_HORA);
        $selectHora->setAttributes(array(
            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HORA,
        ));
        $selectHora->setValueOptions($arrayHoras);
        $this->add($selectHora);

        /* Minutos */
        $arrayMinutos[''] = ConstantesForm::$TRADUCAO_SELECIONE;
        for ($indexMinutos = 0; $indexMinutos <= 59; $indexMinutos++) {
            $valorFormatado = str_pad($indexMinutos, 2, 0, STR_PAD_LEFT);
            $arrayMinutos[$valorFormatado] = $valorFormatado;
        }
        $selectMinutos = new Select();
        $selectMinutos->setName(ConstantesForm::$FORM_MINUTOS);
        $selectMinutos->setAttributes(array(
            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_MINUTOS,
        ));
        $selectMinutos->setValueOptions($arrayMinutos);
        $this->add($selectMinutos);

        /* CEP ou Logradouro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CEP_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CEP_LOGRADOURO,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_CEP,
                            ConstantesForm::$FORM_ONKEYPRESS => ConstantesForm::$FORM_FUNCAO_BUSCAR_CEP_POR_ENTER
                        ])
        );


        /* UF */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_UF)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_UF,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_UF,
                        ])
        );


        /* Cidade */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CIDADE)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CIDADE,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_CIDADE,
                        ])
        );


        /* Bairro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_BAIRRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_BAIRRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_BAIRRO,
                        ])
        );


        /* Logradouro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_LOGRADOURO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_LOGRADOURO,
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

        if (!is_null($eventoCelula->getId())) {
            $this->get(ConstantesForm::$FORM_ID)->setValue($eventoCelula->getId());
            $this->get(ConstantesForm::$FORM_DIA_DA_SEMANA)->setValue($eventoCelula->getEvento()->getDia());
            $this->get(ConstantesForm::$FORM_HORA)->setValue(substr($eventoCelula->getEvento()->getHora(), 0, 2));
            $this->get(ConstantesForm::$FORM_MINUTOS)->setValue(substr($eventoCelula->getEvento()->getHora(), 3, 2));
            $this->get(ConstantesForm::$FORM_CEP_LOGRADOURO)->setValue($eventoCelula->getCep());
            $this->get(ConstantesForm::$FORM_UF)->setValue($eventoCelula->getUf());
            $this->get(ConstantesForm::$FORM_CIDADE)->setValue($eventoCelula->getCidade());
            $this->get(ConstantesForm::$FORM_BAIRRO)->setValue($eventoCelula->getBairro());
            $this->get(ConstantesForm::$FORM_LOGRADOURO)->setValue($eventoCelula->getLogradouro());
            $this->get(ConstantesForm::$FORM_COMPLEMENTO)->setValue($eventoCelula->getComplemento());
            $this->get(ConstantesForm::$FORM_NOME_HOSPEDEIRO)->setValue($eventoCelula->getNome_hospedeiro());
            $this->get(ConstantesForm::$FORM_DDD_HOSPEDEIRO)->setValue(substr($eventoCelula->getTelefone_hospedeiro(), 0, 2));
            $this->get(ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO)->setValue(substr($eventoCelula->getTelefone_hospedeiro(), 2));
            $this->setEnderecoHidden('');
        }
    }

    function getEnderecoHidden() {
        return $this->enderecoHidden;
    }

    function setEnderecoHidden($enderecoHidden) {
        $this->enderecoHidden = $enderecoHidden;
    }

}
