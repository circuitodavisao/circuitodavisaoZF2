/*! circuito-mascaras_e_validacoes.js - v0.1
 * http://circuitodavisao.com.br
 * Copyright (c) 2016 Circuito da Visão;*/


function verificaNumero(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}

$(document).ready(function () {
    $(".campo-dica").hide();

    $(".mostrar-dica").click(function () {
        $(".campo-dica").fadeIn("slow");
    });

    $(".nao-mostrar-dica").click(function () {
        $(".campo-dica").fadeOut("slow");
    });

    $("#cpf").keypress(verificaNumero);
    $("#dataNascimento").keypress(verificaNumero);
});

