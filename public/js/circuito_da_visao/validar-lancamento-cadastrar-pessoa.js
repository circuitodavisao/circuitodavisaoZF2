
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
        if (mensagem === '') {
            mensagem += '  Nome';
        } else {
            mensagem += ', Nome';
        }
    } else {
        var reg = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/;
        if (!reg.exec(nome)) {
            temErro = true;
            if (mensagem === '') {
                mensagem += '  Nome Invalido';
            } else {
                mensagem += ', Nome Invalido';
            }
        }
    }
    if (ddd.length === 0) {
        temErro = true;
        if (mensagem === '') {
            mensagem += ' DDD';
        } else {
            mensagem += ', DDD';
        }
    } else {
        if (ddd.length !== 2) {
            temErro = true;
            if (mensagem === '') {
                mensagem += ' DDD Invalido';
            } else {
                mensagem += ', DDD Invalido';
            }
        }
    }
    if (telefone.length === 0) {
        temErro = true;
        if (mensagem === '') {
            mensagem += ' Telefone';
        } else {
            mensagem += ', Telefone';
        }
    } else {
        if (!(telefone.length >= 8 && telefone.length <= 9)) {
            temErro = true;
            if (mensagem === '') {
                mensagem += ' Telefone Invalido';
            } else {
                mensagem += ', Telefone Invalido';
            }
        }
    }
    if (tipo.length === 0) {
        temErro = true;
        if (mensagem === '') {
            mensagem += ' Tipo';
        } else {
            mensagem += ', Tipo';
        }
    }

    if (temErro) {
        divMensagens
                .html('Preenchao seguintes campos corretamente:' + mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
      
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}
