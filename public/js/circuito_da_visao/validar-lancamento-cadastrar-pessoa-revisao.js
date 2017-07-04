
function validarLancamentoCadastrarPessoaRevisao(form) {
    var primeiroNome = $('#primeiro-nome').val();
    var ultimoNome = $('#ultimo-nome').val();
    var ddd = $('#ddd').val();
    var telefone = $('#telefone').val();
    var Dia = $('#Dia').val();
    var Mes = $('#Mes').val();
    var Ano = $('#Ano').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';
    var mensagemReal = '';
    
    if (primeiroNome.length === 0) {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Primeiro Nome';
        } else {
            mensagem += ', Primeiro Nome';
        }
    }
    if (ultimoNome.length === 0) {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Ultimo Nome';
        } else {
            mensagem += ', Ultimo Nome';
        }
    }
    if (ddd.length === 0) {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'DDD';
        } else {
            mensagem += ', DDD';
        }
    }
    if (telefone.length === 0) {
        temErro = true;
        if (mensagem == '') {
            mensagem = 'Telefone';
        } else {
            mensagem += ', Telefone';
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
    if (temErro) {
        mensagemReal = 'Preencha o(s) seguinte(s) campo(s): ' + mensagem;
        divMensagens
                .html(mensagemReal)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
      
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}
