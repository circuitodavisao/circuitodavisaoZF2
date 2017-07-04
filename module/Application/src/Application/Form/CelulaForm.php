<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\EventoCelula;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Element\Number;

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
                        ->setName(Constantes::$FORM_NOME_HOSPEDEIRO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_NOME_HOSPEDEIRO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME_HOSPEDEIRO,
                        ])
        );

        /* DDD Hospedeiro */
        $this->add(
                (new Number())
                        ->setName(Constantes::$FORM_DDD_HOSPEDEIRO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_DDD_HOSPEDEIRO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_DDD_HOSPEDEIRO,
                        ])
        );

        /* Telefone Hospedeiro */
        $this->add(
                (new Number())
                        ->setName(Constantes::$FORM_TELEFONE_HOSPEDEIRO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_TELEFONE_HOSPEDEIRO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_TELEFONE_HOSPEDEIRO,
                        ])
        );

        /* Botão de buscar CEP ou Lougradouro */
        $this->add(
                (new Button())
                        ->setName(Constantes::$FORM_BUSCAR_CEP_LOGRADOURO)
                        ->setLabel(Constantes::$TRADUCAO_BUSCAR_CEP_LOGRADOURO)
                        ->setAttributes([
                            Constantes::$FORM_ID => Constantes::$FORM_BUSCAR_CEP_LOGRADOURO,
                            Constantes::$FORM_CLASS => Constantes::$FORM_BTN_DEFAULT_DARK,
                        ])
                        ->setValue(Constantes::$TRADUCAO_BUSCAR_CEP_LOGRADOURO)
        );

        if (!is_null($eventoCelula->getId())) {
            $this->get(Constantes::$FORM_ID)->setValue($eventoCelula->getId());
            $this->get(Constantes::$FORM_DIA_DA_SEMANA)->setValue($eventoCelula->getEvento()->getDia());
            $this->get(Constantes::$FORM_HORA)->setValue(substr($eventoCelula->getEvento()->getHora(), 0, 2));
            $this->get(Constantes::$FORM_MINUTOS)->setValue(substr($eventoCelula->getEvento()->getHora(), 3, 2));
            $this->get(Constantes::$FORM_CEP_LOGRADOURO)->setValue($eventoCelula->getCep());
            $this->get(Constantes::$FORM_UF)->setValue($eventoCelula->getUf());
            $this->get(Constantes::$FORM_CIDADE)->setValue($eventoCelula->getCidade());
            $this->get(Constantes::$FORM_BAIRRO)->setValue($eventoCelula->getBairro());
            $this->get(Constantes::$FORM_LOGRADOURO)->setValue($eventoCelula->getLogradouro());
            $this->get(Constantes::$FORM_COMPLEMENTO)->setValue($eventoCelula->getComplemento());
            $this->get(Constantes::$FORM_NOME_HOSPEDEIRO)->setValue($eventoCelula->getNome_hospedeiro());
            $this->get(Constantes::$FORM_DDD_HOSPEDEIRO)->setValue(substr($eventoCelula->getTelefone_hospedeiro(), 0, 2));
            $this->get(Constantes::$FORM_TELEFONE_HOSPEDEIRO)->setValue(substr($eventoCelula->getTelefone_hospedeiro(), 2));
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
