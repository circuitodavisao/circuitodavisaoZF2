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

        Endereco::MontaEnderecoFormulario($this);

        /* Nome Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_NOME_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME_HOSPEDEIRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_NOME_HOSPEDEIRO,
                        ])
        );

        /* DDD Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_DDD_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DDD_HOSPEDEIRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_DDD_HOSPEDEIRO,
                        ])
        );

        /* Telefone Hospedeiro */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_TELEFONE_HOSPEDEIRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
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
