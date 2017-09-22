<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Curso;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form;


/**
 * Nome: CursoForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar cursos do instituto de vencedores.            
 *              
 */
class CursoForm extends Form {

    /**
     * Construtor
     * @param String $name
     */
    public function __construct($name = null, Curso $curso = null) { 
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
                        ->setName(Constantes::$FORM_ID)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_ID,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );


       /* Nome */
        $this->add(
                (new Text())
                        ->setName(Constantes::$FORM_NOME)  
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_GUI_INPUT,
                            Constantes::$FORM_ID => Constantes::$FORM_NOME,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME_CURSO_PLACEHOLDER,
                        ])
        );
        
        if(!is_null($curso)){
            $this->get(Constantes::$FORM_ID)->setValue($curso->getId());
            $this->get(Constantes::$FORM_NOME)->setValue($curso->getMes());
        }
        
    }

}
