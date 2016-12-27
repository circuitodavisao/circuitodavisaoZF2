<?php

namespace Lancamento\Form;

use Cadastro\Form\ConstantesForm;
use Entidade\Entity\GrupoAtendimento;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Date;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: CadastrarAtendimentoForm.php
 * @author Lucas Carvalho  <lucascarvalho.esw@gmail.com>
 * Descricao: Formulario para cadastrar atendimento.            
 *              
 */
class CadastrarAtendimentoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param integer $idGrupo
     * @param String $nomePessoaPai
     * @param integer $idPessoaPai
     */
    public function __construct($name = null, $idGrupo = null ,$nomePessoaPai = null, $idPessoaPai = null, GrupoAtendimento $atendimento = null) {
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
         * IdGrupo
         * Elemento do tipo text
         */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesLancamento::$INPUT_ID_GRUPO_ATENDIDO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_ID_GRUPO_ATENDIDO,
                            'value' => $idGrupo,
                        ])
        );

        /**
         * Data do Atendimento
         * Elemento do tipo Text
         */
        $this->add(
                (new Date())
                        ->setName(ConstantesLancamento::$INPUT_DATA_ATENDIMENTO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_DATA_ATENDIMENTO,
                        ])
        );
        
        $arrayLider[''] = ConstantesForm::$TRADUCAO_SELECIONE;
        $arrayLider["$idPessoaPai"] = str_replace("&nbsp;", " ", $nomePessoaPai);
        $arrayLider[0] = "AMBOS";
        
        /**
         * Selecione Lider 
         * Elemento do tipo select
         */
        $inputSelectLider = new Select();
        $inputSelectLider->setName(ConstantesLancamento::$INPUT_QUEM_ATENDEU);
        $inputSelectLider->setAttributes(array(
            Constantes::$FORM_STRING_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_STRING_ID => ConstantesLancamento::$INPUT_QUEM_ATENDEU,
        ));
        $inputSelectLider->setValueOptions($arrayLider);
        $inputSelectLider->setDisableInArrayValidator(true);
        $this->add($inputSelectLider);

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );
    
        if (!is_null($atendimento)) {
            if (!is_null($atendimento->getId())) {
                $this->get(ConstantesForm::$FORM_ID)->setValue($atendimento->getId());
                $this->get(ConstantesLancamento::$INPUT_ID_GRUPO_ATENDIDO)->setValue($atendimento->getGrupo_id());
                $this->get(ConstantesLancamento::$INPUT_DATA_ATENDIMENTO)->setValue($atendimento->getDia());
                $this->get(ConstantesLancamento::$INPUT_QUEM_ATENDEU)->setValue($atendimento->getQuem());
                
            }
        }
        
    }

}
