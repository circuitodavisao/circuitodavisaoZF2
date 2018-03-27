<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Aula;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: AulaForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar aulas do instituto de vencedores.            
 *              
 */
class AulaForm extends Form {

    /**
     * Construtor
     * @param String $name
     */
    public function __construct($name = null, $idDisciplina, $aulas, Aula $aula = null) {
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

        /**
         * IdDisciplina
         * Elemento do tipo text
         */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$FORM_ID_DISCIPLINA)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_ID_DISCIPLINA,
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
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_NOME,
                        ])
        );
        $this->get(Constantes::$FORM_ID_DISCIPLINA)->setValue($idDisciplina);

        /* Posição */
        $arrayPosicao = array();
        if (!empty($aulas)) {
            for ($indicePosicao = 1; $indicePosicao <= 24; $indicePosicao++) {
                $adicionarPosicao = true;
                foreach ($aulas as $a) {
                    if ($indicePosicao === $a->getPosicao()) {
                        $adicionarPosicao = false;
                    }
                }
                if(!empty($aula)) {
                    if ($indicePosicao == $aula->getPosicao()) {
                        $adicionarPosicao = true;
                    }
                }

                if ($adicionarPosicao) {
                    $arrayPosicao[$indicePosicao] = $indicePosicao;
                }
            }
        } else {
            for ($indicePosicao = 1; $indicePosicao <= 24; $indicePosicao++) {

                $arrayPosicao[$indicePosicao] = $indicePosicao;
            }
        }

        $inputSelectPosicao = new Select();
        $inputSelectPosicao->setName(Constantes::$FORM_POSICAO);
        $inputSelectPosicao->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_POSICAO,
        ));
        $inputSelectPosicao->setValueOptions($arrayPosicao);
        $inputSelectPosicao->setEmptyOption(Constantes::$FORM_SELECT);
        $this->add($inputSelectPosicao);

        if (!is_null($aula)) {
            $this->get(Constantes::$FORM_ID)->setValue($aula->getId());
            $this->get(Constantes::$FORM_NOME)->setValue($aula->getNome());
            $this->get(Constantes::$FORM_POSICAO)->setValue($aula->getPosicao());
            $this->get(Constantes::$FORM_ID_DISCIPLINA)->setValue($aula->getDisciplina_id());
        }
    }

}
