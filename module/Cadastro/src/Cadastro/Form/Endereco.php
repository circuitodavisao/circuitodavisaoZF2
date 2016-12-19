<?php

namespace Cadastro\Form;

use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Nome: EventoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para adicionar o endereço aos formularios
 */
class Endereco {

    /**
     * Adiciona os campos de endereço no formulario Zend passado
     * @param Form $formulario
     */
    public static function MontaEnderecoFormulario(Form $formulario) {
        /* CEP ou Logradouro */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CEP_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CEP_LOGRADOURO,
                            ConstantesForm::$FORM_ONBLUR => ConstantesForm::$FORM_FUNCAO_BUSCAR_CEP,
                            ConstantesForm::$FORM_ONKEYPRESS => ConstantesForm::$FORM_FUNCAO_BUSCAR_POR_ENTER
                        ])
        );

        /* UF */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_UF)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_UF,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_UF,
                        ])
        );

        /* Cidade */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_CIDADE)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_CIDADE,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_CIDADE,
                        ])
        );

        /* Bairro */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_BAIRRO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_BAIRRO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_BAIRRO,
                        ])
        );

        /* Logradouro */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_LOGRADOURO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_LOGRADOURO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_LOGRADOURO,
                        ])
        );

        /* Complemento */
        $formulario->add(
                (new Text())
                        ->setName(ConstantesForm::$FORM_COMPLEMENTO)
                        ->setAttributes([
                            ConstantesForm::$FORM_CLASS => ConstantesForm::$FORM_CLASS_FORM_CONTROL,
                            ConstantesForm::$FORM_ID => ConstantesForm::$FORM_COMPLEMENTO,
                            ConstantesForm::$FORM_PLACEHOLDER => ConstantesForm::$TRADUCAO_COMPLEMENTO,
                        ])
        );
    }

}
