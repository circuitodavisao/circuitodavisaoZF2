<?php

namespace Cadastro\Form;

use Entidade\Entity\EventoCelula;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;

/**
 * Nome: CelulaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar célula
 */
class CelulaForm extends EventoForm {

    private $enderecoHidden;

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, EventoCelula $eventoCelula = null) {
        parent::__construct($name);

        /* CEP ou Logradouro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CEP_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CEP_LOGRADOURO,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_CEP,
                            ConstantesForm::$FORM_ONKEYPRESS => ConstantesForm::$FORM_FUNCAO_BUSCAR_POR_ENTER
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
