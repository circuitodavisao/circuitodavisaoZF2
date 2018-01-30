<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;

/**
 * Nome: SolicitacaoReceberForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para receber uma solicitacao
 */
class SolicitacaoReceberForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null) {
        parent::__construct($name);

        $this->add(
                (new Hidden())
                        ->setName('objeto2')
                        ->setAttributes([
                            Constantes::$FORM_ID => 'objeto2',
                        ])
        );

        $this->add(
                (new Hidden())
                        ->setName('numeracao')
                        ->setAttributes([
                            Constantes::$FORM_ID => 'numeracao',
                        ])
        );
    }

}
