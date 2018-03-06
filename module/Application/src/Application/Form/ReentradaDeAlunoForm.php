<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: ReentradaDeAlunoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar reentrada de aluno
 */
class ReentradaDeAlunoForm extends CadastrarPessoaRevisaoForm {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = 'formulario', $idTurma, $lideres = null) {
        parent::__construct($name);


        $this->add(
                (new Hidden())
                        ->setName(Constantes::$ID . 'Turma')
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$ID . 'Turma',
                            'value' => $idTurma,
                        ])
        );
        if ($lideres) {
            $array = array();
            $array[0] = Constantes::$TRADUCAO_SELECIONE;
            foreach ($lideres as $lider) {
                $array[$lider->getId()] = $lider->getEntidadeATiva()->infoEntidade() . ' - ' . $lider->getNomeLideresAtivos();
            }

            $inputSelectHierarquia = new Select();
            $inputSelectHierarquia->setName(Constantes::$INPUT_ID_GRUPO);
            $inputSelectHierarquia->setAttributes(array(
                Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                Constantes::$FORM_ID => Constantes::$INPUT_ID_GRUPO,
            ));
            $inputSelectHierarquia->setValueOptions($array);
            $this->add($inputSelectHierarquia);
        }
    }

}
