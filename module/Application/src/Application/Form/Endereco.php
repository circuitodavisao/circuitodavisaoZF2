<?php

namespace Application\Form;

use Application\Controller\Helper\Constantes;
use Zend\Form\Element\Text;
use Zend\Form\Element\Number;
use Zend\Form\Form;

/**
 * Nome: Endereco.php
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
                (new Number())
                        ->setName(Constantes::$FORM_CEP_LOGRADOURO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_CEP_LOGRADOURO,
                            'onpaste' => 'retrun false',
                            'ondrop' => 'return false',
                        ])
        );

        /* UF */
        $formulario->add(
                (new Text())
                        ->setName(Constantes::$FORM_UF)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_UF,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_UF,
                        ])
        );

        /* Cidade */
        $formulario->add(
                (new Text())
                        ->setName(Constantes::$FORM_CIDADE)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_CIDADE,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_CIDADE,
                        ])
        );

        /* Bairro */
        $formulario->add(
                (new Text())
                        ->setName(Constantes::$FORM_BAIRRO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_BAIRRO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_BAIRRO,
                        ])
        );

        /* Logradouro */
        $formulario->add(
                (new Text())
                        ->setName(Constantes::$FORM_LOGRADOURO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_LOGRADOURO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_LOGRADOURO,
                        ])
        );

        /* Complemento */
        $formulario->add(
                (new Text())
                        ->setName(Constantes::$FORM_COMPLEMENTO)
                        ->setAttributes([
                            Constantes::$FORM_CLASS => Constantes::$FORM_CLASS_FORM_CONTROL,
                            Constantes::$FORM_ID => Constantes::$FORM_COMPLEMENTO,
                            Constantes::$FORM_PLACEHOLDER => Constantes::$TRADUCAO_COMPLEMENTO,
                        ])
        );
    }

}
