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
    public static $FORM_ID_ALUNO_SELECIONADO = 'idAlunoSelecionado';
    public static $FORM_SELECT = 'SELECT';
    public static $FORM_DIA_DA_SEMANA = 'dia_da_semana';
    public static $FORM_HIERARQUIA = 'hierarquia';
    public static $FORM_DATA_NASCIMENTO = 'dataNascimento';
    public static $FORM_NOME_ALUNO = 'nomeAluno';
    public static $FORM_NUMERACAO = 'numeracao';
    public static $FORM_HORA = 'hora';
    public static $FORM_MINUTOS = 'minutos';
    public static $FORM_ICONE_HORA = 'fa-clock-o';
    public static $FORM_CEP_LOGRADOURO = 'cep_logradouro';
    public static $FORM_CPF = 'cpf';
    public static $FORM_EMAIL = 'email';
    public static $FORM_REPETIR_EMAIL = 'repetirEmail';
    public static $FORM_COMPLEMENTO = 'complemento';
    public static $FORM_UF = 'uf';
    public static $FORM_HIDDEN = 'hidden';
    public static $FORM_CIDADE = 'cidade';
    public static $FORM_BAIRRO = 'bairro';
    public static $FORM_LOGRADOURO = 'logradouro';
    public static $FORM_ICONE_COMPLEMENTO = 'fa-location-arrow';
    public static $FORM_ICONE_DATABASE = 'fa-database';
    public static $FORM_NOME_HOSPEDEIRO = 'nome_hospedeiro';
    public static $FORM_NOME = 'nome';
    public static $FORM_ICONE_NOME_HOSPEDEIRO = 'fa-terminal';
    public static $FORM_DDD_HOSPEDEIRO = 'ddd_hospedeiro';
    public static $FORM_ICONE_DDD_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_TELEFONE_HOSPEDEIRO = 'telefone_hospedeiro';
    public static $FORM_ICONE_TELEFONE_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_BUSCAR_CEP_LOGRADOURO = 'buscar_cep_logradouro';
    public static $FORM_CLASS = 'class';
    public static $FORM_CLASS_GUI_INPUT = 'gui-input';
    public static $FORM_CLASS_FORM_CONTROL = 'form-control';
    public static $FORM_CLASS_DATE = 'date';
    public static $FORM_PLACEHOLDER = 'placeholder';
    public static $FORM_ONCLICK = 'onClick';
    public static $FORM_ONCHANGE = 'onChange';
    public static $FORM_FUNCAO_BUSCAR_CEP = 'buscarEndereco(document.getElementById(\'cep_logradouro\').value);';
    public static $FORM_FUNCAO_BUSCAR_CPF = 'buscarCPF()';
    public static $FORM_FUNCAO_BUSCAR_EMAIL = 'buscarEmail()';
    public static $FORM_FUNCAO_VERIFICAR_EMAIL_IGUAL = 'verificarEmailIgual(this.value);';
    public static $FORM_FUNCAO_VALIDAR_FORMULARIO = 'validarFormulario();';
    public static $FORM_FUNCAO_BUSCAR_POR_ENTER = 'return submitEnter(this, event)';
    public static $FORM_ONBLUR = 'onblur';
    public static $FORM_ONKEYPRESS = 'onkeypress';
    public static $FORM_BTN_DEFAULT_DARK = 'btn ladda-button btn-default dark';
    public static $FORM_CSRF = 'csrf';
    public static $FORM_CELULA = 'CelulaForm';
    public static $FORM = 'Form';
    public static $EXTRA = 'extra';
    public static $FORM_ENDERECO_HIDDEN = 'enderecoHidden';
    public static $FORM_ACTION = 'action';
    public static $FORM_READONLY = 'readonly';
    public static $FORM_DISABLED = 'disabled';
    public static $FORM_SUBMIT = 'submit';
    public static $FORM_TIPO = 'tipo';

    /* Formulario de Celulas */
    public static $STRING_DIV_CONFIRMACAO = 'divConfirmacao';
    public static $STRING_ICONE_PENCIL = '<i class="fa fa-pencil" aria-hidden="true"></i>';
    public static $STRING_ICONE_TIMES = '<i class="fa fa-times" aria-hidden="true"></i>';
    public static $STRING_ICONE_PLUS = '<i class="fa fa-plus" aria-hidden="true"></i>';
    public static $STRING_ICONE_ARROW_LEFT = '<i class="fa fa-arrow-left" aria-hidden="true"></i>';
    public static $STRING_ICONE_ARROW_RIGHT = '<i class="fa fa-arrow-right" aria-hidden="true"></i>';
    public static $STRING_HASHTAG = '#';
    public static $STRING_FUNCAO_PRE_VALIDACAO_CELULA = 'preValidacao(1)';
    public static $STRING_FUNCAO_PRE_VALIDACAO_CULTO = 'preValidacao(2)';

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
    public static $TRADUCAO_CPF = 'CPF';
    public static $TRADUCAO_EMAIL = 'Email';
    public static $TRADUCAO_REPETIR_EMAIL = 'Repeat Email';
    public static $TRADUCAO_CEP_LOGRADOURO_SITE_CORREIOS = ' - Do not know your ZIP code <u><a target="_blank" href="http://www.buscacep.correios.com.br/sistemas/buscacep/">Click Here</a></u>';
    public static $TRADUCAO_COMPLEMENTO = 'Complement';
    public static $TRADUCAO_UF = 'UF';
    public static $TRADUCAO_CIDADE = 'City';
    public static $TRADUCAO_BAIRRO = 'Neighborhood';
    public static $TRADUCAO_LOGRADOURO = 'Public Place';
    public static $TRADUCAO_NOME_HOSPEDEIRO = 'Name Host';
    public static $TRADUCAO_NOME = 'Name';
    public static $TRADUCAO_DATA_NASCIMENTO = 'Date of Birth';
    public static $TRADUCAO_EQUIPES = 'Teams';
    public static $TRADUCAO_HOSPEDEIRO = 'Host';
    public static $TRADUCAO_ENDERECO = 'Address';
    public static $TRADUCAO_DDD_HOSPEDEIRO = 'DDD';
    public static $TRADUCAO_TELEFONE_HOSPEDEIRO = 'Phone Host';
    public static $TRADUCAO_BUSCAR_CEP_LOGRADOURO = 'Search';
    public static $TRADUCAO_DIA_DA_SEMANA = 'Day of Week';
    public static $TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO = 'Day';
    public static $TRADUCAO_CADASTRO_CELULA = 'Registration Cell';
    public static $TRADUCAO_CADASTRO_CULTO = 'Registration <b class="text-danger">Cult</b>';
    public static $TRADUCAO_NOVA = 'New';
    public static $TRADUCAO_NOVA_CELULA = 'New Cell';
    public static $TRADUCAO_NOVO_CULTO = 'New Cult';
    public static $TRADUCAO_MULTIPLICACAO = 'Multiplication';
    public static $TRADUCAO_LISTAGEM_CULTOS = 'Listing <b class="text-danger">Cults</b>';
    public static $TRADUCAO_LISTAGEM_CELULAS = 'Listing Cells of';
    public static $TRADUCAO_DIA_HORA = 'Day / Hour';
    public static $TRADUCAO_DADOS_DO_HOSPEDEIRO = 'Data from Host';
    public static $TRADUCAO_ALTERAR = 'Change';
    public static $TRADUCAO_BUSCAR = 'Search';
    public static $TRADUCAO_VALIDACAO_DIA_DA_SEMANA_REQUERIDO = 'Select one day of week';
    public static $TRADUCAO_VALIDACAO_HORA_REQUERIDO = 'Select Hour';
    public static $TRADUCAO_VALIDACAO_MINUTOS_REQUERIDO = 'Select Minutes';
    public static $TRADUCAO_VALIDACAO_HORA_INVALIDA = 'Invalid Hour ';
    public static $TRADUCAO_VALIDACAO_CEP_LOGRADOURO_REQUERIDO = 'Enter CEP';
    public static $TRADUCAO_VALIDACAO_CEP_LOGRADOURO_INVALIDO = 'Invalid CEP';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_REQUERIDO = 'Enter host name';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_MINIMO = 'Enter at least 3 characters or more';
    public static $TRADUCAO_VALIDACAO_NOME_HOSPEDEIRO_MAXIMO = 'Enter at 80 characters';
    public static $TRADUCAO_VALIDACAO_NOME_REQUERIDO = 'Enter Name';
    public static $TRADUCAO_VALIDACAO_NOME_MINIMO = 'Enter at least 3 characters or more';
    public static $TRADUCAO_VALIDACAO_NOME_MAXIMO = 'Enter at 30 characters';
    public static $TRADUCAO_VALIDACAO_DDD_HOSPEDEIRO_REQUERIDO = 'Enter DDD';
    public static $TRADUCAO_VALIDACAO_DDD_HOSPEDEIRO_INVALIDO = 'Invalid DDD';
    public static $TRADUCAO_VALIDACAO_TELEFONE_HOSPEDEIRO_REQUERIDO = 'Enter Phone';
    public static $TRADUCAO_VALIDACAO_TELEFONE_HOSPEDEIRO_INVALIDO = 'Invalid Phone';
    public static $TRADUCAO_CONFIRMACAO_CADASTRO_CELULA = 'Confirmation <strong><span class="text-danger">Cell Creation</span></strong>';
    public static $TRADUCAO_CONFIRMACAO_EXCLUSAO_CELULA = 'Confirmation <strong><span class="text-danger">Cell Exclusion</span></strong>';
    public static $TRADUCAO_CONFIRMACAO_EXCLUSAO = 'Confirmation <strong><span class="text-danger">Exclusion</span></strong>';
    public static $TRADUCAO_PERGUNTA_CADASTRO_CELULA = 'The data are correct?';
    public static $TRADUCAO_PERGUNTA_EXCLUSAO_CELULA = 'Really want to delete this cell?';
    public static $TRADUCAO_PERGUNTA_EXCLUSAO_CULTO = 'Really want to delete this cult?';
    public static $TRADUCAO_CIENCIA_CADASTRO_CELULA = 'I confirm that the data is correct';
    public static $TRADUCAO_CIENCIA_EXCLUSAO_CULTO = 'I am <span class="text-danger">aware</span> that by <span class="text-danger">excluding cult</span> is the same no longer appear in the <span class="text-danger">launch line</span>!';
    public static $TRADUCAO_CIENCIA_EXCLUSAO_CELULA = 'I am <span class="text-danger">aware</span> that by <span class="text-danger">excluding cell</span> is the same no longer appear in the <span class="text-danger">launch line</span>!';
    public static $TRADUCAO_MOTIVO_EXCLUSAO = 'What reason for exclusion?';
    public static $TRADUCAO_DESCREVA_MOTIVO_EXCLUSAO = 'Please specify the reason';
    public static $TRADUCAO_CONTINUAR = 'Continue';
    public static $TRADUCAO_DIGITE_SENHA_EXCUSAO_SENHA = 'Enter your password to confirm the deletion';
    public static $TRADUCAO_CONFIRMACAO = 'Confirm';
    public static $TRADUCAO_NUMERO_MAXIMO_CELULAS = 'Maximum number of cells reached!';

    /* Layout */
    public static $LAYOUT_JS_EVENTO = 'layout/layout-js-evento';
    public static $LAYOUT_STRING_JS_EVENTO = 'layoutJSEvento';
    public static $LAYOUT_JS_GRUPO = 'layout/layout-js-grupo';
    public static $LAYOUT_STRING_JS_GRUPO = 'layoutJSGrupo';
    public static $LAYOUT_JS_EVENTO_VALIDACAO = 'layout/layout-js-evento-validacao';
    public static $LAYOUT_STRING_JS_EVENTO_VALIDACAO = 'layoutJSEventoValidacao';
    public static $LAYOUT_JS_EVENTOS = 'layout/layout-js-eventos';
    public static $LAYOUT_JS_EVENTOS_VALIDACAO = 'layout/layout-js-eventos-validacao';
    public static $LAYOUT_STRING_JS_EVENTOS = 'layoutJSEventos';
    public static $LAYOUT_STRING_JS_EVENTOS_VALIDACAO = 'layoutJSEventosValidacao';
    public static $LAYOUT_NOME_HOSPEDEIRO_CELULA_CADASTRADO = 'nomeHospedeiroCelulaCadastrado';
    public static $LAYOUT_NOME_HOSPEDEIRO_CELULA_ALTERADA = 'nomeHospedeiroCelulaAlterada';
    public static $LAYOUT_NOME_HOSPEDEIRO_CELULA_EXCLUIDA = 'nomeHospedeiroCelulaExcluida';

    /* Listagem */
    public static $LISTAGEM_EVENTOS = 'eventos';
    public static $TITULO_DA_PAGINA = 'tituloDaPagina';
    public static $TIPO_EVENTO = 'tipoEvento';
    public static $LISTAGEM_CELULAS = 'celulas';
    public static $CELULA = 'celula';
    public static $EVENTO = 'evento';

}
