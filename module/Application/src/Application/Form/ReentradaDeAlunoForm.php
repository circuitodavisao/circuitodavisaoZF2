<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Select;

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
    public function __construct($name = 'formulario', $lideres = null, $turmas = null) {
        parent::__construct($name);

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
        if ($turmas) {
            $array = array();
            $array[0] = Constantes::$TRADUCAO_SELECIONE;
            foreach ($turmas as $turma) {
                $array[$turma->getId()] = $turma->getCurso()->getNome() . ' - ' . str_pad($turma->getMes(), 2, 0, STR_PAD_LEFT) . '/' . $turma->getAno();
            }

            $inputSelectHierarquia = new Select();
            $inputSelectHierarquia->setName(Constantes::$INPUT_ID_TURMA);
            $inputSelectHierarquia->setAttributes(array(
                Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                Constantes::$FORM_ID => Constantes::$INPUT_ID_TURMA,
            ));
            $inputSelectHierarquia->setValueOptions($array);
            $this->add($inputSelectHierarquia);
        }
    }

}
