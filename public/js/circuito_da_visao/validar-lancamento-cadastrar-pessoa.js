
function validarLancamentoCadastrarPessoa(form) {
    var nome = $('#nome').val();
    var ddd = $('#ddd').val();
    var telefone = $('#telefone').val();
    var tipo = $('#tipo').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';

    if (nome.length === 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Nome';
    } else {
        var reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
        if (!reg.exec(nome)) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Nome Inválido';
        }
        if (nome.length > 80) {
            temErro = true;
            if (mensagem !== '') {
                mensagem += ', ';
            }
            mensagem += 'Nome pode ter no máximo 80 caracteres';
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
    if (tipo == 0) {
        temErro = true;
        if (mensagem !== '') {
            mensagem += ', ';
        }
        mensagem += 'Tipo';
    }

    if (temErro) {
        divMensagens
                .html('Preencha os seguintes campos corretamente: ' + mensagem)
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
