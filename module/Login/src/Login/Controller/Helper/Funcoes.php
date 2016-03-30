<?php

namespace Login\Controller\Helper;

/**
 * Nome: Funcoes.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com funções auxiliares
 */
class Funcoes {

    /**
     * Passa uma data e muda seu padrão, 
     * 0 YYYY-mm-dd  
     * 1 dd/mm/YYYY
     * @param String $data
     * @param Integer $padrao
     * @return date 
     */
    public static function mudarPadraoData($data, $padrao) {// padrao - 0 YYYY-mm-dd / 1 dd/mm/YYYY
        $ano = "";
        $mes = "";
        $dia = "";
        if (strstr($data, "-")) {
            $explodeData = explode("-", $data);
            $ano = $explodeData[0];
            $mes = $explodeData[1];
            $dia = $explodeData[2];
        } else {
            $explodeData = explode("/", $data);
            $ano = $explodeData[2];
            $mes = $explodeData[1];
            $dia = $explodeData[0];
        }
        if ($padrao == 0) {//YYYY-mm-dd
            $dataFormatada = "$ano-$mes-$dia";
        } else {//dd/mm/YYYY
            $dataFormatada = "$dia/$mes/$ano";
        }
        return $dataFormatada;
    }

}
