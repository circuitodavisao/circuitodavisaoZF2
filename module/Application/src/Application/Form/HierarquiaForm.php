<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: HierarquiaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar de hierarquia
 */
class HierarquiaForm extends Form {

    private $hierarquia;
    private $pessoa;

    public function __construct($name = null, $pessoa = null, $hierarquia = null) {
        parent::__construct($name);

        $this->setPessoa($pessoa);
        $this->setHierarquia($hierarquia);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
            Constantes::$FORM_STRING_CLASS => 'form-horizontal',
        ));

        /**
         * Elemento Hidden
         */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$INPUT_ID_PESSOA)
                        ->setValue($pessoa->getId())
        );


        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        /* Hierarquia */
        $arrayHierarquia = array();
        $arrayHierarquia[0] = Constantes::$TRADUCAO_SELECIONE_A_HIERARQUIA;
        foreach ($this->getHierarquia() as $hierarquia) {
            $arrayHierarquia[$hierarquia->getId()] = $hierarquia->getNome();
        }

        if($this->getPessoa()->getPessoaHierarquiaAtivo()){
           $valueHierarquia = $this->getPessoa()->getPessoaHierarquiaAtivo()->getHierarquia()->getId();
        }
        $inputSelectHierarquia = new Select();
        $inputSelectHierarquia->setName(Constantes::$FORM_HIERARQUIA);
        $inputSelectHierarquia->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_HIERARQUIA,
            'value' => $valueHierarquia,
        ));
        $inputSelectHierarquia->setValueOptions($arrayHierarquia);
        $this->add($inputSelectHierarquia);
    }

    function getHierarquia() {
        return $this->hierarquia;
    }

    function setHierarquia($hierarquia) {
        $this->hierarquia = $hierarquia;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

}
