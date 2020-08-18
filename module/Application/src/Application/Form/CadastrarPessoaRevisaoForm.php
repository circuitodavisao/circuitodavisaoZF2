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
 * Nome: CadastrarPessoaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario para cadastrar pessoa na tela de lançamento
 */
class CadastrarPessoaRevisaoForm extends Form {

    /**
     * Contrutor
     * @param String $name
     * @param array $grupoPessoaTipos
     */
    public function __construct($name = null, $pessoa = null) {
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

        $this->add(
                (new Text())
                        ->setName('email_revisao')
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => 'email_revisao',
                            Constantes::$FORM_STRING_PLACEHOLDER => 'Email',
                        ])
        );

        /**
         * Nome
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_PRIMEIRO_NOME)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_PRIMEIRO_NOME,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_PRIMEIRO_NOME,
                        ])
        );
        /**
         * Nome
         * Elemento do tipo text
         */
        $this->add(
                (new Text())
                        ->setName(Constantes::$INPUT_ULTIMO_NOME)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_ULTIMO_NOME,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_ULTIMO_NOME,
                        ])
        );

        /**
         * DDD
         * Elemento do tipo text
         */
        $this->add(
                (new Number())
                        ->setName(Constantes::$INPUT_DDD)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_DDD,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_DDD,
                        ])
        );
        /**
         * Telefone
         * Elemento do tipo text
         */
        $this->add(
                (new Number())
                        ->setName(Constantes::$INPUT_TELEFONE)
                        ->setAttributes([
                            Constantes::$FORM_STRING_CLASS => Constantes::$FORM_STRING_CLASS_GUI_INPUT,
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_TELEFONE,
                            Constantes::$FORM_STRING_PLACEHOLDER => Constantes::$TRADUCAO_TELEFONE,
                        ])
        );

        /* dataDeNascimento0 */
        $this->add(
                (new Hidden())
                        ->setName(Constantes::$INPUT_TIPO)
                        ->setAttributes([
                            Constantes::$FORM_ID => Constantes::$INPUT_TIPO,
                        ])
        );



        /* Dia da data de nascimento */
        $arrayDiaDataNascimento = array();
        $arrayDiaDataNascimento[0] = Constantes::$TRADUCAO_DIA;
        for ($indiceDiaDoMes = 1; $indiceDiaDoMes <= 31; $indiceDiaDoMes++) {
            $numeroAjustado = str_pad($indiceDiaDoMes, 2, 0, STR_PAD_LEFT);
            $arrayDiaDataNascimento[$indiceDiaDoMes] = $numeroAjustado;
        }
        $inputSelectDiaDataNascimento = new Select();
        $inputSelectDiaDataNascimento->setName(Constantes::$FORM_INPUT_DIA);
        $inputSelectDiaDataNascimento->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_DIA,
        ));
        $inputSelectDiaDataNascimento->setValueOptions($arrayDiaDataNascimento);
        $this->add($inputSelectDiaDataNascimento);

        /* Mês da data de nascimento */
        $arrayMesDataNascimento = array();
        $arrayMesDataNascimento[0] = Constantes::$TRADUCAO_MES;
        for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
            $numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
            $arrayMesDataNascimento[$indiceMesNoAno] = $numeroAjustado;
        }
        $inputSelectMesDataNascimento = new Select();
        $inputSelectMesDataNascimento->setName(Constantes::$FORM_INPUT_MES);
        $inputSelectMesDataNascimento->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_MES,
        ));
        $inputSelectMesDataNascimento->setValueOptions($arrayMesDataNascimento);
        $this->add($inputSelectMesDataNascimento);

        /* Ano da data de nascimento */
        $arrayAnoDataNascimento = array();
        $arrayAnoDataNascimento[0] = Constantes::$TRADUCAO_ANO;
        $anoAtual = date('Y');
        for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 100); $indiceAno--) {
            $arrayAnoDataNascimento[$indiceAno] = $indiceAno;
        }
        $inputSelectAnoDataNascimento = new Select();
        $inputSelectAnoDataNascimento->setName(Constantes::$FORM_INPUT_ANO);
        $inputSelectAnoDataNascimento->setAttributes(array(
            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
            Constantes::$FORM_ID => Constantes::$FORM_INPUT_ANO,
        ));
        $inputSelectAnoDataNascimento->setValueOptions($arrayAnoDataNascimento);
        $this->add($inputSelectAnoDataNascimento);

        $classOption = 'block mt15';
        $this->add(
                (new Radio())
                        ->setName(Constantes::$INPUT_NUCLEO_PERFEITO)
                        ->setAttributes([
                            Constantes::$FORM_STRING_ID => Constantes::$INPUT_NUCLEO_PERFEITO,
                        ])
                        ->setOptions([
                            Constantes::$FORM_STRING_VALUE_OPTIONS => array(
                                1 => array(
                                    Constantes::$FORM_STRING_VALUE => 'M',
                                    Constantes::$FORM_STRING_LABEL => ' MASCULINO',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                                2 => array(
                                    Constantes::$FORM_STRING_VALUE => 'F',
                                    Constantes::$FORM_STRING_LABEL => ' FEMININO',
                                    Constantes::$FORM_STRING_LABEL_ATRIBUTES => array(Constantes::$FORM_STRING_CLASS => $classOption),
                                ),
                            ),
                        ])
        );


        $this->add(
                (new Csrf())
                        ->setName(Constantes::$INPUT_CSRF)
        );

        if ($pessoa) {
            $telefoneCelular = substr($pessoa->getTelefone(), 2);
            $dddTelefone = substr($pessoa->getTelefone(), 0, 2);
            $nomeExplodido = explode(' ', $pessoa->getNome());
            $ultimo = count($nomeExplodido);
            $this->get(Constantes::$FORM_ID)->setValue($pessoa->getId());
            $this->get(Constantes::$INPUT_PRIMEIRO_NOME)->setValue($nomeExplodido[0]);

            if ($ultimo > 1) {
                $this->get(Constantes::$INPUT_ULTIMO_NOME)->setValue($nomeExplodido[$ultimo - 1]);
            }
            $this->get(Constantes::$INPUT_TELEFONE)->setValue($telefoneCelular);
            $this->get(Constantes::$INPUT_DDD)->setValue($dddTelefone);
        }
    }

}
