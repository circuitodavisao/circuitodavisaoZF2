<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Form;

/**
 * Nome: SelecionarAlunosForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para selecionar alunos do instituto de vencedores.            
 *              
 */
class SelecionarAlunosForm extends Form {

    /**
     * Construtor
     * @param String $name
     */
    public function __construct($name = null, $idTurma, $pessoas) {
        parent::__construct($name);
        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));
        /**
         * IdTurma
         * Elemento do tipo text
         */
        $this->add(
                (new Hidden())
                        ->setName('idTurma')
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => 'idTurma',
                        ])
                        ->setValue($idTurma)
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        foreach ($pessoas as $p) {
            $arrayPessoas[] = array(
                'value' => $p->getNome() . '#' . $p->getId(),
                'label' => $p->getNome(),
                'selected' => false,
            );
        }
        $multiCheckbox = new MultiCheckbox('alunos');
        $multiCheckbox->setValueOptions($arrayPessoas);
        $multiCheckbox->setAttribute('id', 'alunos');
        $this->add($multiCheckbox);
    }

}
