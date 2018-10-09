<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: ParceiroDeDeusForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar parceiro de Deus
 */
class ParceiroDeDeusForm extends Form {

    public function __construct($name = null) {
        parent::__construct($name);

        $arrayDiaData = array();
        $arrayDiaData[0] = Constantes::$TRADUCAO_DIA;
        for ($indiceDiaDoMes = 1; $indiceDiaDoMes <= 31; $indiceDiaDoMes++) {
            $numeroAjustado = str_pad($indiceDiaDoMes, 2, 0, STR_PAD_LEFT);
            $arrayDiaData[$indiceDiaDoMes] = $numeroAjustado;
        }
        $inputSelectDiaData = new Select();
        $inputSelectDiaData->setName(Constantes::$FORM_INPUT_DIA);
        $inputSelectDiaData->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_DIA,
        ));
        $inputSelectDiaData->setValueOptions($arrayDiaData);
        $this->add($inputSelectDiaData);

        $arrayMesData = array();
        $arrayMesData[0] = Constantes::$TRADUCAO_MES;
        for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
            $numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
            $arrayMesData[$indiceMesNoAno] = $numeroAjustado;
        }
        $inputSelectMesData = new Select();
        $inputSelectMesData->setName(Constantes::$FORM_INPUT_MES);
        $inputSelectMesData->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_MES,
        ));
        $inputSelectMesData->setValueOptions($arrayMesData);
        $this->add($inputSelectMesData);

        $arrayAnoData = array();
        $arrayAnoData[0] = Constantes::$TRADUCAO_ANO;
        $anoAtual = date('Y');
        for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 1); $indiceAno--) {
            $arrayAnoData[$indiceAno] = $indiceAno;
        }
        $inputSelectAnoData = new Select();
        $inputSelectAnoData->setName(Constantes::$FORM_INPUT_ANO);
        $inputSelectAnoData->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_ANO,
        ));
        $inputSelectAnoData->setValueOptions($arrayAnoData);
		$inputSelectAnoData->setValue(date('Y'));
        $this->add($inputSelectAnoData);

        $this->add(
                (new Text())
                        ->setName('individual')
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => 'individual',
                            Constantes::$FORM_PLACEHOLDER => 'Individual',
                        ])
        );

        $this->add(
                (new Text())
                        ->setName('celula')
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => 'celula',
                            Constantes::$FORM_PLACEHOLDER => 'Célula',
                        ])
        );

        $arrayEvento = array();
        $arrayEvento[0] = 'Selecione';

        $inputSelectCelula = new Select();
        $inputSelectCelula->setName('idGrupoEvento');
        $inputSelectCelula->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => 'idGrupoEvento',
        ));
        $inputSelectCelula->setValueOptions($arrayEvento);
        $this->add($inputSelectCelula);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
