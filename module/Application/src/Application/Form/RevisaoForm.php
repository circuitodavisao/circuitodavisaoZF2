<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Date;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: RevisaoForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar revisõ.            
 *              
 */
class RevisaoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, $igrejas = null) {
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


        /**
         * Dia do Revisao
         * Elemento do tipo Date
         */
        $this->add(
                (new Date())
                        ->setName(Constantes::$FORM_INPUT_DATA_REVISAO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_INPUT_DATA_REVISAO,
                        ])
        );


        /* Observacao */
        $this->add(
                (new Text())
                        ->setName(Constantes::$FORM_NOME)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_GUI_INPUT,
                            Constantes::$FORM_ID => Constantes::$FORM_NOME,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                        ])
        );
        
        foreach($igrejas as $i){
            $arrayIgrejas[$i->getNome().'#'.$i->getId()] = $i->getNome();
        }
        $multiCheckbox = new MultiCheckbox('igrejas');
        $multiCheckbox->setValueOptions($arrayIgrejas);
        $multiCheckbox->setAttribute('id', 'igrejas');
        $this->add($multiCheckbox);
    }

}
