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
     * Retorna o perido do ciclo selecionado
     * @param type $ciclo
     * @param type $mesUsado
     * @param type $anoUsado
     * @return string
     */
    static public function periodoCicloMesAno($ciclo = 1, $mesUsado = 5, $anoUsado = 2016) {
        $resposta = '';

        $mesFormatado = str_pad($mesUsado, 2, 0, STR_PAD_LEFT);

        $diaDaSemanaDoPrimeiroDia = date('N', mktime(0, 0, 0, $mesUsado, 1, $anoUsado));
        if ($diaDaSemanaDoPrimeiroDia == 7) {// 7 - domingo
            $cicloAux = 1;
        } else {
            $cicloAux = 0;
        }
        $umaVez = 0;
        for ($z = 1; $z <= 31; $z++) {
            /* Periodo */
            if ($cicloAux == $ciclo && $umaVez == 0) {
                $diaFormatado = str_pad($z, 2, 0, STR_PAD_LEFT);
                $resposta .= $diaFormatado . '/' . $mesFormatado . ' a ';
                $umaVez++;
            }

            $diaDaSemana = date('N', mktime(0, 0, 0, $mesUsado, $z, $anoUsado));
            if ($diaDaSemana == 7) {
                if ($cicloAux == $ciclo) {
                    $diaFormatado = str_pad($z, 2, 0, STR_PAD_LEFT);
                    $resposta .= $diaFormatado . '/' . $mesFormatado;
                }
                $cicloAux++;
            }
        }
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
        return $mesSelecionado;
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
        return $anoSelecionado;
    }

}
