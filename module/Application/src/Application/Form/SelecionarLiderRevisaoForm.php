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
 * Nome: SelecionarLiderRevisao.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para selecionar os líderes para trabalharem no revisao.
 */
class SelecionarLiderRevisaoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param array $pessoasAbaixo
     */
    public function __construct($name = null, $pessoasAbaixo = null, $pessoasAtivas = null) {
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
         * Select dos líderes
         */
        foreach ($pessoasAbaixo as $pessoa) {
            $explodeNome = explode(" ", $pessoa->getNome());
            $primeiroNome = $explodeNome[0];
            if (count($explodeNome) > 1) {
                $primeiroNome .= ' ' . $explodeNome[(count($explodeNome) - 1)];
            }
            $selected = false;
            if ($pessoasAtivas) {
                foreach ($pessoasAtivas as $pessoaAtiva) {
                    if ($pessoa->getId() == $pessoaAtiva->getId()) {
                        $selected = true;
                    } else {
                        $selected = false;
                    }
                }
            }
            $arrayPessoa['value'] = $pessoa->getId();
            $arrayPessoa['label'] = $primeiroNome;
            $arrayPessoa['selected'] = $selected;
            $arrayLideres[] = $arrayPessoa;
        }
        // elemento do tipo Select
        $select = new Select();
        $select->setName(Constantes::$INPUT_LIDERES);
        $select->setAttributes(array(
            Constantes::$FORM_STRING_CLASS => 'demo1',
            Constantes::$FORM_STRING_ID => Constantes::$INPUT_LIDERES,
            'multiple' => 'multiple',
            'size' => 30,
        ));
        $select->setValueOptions($arrayLideres);
        $this->add($select);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    }

}
