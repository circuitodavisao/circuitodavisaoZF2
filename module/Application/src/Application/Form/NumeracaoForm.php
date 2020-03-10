<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: NumeracaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar grupo
 */
class NumeracaoForm extends Form {

    public function __construct($name = null, $grupoPai = null, $grupo = null) {
        parent::__construct($name);

        $numeros = array();
        if ($grupoPai->getGrupoPaiFilhoFilhosAtivos(1)) {
            $filhos = $grupoPai->getGrupoPaiFilhoFilhosAtivos(1);
            foreach ($filhos as $filho) {
                if ($filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero()) {
                    $numero = $filho->getGrupoPaiFilhoFilho()->getEntidadeAtiva()->getNumero();
                    $numeros[] = $numero;
                }
            }
        }

        /* Numeracao */
        $arrayNumeracao = array();
        $arrayNumeracao[0] = Constantes::$FORM_SELECT;
        for ($indiceNumeroSubEquipe = 1; $indiceNumeroSubEquipe <= 48; $indiceNumeroSubEquipe++) {
            $adicionarNumero = true;
            if ($numeros) {
                foreach ($numeros as $numero) {
                    if ($indiceNumeroSubEquipe == $numero) {
                        $adicionarNumero = false;
                    }
                }
            }
            if ($adicionarNumero) {
                $numeroAjustado = str_pad($indiceNumeroSubEquipe, 2, 0, STR_PAD_LEFT);
                $arrayNumeracao[$indiceNumeroSubEquipe] = $numeroAjustado;
            }
        }
        $inputSelectNumeracao = new Select();
        $inputSelectNumeracao->setName(Constantes::$FORM_NUMERACAO);
        $inputSelectNumeracao->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_NUMERACAO,
        ));
        $inputSelectNumeracao->setValueOptions($arrayNumeracao);
        $this->add($inputSelectNumeracao);


        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        $this->add(
                (new Hidden())
                        ->setName(Constantes::$FORM_ID)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_ID,
                            'value' => $grupo->getId()
                        ])
        );
    }

}
