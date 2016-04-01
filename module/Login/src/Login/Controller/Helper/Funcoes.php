<?php

namespace Login\Controller\Helper;

use Exception;
use PHPMailer;

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
     * @param int $padrao
     * @return date 
     */
    public static function mudarPadraoData($data, $padrao) {// padrao - 0 YYYY-mm-dd / 1 dd/mm/YYYY
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

    /**
     * Função para enviar email
     * @param String $email
     * @param String $titulo
     * @param String $mensagem
     */
    public static function enviarEmail($email, $titulo, $mensagem) {
        $mail = new PHPMailer;
        try {
//            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host = '200.147.36.31';
            $mail->SMTPAuth = true;
            $mail->Username = 'leonardo@circuitodavisao.com.br';
            $mail->Password = 'Leonardo142857';
//      $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;
            $mail->setFrom('leonardo@circuitodavisao.com.br', 'Circuito da Visao');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $titulo;
            $mail->Body = $mensagem;
//      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
        } catch (Exception $exc) {
            echo $mail->ErrorInfo;
            echo $exc->getTraceAsString();
        }
    }

}
