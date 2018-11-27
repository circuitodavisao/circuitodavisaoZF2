<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: NovoEmailForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para reenviar o novo email
 */
class NomeForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, $grupo = null) {
        parent::__construct($name);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
            Constantes::$FORM_STRING_CLASS => 'form-horizontal',
        ));

        /**
         * Nova Senha
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_NOME)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_NOME,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                        ])
        );
    
        /**
         * Elemento CSRF
         */
        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );


        /**
         * Botao verificar entrar
         */
        $this->add(
                (new Submit())
                        ->setName('Enviar')
                        ->setValue(Constantes::$TRADUCAO_ALTERAR)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => 'botaoEnviar',
                            Constantes::$FORM_STRING_CLASS => 'button btn-primary-circuito mr10 pull-right',
                        ])
        );


        /**
         * Elemento Hidden
         */

        if($grupo){
          $this->get(Constantes::$INPUT_NOME)->setValue($grupo->getEntidadeAtiva()->infoEntidade());
          $this->add(
                  (new Hidden())
                          ->setName(Constantes::$FORM_ID)
                          ->setValue($grupo->getId())
          );
        }
    }

}
