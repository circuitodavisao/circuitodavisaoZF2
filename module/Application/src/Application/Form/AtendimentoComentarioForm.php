<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: AtendimentoComentarioForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar comentario do atendimento
 */
class AtendimentoComentarioForm extends Form {

    public function __construct($name = null, $grupo = null, $mes = null, $ano = null) {
        parent::__construct($name);

        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));


        $this->add(
                (new Hidden())
                        ->setName(Constantes::$ID)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$ID,
                            'value' => $grupo->getId(),
                        ])
        );

        $this->add(
                (new Hidden())
                        ->setName('mes')
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => 'mes',
                            'value' => $mes,
                        ])
        );

        $this->add(
            (new Hidden())
                    ->setName('ano')
                    ->setAttributes([
                        Constantes::$FORM_STRING_ID => 'ano',
                        'value' => $ano,
                    ])
        );

        $this->add(
                (new Text())
                        ->setName('comentario')
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => 'comentario',
                            Constantes::$FORM_STRING_PLACEHOLDER => 'Comentário',
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
