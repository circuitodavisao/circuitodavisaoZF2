<?php

namespace Login\Controller\Helper;

/**
 * Nome: Constantes.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com constantes
 */
class Constantes {
    /* Constates Login */

    public static $ENTITY_PESSOA = 'Login\Entity\Pessoa';
    public static $ENTITY_PESSOA_EMAIL = 'email';
    public static $ENTITY_PESSOA_DOCUMENTO = 'documento';
    public static $ENTITY_PESSOA_DATA_NASCIMENTO = 'data_nascimento';
    public static $ENTITY_PERFIL_ACESSO = 'Login\Entity\PerfilAcesso';
    public static $ENTITY_PESSOA_PERFIL_ACESSO = 'Login\Entity\PessoaPerfilAcesso';
    public static $CONTROLLER_LOGIN = 'Login\Controller\Login';
    public static $LOGIN_FORM = 'LoginForm';
    public static $FORM_LOGIN = 'formLogin';
    public static $ROUTE_LOGIN = 'login';
    public static $ACTION_ACESSO = 'acesso';
    public static $URL_ESQUECEU_SENHA = 'esqueceuSenha';
    public static $ACTION_INDEX = 'index';
    public static $ACTION = 'action';
    public static $ACTION_LOGAR = 'logar';
    public static $INPUT_EMAIL = 'email';
    public static $INPUT_SENHA = 'senha';
    public static $INPUT_CSRF = 'csrf';
    public static $INPUT_ENTRAR = 'entrar';
    public static $MENSAGEM_ERRO_CSRF = 'Poss&iacute;vel ataque CSRF';
    public static $IMAGEM_LOGO = '/img/logos/logo_circuito.png';
    public static $CLASS_HIDDEN = 'hidden';
    public static $TRADUCAO_NOME_APLICACAO = 'View of the Circuit';
    public static $TRADUCAO_USUARIO = 'User';
    public static $TRADUCAO_USUARIO_PLACEHOLDER = 'Enter your user';
    public static $TRADUCAO_SENHA = 'Password';
    public static $TRADUCAO_SENHA_PLACEHOLDER = 'Enter your password';
    public static $TRADUCAO_ENTRAR = 'Sign in';
    public static $TRADUCAO_ESQUECEU_SENHA = 'Forgot password?';
    public static $TRADUCAO_PROBLEMA_DE_ACESSO = 'Access problem?';
    public static $TRADUCAO_FALHA_LOGIN = 'The informed login details do not match an account in our records.';
    public static $TRADUCAO_CAPSLOCK = 'Caps Lock is on.';

    /* Constantes recuperar acesso */
    public static $RECUPERAR_ACESSO_FORM = 'RecuperarAcessoForm';
    public static $FORM_RECUPERAR_ACESSO = 'formRecuperarAcesso';
    public static $INPUT_OPCAO = 'opcao';
    public static $INPUT_BOTAO_CANCELAR = 'cancelar';
    public static $INPUT_BOTAO_CONTINUAR = 'continuar';
    public static $INPUT_BOTAO_VOLTAR = 'voltar';
    public static $INPUT_BOTAO_ENVIAR_EMAIL = 'enviarEmail';
    public static $INPUT_BOTAO_VERIFICAR_USUARIO = 'verificarUsuario';
    public static $INPUT_CAPTCHA = 'captcha';
    public static $INPUT_CPF = 'cpf';
    public static $INPUT_DATA_NASCIMENTO = 'dataNascimento';
    public static $INPUT_TIPO = 'tipo'; 
    public static $INDEX = '/';
    public static $TRADUCAO_CANCELAR = 'Back';
    public static $TRADUCAO_CONTINUAR = 'Continue';
    public static $TRADUCAO_CAPTCHA_LABEL = 'Please verify you are human';
    public static $TRADUCAO_ESQUECI_MINHA_SENHA = 'Because I forgot my password';
    public static $TRADUCAO_ESQUECI_MEU_USUARIO = 'I know my password, but I do not remember my login';
    public static $TRADUCAO_ENVIAR_EMAIL = 'Send email';
    public static $TRADUCAO_VERIFICAR_USUARIO = 'Check User';
    public static $TRADUCAO_PERGUNTA_ESQUECI_SENHA = 'Because you can not enter the View of the Circuit?';
    public static $TRADUCAO_TITULO_ESQUECI_MINHA_SENHA = 'We can help you reset your password. First, enter your email and follow the instructions.';
    public static $TRADUCAO_INFORME_2_DIGITO_CPF = 'Tell us what the last 2 digits of your CPF';
    public static $TRADUCAO_SOLICITACAO_ENVIADA_AS = 'request sent at';
    public static $TRADUCAO_ACESSE_O = 'Access';
    public static $TRADUCAO_MENSAGEM_TEM_DURACAO_24_HORAS = 'The message in your email lasts 24 hours';
    /* Forms */
    public static $FORM_STRING_METHOD = 'method';
    public static $FORM_STRING_POST = 'POST';
    public static $FORM_STRING_ID = 'id';
    public static $FORM_STRING_CLASS = 'class';
    public static $FORM_STRING_LABEL = 'label';
    public static $FORM_STRING_LABEL_ATRIBUTES = 'label_attributes';
    public static $FORM_STRING_VALUE = 'value';
    public static $FORM_STRING_VALUE_OPTIONS = 'value_options';
    public static $FORM_STRING_CLASS_GUI_INPUT = 'gui-input';
    public static $FORM_STRING_REQUIRED = 'required';
    public static $FORM_STRING_ONKEYPRESS = 'onkeypress';
    public static $FORM_STRING_ONCLICK = 'onClick';
    public static $FORM_STRING_PLACEHOLDER = 'placeholder';
    public static $FORM_STRING_MAXLENGTH = 'maxlength';
    public static $FORM_STRING_FUNCAO_CAPSLOCK = 'capsLock(event)';

}
