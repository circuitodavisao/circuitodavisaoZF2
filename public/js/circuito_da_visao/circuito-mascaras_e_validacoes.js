/*! circuito-mascaras_e_validacoes.js - v0.1
 * http://circuitodavisao.com.br
 * Copyright (c) 2016 Circuito da Visão;*/


function verificaNumero(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}

function validarDataNascimento(dataNascimento) {
    var er = RegExp("(0[1-9]|[012][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
    if (er.test(dataNascimento) == false) {
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