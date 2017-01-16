
function validarUsuario() {
    var usuario = $('#usuario').val();
    var temErro = false;
    var divMensagens = $('#divMensagens');
    if (usuario.length === 0) {
        temErro = true;
    }
    if (temErro) {
        divMensagens
                .html('Preencha o usuário')
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        abrirModalCarregando();
        divMensagens
                .addClass('hidden');
        $('#RecuperarAcessoForm').submit();
    }
}

function validarCPFEDataNascimento() {
    var cpf = $('#cpf').val();
    var dataNascimento = $('#dataNascimento').val();
    var temErro = false;
    var divMensagens = $('#divMensagens');
    if (cpf.length === 0) {
        temErro = true;
    }
    if (dataNascimento.length === 0) {
        temErro = true;
    } else {
        var er = RegExp("(0[1-9]|[012][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
        if (!er.test(dataNascimento)) {
            temErro = true;
        }
    }
    if (temErro) {
        divMensagens
                .html('Preencha os digitos do CPF e Data de Nascimento Valida')
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        abrirModalCarregando();
        divMensagens
                .addClass('hidden');
        $('#RecuperarAcessoForm').submit();
    }
}


function validarDataNascimento(dataNascimento, icone, botaoSubmit) {
    var er = RegExp("(0[1-9]|[012][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
    var botaoSubmit = document.getElementById(botaoSubmit);

    if (er.test(dataNascimento) == false) {
        botaoSubmit.disabled = 'disabled';
    } else {
        botaoSubmit.disabled = false;
    }
}

function validarDigitosDoCPF() {

    if (digitosCPF.length < 2) {
        botaoSubmit.disabled = 'disabled';
    } else {
        botaoSubmit.disabled = false;
    }
}

function verificarSenhas(valor, tipo) {
    var botaoAlterar = document.getElementById('alterar');
    var er = RegExp("^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$");
    var mensagem1 = document.getElementById('mensagem1');
    var mensagem2 = document.getElementById('mensagem2');

    /* Senha nova */
    if (tipo == 1) {
        var icone1 = document.getElementById('iconeSenha');
        if (valor.length >= 6) {
            mensagem1.className = 'text-success';
        } else {
            icone1.className = 'fa fa-times danger-style';
            mensagem1.className = 'text-danger';
        }
        if (er.test(valor)) {
            icone1.className = 'fa fa-check sucess-style';
            mensagem2.className = 'text-success';
        } else {
            icone1.className = 'fa fa-times danger-style';
            mensagem2.className = 'text-danger';
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