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
    public static $FORM_SELECT = 'SELECT';
    public static $FORM_DIA_DA_SEMANA = 'dia_da_semana';
    public static $FORM_HORA = 'hora';
    public static $FORM_MINUTOS = 'minutos';
    public static $FORM_ICONE_HORA = 'fa-clock-o';
    public static $FORM_CEP_LOGRADOURO = 'cep_logradouro';
    public static $FORM_COMPLEMENTO = 'complemento';
    public static $FORM_UF = 'uf';
    public static $FORM_CIDADE = 'cidade';
    public static $FORM_BAIRRO = 'bairro';
    public static $FORM_LOGRADOURO = 'logradouro';
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
    public static $FORM_ONCLICK = 'onClick';
    public static $FORM_FUNCAO_BUSCAR_CEP = 'buscarEndereco(document.getElementById(\'cep_logradouro\').value);';
    public static $FORM_FUNCAO_BUSCAR_CEP_POR_ENTER = 'return submitEnter(this, event)';
    public static $FORM_ONBLUR = 'onblur';
    public static $FORM_ONKEYPRESS = 'onkeypress';
    public static $FORM_BTN_DEFAULT_DARK = 'btn ladda-button btn-default dark';
    public static $FORM_CSRF = 'csrf';
    public static $FORM_CELULA = 'CelulaForm';
    public static $FORM_ACTION = 'action';
    public static $FORM_READONLY = 'readonly';
    public static $FORM_DISABLED = 'disabled';
    public static $FORM_SUBMIT = 'submit';

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
    public static $TRADUCAO_MINUTOS = 'Minutes';
    public static $TRADUCAO_SELECIONE = 'SELECT';
    public static $TRADUCAO_CEP_LOGRADOURO = 'CEP';
    public static $TRADUCAO_COMPLEMENTO = 'Complement';
    public static $TRADUCAO_UF = 'UF';
    public static $TRADUCAO_CIDADE = 'City';
    public static $TRADUCAO_BAIRRO = 'Neighborhood';
    public static $TRADUCAO_LOGRADOURO = 'Public Place';
    public static $TRADUCAO_NOME_HOSPEDEIRO = 'Name Host';
    public static $TRADUCAO_DDD_HOSPEDEIRO = 'DDD';
    public static $TRADUCAO_TELEFONE_HOSPEDEIRO = 'Phone Host';
    public static $TRADUCAO_BUSCAR_CEP_LOGRADOURO = 'Search';
    public static $TRADUCAO_DIA_DA_SEMANA = 'Day of Week';
    public static $TRADUCAO_CADASTRO_CELULA = 'Registration Celula';
    public static $TRADUCAO_MULTIPLICACAO = 'Multiplication';
    public static $TRADUCAO_DIA_HORA = 'Day / Hour';
    public static $TRADUCAO_DADOS_DO_HOSPEDEIRO = 'Data from Host';
    public static $TRADUCAO_VALIDACAO_DIA_DA_SEMANA_REQUERIDO = 'Select one day of week';
    public static $TRADUCAO_VALIDACAO_HORA_REQUERIDO = 'Select Hour';
    public static $TRADUCAO_VALIDACAO_MINUTOS_REQUERIDO = 'Select Minutes';
    public static $TRADUCAO_VALIDACAO_HORA_INVALIDA = 'Invalid Hour ';
    public static $TRADUCAO_VALIDACAO_CEP_LOGRADOURO_REQUERIDO = 'Enter CEP';
    public static $TRADUCAO_VALIDACAO_CEP_LOGRADOURO_INVALIDO = 'Invalid CEP';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_REQUERIDO = 'Enter host name';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_MINIMO = 'Enter at least 3 characters or more';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_MAXIMO = 'Enter at 80 characters';
    public static $TRADUCAO_VALIDACAO_DDD_HOSPEDEIRO_REQUERIDO = 'Enter DDD';
    public static $TRADUCAO_VALIDACAO_DDD_HOSPEDEIRO_INVALIDO = 'Invalid DDD';
    public static $TRADUCAO_VALIDACAO_TELEFONE_HOSPEDEIRO_REQUERIDO = 'Enter Phone';
    public static $TRADUCAO_VALIDACAO_TELEFONE_HOSPEDEIRO_INVALIDO = 'Invalid Phone';

    /* Layout */
    public static $LAYOUT_JS_CELULA = 'layout/layout-js-celula';
    public static $LAYOUT_STRING_JS_CELULA = 'layoutJSCelula';
    public static $LAYOUT_JS_CELULA_VALIDACAO = 'layout/layout-js-celula-validacao';
    public static $LAYOUT_STRING_JS_CELULA_VALIDACAO = 'layoutJSCelulaValidacao';
    public static $LAYOUT_JS_CELULAS = 'layout/layout-js-celulas';
    public static $LAYOUT_STRING_JS_CELULAS = 'layoutJSCelulas';
    public static $LAYOUT_NOME_HOSPEDEIRO_CELULA_CADASTRADO = 'nomeHospedeiroCelulaCadastrado';

    /* Listagem */
    public static $LISTAGEM_CELULAS = 'celulas';

}
