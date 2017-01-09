<?php

namespace Application\Controller\Helper;

/**
 * Nome: Constantes.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com constantes
 */
class Constantes {
    /* Constates Login */

    public static $CONTROLLER_LOGIN = 'Application\Controller\Login';
    public static $CONTROLLER_PRINCIPAL = 'Principal\Controller\Principal';
    public static $LOGIN_FORM = 'LoginForm';
    public static $FORM_LOGIN = 'formLogin';
    public static $ROUTE_LOGIN = 'login';
    public static $ACTION_PRINCIPAL = 'principal';
    public static $ACTION_SELECIONAR_PERFIL = 'selecionarPerfil';
    public static $URL_ESQUECEU_SENHA = 'esqueceuSenha';
    public static $ACTION_INDEX = 'index';
    public static $ACTION = 'action';
    public static $ACTION_LOGAR = 'logar';
    public static $INPUT_EMAIL = 'email';
    public static $INPUT_SENHA = 'senha';
    public static $INPUT_NOVA_SENHA = 'novaSenha';
    public static $CLASS_FORM_CONTROL = 'form-control';
    public static $INPUT_REPETIR_SENHA = 'repetirSenha';
    public static $INPUT_CSRF = 'csrf';
    public static $INPUT_ENTRAR = 'entrar';
    public static $MENSAGEM_ERRO_CSRF = 'Poss&iacute;vel ataque CSRF';
    public static $IMAGEM_LOGO = '/img/logos/logo_circuito.png';
    public static $LOADER_GIF = '/img/loader.gif';
    public static $LOADER_GIF_GRANDE = '/img/loader-grande.gif';
    public static $CLASS_HIDDEN = 'hidden';
    public static $TRADUCAO_NOME_APLICACAO = 'View of the Circuit';
    public static $TRADUCAO_USUARIO = 'User';
    public static $TRADUCAO_USUARIO_PLACEHOLDER = 'Enter your user';
    public static $TRADUCAO_SENHA = 'Password';
    public static $TRADUCAO_SENHA_PLACEHOLDER = 'Enter your password';
    public static $TRADUCAO_NOVA_SENHA_PLACEHOLDER = 'Enter your new password';
    public static $TRADUCAO_REPETIR_SENHA_PLACEHOLDER = 'Reenter your password';
    public static $TRADUCAO_ENTRAR = 'Sign in';
    public static $TRADUCAO_ESQUECEU_SENHA = 'Forgot password?';
    public static $TRADUCAO_PROBLEMA_DE_ACESSO = 'Access problem?';
    public static $TRADUCAO_FALHA_LOGIN = 'The informed login details do not match an account in our records.';
    public static $TRADUCAO_CAPSLOCK = 'Caps Lock is on.';
    public static $MENSAGEM = 'mensagem';
    public static $TIPO = 'tipo';
    public static $ID = 'id';
    public static $FUNCAO = 'funcao';
    public static $DIV = 'div';

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
    public static $INPUT_ID_PESSOA = 'idPessoa';
    public static $INPUT_DATA_NASCIMENTO = 'dataNascimento';
    public static $INPUT_TIPO = 'tipo';
    public static $INPUT_ALTERAR = 'alterar';
    public static $INDEX = '/';
    public static $ACTION_ESQUECEU_SENHA = 'esqueceuSenha';
    public static $ACTION_EMAIL_ENVIADO = 'emailEnviado';
    public static $ACTION_ALTERAR_SENHA = 'alterarSenha';
    public static $TRADUCAO_CANCELAR = 'Back';
    public static $TRADUCAO_VOLTAR = 'Back';
    public static $TRADUCAO_CONTINUAR = 'Continue';
    public static $TRADUCAO_CAPTCHA_LABEL = 'Please verify you are human';
    public static $TRADUCAO_ESQUECI_MINHA_SENHA = ' Because I forgot my password.';
    public static $TRADUCAO_ESQUECI_MEU_USUARIO = ' I know my password, but I do not remember my login.';
    public static $TRADUCAO_ENVIAR_EMAIL = 'Send email';
    public static $TRADUCAO_VERIFICAR_USUARIO = 'Check User';
    public static $TRADUCAO_PERGUNTA_ESQUECI_SENHA = 'Because you can not enter the View of the Circuit?';
    public static $TRADUCAO_TITULO_ESQUECI_MINHA_SENHA = 'We can help you reset your password. First, enter your email and follow the instructions.';
    public static $TRADUCAO_INFORME_2_DIGITO_CPF_DATA_NASCIMENTO = 'Tell us what the last 2 digits of your <b>Social Security number</b> and <b>date of birth</b>.';
    public static $TRADUCAO_SOLICITACAO_ENVIADA_AS = 'request sent at';
    public static $TRADUCAO_ACESSE_O = 'Access';
    public static $TRADUCAO_MENSAGEM_TEM_DURACAO_24_HORAS = 'The message in your email lasts 24 hours';
    public static $TRADUCAO_SEU_LOGIN_E = 'Your username is';
    public static $TRADUCAO_PESSOA_NAO_ENCONTRADA = 'Person not found in our records!';
    public static $TRADUCAO_PESSOA_INATIVADA = 'Your user is inactive!';
    public static $TRADUCAO_EMAIL_ENVIADO = 'Check your email with the recovery of your password link.';

    /* Constantes recuperar senha */
    public static $TRADUCAO_EMAIL_TITULO_RECUPERAR_SENHA = 'Recover Password';
    public static $TRADUCAO_EMAIL_MENSAGEM_RECUPERAR_SENHA = '<pre>Hello #email</pre><pre>We received a request to reset the password for your account CircuitoDaVisão.com . To reset your password, use the links below:</pre><pre>Reset your password using a Web browser:</pre><pre><a href="158.69.124.139/recuperarSenha/#id">158.69.124.139/recuperarSenha/#id</a></pre><pre>If you did not request a password reset, you can ignore this message and your password will not change.</pre>';
    public static $TRADUCAO_ALTERAR = 'Update';
    public static $RECUPERAR_SENHA_FORM = 'RecuperarSenhaForm';
    public static $NOVA_SENHA_FORM = 'NovaSenhaForm';
    public static $FORM_RECUPERAR_SENHA = 'formRecuperarSenha';
    public static $FORM_NOVA_SENHA = 'formNovaSenha';
    public static $FORM_RECUPERAR_SENHA_6_CARACTERES = 'At least 6 characters';
    public static $FORM_RECUPERAR_SENHA_LETRA_NUMERO = 'Your password must contain at least <strong> a </ strong> letter and <strong> one </ strong> number.';
    public static $TRADUCAO_SENHA_ATUALIZADA_COM_SUCESSO = 'Your password has been changed successfully!';
    public static $TRADUCAO_ACESSAR = 'To access';

    /* Constantes selecionar perfil */
    public static $PERFIS_DE_ACESSO = 'perfiDeAcesso';
    public static $RESPONSABILIDADES = 'responsabilidades';
    public static $PESSOA = 'pessoa';
    public static $IMAGEM_LOGO_PEQUENA = '/img/logos/new_logo-circuito.png';
    public static $TRADUCAO_QUAL_PERFIL = 'What is the profile you want to use?';
    public static $TRADUCAO_SELECIONE_PERFIL = 'Select one profile from the list.';

    /* Constantes Principal */
    public static $IMAGEM_LOGO_BRANCA = '/img/logos/logo_white-circuito.png';
    public static $URL_PRE_SAIDA = 'preSaida';
    public static $URL_SAIR = 'Sair';
    public static $URL_PRINCIPAL = 'principal';
    public static $STRING_LINK_TEXTO = 'CircuitoDaVis&atilde;o.com.br';
    public static $TRADUCAO_SAIR = 'Logout';

    /* Pre saida */
    public static $TRADUCAO_SESSAO_ENCERRADA = 'Closed Session';
    public static $TRADUCAO_OLA = 'Hello';
    public static $TRADUCAO_PRE_SAIDA = 'Use the LOGOUT option <a href="/">CircuitoDaVisão.com.br</a> when using a device that is not of particular use. The <a href="/">CircuitoDaVisão.com.br</a> home page will be opened in 30 seconds.';
    public static $TRADUCAO_ENTRAR_NOVAMENTE = 'Reenter';

    /* Forms */
    public static $FORM_STRING_METHOD = 'method';
    public static $FORM_STRING_POST = 'POST';
    public static $FORM_STRING_ID = 'id';
    public static $FORM_STRING_CLASS = 'class';
    public static $FORM_STRING_CLASS_BTN_PRETO = 'btn ladda-button btn-primary-circuito';
    public static $FORM_STRING_CLASS_BTN_DEFAULT_ESCURO = 'btn ladda-button btn-default dark';
    public static $FORM_STRING_DISABLED = 'disabled';
    public static $FORM_STRING_LABEL = 'label';
    public static $FORM_STRING_LABEL_ATRIBUTES = 'label_attributes';
    public static $FORM_STRING_VALUE = 'value';
    public static $FORM_STRING_ATTRIBUTES = 'attributes';
    public static $FORM_STRING_OPTIONS = 'options';
    public static $FORM_STRING_VALUE_OPTIONS = 'value_options';
    public static $FORM_STRING_NAME = 'name';
    public static $FORM_STRING_CLASS_GUI_INPUT = 'gui-input';
    public static $FORM_STRING_REQUIRED = 'required';
    public static $FORM_STRING_TRUE = 'true';
    public static $FORM_STRING_ARIA_REQUIRED = 'aria-required';
    public static $FORM_STRING_ONKEYPRESS = 'onkeypress';
    public static $FORM_STRING_ONKEYUP = 'onkeyup';
    public static $FORM_STRING_ONCLICK = 'onClick';
    public static $FORM_STRING_PLACEHOLDER = 'placeholder';
    public static $FORM_STRING_MAXLENGTH = 'maxlength';
    public static $FORM_STRING_FUNCAO_CAPSLOCK = 'capsLock(event)';
    public static $FORM_STRING_FUNCAO_VERIFICAR_SENHAS = 'verificarSenhas(this.value, #tipo)';
    public static $FORM_STRING_FUNCAO_VALIDAR_DATA_NASCIMENTO = 'validarDataNascimento(this.value, \'#idIcone\', \'#idBotaoSubmit\')';
    public static $FORM_STRING_FUNCAO_VALIDAR_DIGITOS_CPF = 'validarDigitosDoCPF(this.value, \'#idIcone\', \'#idBotaoSubmit\')';
    public static $FORM_STRING_FUNCAO_DESABILITAR_ELEMENTO = 'desabilitarElemento(\'#id\')';

    /* Entidade do banco de dados */
    public static $ENTITY_PESSOA = 'Application\Model\Entity\Pessoa';
    public static $ENTITY_ENTIDADE = 'Application\Model\Entity\Entidade';
    public static $ENTITY_PESSOA_EMAIL = 'email';
    public static $ENTITY_PESSOA_DOCUMENTO = 'documento';
    public static $ENTITY_PESSOA_DATA_NASCIMENTO = 'data_nascimento';
    public static $ENTITY_PESSOA_DATA_CRIACAO = 'data_criacao';
    public static $ENTITY_PESSOA_DATA_INATIVACAO = 'data_inativacao';
    public static $ENTITY_PESSOA_TOKEN = 'token';
    public static $ENTITY_PESSOA_NOME = 'nome';
    public static $ENTITY_PERFIL_ACESSO = 'Application\Model\Entity\PerfilAcesso';
    public static $ENTITY_PESSOA_PERFIL_ACESSO = 'Application\Model\Entity\PessoaPerfilAcesso';
    public static $ENTITY_PESSOA_PERFIL_ACESSO_ID_PESSOA = 'id_pessoa';

    /* Templates */
    public static $TEMPLATE_SELECIONAR_PERFIL = 'layout/layout-selecionar-perfil';
    public static $TEMPLATE_PRE_SAIDA = 'layout/layout-pre-saida';
    public static $TEMPLATE_PRINCIPAL = 'layout/layout-principal';
    public static $TEMPLATE_JS_INDEX = 'layout/layout-js-index';
    public static $STRING_JS_INDEX = 'layoutJSIndex';
    public static $TEMPLATE_LOGIN_TOP = 'layout/layout-login-top';
    public static $STRING_LOGIN_TOP = 'layoutLoginTop';
    public static $TEMPLATE_LOGIN_BOTTON = 'layout/layout-login-botton';
    public static $STRING_LOGIN_BOTTON = 'layoutLoginBotton';
    public static $TEMPLATE_JS_RECUPERAR_ACESSO = 'layout/layout-js-recuperar-acesso';
    public static $STRING_JS_RECUPERAR_ACESSO = 'layoutJSRecuperarAcesso';
    public static $TEMPLATE_JS_NOVA_SENHA_VALIDACAO = 'layout/layout-js-nova-senha-validacao';
    public static $STRING_JS_NOVA_SENHA_VALIDACAO = 'validarSenhas';
    public static $TEMPLATE_JS_MODAL_SELECIONAR_PERFIL = 'layout/layout-js-modal-selecionar-perfil';
    public static $STRING_JS_MODAL_SELECIONAR_PERFIL = 'layoutJSModalSelecionarPerfil';
    public static $TEMPLATE_JS_PRE_SAIDA = 'layout/layout-js-pre-saida';
    public static $STRING_JS_PRE_SAIDA = 'layoutJSPreSaida';
    public static $TEMPLATE_JS_PRINCIPAL = 'layout/layout-js-principal';
    public static $STRING_JS_PRINCIPAL = 'layoutJSPrincipal';

    /* Geral */
    public static $NOME_APLICACAO = 'CircuitoDaVisao';
    public static $COR_BOTAO = 'primary';


    /* Lançamento */
    public static $ENTITY_GRUPO_PESSOA = 'Application\Model\Entity\GrupoPessoa';
    public static $ENTITY_GRUPO_PESSOA_TIPO = 'Application\Model\Entity\GrupoPessoaTipo';
    public static $ENTITY_EVENTO = 'Application\Model\Entity\Evento';
    public static $ENTITY_EVENTO_FREQUENCIA = 'Application\Model\Entity\EventoFrequencia';
    public static $ENTITY_GRUPO = 'Application\Model\Entity\Grupo';
    public static $ENTITY_PESSOA_ID = 'pessoa_id';
    public static $ENTITY_DATA_INATIVACAO = 'data_inativacao';
    public static $ENTITY_TIPO_ID = 'tipo_id';
    public static $ENTIDADE = 'entidade';
    public static $ENTIDADE_INATIVA = 'entidadeInativa';
    public static $DOCTRINE_ORM_ENTITY_MANAGER = 'doctrineORMEntityManager';
    public static $LANCAMENTO_ORM = 'lancamentoORM';
    public static $TURMA = 'turma';
    public static $GRUPO = 'grupo';
    public static $QUANTIDADE_MAXIMA_PESSOAS_NO_LANÇAMENTO = 60;
    public static $ABA_SELECIONADA = 'abaSelecionada';
    public static $CICLO_SELECIONADO = 'cicloSelecionado';
    public static $QUANTIDADE_EVENTOS_CICLOS = 'quantidadeDeEventosNoCiclo';
    public static $QUANTIDADE_PESSOAS_CADASTRADAS = 'quantidadeDePessoasCadastradas';
    public static $VALIDACAO = 'validacao';
    public static $VALIDACAO_NESSE_MES = 'validacaoNesseMes';
    public static $VALIDACAO_ENTIDADE_INATIVA = 'validacaoEntidadeInativa';
    public static $STATUS_ENVIO = 'statusEnvio';
    public static $TEMPLATE_JS_LANCAMENTO = 'layout/layout-js-lancamento';
    public static $TEMPLATE_JS_CADASTRAR_PESSOA = 'layout/layout-js-cadastrar-pessoa';
    public static $TEMPLATE_JS_CADASTRAR_PESSOA_VALIDACAO = 'layout/layout-js-cadastrar-pessoa-validacao';
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS = 'layout/layout-js-lancamento-modal-eventos';
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layout/layout-js-lancamento-modal-muitos-cadastros';
    public static $STRING_JS_LANCAMENTO = 'layoutJSLancamento';
    public static $STRING_JS_CADASTRAR_PESSOA = 'layoutJSCadastrarPessoa';
    public static $STRING_JS_CADASTRAR_PESSOA_VALIDACAO = 'layoutJSCadastrarPessoaValidacao';
    public static $STRING_JS_LANCAMENTO_MODAL_EVENTOS = 'layoutJSLancamentoModalEventos';
    public static $STRING_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layoutJSLancamentoModalMuitosCadastros';
    public static $ROUTE_LANCAMENTO = 'lancamento';
    public static $PAGINA_SALVAR_PESSOA = 'SalvarPessoa';
    public static $ONCLICK_ABRIR_MODAL = 'onclick="abrirModal();"';
    public static $PAGINA = 'pagina';
    public static $PAGINA_CADASTRAR_PESSOA = 'CadastrarPessoa';
    public static $PAGINA_CADASTRAR_PESSOA_REVISAO = 'CadastrarPessoaRevisao';
    public static $PAGINA_FICHA_REVISAO = 'FichaRevisao';
    public static $PAGINA_FUNCOES = 'Funcoes';
    public static $PAGINA_MUDAR_FREQUENCIA = 'MudarFrequencia';
    public static $PAGINA_ENVIAR_RELATORIO = 'EnviarRelatorio';
    public static $PAGINA_ALTERAR_NOME = 'AlterarNome';
    public static $PAGINA_REMOVER_PESSOA = 'RemoverPessoa';
    public static $CONTROLLER_LANCAMENTO = 'Application\Controller\Lancamento';
    public static $CLASS_CENTER_BLOCk = 'center-block';
    public static $NBSP = '&nbsp;';
    public static $CLASS_PHONE = 'phone';
    public static $FORM_CADASTRAR_PESSOA = 'CadastrarPessoaForm';
    /* Tradução */
    public static $TRADUCAO_ENVIAR_RELATORIO = 'Send Report';
    public static $TRADUCAO_GIRE_O_CELULAR = '<h3>ROTATE THE CELLULAR</h3><span>Turn your phone to landscape to view all your events.</span>';
    public static $TRADUCAO_RELATORIO_ATUALIZADO = 'Report <strong>Updated!</strong>';
    public static $TRADUCAO_RELATORIO_DEZATUALIZADO = 'Report outdated! <strong>Send your report</strong>';
    public static $TRADUCAO_MES_ATUAL = 'Current Month';
    public static $TRADUCAO_MES_ANTERIOR = 'Last Month';
    public static $TRADUCAO_CARREGANDO = 'Loading';
    public static $TRADUCAO_CICLO = 'Cycle';
    public static $TRADUCAO_PERIODO = 'Period';
    public static $TRADUCAO_LIMITE_CADASTROS = 'Maximum limit of entries achieved!';
    public static $TRADUCAO_NOVO = 'New';
    public static $TRADUCAO_NOVO_CADASTRO = 'New Register';
    public static $TRADUCAO_NOVO_CADASTRO_LABEL = 'Person at the launch line';
    public static $TRADUCAO_NOME = 'Full Name';
    public static $TRADUCAO_DDD = 'DDD';
    public static $TRADUCAO_TELEFONE = 'Phone';
    public static $TRADUCAO_TIPO = 'Type';
    public static $TRADUCAO_NUCLEO_CELULA = 'Perfect Core';
    public static $TRADUCAO_SELECIONE = 'SELECT';
    public static $TRADUCAO_CADASTRAR = 'Register';
    public static $TRADUCAO_VALIDACAO_NOME_VAZIO = 'Enter Full Name';
    public static $TRADUCAO_VALIDACAO_NOME_MIN = 'Enter at least 3 characters or more';
    public static $TRADUCAO_VALIDACAO_NOME_MAX = 'Enter at 80 characters';
    public static $TRADUCAO_VALIDACAO_DDD_VAZIO = 'Enter DDD';
    public static $TRADUCAO_VALIDACAO_DDD_NUMERO = 'Enter only numbers';
    public static $TRADUCAO_VALIDACAO_DDD_MIN = 'Enter at 2 numbers';
    public static $TRADUCAO_VALIDACAO_DDD_MAX = 'Enter at 2 numbers';
    public static $TRADUCAO_VALIDACAO_TELEFONE_VAZIO = 'Enter Phone';
    public static $TRADUCAO_VALIDACAO_TELEFONE_NUMERO = 'Enter only numbers';
    public static $TRADUCAO_VALIDACAO_TELEFONE_MIN = 'Enter at least 8 numbers';
    public static $TRADUCAO_VALIDACAO_TELEFONE_MAX = 'Enter at 9 numbers';
    public static $TRADUCAO_VALIDACAO_TIPO_VAZIO = 'Choose a Type';
    public static $TRADUCAO_PESSOA_CADASTRADA = 'Person Registered';
    /* Fomulário */
    public static $INPUT_NOME = 'nome';
    public static $INPUT_DDD = 'ddd';
    public static $INPUT_TELEFONE = 'telefone';
    public static $INPUT_NUCLEO_PERFEITO = 'nucleoPerfeito';
    public static $INPUT_VOLTAR = 'voltar';
    public static $INPUT_CADASTRAR = 'cadastrar';
    public static $CLASS_BTN_DANGER_DARK = 'btn-danger dark';
    public static $ICONE_USER = 'fa-user';
    public static $ICONE_PHONE = 'fa-phone-square';
    public static $INPUT_SELECTED = 'selected';
    public static $NOME_PESSOA_CADASTRADA = 'nomePessoaCadastrada';
    /* Rotas */
    public static $ROUTE_REMOVER_PESSOA = 'RemoverPessoa';
    public static $ROUTE_CADASTRAR_PESSOA_REVISAO = 'CadastrarPessoaRevisao';
    public static $ROUTE_INDEX = 'Index';
    public static $ROUTE_FICHA_REVISAO = 'FichaRevisao';

    /* Fim lançamento */
}
