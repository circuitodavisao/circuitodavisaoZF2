<?php

namespace Cadastro\Form;

use Entidade\Entity\Evento;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: EventoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar de um evento
 */
class EventoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, Evento $evento = null) {
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

        /* Nome do Evento */
        $this->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_NOME)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_NOME,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(ConstantesForm::$FORM_CSRF)
        );

        if (!is_null($evento->getId())) {
            $this->get(ConstantesForm::$FORM_ID)->setValue($evento->getId());
            $this->get(ConstantesForm::$FORM_DIA_DA_SEMANA)->setValue($evento->getDia());
            $this->get(ConstantesForm::$FORM_HORA)->setValue(substr($evento->getHora(), 0, 2));
            $this->get(ConstantesForm::$FORM_MINUTOS)->setValue(substr($evento->getHora(), 3, 2));
            $this->get(ConstantesForm::$FORM_NOME)->setValue($evento->getNome());
        }
    }

}
