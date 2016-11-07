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
    public static $ENTIDADE_HIERARQUIA = 'Entidade\Entity\Hierarquia';
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
    /* Tipo de Mensagens */
    public static $TIPO_MENSAGEM_CADASTRAR_CELULA = 1;
    public static $TIPO_MENSAGEM_ALTERAR_CELULA = 2;
    public static $TIPO_MENSAGEM_EXCLUIR_CELULA = 3;
    public static $TIPO_MENSAGEM_CADASTRAR_CULTO = 4;
    public static $TIPO_MENSAGEM_ALTERAR_CULTO = 5;
    public static $TIPO_MENSAGEM_EXCLUIR_CULTO = 6;
    /* Inputs */
    public static $INPUT_ESTADO_CIVIL = 'inputEstadoCivil';

}
