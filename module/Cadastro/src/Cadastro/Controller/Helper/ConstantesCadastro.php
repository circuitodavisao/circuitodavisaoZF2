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
    public static $PAGINA_CELULA = 'Celula';
    public static $PAGINA_CELULA_CONFIRMACAO = 'CelulaConfirmacao';
    public static $PAGINA_SALVAR_CELULA = 'SalvarCelula';
    public static $PAGINA_BUSCAR_ENDERECO = 'BuscarEndereco';
    public static $CONTROLLER_CADASTRO = 'Cadastro\Controller\Cadastro';
    public static $ROUTE_CADASTRO = 'cadastro';
    /* Entidades */
    public static $ENTIDADE_EVENTO_CELULA = 'Entidade\Entity\EventoCelula';
    public static $ENTIDADE_EVENTO_TIPO = 'Entidade\Entity\EventoTipo';
    public static $ENTIDADE_GRUPO_EVENTO = 'Entidade\Entity\GrupoEvento';
    /* Traduções */
    public static $TRADUCAO_CELULA_CADASTRADA = 'Cell Registered';
    public static $TRADUCAO_CELULA_CADASTRADA_NOME_HOSPEDEIRO = 'Host: ';

}
