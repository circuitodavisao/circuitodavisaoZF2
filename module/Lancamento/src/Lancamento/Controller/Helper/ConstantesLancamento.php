<?php

namespace Lancamento\Controller\Helper;

/**
 * Nome: ConstantesLancamento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com constantes de lançamento de dados
 */
class ConstantesLancamento {

    public static $ENTITY_ENTIDADE = 'Entidade\Entity\Entidade';
    public static $ENTITY_GRUPO_PESSOA = 'Entidade\Entity\GrupoPessoa';
    public static $ENTITY_GRUPO_PESSOA_TIPO = 'Entidade\Entity\GrupoPessoaTipo';
    public static $ENTITY_EVENTO = 'Entidade\Entity\Evento';
    public static $ENTITY_EVENTO_FREQUENCIA = 'Entidade\Entity\EventoFrequencia';
    public static $ENTITY_GRUPO = 'Entidade\Entity\Grupo';
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
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_EVENTOS = 'layout/layout-js-lancamento-modal-eventos';
    public static $TEMPLATE_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layout/layout-js-lancamento-modal-muitos-cadastros';
    public static $STRING_JS_LANCAMENTO = 'layoutJSLancamento';
    public static $STRING_JS_CADASTRAR_PESSOA = 'layoutJSCadastrarPessoa';
    public static $STRING_JS_LANCAMENTO_MODAL_EVENTOS = 'layoutJSLancamentoModalEventos';
    public static $STRING_JS_LANCAMENTO_MODAL_MUITOS_CADASTROS = 'layoutJSLancamentoModalMuitosCadastros';
    public static $ROUTE_LANCAMENTO = 'lancamento';
    public static $ACTION_SALVAR_PESSOA = 'SalvarPessoa';
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
    public static $CONTROLLER_LANCAMENTO = 'Lancamento\Controller\Lancamento';
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
    public static $TRADUCAO_NOME = 'Name';
    public static $TRADUCAO_TELEFONE = 'Phone';
    public static $TRADUCAO_TIPO = 'Type';
    public static $TRADUCAO_NUCLEO_CELULA = 'Perfect Core';
    public static $TRADUCAO_SELECIONE = 'SELECT';
    /* Fomulário */
    public static $INPUT_NOME = 'nome';
    public static $INPUT_TELEFONE = 'telefone';
    public static $INPUT_TIPO = 'tipo';
    public static $INPUT_NUCLEO_PERFEITO = 'nucleoPerfeito';
    public static $INPUT_VOLTAR = 'voltar';
    public static $INPUT_CADASTRAR = 'cadastrar';
    /* Rotas */
    public static $ROUTE_REMOVER_PESSOA = 'RemoverPessoa';
    public static $ROUTE_CADASTRAR_PESSOA_REVISAO = 'CadastrarPessoaRevisao';
    public static $ROUTE_INDEX = 'Index';
    public static $ROUTE_FICHA_REVISAO = 'FichaRevisao';

}
