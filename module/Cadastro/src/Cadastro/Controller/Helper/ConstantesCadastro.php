<?php

namespace Cadastro\Controller\Helper;

/**
 * Nome: ConstantesCadastro.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com constantes do cadastro
 */
class ConstantesCadastro {
    /* Rotas */

    public static $PAGINA = 'pagina';
    public static $PAGINA_CELULAS = 'Celulas';
    public static $PAGINA_CULTOS = 'Cultos';
    public static $PAGINA_CELULA = 'Celula';
    public static $PAGINA_EVENTO_CULTO = 'EventoCulto';
    public static $PAGINA_EVENTO_CELULA = 'EventoCelula';
    public static $PAGINA_EVENTO = 'Evento';
    public static $PAGINA_GRUPO = 'Grupo';
    public static $PAGINA_GRUPO_FINALIZAR = 'GrupoFinalizar';
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
    public static $CONTROLLER_CADASTRO = 'Cadastro\Controller\Cadastro';
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
    public static $TRADUCAO_ALTERAR = 'Change';
    public static $TRADUCAO_LIMPAR = 'Clean';
    public static $TRADUCAO_TITULO_PAGINA_CADASTRO_GRUPO_SUB_EQUIPE = 'Registration of <span class="text-primary">Sub Team</span>';
    public static $TRADUCAO_INSIRA_OS_DADOS_DOS_RESPONSAVEIS_E_DADOS_COMPLEMENTARES = 'Enter the Responsible(s) and the complementary data';
    public static $TRADUCAO_INSIRA_OS_DADOS_COMPLEMENTARES = 'Enter the Complementary Data';
    public static $TRADUCAO_SELECIONE_O_ALUNO = 'Select the Student that will be used for the registration';
    public static $TRADUCAO_MATRICULA = 'Registration';
    public static $TRADUCAO_NOME = 'Name';
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
    public static $TRADUCAO_CADASTRAR = 'Register';
    public static $TRADUCAO_ENTRE_COM_A_SENHA = 'Enter the Password';
    public static $TRADUCAO_REPITA_A_SENHA = 'Repeat the Password';
    public static $TRADUCAO_DDD = 'DDD';
    public static $TRADUCAO_CELULAR = 'Phone';


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
    public static $FORM_INPUT_CELULAR = 'inputCelular';
    public static $FORM_CLASS_FORM_CONTROL = 'form-control';

}
