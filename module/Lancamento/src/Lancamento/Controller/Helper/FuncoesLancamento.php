<?php

namespace Lancamento\Controller\Helper;

/**
 * Nome: FuncoesLancamento.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com funções de lançamento de dados
 */
class FuncoesLancamento {

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
     * Retorna o total de ciclos do mês
     * @param int $mesUsado
     * @param int $anoUsado
     * @return int
     */
    static public function totalCiclosMes($mesUsado = 5, $anoUsado = 2016) {
        $diaDaSemanaDoPrimeiroDia = date('N', mktime(0, 0, 0, $mesUsado, 1, $anoUsado));
        if ($diaDaSemanaDoPrimeiroDia == 7) {// 7 - domingo
            $resposta = 1;
        } else {
            $resposta = 0;
        }
        for ($z = 1; $z <= 31; $z++) {
            $diaDaSemana = date('N', mktime(0, 0, 0, $mesUsado, $z, $anoUsado));
            if ($diaDaSemana == 7) {
                $resposta++;
            }
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
            $diaDaSemana = 4;
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
            if ($ciclo == 5 && FuncoesLancamento::totalCiclosMes($mes, $ano) == 6) {
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

}
