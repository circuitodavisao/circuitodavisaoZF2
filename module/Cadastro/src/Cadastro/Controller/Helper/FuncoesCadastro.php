<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cadastro\Controller\Helper;

/**
 * Description of FuncoesCadastro
 *
 * @author leonardo
 */
class FuncoesCadastro {

    /**
     * Retorna a data atual formato Y-m-d
     * @return String
     */
    public static function dataAtual() {
        return date('Y-m-d');
    }

    /**
     * Retorna a hora atual formato Y-m-d
     * @return String
     */
    public static function horaAtual() {
        return date('H:i:s');
    }

    /**
     * Retorna a data do proximo domingo
     * @return String
     */
    public static function proximoDomingo() {
        $proximoDomingo = strtotime("next Sunday");
        return date('Y-m-d', $proximoDomingo);
    }

}
