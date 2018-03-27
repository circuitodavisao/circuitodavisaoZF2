<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Select;

/**
 * Nome: CursoUsuarioForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar usuario do curso
 */
class CursoUsuarioForm extends CadastrarPessoaRevisaoForm {

    /**
     * Contrutor
     * @param String $name
     */
    public function __construct($name = 'formulario', $acessos = null, $lideres = null) {
        parent::__construct($name);

        if ($acessos) {
            $array = array();
            $array[0] = Constantes::$TRADUCAO_SELECIONE;
            foreach ($acessos as $acesso) {
                $array[$acesso->getId()] = $acesso->getNome();
            }
            $inputSelect = new Select();
            $inputSelect->setName(Constantes::$INPUT_ID_ACESSO);
            $inputSelect->setAttributes(array(
                Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                Constantes::$FORM_ID => Constantes::$INPUT_ID_ACESSO,
            ));
            $inputSelect->setValueOptions($array);
            $this->add($inputSelect);
        }

        if ($lideres) {
            $array = array(); 
            $array[0] = Constantes::$TRADUCAO_SELECIONE;
            foreach ($lideres as $lider) {
                $array[$lider->getId()] = $lider->getResponsabilidadesAtivas()[0]->getGrupo()->getEntidadeAtiva()->infoEntidade() . ' - ' . $lider->getNome();
            }
            $inputSelect = new Select();
            $inputSelect->setName(Constantes::$INPUT_ID_PESSOA);
            $inputSelect->setAttributes(array(
                Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                Constantes::$FORM_ID => Constantes::$INPUT_ID_PESSOA,
            ));
            $inputSelect->setValueOptions($array);
            $this->add($inputSelect);
        }
    }

}
