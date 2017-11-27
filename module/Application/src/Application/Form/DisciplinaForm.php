<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Disciplina;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: DisciplinaForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar disciplinas do instituto de vencedores.
 *
 */
class DisciplinaForm extends Form {

    /**
     * Construtor
     * @param String $name
     */
    public function __construct($name = null, $idCurso, $disciplinas, Disciplina $disciplina = null) {
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
         * IdCurso
         * Elemento do tipo text
         */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$FORM_ID_CURSO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$FORM_ID_CURSO,
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
        $this->get(Constantes::$FORM_ID_CURSO)->setValue($idCurso);

        /* Posição */
        $arrayPosicao = array();
        if (count($disciplinas) > 0) {
            for ($indicePosicao = 1; $indicePosicao <= 24; $indicePosicao++) {
                $adicionarPosicao = true;
                foreach ($disciplinas as $d) {
                    if ($indicePosicao === $d->getPosicao()) {
                        $adicionarPosicao = false;
                    }
                }
                if ($indicePosicao == $disciplina->getPosicao()) {
                    $adicionarPosicao = true;
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

        if (!is_null($disciplina)) {
            $this->get(Constantes::$FORM_ID)->setValue($disciplina->getId());
            $this->get(Constantes::$FORM_NOME)->setValue($disciplina->getNome());
            $this->get(Constantes::$FORM_POSICAO)->setValue($disciplina->getPosicao());
            $this->get(Constantes::$FORM_ID_CURSO)->setValue($disciplina->getCurso_id());
        }
    }

}
