<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Application\Model\Entity\Evento;
use Application\Model\ORM\RepositorioORM;
use Zend\Form\Element\Date;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: RevisaoForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar Fato Discipulado.            
 *              
 */
class FatoDiscipuladoForm extends Form {

    /**
     * Contrutor
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

        comecou_no_horario;
        terminou_no_horario;
        teve_lanche;
        teve_avisos;
        teve_palavra;

        $classOption = 'block mt15';
        for ($indiceInputs = 0; $indiceInputs <= 4; $indiceInputs++) {
            $input = '';
            switch ($indiceInputs) {
                case 0:$input = 'inputComecouNoHorario';
                    break;
                case 1:$input = 'inputTerminouNoHorario';
                    break;
                case 2:$input = 'inputTeveLanche';
                    break;
                case 3:$input = 'inputTeveAvisos';
                    break;
                case 4:$input = 'inputTevePalavra';
                    break;
            }
            $this->add(
                    (new Radio())
                            ->setName($input)
                            ->setAttributes([
                                Constantes::$FORM_STRING_ID => $input,
                            ])
                            ->setOptions([
                                Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                    1 => array(
                                        Constantes::$FORM_STRING_VALUE => 'S',
                                        Constantes::$FORM_STRING_LABEL => ' Sim',
                                        Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                    ),
                                    2 => array(
                                        Constantes::$FORM_STRING_VALUE => 'N',
                                        Constantes::$FORM_STRING_LABEL => ' Não',
                                        Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                    ),
                                ),
                            ])
            );
            $element = $this->get($input);
            $element->setLabelOptions(['disable_html_escape' => true]);
        }

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
