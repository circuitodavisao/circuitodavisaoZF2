
function validarSenhas(form) {
    var senha = $('#senha').val();
    var repetirSenha = $('#repetirSenha').val();
    var temErro = false;
    var divMensagens = $('#divMensagens');
    var mensagem = '';
    if (senha.length === 0) {
        temErro = true;
        mensagem = 'Preencha a senha e repita';
    }
    if (repetirSenha.length === 0) {
        temErro = true;
        mensagem = 'Preencha a senha e repita';
    }
    if (senha !== repetirSenha
            && senha.length !== 0
            && repetirSenha.length !== 0) {
        temErro = true;
        mensagem = 'Senhas diferentes';
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
