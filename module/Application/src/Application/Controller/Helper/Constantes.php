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
    public static $INPUT_USUARIO = 'usuario';
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
    public static $TRADUCAO_TITULO_ESQUECI_MINHA_SENHA = 'We can help you reset your password. First, enter your user and follow the instructions.';
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
    public static $ENTITY_ENTIDADE_TIPO = 'Application\Model\Entity\EntidadeTipo';
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
    public static $TEMPLATE_JS_RECUPERAR_SENHA = 'layout/layout-js-recuperar-senha';
    public static $STRING_JS_RECUPERAR_SENHA = 'layoutJSRecuperarSenha';
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
    public static $ENTITY_GRUPO_RESPONSAVEL = 'Application\Model\Entity\GrupoResponsavel';
    public static $ENTITY_GRUPO_PAI_FILHO = 'Application\Model\Entity\GrupoPaiFilho';
    public static $ENTITY_GRUPO_PESSOA_TIPO = 'Application\Model\Entity\GrupoPessoaTipo';
    public static $ENTITY_EVENTO = 'Application\Model\Entity\Evento';
    public static $ENTITY_EVENTO_CELULA = 'Application\Model\Entity\EventoCelula';
    public static $ENTITY_EVENTO_TIPO = 'Application\Model\Entity\EventoTipo';
    public static $ENTITY_HIERAQUIA = 'Application\Model\Entity\Hierarquia';
    public static $ENTITY_PESSOA_HIERAQUIA = 'Application\Model\Entity\PessoaHierarquia';
    public static $ENTITY_EVENTO_FREQUENCIA = 'Application\Model\Entity\EventoFrequencia';
    public static $ENTITY_GRUPO = 'Application\Model\Entity\Grupo';
    public static $ENTITY_GRUPO_EVENTO = 'Application\Model\Entity\GrupoEvento';
    public static $ENTITY_GRUPO_ATENDIMENTO = 'Application\Model\Entity\GrupoAtendimento';
    public static $ENTITY_TURMA_ALUNO = 'Application\Model\Entity\TurmaAluno';
    public static $ENTITY_PESSOA_ID = 'pessoa_id';
    public static $ENTITY_DATA_INATIVACAO = 'data_inativacao';
    public static $ENTITY_TIPO_ID = 'tipo_id';
    public static $ENTIDADE = 'entidade';
    public static $ENTIDADE_INATIVA = 'entidadeInativa';
    public static $DOCTRINE_ORM_ENTITY_MANAGER = 'doctrineORMEntityManager';
    public static $LANCAMENTO_ORM = 'lancamentoORM';
    public static $TURMA = 'turma';
    public static $GRUPO = 'grupo';
    public static $NUMERO_ATENDIMENTOS = 'numeroAtendimentos';
    public static $ARRAY_ATENDIMENTOS_GRUPO = 'atendimentosGrupo';
    public static $NOME_LIDER_ATENDIMENTO = 'nomePessoaPai';
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
    public static $TEMPLATE_JS_CADASTRAR_ATENDIMENTO = 'layout/layout-js-cadastrar-atendimento';
    public static $TEMPLATE_JS_VALIDACAO_ATENDIMENTO = 'layout/layout-js-validacao-atendimento';
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS = 'layout/layout-js-lancamento-modal-eventos';
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layout/layout-js-lancamento-modal-muitos-cadastros';
    public static $STRING_JS_LANCAMENTO = 'layoutJSLancamento';
    public static $STRING_JS_CADASTRAR_PESSOA = 'layoutJSCadastrarPessoa';
    public static $STRING_JS_CADASTRAR_PESSOA_VALIDACAO = 'layoutJSCadastrarPessoaValidacao';
    public static $STRING_JS_CADASTRAR_ATENDIMENTO = 'layoutJSCadastrarAtendimento';
    public static $STRING_JS_VALIDACAO_ATENDIMENTO = 'layoutJSValidacaoAtendimento';
    public static $STRING_JS_LANCAMENTO_MODAL_EVENTOS = 'layoutJSLancamentoModalEventos';
    public static $STRING_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layoutJSLancamentoModalMuitosCadastros';
    public static $STRING_FUNCAO_VALIDACAO_ATENDIMENTO = 'preValidacao()';
    public static $ROUTE_LANCAMENTO = 'lancamento';
    public static $PAGINA_SALVAR_PESSOA = 'SalvarPessoa';
    public static $ROUTE_LANCAMENTO_ATENDIMENTO = 'lancamentoAtendimento';
    public static $PAGINA_ATENDIMENTO = 'Atendimento';
    public static $PAGINA_LANCAR_ATENDIMENTO = 'LancarAtendimento';
    public static $PAGINA_LANCAR_ATENDIMENTO_EDIT = 'LancarAtendimentoEdit';
    public static $PAGINA_ATENDIMENTO_EXCLUSAO = 'AtendimentoExclusao';
    public static $PAGINA_ATENDIMENTO_EXCLUSAO_CONFIRMACAO = 'AtendimentoExclusaoConfirmacao';
    public static $PAGINA_LABEL_LANCAR_ATENDIMENTO = 'Lançar';
    public static $PAGINA_SALVAR_ATENDIMENTO = 'SalvarAtendimento';
    public static $PAGINA_EXCLUIR_ATENDIMENTO = 'AtendimentoExclusao';
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
    public static $FORM_CADASTRAR_ATENDIMENTO = 'CadastrarAtendimentoForm';
    public static $GRUPOS_ABAIXO = 'gruposAbaixo';
    public static $MES_ATENDIMENTO = 'mes';
    public static $TITULO_MENSAGEM = 'titulo';
    public static $TEXTO_MENSAGEM = 'texto';
    public static $MOSTRAR_MENSAGEM = 'mostrar';
    public static $ENTIDADE_ATENDIMENTO = 'atendimento';
    public static $PESSOA_LIDER = 'pessoaLider';
    public static $CAMPO_DATA_ATENDIMENTO = 'dataAtendimento';
    public static $TRADUCAO_MENSAGEM_TITULO_LANCAMENTO_ATENDIMENTO = 'Atendimento Lançado com sucesso';
    public static $TRADUCAO_MENSAGEM_TITULO_EXCLUSAO_ATENDIMENTO = 'Atendimento Excluido com sucesso';
    public static $TRADUCAO_VALIDACAO_DATA = 'Insert Date';
    public static $TRADUCAO_VALIDACAO_DATA_VALIDA = 'Insert Valid Date';
    public static $TRADUCAO_VALIDACAO_MAX_DATE = 'Please enter a valid date. Max: 10 characters';
    public static $TRADUCAO_VALIDACAO_MIN_DATE = 'Please enter a valid date. Min: 10 characters';
    public static $TRADUCAO_VALIDACAO_LIDER = 'Select Leader';
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
    public static $INPUT_DATA_ATENDIMENTO = 'dataAtendimento';
    public static $INPUT_QUEM_ATENDEU = 'quem';
    public static $INPUT_ID_GRUPO_ATENDIDO = 'idGrupo';
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

    /* Cadastro */
    /* Rotas */
    public static $PAGINA_CELULAS = 'Celulas';
    public static $PAGINA_CULTOS = 'Cultos';
    public static $PAGINA_REVISAO = 'Revisao';
    public static $PAGINA_CELULA = 'Celula';
    public static $PAGINA_EVENTO_CULTO = 'EventoCulto';
    public static $PAGINA_EVENTO_CELULA = 'EventoCelula';
    public static $PAGINA_EVENTO = 'Evento';
    public static $PAGINA_GRUPO = 'Grupo';
    public static $PAGINA_GRUPO_FINALIZAR = 'GrupoFinalizar';
    public static $PAGINA_GRUPO_ATUALIZACAO = 'GrupoAtualizacao';
    public static $PAGINA_GRUPO_ATUALIZAR = 'GrupoAtualizar';
    public static $PAGINA_GRUPO_EMAIL_ENVIADO = 'GrupoEmailEnviado';
    public static $PAGINA_EVENTO_CELULA_PERSISTIR = 'EventoCelulaPersistir';
    public static $PAGINA_EVENTO_CULTO_PERSISTIR = 'EventoCultoPersistir';
    public static $PAGINA_EVENTO_EXCLUSAO = 'EventoExclusao';
    public static $PAGINA_CELULA_EXCLUSAO_CONFIRMACAO = 'CelulaExclusaoConfirmacao';
    public static $PAGINA_EVENTO_EXCLUSAO_CONFIRMACAO = 'EventoExclusaoConfirmacao';
    public static $PAGINA_CELULA_CONFIRMACAO = 'CelulaConfirmacao';
    public static $PAGINA_BUSCAR_ENDERECO = 'BuscarEndereco';
    public static $PAGINA_BUSCAR_CPF = 'BuscarCPF';
    public static $PAGINA_BUSCAR_EMAIL = 'BuscarEmail';
    public static $PAGINA_CADASTRO_REVISAO = 'CadastrarRevisao';
    public static $PAGINA_SALVAR_REVISAO = 'SalvarRevisao';
    public static $CONTROLLER_CADASTRO = 'Application\Controller\Cadastro';
    public static $ROUTE_CADASTRO = 'cadastro';
    /* Entidades */
    public static $ENTIDADE_EVENTO_CELULA = 'Entidade\Entity\EventoCelula';
    public static $ENTIDADE_EVENTO_TIPO = 'Entidade\Entity\EventoTipo';
    public static $ENTIDADE_GRUPO_EVENTO = 'Entidade\Entity\GrupoEvento';
    public static $ENTIDADE_PESSOA_HIERARQUIA = 'Entidade\Entity\PessoaHierarquia';
    public static $ENTIDADE_TURMA_ALUNO = 'Entidade\Entity\TurmaAluno';
    public static $ENTIDADE_HIERARQUIA = 'Entidade\Entity\Hierarquia';
    public static $ENTIDADE_ENTIDADE_TIPO = 'Entidade\Entity\EntidadeTipo';
    public static $ENTIDADE_GRUPO_RESPONSAVEL = 'Entidade\Entity\GrupoResponsavel';
    public static $ENTIDADE_GRUPO_PAI_FILHO = 'Entidade\Entity\GrupoPaiFilho';
    /* Traduções */
    public static $TRADUCAO_CADASTRADO = 'Registered';
    public static $TRADUCAO_ALTERADO = 'Changed';
    public static $TRADUCAO_EXCLUIDO = 'Deleted';
    public static $TRADUCAO_CELULA_TEXTO = 'Host: ';
    public static $TRADUCAO_CULTO_TEXTO = 'Name: ';
    public static $TRADUCAO_CELULA_ = 'Cell ';
    public static $TRADUCAO_CULTO_ = 'Cult ';
    public static $TRADUCAO_FINALIZAR = 'Finish';
    public static $TRADUCAO_INSERIR = 'Insert';
    public static $TRADUCAO_LIMPAR = 'Clean';
    public static $TRADUCAO_TITULO_PAGINA_CADASTRO_GRUPO_SUB_EQUIPE = 'Registration of <span class="text-primary">Sub Team</span>';
    public static $TRADUCAO_INSIRA_OS_DADOS_DOS_RESPONSAVEIS_E_DADOS_COMPLEMENTARES = 'Enter the Responsible(s) and the complementary data';
    public static $TRADUCAO_INSIRA_OS_DADOS_COMPLEMENTARES = 'Enter the Complementary Data';
    public static $TRADUCAO_SELECIONE_O_ALUNO = 'Select the Student that will be used for the registration';
    public static $TRADUCAO_MATRICULA = 'Registration';
    public static $TRADUCAO_NOME = 'Name';
    public static $TRADUCAO_OBSERVACAO = 'Observation';
    public static $TRADUCAO_IGREJAS = 'Churches';
    public static $TRADUCAO_DATA_NASCIMENTO = 'Birth Date';
    public static $TRADUCAO_SEM_ALUNOS_CADASTRADOS = 'No Students Registered';
    public static $TRADUCAO_BUSQUE_O_ALUNO = 'Find the Student';
    public static $TRADUCAO_INFORME_DATA_NASCIMENTO_E_CPF = 'Report Date of Birth and CPF';
    public static $TRADUCAO_INSIRA_OS_DADOS = 'Enter the data of the ';
    public static $TRADUCAO_RESPONSAVEL = 'Responsible';
    public static $TRADUCAO_HOMEM = 'Man';
    public static $TRADUCAO_MULHER = 'Woman';
    public static $TRADUCAO_LIDERARA = 'Will Lead';
    public static $TRADUCAO_SELECIONE_ESTADO_CIVIL = 'Select one of the options below';
    public static $TRADUCAO_DIA = 'Date';
    public static $TRADUCAO_MES = 'Month';
    public static $TRADUCAO_ANO = 'Year';
    public static $TRADUCAO_SELECIONE_O_NUMERO_DA_SUB_EQUIPE = 'Select Sub Team Numbering';
    public static $TRADUCAO_PREENCHA_DATA_NASCIMENTO_E_CPF = 'Fill in the Birthdate and CPF correctly';
    public static $TRADUCAO_CPF_INVALIDO = 'CPF is invalid';
    public static $TRADUCAO_NOME_NAO_CONFERE = 'Name of the CPF does not match with the student';
    public static $TRADUCAO_DATA_NASCIMENTO_NAO_CONFERE = 'Date of Birth does not match with found';
    public static $TRADUCAO_DADOS_LIBERADOS = 'Data Released';
    public static $TRADUCAO_DADOS_NAO_CADASTRADOS = 'Data not found in the database';
    public static $TRADUCAO_CPF_JA_UTILIZADO = 'CPF already used';
    public static $TRADUCAO_EMAILS_NAO_CONFEREM = 'Emails do not match';
    public static $TRADUCAO_PREENCHA_O_EMAIL = 'Fill in the email';
    public static $TRADUCAO_EMAIL_INVALIDO = 'Email is invalid';
    public static $TRADUCAO_EMAIL_USADO_PELO_CONJUGE = 'Email already used by the spouse';
    public static $TRADUCAO_REPETIR_EMAIL = 'Repeat Email';
    public static $TRADUCAO_PREENCHA_E_REPETA_O_EMAIL = 'Fill in and repeat Email';
    public static $TRADUCAO_EMAIL_LIBERADO = 'Email Released';
    public static $TRADUCAO_EMAIL_JA_UTILIZADO = 'Email already used';
    public static $TRADUCAO_DADOS_DO_RESPONSAVEL = 'Responsible Details';
    public static $TRADUCAO_DADOS_DO_HOMEM = 'Man Details';
    public static $TRADUCAO_DADOS_DA_MULHER = 'Woman Details';
    public static $TRADUCAO_INSIRA_DADOS_DO_RESPONSAVEL = 'Insert Responsible Details';
    public static $TRADUCAO_INSIRA_DADOS_DO_HOMEM = 'Insert Man Details';
    public static $TRADUCAO_INSIRA_DADOS_DA_MULHER = 'Insert Woman Details';
    public static $TRADUCAO_NUMERACAO = 'Numberation: ';
    public static $TRADUCAO_DADOS_COMPLEMENTARES = 'Complemetary Data';
    public static $TRADUCAO_PASSO_A_PASSO_SELECIONE_O_ALUNO = 'Select the Student';
    public static $TRADUCAO_PASSO_A_PASSO_DADOS_PESSOAIS = 'Personal Data';
    public static $TRADUCAO_PASSO_A_PASSO_EMAIL = 'Email';
    public static $TRADUCAO_PASSO_A_PASSO_HIERARQUIA = 'Hierarchy';
    public static $TRADUCAO_SELECIONE_A_HIERARQUIA = 'Select the Hierarchy';
    public static $TRADUCAO_CADASTRO_CONCLUIDO_COM_SUCESSO = 'Registration completed successfully';
    public static $TRADUCAO_NAO_ESTA_ATIVADO = '<small>But <strong>IS NOT ON</strong> in a few minutes users will receive an email with the activation instructions.</small>';
    public static $TRADUCAO_BOTAO_PRONTO = 'Ready';
    public static $TRADUCAO_ENTRE_COM_A_SENHA = 'Enter the Password';
    public static $TRADUCAO_REPITA_A_SENHA = 'Repeat the Password';


    /* Tipo de Mensagens */
    public static $TIPO_MENSAGEM_CADASTRAR_CELULA = 1;
    public static $TIPO_MENSAGEM_ALTERAR_CELULA = 2;
    public static $TIPO_MENSAGEM_EXCLUIR_CELULA = 3;
    public static $TIPO_MENSAGEM_CADASTRAR_CULTO = 4;
    public static $TIPO_MENSAGEM_ALTERAR_CULTO = 5;
    public static $TIPO_MENSAGEM_EXCLUIR_CULTO = 6;
    /* Inputs */
    public static $INPUT_ESTADO_CIVIL = 'inputEstadoCivil';

    /* Dados PROCOB */
    public static $PROCOB_URL = 'https://api.procob.com/consultas/v1/';
    public static $PROCOB_URL_DADOS_PESSOAIS = 'L0032/';
    public static $PROCOB_URL_RECEITA_FEDERAL = 'L0014/';
    public static $PROCOB_USUARIO = 'comunidadeevangelica@sara.com';
    public static $PROCOB_SENHA = 'HK8C';

    /* Funcões JS */
    public static $FUNCAO_JS_ABRIR_TELAS_DE_ALUNO = 'abrirTelaDeAlunos(#tipo)';

    /* Forms */
    public static $FORM_ACTION_CADASTRO_GRUPO_FINALIZAR = 'cadastroGrupoFinalizar';
    public static $FORM_INPUT_DIA = 'Dia';
    public static $FORM_INPUT_MES = 'Mes';
    public static $FORM_INPUT_ANO = 'Ano';
    public static $FORM_INPUT_DDD = 'inputDDD';
    public static $FORM_INPUT_DATA_REVISAO = 'dataRevisao';
    public static $FORM_INPUT_CODIGO_VERIFICADOR = 'inputCodigoVerificador';
    public static $FORM_INPUT_CELULAR = 'inputCelular';
    public static $FORM_INPUT_PROFISSAO = 'inputProfissao';
    public static $FORM_CLASS_FORM_CONTROL = 'form-control';

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
    public static $FORM_NOME_ENTIDADE = 'nomeEntidade';
    public static $FORM_ICONE_NOME_HOSPEDEIRO = 'fa-terminal';
    public static $FORM_DDD_HOSPEDEIRO = 'ddd_hospedeiro';
    public static $FORM_ICONE_DDD_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_TELEFONE_HOSPEDEIRO = 'telefone_hospedeiro';
    public static $FORM_ICONE_TELEFONE_HOSPEDEIRO = 'fa-phone-square';
    public static $FORM_BUSCAR_CEP_LOGRADOURO = 'buscar_cep_logradouro';
    public static $FORM_CLASS = 'class';
    public static $FORM_CLASS_GUI_INPUT = 'gui-input';
    public static $FORM_CLASS_DATE = 'date';
    public static $FORM_PLACEHOLDER = 'placeholder';
    public static $FORM_ONCLICK = 'onClick';
    public static $FORM_ONCHANGE = 'onChange';
    public static $FORM_FUNCAO_BUSCAR_CEP = 'buscarEndereco()';
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
    public static $TRADUCAO_PERGUNTA_EXCLUSAO_ATENDIMENTO = 'Really want to delete this individual discipleship ?';
    public static $TRADUCAO_CIENCIA_EXCLUSAO_ATENDIMENTO = 'I am <span class="text-danger">aware</span> that by <span class="text-danger">excluding individual discipleship </span> is the same no longer appear in the <span class="text-danger">launch line</span>!';
    public static $TRADUCAO_HORA = 'Hour';
    public static $TRADUCAO_MINUTOS = 'Minutes';
    public static $TRADUCAO_CEP_LOGRADOURO = 'CEP';
    public static $TRADUCAO_CPF = 'CPF';
    public static $TRADUCAO_EMAIL = 'Email';
    public static $TRADUCAO_CEP_LOGRADOURO_SITE_CORREIOS = ' - Do not know your ZIP code <u><a target="_blank" href="http://www.buscacep.correios.com.br/sistemas/buscacep/">Click Here</a></u>';
    public static $TRADUCAO_COMPLEMENTO = 'Complement';
    public static $TRADUCAO_UF = 'UF';
    public static $TRADUCAO_CIDADE = 'City';
    public static $TRADUCAO_BAIRRO = 'Neighborhood';
    public static $TRADUCAO_LOGRADOURO = 'Public Place';
    public static $TRADUCAO_NOME_HOSPEDEIRO = 'Name Host';
    public static $TRADUCAO_EQUIPES = 'Teams';
    public static $TRADUCAO_HOSPEDEIRO = 'Host';
    public static $TRADUCAO_ENDERECO = 'Address';
    public static $TRADUCAO_DDD_HOSPEDEIRO = 'DDD';
    public static $TRADUCAO_TELEFONE_HOSPEDEIRO = 'Phone Host';
    public static $TRADUCAO_BUSCAR_CEP_LOGRADOURO = 'Search';
    public static $TRADUCAO_DIA_DA_SEMANA = 'Day of Week';
    public static $TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO = 'Day';
    public static $TRADUCAO_DATA_SIMPLIFICADO = 'Date';
    public static $TRADUCAO_CADASTRO_CELULA = 'Registration Cell';
    public static $TRADUCAO_CADASTRO_CULTO = 'Registration <b class="text-danger">Cult</b>';
    public static $TRADUCAO_NOVA = 'New';
    public static $TRADUCAO_NOVA_CELULA = 'New Cell';
    public static $TRADUCAO_NOVO_CULTO = 'New Cult';
    public static $TRADUCAO_NOVO_REVISAO = 'New Revision'; 
    public static $TRADUCAO_MULTIPLICACAO = 'Multiplication';
    public static $TRADUCAO_LISTAGEM_CULTOS = 'Listing <b class="text-danger">Cults</b>';
    public static $TRADUCAO_LISTAGEM_REVISAO = 'Listing <b class="text-danger">Revision of lives</b>';
    public static $TRADUCAO_LISTAGEM_CELULAS = 'Listing Cells of';
    public static $TRADUCAO_DIA_HORA = 'Day / Hour';
    public static $TRADUCAO_DADOS_DO_HOSPEDEIRO = 'Data from Host';
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
    public static $TRADUCAO_DIGITE_SENHA_EXCUSAO_SENHA = 'Enter your password to confirm the deletion';
    public static $TRADUCAO_CONFIRMACAO = 'Confirm';
    public static $TRADUCAO_NUMERO_MAXIMO_CELULAS = 'Maximum number of cells reached!';

    /* Layout */
    public static $LAYOUT_JS_EVENTO = 'layout/layout-js-evento';
    public static $LAYOUT_STRING_JS_EVENTO = 'layoutJSEvento';
    public static $LAYOUT_JS_GRUPO_VALIDACAO = 'layout/layout-js-grupo-validacao';
    public static $LAYOUT_STRING_JS_GRUPO_VALIDACAO = 'layoutJSGrupoValidacao';
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

    /* Fim Cadastro */
}
