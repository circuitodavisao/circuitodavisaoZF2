<?php

namespace Cadastro\Form;

/**
 * Nome: ConstantesForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Constantes para os formularios
 */
class ConstantesForm {
    /* Formulários */

    public static $FORM_METHOD = 'method';
    public static $FORM_POST = 'POST';
    public static $FORM_ID = 'id';
    public static $FORM_DIA_DA_SEMANA = 'dia_da_semana';
    public static $FORM_HORA = 'hora';
    public static $FORM_ICONE_HORA = 'fa-clock-o';
    public static $FORM_CEP_LOGRADOURO = 'cep_logradouro';
    public static $FORM_COMPLEMENTO = 'complemento';
    public static $FORM_ICONE_COMPLEMENTO = 'fa-location-arrow';
    public static $FORM_NOME_HOSPEDEIRO = 'nome_hospedeiro';
    public static $FORM_ICONE_NOME_HOSPEDEIRO = 'fa-terminal';
    public static $FORM_DDD_HOSPEDEIRO = 'ddd_hospedeiro';
    public static $FORM_ICONE_DDD_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_TELEFONE_HOSPEDEIRO = 'telefone_hospedeiro';
    public static $FORM_ICONE_TELEFONE_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_BUSCAR_CEP_LOGRADOURO = 'buscar_cep_logradouro';
    public static $FORM_CLASS = 'class';
    public static $FORM_CLASS_GUI_INPUT = 'gui-input';
    public static $FORM_PLACEHOLDER = 'placeholder';
    public static $FORM_BTN_DEFAULT_DARK = 'btn ladda-button btn-default dark';
    public static $FORM_CSRF = 'csrf';
    public static $FORM_CELULA = 'CelulaForm';
    public static $FORM_ACTION = 'action';

    /* Validações */
    public static $VALIDACAO_NAME = 'name';
    public static $VALIDACAO_REQUIRED = 'required';
    public static $VALIDACAO_FILTER = 'filter';
    public static $VALIDACAO_STRING_TAGS = 'StripTags';
    public static $VALIDACAO_STRING_TRIM = 'StringTrim';
    public static $VALIDACAO_INT = 'Int';
    public static $VALIDACAO_STRING_TO_UPPER = 'StringToUpper';
    public static $VALIDACAO_VALIDATORS = 'validators';
    public static $VALIDACAO_NOT_EMPTY = 'NotEmpty';
    public static $VALIDACAO_STRING_LENGTH = 'StringLength';
    public static $VALIDACAO_OPTIONS = 'options';
    public static $VALIDACAO_ENCODING = 'encoding';
    public static $VALIDACAO_UTF_8 = 'UTF-8';
    public static $VALIDACAO_MIN = 'min';
    public static $VALIDACAO_MAX = 'max';

    /* Traduções */
    public static $TRADUCAO_HORA = 'Hour';
    public static $TRADUCAO_CEP_LOGRADOURO = 'CEP or Public Place';
    public static $TRADUCAO_COMPLEMENTO = 'Complement';
    public static $TRADUCAO_NOME_HOSPEDEIRO = 'Name Host';
    public static $TRADUCAO_DDD_HOSPEDEIRO = 'DDD';
    public static $TRADUCAO_TELEFONE_HOSPEDEIRO = 'Phone Host';
    public static $TRADUCAO_BUSCAR_CEP_LOGRADOURO = 'Search';

}
