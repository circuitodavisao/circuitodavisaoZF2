
function validarUsuario(form) {
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
       
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}

function validarCPFEDataNascimento(form) {
    var cpf = $('#cpf').val();
    var Dia = $('#Dia').val();
    var Mes = $('#Mes').val();
    var Ano = $('#Ano').val();
    var temErro = false;
    var divMensagens = $('#divMensagens');
    if (cpf.length === 0) {
        temErro = true;
    }
    if (parseInt(Dia) === 0 || parseInt(Mes) === 0 || parseInt(Ano) === 0) {
        temErro = true;
    }
    if (temErro) {
        divMensagens
                .html('Preencha os digitos do CPF e Data de Nascimento Valida')
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
       
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}