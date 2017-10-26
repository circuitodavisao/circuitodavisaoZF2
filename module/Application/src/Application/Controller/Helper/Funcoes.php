<?php

namespace Application\Controller\Helper;

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
     * 2 dd-mm-YYYY -> dd/mm/YYYY
     * @param String $data
     * @param int $padrao
     * @return date  
     */
    public static function mudarPadraoData($data, $padrao) {
        if ($padrao !== 2) {
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
        } else {
            $explodeData = explode("-", $data);
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
            $mail->Charset = 'utf8_decode()';
            $mail->Host = '200.147.36.31';
            $mail->SMTPAuth = true;
            $mail->Username = 'leonardo@circuitodavisao.com.br';
            $mail->Password = 'Leonardo142857';
//      $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;
            $mail->From = 'leonardo@circuitodavisao.com.br';
            $mail->FromName = utf8_decode('Circuito da Visão');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = utf8_decode($titulo);
            $mail->Body = utf8_decode($mensagem);
//      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
        } catch (Exception $exc) {
            echo $mail->ErrorInfo;
            echo $exc->getMessage();
        }
    }

    /**
     * Retorna o ciclo atual baseado no mes e ano informado
     * @param int $mesUsado
     * @param int $anoUsado
     * @return int
     */
    static public function cicloAtual($mesUsado = 5, $anoUsado = 2016) {
        $diaDaSemanaDoPrimeiroDia = date('N', mktime(0, 0, 0, $mesUsado, 1, $anoUsado));
        if ($diaDaSemanaDoPrimeiroDia == 1) {
            $resposta = 0;
        } else {
            $resposta = 1;
        }
        $diaDeHoje = date('d');
        for ($z = 1; $z <= 31; $z++) {
            $diaDaSemana = date('N', mktime(0, 0, 0, $mesUsado, $z, $anoUsado));
            if ($diaDaSemana == 1) {
                $resposta++;
            }

            if ($diaDeHoje == $z) {
                break;
            }
        }
        return $resposta;
    }

    /**
     * Retorna o ciclo atual baseado no mes e ano informado
     * @param int $mesUsado
     * @param int $anoUsado
     * @return int
     */
    static public function cicloPorData($dia, $mes, $ano) {
        $diaDaSemanaDoPrimeiroDia = date('N', mktime(0, 0, 0, $mes, 1, $ano));
        if ($diaDaSemanaDoPrimeiroDia == 1) {
            $resposta = 0;
        } else {
            $resposta = 1;
        }

        for ($z = 1; $z <= 31; $z++) {
            $diaDaSemana = date('N', mktime(0, 0, 0, $mes, $z, $ano));
            if ($diaDaSemana == 1) {
                $resposta++;
            }

            if ($dia == $z) {
                break;
            }
        }
        return $resposta;
    }

    /**
     * Retorna o perido do ciclo selecionado
     * @param type $ciclo
     * @param type $mesUsado
     * @param type $anoUsado
     * @return string
     */
    static public function periodoCicloMesAno($ciclo = 1, $mesUsado = 5, $anoUsado = 2016, $retorno = 0) {
        $resposta = '';
        $primeiroDiaCiclo = '';
        $ultimoDiaCiclo = '';
//        $resposta .= Constantes::$NBSP . $traducaoPeriodo . Constantes::$NBSP;
        $mesFormatado = str_pad($mesUsado, 2, 0, STR_PAD_LEFT);

        $cicloAux = 1;
        $umaVez = 0;
        for ($z = 1; $z <= cal_days_in_month(CAL_GREGORIAN, $mesUsado, $anoUsado); $z++) {
            /* Periodo */
            if ($cicloAux == $ciclo && $umaVez == 0) {
                $diaFormatado = str_pad($z, 2, 0, STR_PAD_LEFT);
                $resposta .= $diaFormatado . '/' . $mesFormatado;
                $primeiroDiaCiclo = $z;
                $umaVez++;
            }

            $diaDaSemana = date('N', mktime(0, 0, 0, $mesUsado, $z, $anoUsado));
            if ($diaDaSemana == 7) {
                if ($cicloAux == $ciclo) {
                    $diaFormatado = str_pad($z, 2, 0, STR_PAD_LEFT);
                    $resposta .= Constantes::$NBSP . '-' . Constantes::$NBSP . $diaFormatado . '/' . $mesFormatado;
                    $ultimoDiaCiclo = $z;
                    break;
                }
                $cicloAux++;
            }
        }
        /* Ultimo dia do mes */
        if ($ciclo == self::totalCiclosMes($mesUsado, $anoUsado)) {
            $diaFormatado = str_pad(cal_days_in_month(CAL_GREGORIAN, $mesUsado, $anoUsado), 2, 0, STR_PAD_LEFT);
            $diaDaSemana = date('N', mktime(0, 0, 0, $mesUsado, $z, $anoUsado));
            if ($diaDaSemana != 7) {
                $resposta .= Constantes::$NBSP . '-' . Constantes::$NBSP . $diaFormatado . '/' . $mesFormatado;
            }
            $ultimoDiaCiclo = cal_days_in_month(CAL_GREGORIAN, $mesUsado, $anoUsado);
        }
        if ($retorno != 0) {
            if ($retorno == 1) {
                $resposta = $primeiroDiaCiclo;
            }
            if ($retorno == 2) {
                $resposta = $ultimoDiaCiclo;
            }
        }
        return $resposta;
    }

    /**
     * Retorna o periodo
     * @return string
     */
    static public function montaPeriodo($periodo = 0) {
        $resposta = array();

        $diaDaSemana = date('N', mktime(0, 0, 0, date('m'), date('d'), date('Y')));

        $stringPeriodo = '';
        if ($periodo > 0) {
            $stringPeriodo = '+' . $periodo;
        } else {
            $stringPeriodo = $periodo;
        }
        $tempoUnix = strtotime($stringPeriodo . ' week -' . ($diaDaSemana - 1) . ' days');
        $diaAjustado = date('d', $tempoUnix);
        $mesAjustado = date('m', $tempoUnix);
        $anoAjustado = date('Y', $tempoUnix);

        $inicioPeriodo = $diaAjustado;
        $stringDataInicioPeriodo = $anoAjustado . '-' . $mesAjustado . '-' . $diaAjustado;
        $tempoUnixMais6Dias = strtotime($stringDataInicioPeriodo . ' +6 days');
        $diaFimPeriodo = date('d', $tempoUnixMais6Dias);
        $mesFimPeriodo = date('m', $tempoUnixMais6Dias);
        $anoFimPeriodo = date('Y', $tempoUnixMais6Dias);

        $inicioPeriodoFormatado = str_pad($inicioPeriodo, 2, 0, STR_PAD_LEFT);
        $diaFimPeriodoFormatado = str_pad($diaFimPeriodo, 2, 0, STR_PAD_LEFT);
        $resposta[0] = $inicioPeriodoFormatado . '/' . $mesAjustado . '&nbsp;-&nbsp;' . $diaFimPeriodoFormatado . '/' . $mesFimPeriodo;
        $resposta[1] = $inicioPeriodoFormatado;
        $resposta[2] = $mesAjustado;
        $resposta[3] = $anoAjustado;
        $resposta[4] = $diaFimPeriodoFormatado;
        $resposta[5] = $mesFimPeriodo;
        $resposta[6] = $anoFimPeriodo;
        return $resposta;
    }

    /**
     * Retorna o total de ciclos do mês
     * @param int $mesSelecionado
     * @param int $anoSelecionado
     * @return int
     */
    static public function totalCiclosMes($mesSelecionado = 5, $anoSelecionado = 2016) {
        $diaDaSemanaDoPrimeiroDia = date('N', mktime(0, 0, 0, $mesSelecionado, 1, $anoSelecionado));
        if ($diaDaSemanaDoPrimeiroDia == 7) {// 7 - domingo
            $resposta = 1;
        } else {
            $resposta = 0;
        }
        for ($z = 1; $z <= 31; $z++) {
            $diaDaSemana = date('N', mktime(0, 0, 0, $mesSelecionado, $z, $anoSelecionado));
            if ($diaDaSemana == 7) {
                $resposta++;
            }
        }
        $diaDaSemanaDoUltimoDia = date('N', mktime(0, 0, 0, $mesSelecionado, cal_days_in_month(CAL_GREGORIAN, $mesSelecionado, $anoSelecionado), $anoSelecionado));
        if ($diaDaSemanaDoUltimoDia != 7 && $diaDaSemanaDoPrimeiroDia != 7) {// 7 - domingo
            $resposta++;
        }
        return $resposta;
    }

    /**
     * Retorna se o evento existe naquele ciclo
     * @param int $diaDoEvento
     * @param int $ciclo
     * @param int $mes
     * @param int $ano
     * @return boolean
     */
    static public function eventoNoCiclo($diaDoEvento, $ciclo, $mes, $ano) {
        $resposta = false;
        if ($ciclo == 1) {
            /* 1 para segunda e 7 para domingo */
            $diaDaSemana = date('N', mktime(0, 0, 0, $mes, 1, $ano));
            if ($diaDaSemana == 7) {
                if ($diaDoEvento == 1) {
                    $resposta = true;
                }
            } else {
                if ($diaDoEvento == 1) {
                    $resposta = true;
                } else {
                    for ($index = $diaDaSemana; $index <= 7; $index++) {
                        $diaDaSemana2 = date('N', mktime(0, 0, 0, $mes, $index, $ano));
                        if ($diaDaSemana2 == ($diaDoEvento - 1)) {
                            $resposta = true;
                            break;
                        }
                    }
                }
            }
        }
        if ($ciclo == 5 || $ciclo == 6) {
            if ($ciclo == 5 && self::totalCiclosMes($mes, $ano) == 6) {
                $resposta = true;
            } else {
                $ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano));
                $diaDaSemana = date('N', mktime(0, 0, 0, $mes, $ultimo_dia, $ano));
                if ($diaDaSemana == 7) {
                    $resposta = true;
                } else {
                    for ($index = $ultimo_dia; $index >= ($ultimo_dia - $diaDaSemana); $index--) {
                        $diaDaSemana3 = date('N', mktime(0, 0, 0, $mes, $index, $ano));
                        if ($diaDaSemana3 == ($diaDoEvento - 1)) {
                            $resposta = true;
                            break;
                        }
                    }
                }
            }
        }

        return $resposta;
    }

    public static function mesPorAbaSelecionada($abaSelecionada) {
        $mesSelecionado = 0;
        if ($abaSelecionada == 1) { // mes atual
            $mesSelecionado = date('n');
        }
        if ($abaSelecionada == 2) {// mes anterior
            $mesAtual = date('n');
            if ($mesAtual == 1) {
                $mesSelecionado = 12;
            } else {
                $mesSelecionado = $mesAtual - 1;
            }
        }
        return (int) $mesSelecionado;
    }

    public static function anoPorAbaSelecionada($abaSelecionada) {
        $anoSelecionado = 0;
        if ($abaSelecionada == 1) { // mes atual
            $anoSelecionado = date('Y');
        }
        if ($abaSelecionada == 2) {// mes anterior
            $mesAtual = date('n');
            $anoAtual = date('Y');
            if ($mesAtual == 1) {
                $anoSelecionado = $anoAtual - 1;
            } else {
                $anoSelecionado = $anoAtual;
            }
        }
        return (int) $anoSelecionado;
    }

    public static function nomeDoEvento($idEvento) {
        $resposta = '';
        $id = (int) $idEvento;
        switch ($id) {
            case 1:
                $resposta = 'Cult';
                break;
            case 2:
                $resposta = 'Cell';
                break;
            default:
                break;
        }
        return $resposta;
    }

    /**
     * Retorna o dia da semana por extenso para o dia informado com 3 digitos ou por extenso dependendo do tipo
     * @param int $dia
     * @param int $tipo
     * @return string
     */
    public static function diaDaSemanaPorDia($dia, $tipo = 0) {
        $resposta = '';
        switch ($dia) {
            case 1:$resposta = 'SUN';
                if ($tipo == 1) {
                    $resposta = 'SUNDAY';
                }
                break;
            case 2:$resposta = 'MON';
                if ($tipo == 1) {
                    $resposta = 'MONDAY';
                }
                break;
            case 3:$resposta = 'TUE';
                if ($tipo == 1) {
                    $resposta = 'TUESDAY';
                }
                break;
            case 4:$resposta = 'WED';
                if ($tipo == 1) {
                    $resposta = 'WEDNESDAY';
                }
                break;
            case 5:$resposta = 'THU';
                if ($tipo == 1) {
                    $resposta = 'THURSDAY';
                }
                break;
            case 6:$resposta = 'FRI';
                if ($tipo == 1) {
                    $resposta = 'FRIDAY';
                }
                break;
            case 7:$resposta = 'SAT';
                if ($tipo == 1) {
                    $resposta = 'SATURDAY';
                }
                break;
        }
        return $resposta;
    }

    /**
     * Retorna o mês por extenso.
     * @param int $mes
     * @param int $tipo
     * @return string
     */
    public static function mesPorExtenso($mes, $tipo = 0) {
        $resposta = '';
        switch ($mes) {
            case 1:$resposta = 'JAN';
                if ($tipo == 1) {
                    $resposta = 'JANEIRO';
                }
                break;
            case 2:$resposta = 'FEV';
                if ($tipo == 1) {
                    $resposta = 'FEVEREIRO';
                }
                break;
            case 3:$resposta = 'MAR';
                if ($tipo == 1) {
                    $resposta = 'MARÇO';
                }
                break;
            case 4:$resposta = 'ABR';
                if ($tipo == 1) {
                    $resposta = 'ABRIL';
                }
                break;
            case 5:$resposta = 'MAI';
                if ($tipo == 1) {
                    $resposta = 'MAIO';
                }
                break;
            case 6:$resposta = 'JUN';
                if ($tipo == 1) {
                    $resposta = 'JUNHO';
                }
                break;
            case 7:$resposta = 'JUL';
                if ($tipo == 1) {
                    $resposta = 'JULHO';
                }
                break;
            case 8:$resposta = 'AGO';
                if ($tipo == 1) {
                    $resposta = 'AGOSTO';
                }
                break;
            case 9:$resposta = 'SET';
                if ($tipo == 1) {
                    $resposta = 'SETEMBRO';
                }
                break;
            case 10:$resposta = 'OUT';
                if ($tipo == 1) {
                    $resposta = 'OUTUBRO';
                }
                break;
            case 11:$resposta = 'NOV';
                if ($tipo == 1) {
                    $resposta = 'NOVEMBRO';
                }
                break;
            case 12:$resposta = 'DEZ';
                if ($tipo == 1) {
                    $resposta = 'DEZEMBRO';
                }
                break;
        }
        return $resposta;
    }

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

    /**
     * Retorna a data do proximo domingo
     * @return String
     */
    public static function proximaSegunda() {
        $proximoDomingo = strtotime("next Monday");
        return date('Y-m-d', $proximoDomingo);
    }

    public static function var_dump($expression) {
        echo "<pre>";
        var_dump($expression);
        echo "</pre>";
    }

}
