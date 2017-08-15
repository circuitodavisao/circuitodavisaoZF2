<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

/**
 * Nome: TurmaForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar turmas do instituto de vencedores.            
 *              
 */
class TurmaForm extends Form {

    /**
     * Construtor
     * @param String $name
     */
    public function __construct($name = null) {
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

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );


        /* Tipo */
        $arrayTipo = array();
        $arrayTipo[0] = Constantes::$TRADUCAO_TIPO;
        $arrayTipo[1] = 'Instituto de Vencedores';
        $inputSelectTipoTurma = new Select();
        $inputSelectTipoTurma->setName(Constantes::$FORM_INPUT_TIPO);
        $inputSelectTipoTurma->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_TIPO,
        ));
        $inputSelectTipoTurma->setValueOptions($arrayTipo);
        $this->add($inputSelectTipoTurma);

        /* Mês da Turma */
        $arrayMesTurma = array();
        $arrayMesTurma[0] = Constantes::$TRADUCAO_MES;
        for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
            $numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
            $arrayMesTurma[$indiceMesNoAno] = $numeroAjustado;
        }
        $inputSelectMesTurma = new Select();
        $inputSelectMesTurma->setName(Constantes::$FORM_INPUT_MES);
        $inputSelectMesTurma->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_MES,
        ));
        $inputSelectMesTurma->setValueOptions($arrayMesTurma);
        $this->add($inputSelectMesTurma);

        /* Ano da Turma do IV */
        $arrayAnoTurma = array();
        $arrayAnoTurma[0] = Constantes::$TRADUCAO_ANO;
        $anoAtual = date('Y');
        for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 100); $indiceAno--) {
            $arrayAnoTurma[$indiceAno] = $indiceAno;
        }
        $inputSelectAnoTurma = new Select();
        $inputSelectAnoTurma->setName(Constantes::$FORM_INPUT_ANO);
        $inputSelectAnoTurma->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_ANO,
        ));
        $inputSelectAnoTurma->setValueOptions($arrayAnoTurma);
        $this->add($inputSelectAnoTurma);

        $observacaoTextArea = new Textarea(Constantes::$FORM_OBSERVACAO);
        $observacaoTextArea->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_GUI_INPUT,
            Constantes::$FORM_ID => Constantes::$FORM_OBSERVACAO,
        ));
        /* Observacao */
        $this->add($observacaoTextArea);
    }

}
