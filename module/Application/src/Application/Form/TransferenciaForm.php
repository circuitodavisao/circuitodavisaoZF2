<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Form;

/**
 * Nome: TransferenciaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar de uma transferencia
 */
class TransferenciaForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null) {
        parent::__construct($name);

        $this->setAttributes(array(
            Constantes::$FORM_METHOD => Constantes::$FORM_POST,
        ));
    }

}
