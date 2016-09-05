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
    public static $PAGINA_EVENTO_CELULA_PERSISTIR = 'EventoCelulaPersistir';
    public static $PAGINA_EVENTO_CULTO_PERSISTIR = 'EventoCultoPersistir';
    public static $PAGINA_CELULA_EXCLUSAO = 'CelulaExclusao';
    public static $PAGINA_EVENTO_CULTO_EXCLUSAO = 'EventoCultoExclusao';
    public static $PAGINA_CELULA_EXCLUSAO_CONFIRMACAO = 'CelulaExclusaoConfirmacao';
    public static $PAGINA_EVENTO_CULTO_EXCLUSAO_CONFIRMACAO = 'EventoCultoExclusaoConfirmacao';
    public static $PAGINA_CELULA_CONFIRMACAO = 'CelulaConfirmacao';
    public static $PAGINA_BUSCAR_ENDERECO = 'BuscarEndereco';
    public static $CONTROLLER_CADASTRO = 'Cadastro\Controller\Cadastro';
    public static $ROUTE_CADASTRO = 'cadastro';
    /* Entidades */
    public static $ENTIDADE_EVENTO_CELULA = 'Entidade\Entity\EventoCelula';
    public static $ENTIDADE_EVENTO_TIPO = 'Entidade\Entity\EventoTipo';
    public static $ENTIDADE_GRUPO_EVENTO = 'Entidade\Entity\GrupoEvento';
    /* Traduções */
    public static $TRADUCAO_CELULA_CADASTRADA = 'Cell Registered';
    public static $TRADUCAO_CELULA_ALTERADA = 'Cell Changed';
    public static $TRADUCAO_CELULA_EXCLUIDA = 'Cell Exploded Suddenly';
    public static $TRADUCAO_CELULA_CADASTRADA_NOME_HOSPEDEIRO = 'Host: ';

}
