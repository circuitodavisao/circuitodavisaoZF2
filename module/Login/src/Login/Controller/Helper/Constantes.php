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
    public static $INPUT_EMAIL = 'email';
    public static $INPUT_SENHA = 'senha';
    public static $INPUT_CSRF = 'csrf';
    public static $MENSAGEM_ERRO_CSRF = 'Poss&iacute;vel ataque CSRF';
    public static $IMAGEM_LOGO = 'img/logos/logo_circuito.png';
    public static $CLASS_HIDDEN = 'hidden';
    /* Traduções */
    public static $TRADUCAO_NOME_APLICACAO = 'View of the Circuit';
    public static $TRADUCAO_USUARIO = 'User';
    public static $TRADUCAO_SENHA = 'Password';
    public static $TRADUCAO_ENTRAR = 'Sign in';
    public static $TRADUCAO_ESQUECEU_SENHA = 'Forgot password?';
    public static $TRADUCAO_FALHA_LOGIN = 'The informed login details do not match an account in our records. Remember that your password is case sensitive.';

}
