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
 * Descricao: Formulario para cadastrar revisõ.            
 *              
 */
class RevisaoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = null, $gruposIgrejas = null, Evento $revisao = null) {
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
         * Dia do Revisao
         * Elemento do tipo Date
         */
        $this->add(
                (new Date())
                        ->setName(Constantes::$FORM_INPUT_DATA_REVISAO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_INPUT_DATA_REVISAO,
                        ])
        );


        /* Observacao */
        $this->add(
                (new Text())
                        ->setName(Constantes::$FORM_NOME)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_GUI_INPUT,
                            Constantes::$FORM_ID => Constantes::$FORM_NOME,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                        ])
        );



        if (!is_null($revisao->getId())) {

            $this->get(Constantes::$FORM_ID)->setValue($revisao->getId());
            $this->get(Constantes::$FORM_INPUT_DATA_REVISAO)->setValue(Funcoes::mudarPadraoData($revisao->getData(), 1));
            $this->get(Constantes::$FORM_NOME)->setValue($revisao->getNome());
            foreach ($gruposIgrejas as $gi) {
                $i = $gi->getEntidadeAtiva();
                if ($gi->verificaSeParticipaDoEvento($this->get(Constantes::$FORM_ID)->getValue())) {
                    $selected = true;
                }

                $arrayIgrejas [] = array(
                    'value' => $i->getNome() . '#' . $i->getId(),
                    'label' => $i->getNome(),
                    'selected' => $selected,
                );
            }
        } else {
            foreach ($gruposIgrejas as $gi) {
                $i = $gi->getEntidadeAtiva();
                $arrayIgrejas [] = array(
                    'value' => $i->getNome() . '#' . $i->getId(),
                    'label' => $i->getNome(),
                    'selected' => false,
                );
            }
        }

        $multiCheckbox = new MultiCheckbox('igrejas');
        $multiCheckbox->setValueOptions($arrayIgrejas);
        $multiCheckbox->setAttribute('id', 'igrejas');
        $this->add($multiCheckbox);
    }

}
