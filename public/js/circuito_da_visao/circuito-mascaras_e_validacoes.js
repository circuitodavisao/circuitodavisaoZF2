/*! circuito-mascaras_e_validacoes.js - v0.1
 * http://circuitodavisao.com.br
 * Copyright (c) 2016 Circuito da Visão;*/

function desabilitarElemento(id) {
    $(id).prop('disabled', true);
}

function verificaNumero(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}

function validarDataNascimento(dataNascimento, icone, botaoSubmit) {
    var er = RegExp("(0[1-9]|[012][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
    var botaoSubmit = document.getElementById(botaoSubmit);
    var icone = document.getElementById(icone);

    if (er.test(dataNascimento) == false) {
        botaoSubmit.disabled = 'disabled';
        icone.className = 'fa fa-times danger-style';
    } else {
        botaoSubmit.disabled = false;
        icone.className = 'fa fa-check sucess-style';
    }
}

function validarDigitosDoCPF(digitosCPF, icone, botaoSubmit) {
    var botaoSubmit = document.getElementById(botaoSubmit);
    var icone = document.getElementById(icone);
    if (digitosCPF.length < 2) {
        botaoSubmit.disabled = 'disabled';
        icone.className = 'fa fa-times danger-style';
    } else {
        botaoSubmit.disabled = false;
        icone.className = 'fa fa-check sucess-style';
    }
}

function verificarSenhas(valor, tipo) {
    var botaoAlterar = document.getElementById('alterar');
    var er = RegExp("^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$");
    var classeBase = 'fa-li fa fa-check-square ';
    var mensagem1 = document.getElementById('mensagem1');
    var mensagem2 = document.getElementById('mensagem2');

    /* Senha nova */
    if (tipo == 1) {
        var icone1 = document.getElementById('iconeSenha');
        if (valor.length >= 6) {
            mensagem1.className = classeBase + 'text-success';
        } else {
            icone1.className = 'fa fa-times danger-style';
            mensagem1.className = classeBase + 'text-danger';
        }
        if (er.test(valor)) {
            icone1.className = 'fa fa-check sucess-style';
            mensagem2.className = classeBase + 'text-success';
        } else {
            icone1.className = 'fa fa-times danger-style';
            mensagem2.className = classeBase + 'text-danger';
        }
        botaoAlterar.disabled = 'disabled';
    }

    /* Repetir senha */
    if (tipo == 2) {
        var icone2 = document.getElementById('iconeRecuperaSenha');
        var senhaNova = document.getElementById('senha').value;
        if (valor == senhaNova) {
            icone2.className = 'fa fa-check sucess-style';
            botaoAlterar.disabled = false;
        } else {
            icone2.className = 'fa fa-times danger-style';
            botaoAlterar.disabled = 'disabled';
        }
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