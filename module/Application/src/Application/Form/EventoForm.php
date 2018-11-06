<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Evento;
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
            Constantes::$FORM_METHOD => Constantes::$FORM_POST,
			'autocomplete' => 'off',
		));

        /* Id */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$FORM_ID)
                        ->setAttributes([
                            Constantes::$FORM_ID => Constantes::$FORM_ID,
                        ])
        );

        /* Dia da semana */
        $arrayDiaDaSemana = array();
        $arrayDiaDaSemana[''] = Constantes::$FORM_SELECT;
        for ($indiceDiaDaSemana = 1; $indiceDiaDaSemana <= 7; $indiceDiaDaSemana++) {
            $diaDaSemanaPorExtenso = Funcoes::diaDaSemanaPorDia($indiceDiaDaSemana, 1);
            $arrayDiaDaSemana[$indiceDiaDaSemana] = $diaDaSemanaPorExtenso;
        }
        $inputSelectDiaDaSemana = new Select();
        $inputSelectDiaDaSemana->setName(Constantes::$FORM_DIA_DA_SEMANA);
        $inputSelectDiaDaSemana->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_DIA_DA_SEMANA,
        ));
        $inputSelectDiaDaSemana->setValueOptions($arrayDiaDaSemana);
        $this->add($inputSelectDiaDaSemana);

        /* Hora */
        $arrayHoras[''] = Constantes::$TRADUCAO_SELECIONE;
        for ($indexHoras = 0; $indexHoras <= 23; $indexHoras++) {
            $valorFormatado = str_pad($indexHoras, 2, 0, STR_PAD_LEFT);
            $arrayHoras[$valorFormatado] = $valorFormatado;
        }
        $selectHora = new Select();
        $selectHora->setName(Constantes::$FORM_HORA);
        $selectHora->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_HORA,
        ));
        $selectHora->setValueOptions($arrayHoras);
        $this->add($selectHora);

        /* Minutos */
        $arrayMinutos[''] = Constantes::$TRADUCAO_SELECIONE;
        for ($indexMinutos = 0; $indexMinutos <= 59; $indexMinutos++) {
            $valorFormatado = str_pad($indexMinutos, 2, 0, STR_PAD_LEFT);
            $arrayMinutos[$valorFormatado] = $valorFormatado;
        }
        $selectMinutos = new Select();
        $selectMinutos->setName(Constantes::$FORM_MINUTOS);
        $selectMinutos->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_MINUTOS,
        ));
        $selectMinutos->setValueOptions($arrayMinutos);
        $this->add($selectMinutos);

        /* Nome do Evento */
        $this->add(
                (new Text())
                        ->setName(Constantes::$FORM_NOME)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_NOME,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$FORM_CSRF)
        );

        if (!is_null($evento)) {
            if (!is_null($evento->getId())) {
                $this->get(Constantes::$FORM_ID)->setValue($evento->getId());
                $this->get(Constantes::$FORM_DIA_DA_SEMANA)->setValue($evento->getDia());
                $this->get(Constantes::$FORM_HORA)->setValue(substr($evento->getHora(), 0, 2));
                $this->get(Constantes::$FORM_MINUTOS)->setValue(substr($evento->getHora(), 3, 2));
                $this->get(Constantes::$FORM_NOME)->setValue($evento->getNome());
            }
        }
    }

}
