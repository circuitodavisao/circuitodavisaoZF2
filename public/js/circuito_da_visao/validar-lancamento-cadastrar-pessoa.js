
function validarLancamentoCadastrarPessoa(form) {
    var nome = $('#nome').val();
    var ddd = $('#ddd').val();
    var telefone = $('#telefone').val();
    var tipo = $('#tipo').val();

    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';
    
    if (tipo.length === 0) {
        temErro = true;
        mensagem = 'Preencha o Tipo';
    }
    if (telefone.length === 0) {
        temErro = true;
        mensagem = 'Preencha o Telefone';
    }
    if (ddd.length === 0) {
        temErro = true;
        mensagem = 'Preencha o DDD';
    }
    if (nome.length === 0) {
        temErro = true;
        mensagem = 'Preencha o Nome';
    }
    if (temErro) {
        divMensagens
                .html(mensagem)
                .removeClass('alert-success')
                .removeClass('hidden')
                .addClass('alert-danger');
    } else {
        abrirModalCarregando();
        divMensagens
                .addClass('hidden');
        form.submit();
    }
}
