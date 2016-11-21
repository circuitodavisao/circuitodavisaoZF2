<?php

namespace Cadastro\Form;

use Cadastro\Controller\Helper\ConstantesCadastro;
use Entidade\Entity\GrupoAluno;
use Login\Controller\Helper\Constantes;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Date;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Nome: GrupoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar grupo
 */
class GrupoForm extends Form {

    private $alunos;
    private $hierarquia;

    /**
     * Contrutor
     * @param String $name
     * @param GrupoAluno $alunos
     */
    public function __construct($name = null, $alunos = null, $hierarquia = null) {
        parent::__construct($name);

        $this->setAlunos($alunos);
        $this->setHierarquia($hierarquia);

        /**
         * Configuração do formulário
         */
        $this->setAttributes(array(
            Constantes::$FORM_STRING_METHOD => Constantes::$FORM_STRING_POST,
        ));

        /* IdAlunoSelecionado0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '0',
                        ])
        );

        /* IdAlunoSelecionado1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '1',
                        ])
        );

        /* IdAlunoSelecionado2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_ID_ALUNO_SELECIONADO . '2',
                        ])
        );


        /* Nome0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_NOME . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME . '0',
                        ])
        );
        /* Nome1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_NOME . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME . '1',
                        ])
        );
        /* Nome2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_NOME . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NOME . '2',
                        ])
        );
        /* email0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_EMAIL . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_EMAIL . '0',
                        ])
        );
        /* email1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_EMAIL . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_EMAIL . '1',
                        ])
        );
        /* email2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_EMAIL . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_EMAIL . '2',
                        ])
        );

        /* cpf0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_CPF . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CPF . '0',
                        ])
        );
        /* cpf1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_CPF . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CPF . '1',
                        ])
        );
        /* cpf2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_CPF . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CPF . '2',
                        ])
        );
        /* hierarquia0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_HIERARQUIA . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HIERARQUIA . '0',
                        ])
        );
        /* hierarquia1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_HIERARQUIA . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HIERARQUIA . '1',
                        ])
        );
        /* hierarquiaf2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_HIERARQUIA . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HIERARQUIA . '2',
                        ])
        );
        /* dataDeNascimento0 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_DATA_NASCIMENTO . '0')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DATA_NASCIMENTO . '0',
                        ])
        );
        /* dataDeNascimento1 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_DATA_NASCIMENTO . '1')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DATA_NASCIMENTO . '1',
                        ])
        );
        /* dataDeNascimentof2 */
        $this->add(
                (new Hidden())
                        ->setName(ConstantesForm::$FORM_DATA_NASCIMENTO . '2')
                        ->setAttributes([
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DATA_NASCIMENTO . '2',
                        ])
        );

        /**
         * Radio Estado Civil
         */
        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(ConstantesCadastro::$INPUT_ESTADO_CIVIL)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => ConstantesCadastro::$INPUT_ESTADO_CIVIL,
                            ConstantesForm::$FORM_ONCLICK => 'mostrarBotaoDeProsseguirDoEstadoCivil();',
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 1,
                                    Constantes::$FORM_STRING_LABEL => ' Sozinho',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 2,
                                    Constantes::$FORM_STRING_LABEL => ' Com o conjungê',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                            ),
                        ])
        );
        $element = $this->get(ConstantesCadastro::$INPUT_ESTADO_CIVIL);
        $element->setLabelOptions(['disable_html_escape' => true]);

        /* CPF */
        $this->add(
                (new Number())
                        ->setName(ConstantesForm::$FORM_CPF)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CPF,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_CPF,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_CPF,
                        ])
        );

        /* Data de Nascimento */
        $this->add(
                (new Date())
                        ->setName(ConstantesForm::$FORM_DATA_NASCIMENTO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL . ' ' . ConstantesForm::$FORM_CLASS_DATE,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_DATA_NASCIMENTO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_DATA_NASCIMENTO . ' dd/mm/YYYY',
                       ])
        );

        /* Email */
        $this->add(
                (new Email())
                        ->setName(ConstantesForm::$FORM_EMAIL)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_EMAIL,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_EMAIL,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_EMAIL,
                        ])
        );

        /* Repetir Email */
        $this->add(
                (new Email())
                        ->setName(ConstantesForm::$FORM_REPETIR_EMAIL)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_REPETIR_EMAIL,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_VERIFICAR_EMAIL_IGUAL,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_REPETIR_EMAIL,
                        ])
        );

        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        /* Hierarquia */
        $arrayHierarquia = array();
        $arrayHierarquia[0] = ConstantesForm::$FORM_SELECT;
        foreach ($this->getHierarquia() as $hierarquia) {
            $arrayHierarquia[$hierarquia->getId()] = $hierarquia->getNome();
        }

        $inputSelectHierarquia = new Select();
        $inputSelectHierarquia->setName(ConstantesForm::$FORM_HIERARQUIA);
        $inputSelectHierarquia->setAttributes(array(
            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_HIERARQUIA,
            ConstantesForm::$FORM_ONCHANGE => 'mostrarBotaoDeInserirResponsavel(this.value);',
        ));
        $inputSelectHierarquia->setValueOptions($arrayHierarquia);
        $this->add($inputSelectHierarquia);

        /* Numeracao */
        $arrayNumeracao = array();
        $arrayNumeracao[0] = ConstantesForm::$FORM_SELECT;
        for ($indiceNumeroSubEquipe = 1; $indiceNumeroSubEquipe <= 24; $indiceNumeroSubEquipe++) {
            $numeroAjustado = str_pad($indiceNumeroSubEquipe, 2, 0, STR_PAD_LEFT);
            $arrayNumeracao[$indiceNumeroSubEquipe] = $numeroAjustado;
        }
        $inputSelectNumeracao = new Select();
        $inputSelectNumeracao->setName(ConstantesForm::$FORM_NUMERACAO);
        $inputSelectNumeracao->setAttributes(array(
            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_GUI_INPUT,
            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_NUMERACAO,
            ConstantesForm::$FORM_ONCHANGE => 'mostrarBotaoDeInserirDadosComplementares(this.value);',
        ));
        $inputSelectNumeracao->setValueOptions($arrayNumeracao);
        $this->add($inputSelectNumeracao);
    }

    function getAlunos() {
        return $this->alunos;
    }

    function setAlunos($alunos) {
        $this->alunos = $alunos;
    }

    function getHierarquia() {
        return $this->hierarquia;
    }

    function setHierarquia($hierarquia) {
        $this->hierarquia = $hierarquia;
    }

}
