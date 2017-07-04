<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: CadastrarPessoaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar pessoa na tela de lançamento
 */
class AtivarFichaForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param array $grupoPessoaTipos
     */
    public function __construct($name = null, $pessoa = null) {
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
         * Nome
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_CODIGO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_CODIGO,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_MATRICULA,
                            Constantes::$FORM_ONBLUR => 'consultarFicha();',
                        ])
        );
        
        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
         
    }

}

