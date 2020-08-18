
function validarLancamentoCadastrarPessoaRevisao(form) {
    var primeiroNome = $('#primeiro-nome').val();
    var ultimoNome = $('#ultimo-nome').val();
    var email = $('#email_revisao').val();
    var ddd = $('#ddd').val();
    var telefone = $('#telefone').val();
    var Dia = $('#Dia').val();
    var Mes = $('#Mes').val();
    var Ano = $('#Ano').val();
    var sexo = $('#nucleoPerfeito');

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';
    var mensagemReal = '';

    if (primeiroNome.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Primeiro Nome';
    } else {
        var reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
        if (!reg.exec(primeiroNome)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Primeiro Nome Inválido';
        }
        if (primeiroNome.length > 15) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Primeiro Nome pode ter no máximo 15 caracteres';
        }
    }

    if (ultimoNome.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Ultimo Nome';
    } else {
        var reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
        if (!reg.exec(ultimoNome)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Ultimo Nome Inválido';
        }
        if (ultimoNome.length > 15) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Ultimo Nome pode ter no máximo 15 caracteres';
        }
    }

    if (email.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Email';
    } else {
        var reg = /^[\w+\d+._]+\@[\w+\d+_+]+\.[\w+\d+._]{2,8}$/;
        if (!reg.exec(email)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Email Inválido';
        }
        if (ultimoNome.length > 80) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Email pode ter no máximo 15 caracteres';
        }
    }

    if (ddd.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'DDD';
    } else {
        if (ddd.length !== 2) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'DDD Inválido';
        }
    }
    if (telefone.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Telefone';
    } else {
        if (!(telefone.length >= 8 && telefone.length <= 9)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Telefone Inválido';
        }
    }

    if (Dia === '0') {

        temErro = true;
        if (mensagem == '') {
            mensagem = 'Dia';
        } else {
            mensagem += ', Dia';
        }
    }
    if (Mes === '0') {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Mês';
        } else {
            mensagem += ', Mês';
        }
    }
    if (Ano === '0') {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Ano';
        } else {
            mensagem += ', Ano';
        }
    }

    if (!$("input[type='radio'][name='nucleoPerfeito']").is(':checked')) {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Sexo';
        } else {
            mensagem += ', Sexo';
        }

    }

    if (temErro) {
        mensagemReal = 'Preencha o(s) seguinte(s) campo(s): ' + mensagem;
        divMensagens
                .html(mensagemReal)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        mostrarSplash();
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}
